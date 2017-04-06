<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/5/2017
 * Time: 9:49 AM
 */

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Ixudra\Curl\Facades\Curl;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\DomCrawler\Crawler;

class Kosmic extends Command
{
    var $urlRepo;
    var $url = "https://www.kosmic.com.au";
    var $products = [];
    var $links = [
        'https://www.kosmic.com.au/guitar/',
        'https://www.kosmic.com.au/amps-fx/',
        'https://www.kosmic.com.au/drums',
        'https://www.kosmic.com.au/recording/',
        'https://www.kosmic.com.au/keyboard/',
        'https://www.kosmic.com.au/bass/',
        'https://www.kosmic.com.au/dj/',
        'https://www.kosmic.com.au/light/',
        'https://www.kosmic.com.au/live-sound/'
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler-kosmic';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command runs crawler for particular url or all urls if no parameters given.';

    /**
     * Create a new command instance.
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
        $bar = $this->output->createProgressBar(count($this->links));
        foreach ($this->links as $link) {
            $bar->advance();
            $productBar = $this->output->createProgressBar(10000);
            do {
                $response = Curl::to($link)
                    ->withHeaders([
                        'Accept-Language: en-us',
                        'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
                        'Connection: Keep-Alive',
                        'Cache-Control: no-cache',
                    ])
                    ->returnResponseObject()
                    ->get();

                $productBar->advance();
                $crawler = new Crawler($response->content);
                if ($crawler->filterXPath('//*[@class="pagination"]/li[@class="active"]/following-sibling::li[1]/a')->count() > 0) {
                    $link = $this->url . $crawler->filterXPath('//*[@class="pagination"]/li[@class="active"]/following-sibling::li[1]/a')->attr('href');
                } else {
                    $link = null;
                }
                $this->products = array_merge($this->products, $this->crawl_one_product_page($response, $link));
            } while (!is_null($link));
            $productBar->finish();
        }
        Excel::create('kosmic', function ($excel) {
            $excel->sheet('sheet1', function ($sheet) {
                $sheet->fromArray($this->products);
            });
        })->store('csv');
        $bar->finish();

        dd("End of Crawl");
    }


    private function crawl_one_product_page($response, $category_url)
    {
        $crawler = new Crawler($response->content);
        $products = [];
        $category = $crawler->filterXPath('//title')->text();

        $crawler->filterXPath('//*[@itemtype="http://schema.org/Product"]')->each(function (Crawler $node, $index) use (&$products, $category, $category_url) {
            $product_link = $node->filterXPath('//*[@itemprop="name"]/a')->attr('href');
            $product_name = $node->filterXPath('//*[@itemprop="name"]/a')->attr('title');
            $category_url = 'https://www.kosmic.com.au/guitar/';

            $products[] = [
                'product_name' => $product_name,
                'product_link' => $product_link,
                'category_name' => $category,
                'category_url' => $category_url
            ];
        });

        return $products;
    }
}
