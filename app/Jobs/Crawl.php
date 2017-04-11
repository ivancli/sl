<?php

namespace App\Jobs;

use App\Contracts\Repositories\UrlManagement\CrawlerContract;
use App\Contracts\Repositories\UrlManagement\ParserContract;
use App\Events\Jobs\Crawl\AfterCrawlUrl;
use App\Events\Jobs\Crawl\AfterFetchUrl;
use App\Events\Jobs\Crawl\AfterParseMeta;
use App\Events\Jobs\Crawl\AfterProcessItem;
use App\Events\Jobs\Crawl\AfterSaveMeta;
use App\Events\Jobs\Crawl\BeforeCrawlUrl;
use App\Events\Jobs\Crawl\BeforeFetchUrl;
use App\Events\Jobs\Crawl\BeforeParseMeta;
use App\Events\Jobs\Crawl\BeforeProcessItem;
use App\Events\Jobs\Crawl\BeforeSaveMeta;
use App\Events\Jobs\Crawl\MetaChanged;
use App\Events\Jobs\Crawl\NoConfiguration;
use App\Events\Jobs\Crawl\NoFirstResult;
use App\Events\Jobs\Crawl\NoFormatResult;
use App\Events\Jobs\Crawl\NoParseResult;
use App\Models\Crawler;
use App\Models\Url;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Crawl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;

    /**
     * Create a new job instance.
     *
     * @param Url $url
     */
    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @param CrawlerContract $crawlerRepo
     * @param ParserContract $parserRepo
     * @return mixed
     */
    public function handle(CrawlerContract $crawlerRepo, ParserContract $parserRepo)
    {
        event(new BeforeCrawlUrl($this->url));

        $crawler = $this->url->crawler;

        $crawler->statusPicked();

        #region crawling
        event(new BeforeFetchUrl($this->url));

        $crawler->statusCrawling();

        $result = $crawlerRepo->fetch($crawler);

        if ($result['status'] != 200) {
            $this->url->statusCrawlFailed();
            return false;
        }

        $content = $result['content'];

        $crawler->statusCrawled();

        event(new AfterFetchUrl($this->url, $content));
        #endregion

        $items = $this->url->items;

        $crawler->statusParsing();

        foreach ($items as $item) {

            event(new BeforeProcessItem($item));

            foreach ($item->metas as $meta) {

                if ($meta->confs()->count() == 0) {
                    $meta->statusConfigFailed();
                    event(new NoConfiguration($meta));
                    continue;
                }

                #region parsing
                event(new BeforeParseMeta($meta));
                $parserResult = $parserRepo->parseMeta($meta, $content);
                event(new AfterParseMeta($meta));
                #endregion

                if ($meta->format_type == 'boolean') {
                    #region Boolean Meta
                    $boolean = $parserResult !== false && is_array($parserResult) && count($parserResult) > 0;

                    event(new BeforeSaveMeta($meta));

                    $valueDifferent = $meta->value != $boolean;
                    $meta->value = $boolean;
                    $meta->save();
                    $meta->createHistoricalData($boolean);

                    if ($valueDifferent) {
                        event(new MetaChanged($meta));
                    }

                    $meta->statusStandby();
                    event(new AfterSaveMeta($meta));
                    #endregion
                } else {
                    if ($parserResult !== false && is_array($parserResult) && count($parserResult) > 0) {
                        if (count($parserResult) == 1) {
                            $firstResult = array_first($parserResult);
                            if (!is_null($firstResult)) {

                                #region format parsed data
                                switch ($meta->format_type) {
                                    case "decimal":
                                        $firstResult = $parserRepo->formatMetaValue([
                                            'strip_text', 'currency'
                                        ], $firstResult);
                                        break;
                                    default:
                                }
                                #endregion

                                if (!empty($firstResult)) {

                                    #region save final result
                                    event(new BeforeSaveMeta($meta));

                                    $valueDifferent = $meta->value != $firstResult;
                                    $meta->value = $firstResult;
                                    $meta->save();
                                    $meta->createHistoricalData($firstResult);

                                    if ($valueDifferent) {
                                        event(new MetaChanged($meta));
                                    }

                                    $meta->statusStandby();
                                    event(new AfterSaveMeta($meta));
                                    #endregion

                                } else {
                                    /* empty result after formatted */
                                    $meta->statusFormatFailed();
                                    event(new NoFormatResult($meta));
                                }
                            } else {
                                /* first result is null */
                                $meta->statusParseFailed();
                                event(new NoFirstResult($meta));
                            }
                        } else {
                            /* there are multiple results/nodes */
                            /* json-ise the result */
                        }
                    } else {
                        /* parser has no result*/
                        $meta->statusParseFailed();
                        event(new NoParseResult($meta));
                    }
                }


            }

            event(new AfterProcessItem($item));
        }

        $crawler->statusParsed();

        /* crawler finished */

        dump("End of Crawl");

        $crawler->statusNull();

        $this->url->statusStandby();

        event(new AfterCrawlUrl($this->url));
    }
}
