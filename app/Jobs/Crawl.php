<?php

namespace App\Jobs;

use App\Contracts\Repositories\UrlManagement\CrawlerContract;
use App\Contracts\Repositories\UrlManagement\ParserContract;
use App\Models\Crawler;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Crawl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $crawler;

    /**
     * Create a new job instance.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * Execute the job.
     *
     * @param CrawlerContract $crawlerRepo
     * @param ParserContract $parserRepo
     */
    public function handle(CrawlerContract $crawlerRepo, ParserContract $parserRepo)
    {
        /* TODO fetch */
        $content = $crawlerRepo->fetch($this->crawler);

        /* TODO parse for each item */
        $result = $parserRepo->extract($content, [
            "xpath" => "//*[@class='price-now']"
        ]);

        dd($result);
    }
}
