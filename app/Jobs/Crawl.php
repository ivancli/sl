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

        foreach($items as $item){
            foreach($item->metas as $meta){
                $result = $parserRepo->parseMeta($meta, $content);
                dump($result);
            }
        }
        dd($result);
    }
}
