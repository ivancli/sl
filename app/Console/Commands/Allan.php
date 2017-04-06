<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/3/2017
 * Time: 4:32 PM
 */

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Ixudra\Curl\CurlService;
use Ixudra\Curl\Facades\Curl;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\DomCrawler\Crawler;

class Allan extends Command
{
    var $urlRepo;
    var $urls = [
//        "http://www.allansbillyhyde.com.au/prodcat/Music-Tech_Recording-and-DJ/Recording-Interfaces/USB.aspx",

        "http://www.allansbillyhyde.com.au/prodcat/Guitars.aspx",
//        "http://www.allansbillyhyde.com.au/prodcat/Bass-Guitars.aspx",
//        "http://www.allansbillyhyde.com.au/prodcat/Folk-and-Bluegrass.aspx",
//        "http://www.allansbillyhyde.com.au/prodcat/Drums-and-Percussion.aspx",
//        "http://www.allansbillyhyde.com.au/prodcat/P.A.-Live-Sound-and-Lighting.aspx",
//        "http://www.allansbillyhyde.com.au/prodcat/Band-and-Orchestral.aspx",
//        "http://www.allansbillyhyde.com.au/prodcat/Pianos-and-Keyboards.aspx",
//        "http://www.allansbillyhyde.com.au/prodcat/Print-Music-and-DVDs.aspx",
    ];
    var $products = [];
    var $baseUrl = "http://www.allansbillyhyde.com.au";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler-allan';
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
        $counter = 0;
        $bar = $this->output->createProgressBar(100);
        foreach ($this->urls as $url) {
            $curlService = new CurlService();
            $response = $curlService->to($url)
                ->withHeaders([
                    'Accept-Language: en-us',
                    'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.15) Gecko/20110303 Firefox/3.6.15',
                    'Connection: Keep-Alive',
                    'Cache-Control: no-cache',
                ])
                ->returnResponseObject()
                ->withOption("HEADER", true)
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


            $curlService = new CurlService();
            $response = $curlService->to($url)
                ->withHeaders([
                    'Accept-Language: en-us',
                    'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.15) Gecko/20110303 Firefox/3.6.15',
                    'Connection: Keep-Alive',
                    'Cache-Control: no-cache',
                    'Cookie: ' . $postData,
                ])
                ->returnResponseObject()
                ->withOption("HEADER", true)
                ->get();

            do {
                preg_match_all('/Set-Cookie:(.*?);/', $response->content, $m);
                $postData = "";
                if (isset($m[1])) {
                    $cookies = $m[1];
                    foreach ($cookies as $cookie) {
                        list($index, $value) = (explode('=', $cookie, 2));
                        $postData .= "$index=$value;";
                    }
                }


                $this->products = array_merge($this->products, $this->loadOnePageData($response, $url));
                preg_match('#"pageGUID":"(.*?)"#', $response->content, $matches);
                $pageGUID = $matches[1];
                $crawler = new Crawler($response->content);
                $url = $this->baseUrl . $crawler->filterXPath('//form[@id="aspnetForm"]')->attr('action');
                $previousUrl = $url;
                $url = strtok($url, "?");
                $url = $url . "?RadUrid=$pageGUID";

                preg_match('#_TSM_CombinedScripts_=(.*?)"#', $response->content, $radMatches);
                $rad = urldecode($radMatches[1]);


                $viewstate = $crawler->filterXPath('//input[@id="__VIEWSTATE"]')->attr('value');
                if ($crawler->filterXPath('//a[@id="MainContent_ctl19_dlProductList_LinkButtonNext"][1]')->count() > 0) {
                    $pageNodes = $crawler->filterXPath('//a[@id="MainContent_ctl19_dlProductList_LinkButtonNext"][1]');
                } else {
                    $pageNodes = $crawler->filterXPath('//a[@id="MainContent_pdlAllProducts_dlProductList_LinkButtonNext"][1]');
                }
                $upcomingPagesUrl = null;
                if (count($pageNodes) > 0) {
                    /*load upcoming pages url*/
                    foreach ($pageNodes as $pageNode) {
                        $javascript = $pageNode->getAttribute('href');
                        $javascript = str_replace("javascript:__doPostBack('", '', $javascript);
                        $javascript = str_replace("','')", '', $javascript);
                        $upcomingPagesUrl = $javascript;
                        break;
                    }
                } else {
                    break;
                }

                $response = Curl::to($url)
                    ->withHeaders([
                        'Accept-Language: en-us',
                        'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.15) Gecko/20110303 Firefox/3.6.15',
                        'Connection: Keep-Alive',
                        'Cache-Control: no-cache',
                        'Cookie: ' . $postData,
                        'Referer:' . $previousUrl,
                    ])
                    ->withOption("FOLLOWLOCATION", true)
                    ->withOption("HEADER", true)
                    ->withData(array(
                        "__EVENTTARGET" => $upcomingPagesUrl,
                        "__EVENTARGUMENT" => '',
                        "__VIEWSTATE" => $viewstate,
                        "RadScriptManager1_TSM" => $rad
                    ))->returnResponseObject()
                    ->post();
                $counter++;
                $bar->advance();
                if($counter>4){
                    dd($this->products);
                }

            } while ($counter < 74);
        }
        Excel::create('allan', function ($excel) {
            $excel->sheet('sheet1', function ($sheet) {
                $sheet->fromArray($this->products);
            });
        })->store('csv');
        $bar->finish();
        dd("finished");
    }


    private function loadOnePageData($response, $url)
    {
        $crawler = new Crawler($response->content);
        $products = [];

        $crawler->filterXPath('//*[@class="productListBreadcrumb"]/following-sibling::table//tr[position()>1][position() < last()]/*[@class="padded5"]')->each(function (Crawler $node, $i) use ($crawler, &$products, $url) {
            $productHref = $node->filterXPath('//tr/td/a[@class="ProdLink"]')->attr("href");
            $productName = $node->filterXPath('//tr/td/a[@class="ProdLink"]/div')->text();
            $categoryLink = $url;
            $productName = trim(str_replace("\r\n", '', $productName));
            $products[] = [
                "name" => $productName,
                "link" => $productHref,
                "category_link" => $categoryLink
            ];
        });
        return $products;
    }
}
