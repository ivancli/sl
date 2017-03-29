<?php

namespace App\Jobs;

use App\Contracts\Repositories\UrlManagement\CrawlerContract;
use App\Contracts\Repositories\UrlManagement\ParserContract;
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
        $crawler = $this->url->crawler;

        /* TODO fetch */
        $content = $crawlerRepo->fetch($crawler);

        /* TODO parse for each item */
        $items = $this->url->items;

//        $result = $parserRepo->extract($content, [
//            "xpath" => "//*[@class='price-now']"
//        ]);
        foreach ($items as $item) {
            if ($this->test) {
                dump("Item {$item->getKey()} - {$item->name}:");
            }
            foreach ($item->metas as $meta) {
                $parserResult = $parserRepo->parseMeta($meta, $content);
                if ($this->test) {
                    dump("Meta {$meta->element}:");
                    dump($parserResult);

                    if ($parserResult !== false && is_array($parserResult) && count($parserResult) > 0) {
                        $firstConf = array_first($parserResult);
                        $firstConfResult = array_get($firstConf, 'result');
                        if (!is_null($firstConfResult) && is_array($firstConfResult)) {
                            $firstResult = array_first($firstConfResult);
                            if (!is_null($firstResult) && is_array($firstResult)) {
                                $resultFirstPart = array_first($firstResult);
                                if (!is_null($resultFirstPart)) {
                                    $resultFirstPart = $parserRepo->formatMetaValue([
                                        'strip_text', 'currency'
                                    ], $resultFirstPart);
                                    if (!empty($resultFirstPart)) {
                                        $meta->value = $resultFirstPart;
                                        $meta->save();
                                    }
                                }
                            }
                        }
                    } else {
                        /* parser has no result*/
                    }
                } else {

                    /*TODO save data to meta data and historical prices*/
                    if ($parserResult !== false && is_array($parserResult) && count($parserResult) > 0) {
                        $firstConf = array_first($parserResult);
                        $firstConfResult = array_get($firstConf, 'result');
                        if (!is_null($firstConfResult) && is_array($firstConfResult)) {
                            $firstResult = array_first($firstConfResult);
                            if (!is_null($firstResult) && is_array($firstResult)) {
                                $resultFirstPart = array_first($firstResult);
                                if (!is_null($resultFirstPart)) {
                                    $resultFirstPart = $parserRepo->formatMetaValue([
                                        'strip_text', 'currency'
                                    ], $resultFirstPart);
                                    if (!empty($resultFirstPart)) {
                                        $meta->value = $resultFirstPart;
                                        $meta->save();
                                    }
                                }
                            }
                        }
                    } else {
                        /* parser has no result*/
                    }
                }
            }
        }

        dd("End of Crawl");
    }
}
