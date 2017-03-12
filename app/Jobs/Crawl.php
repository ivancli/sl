<?php

namespace App\Jobs;

use App\Contracts\Repositories\UrlManagement\CrawlerContract;
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
     * @return void
     */
    public function handle(CrawlerContract $crawlerRepo)
    {
        /* TODO fetch */
        $content = $crawlerRepo->fetch($this->crawler);
        dd($content);
        /* TODO parse for each item */
    }
}
