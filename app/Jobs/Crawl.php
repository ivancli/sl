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
    protected $test;

    /**
     * Create a new job instance.
     *
     * @param Url $url
     * @param bool $test
     */
    public function __construct(Url $url, bool $test)
    {
        $this->url = $url;
        $this->test = $test;
    }

    /**
     * Execute the job.
     *
     * @param CrawlerContract $crawlerRepo
     * @param ParserContract $parserRepo
     */
    public function handle(CrawlerContract $crawlerRepo, ParserContract $parserRepo)
    {
        event(new BeforeCrawlUrl($this->url));

        $crawler = $this->url->crawler;

        /* fetch */
        event(new BeforeFetchUrl($this->url));
        $content = $crawlerRepo->fetch($crawler);
        event(new AfterFetchUrl($this->url, $content));

        $items = $this->url->items;

        /* parse each item */
        foreach ($items as $item) {
            event(new BeforeProcessItem($item));

            if ($this->test) {
                dump("Item {$item->getKey()} - {$item->name}:");
            }
            foreach ($item->metas as $meta) {
                event(new BeforeParseMeta($meta));
                $parserResult = $parserRepo->parseMeta($meta, $content);
                event(new AfterParseMeta($meta));

                /*TODO move test to test controller*/
                if ($this->test) {
                    dump("Meta {$meta->element}:");
                    if ($parserResult !== false && is_array($parserResult) && count($parserResult) > 0) {
                        if (count($parserResult) == 1) {
                            $firstResult = array_first($parserResult);
                            if (!is_null($firstResult)) {
                                /* format result */
                                switch ($meta->historical_type) {
                                    case "price":
                                        $firstResult = $parserRepo->formatMetaValue([
                                            'strip_text', 'currency'
                                        ], $firstResult);
                                        break;
                                    default:
                                }
                                /* save result */
                                if (!empty($firstResult)) {
                                    dump("Result:");
                                    dump($firstResult);
                                } else {
                                    dump("Result is empty after formatted.");
                                }
                            } else {
                                dump("First result is null.");
                            }
                        } else {
                            dump("There are multiple results:");
                            dump($parserResult);
                        }
                    } else {
                        dump("Parser has no result.");
                    }
                } else {
                    /*TODO save data to meta data and historical prices*/
                    if ($parserResult !== false && is_array($parserResult) && count($parserResult) > 0) {
                        if (count($parserResult) == 1) {
                            $firstResult = array_first($parserResult);
                            if (!is_null($firstResult)) {
                                /* format result */
                                switch ($meta->historical_type) {
                                    case "price":
                                        $firstResult = $parserRepo->formatMetaValue([
                                            'strip_text', 'currency'
                                        ], $firstResult);
                                        break;
                                    default:
                                }

                                /* save result */
                                if (!empty($firstResult)) {

                                    event(new BeforeSaveMeta($meta));
                                    $valueDifferent = $meta->value != $firstResult;
                                    $meta->value = $firstResult;
                                    $meta->save();
                                    $meta->createHistoricalData($firstResult);

                                    if ($valueDifferent) {
                                        event(new MetaChanged($meta));
                                    }

                                    event(new AfterSaveMeta($meta));

                                } else {
                                    /* empty result after formatted */
                                    event(new NoFormatResult($meta));
                                }
                            } else {
                                /* first result is null */
                                event(new NoFirstResult($meta));
                            }
                        } else {
                            /* there are multiple results/nodes */
                            /* json-ise the result */
                        }
                    } else {
                        /* parser has no result*/
                        event(new NoParseResult($meta));
                    }
                }
            }

            event(new AfterProcessItem($item));
        }

        /* crawler finished */

        dump("End of Crawl");

        event(new AfterCrawlUrl($this->url));
    }
}
