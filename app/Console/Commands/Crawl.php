<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Crawl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl {crawler_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command runs crawler for particular url or all urls if no parameters given.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $crawler_id = $this->argument('crawler_id');
        if(is_null($crawler_id)){
            /* TODO run a for loop to crawl all urls */
        }else{
            /* TODO crawl a particular*/
        }
    }
}
