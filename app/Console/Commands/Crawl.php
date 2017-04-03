<?php

namespace App\Console\Commands;

use App\Contracts\Repositories\UrlManagement\UrlContract;
use App\Models\Crawler;
use App\Models\Url;
use Illuminate\Console\Command;
use App\Jobs\Crawl as CrawlJob;

class Crawl extends Command
{
    var $urlRepo;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl {url_id?} {--active} {--test}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command runs crawler for particular url or all urls if no parameters given.';

    /**
     * Create a new command instance.
     * @param UrlContract $urlContract
     */
    public function __construct(UrlContract $urlContract)
    {
        $this->urlRepo = $urlContract;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // get URL ID
        $url_id = $this->argument('url_id');

        // if URL ID is provided
        if (!is_null($url_id)) {

            // get URL by URL ID
            $url = $this->urlRepo->get($url_id);

            // check activeness of URL
            if ($this->validate($url)) {

                // make sure the URL has corresponding crawler in DB
                if (!is_null($crawler = $url->crawler)) {

                    $this->pushToQueue($url);
                    $this->info("URL-{$url->getKey()} {$url->domainFullPath} has been pushed to queue.");
                } else {
                    $this->error("URL-{$url->getKey()} {$url->domainFullPath} does not have a crawler in DB.");
                }
            }
        } else { // if URL ID is not provided

            // process all URLs
            $urls = $this->urlRepo->all();

            $urls->each(function ($url, $index) {

                // check activeness of URL
                if ($this->validate($url)) {

                    // make sure the URL has corresponding crawler in DB and it's not the ones being processed
                    if (!is_null($crawler = $url->crawler) && is_null($crawler->status)) {

                        // push crawler to queue
                        $this->pushToQueue($crawler);

                        $this->info("URL-{$url->getKey()} {$url->domainFullPath} has been pushed to queue.");
                    } else {
                        $this->error("URL-{$url->getKey()} {$url->domainFullPath} does not have a crawler in DB.");
                    }
                }
            });
        }
    }

    /**
     * Push a single crawl job to queue
     * @param Url $url
     * @return bool
     */
    protected function validate(Url $url)
    {
        if ($this->option('active') && !$url->active) {
            $this->warn("URL-{$url->getKey()} {$url->domainFullPath} is inactive. Scheduler has skipped this URL.");
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param Url $url
     */
    protected function pushToQueue(Url $url)
    {
        $delay = rand(config('crawl.delay.min'), config('crawl.delay.max'));
        dispatch((new CrawlJob($url, $this->option('test')))->onQueue("crawl")->delay($delay));
        $crawler = $url->crawler;
        $crawler->statusQueuing();
    }
}
