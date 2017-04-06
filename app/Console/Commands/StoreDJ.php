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

class StoreDJ extends Command
{
    var $urlRepo;
    var $url = "https://www.storedj.com.au";
    var $products = [];
    var $links = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler-storedj';
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

        $response = Curl::to('https://www.storedj.com.au/')
            ->withHeaders([
                'Accept-Language: en-us',
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
                'Connection: Keep-Alive',
                'Cache-Control: no-cache',
            ])
            ->returnResponseObject()
            ->get();


        $crawler = new Crawler($response->content);
        $crawler->filterXPath('//*[@class="category-items"]/li/a')->each(function (Crawler $categoryItem, $index) {
            $this->links [] = $this->url . $categoryItem->attr('href');
        });
        $bar = $this->output->createProgressBar(count($this->links));


        foreach ($this->links as $link) {
            $this->products = array_merge($this->products, $this->crawl_one_product_page($link));
            $bar->advance();
        }

        Excel::create('storedj', function ($excel) {
            $excel->sheet('sheet1', function ($sheet) {
                $sheet->fromArray($this->products);
            });
        })->store('csv');

        $bar->finish();

        dd("End of Crawl");
    }


    private function crawl_one_product_page($url)
    {
        $response = Curl::to($url)
            ->withHeaders([
                'Accept-Language: en-us',
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
                'Connection: Keep-Alive',
                'Cache-Control: no-cache',
            ])
            ->withOption('HEADER', 1)
            ->returnResponseObject()
            ->get();
        preg_match_all('/Set-Cookie:(.*?);/', $response->content, $m);
        $postData = "";
        if (isset($m[1])) {
            $cookies = $m[1];
            foreach ($cookies as $cookie) {
                list($index, $value) = (explode('=', $cookie, 2));
                $postData .= "$index=$value;";
            }
        }
        $initCrawler = new Crawler($response->content);
        $total = intval($initCrawler->filterXPath('//*[@data-record-count]')->attr('data-record-count'));

        $title = $initCrawler->filterXPath('//title')->text();
        $categoryName = str_replace('- Store DJ', '', $title);


        preg_match('#dynamicServiceSessionId=(.*?);#', $postData, $sessionIdMatches);
        $sessionId = "";
        if (isset($sessionIdMatches[1])) {
            $sessionId = $sessionIdMatches[1];
        }

        $products = [];
        $page = 1;

        do {
            $ajaxUrl = 'https://www.storedj.com.au/service/products/GetInfiniteScrollingProducts?rand=' . rand(1, 100000);
            $result = Curl::to($ajaxUrl)
                ->withHeaders([
                    'Accept-Language: en-us',
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
                    'Connection: Keep-Alive',
                    'Cache-Control: no-cache',
                    'Cookie: ' . $postData
                ])
                ->returnResponseObject()
                ->withData([
                    'pageNumber' => $page,
                    'pageSizeArg' => 30,
                    'rawUrl' => $url,
                    'templateName' => 'Product List Item Zoned',
                    '_applicationType' => 'cssnet',
                    '_sessionId' => $sessionId
                ])
                ->asJsonRequest()
                ->asJsonResponse()
                ->post();
            foreach ($result->content->data->result as $singleProduct) {
                $crawler = new Crawler($singleProduct);
                $productLink = $this->url . $crawler->filterXPath('//a')->attr('href');
                $productName = $crawler->filterXPath('//a/img')->attr('title');
                $products [] = [
                    'product_name' => $productName,
                    'product_link' => $productLink,
                    'category_name' => $categoryName,
                    'category_link' => $url
                ];
            }
            $page++;
        } while (count($products) < $total);
        return $products;
    }
}
