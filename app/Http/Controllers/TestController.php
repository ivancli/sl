<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 21/03/2017
 * Time: 9:31 PM
 */

namespace App\Http\Controllers;


use App\Contracts\Repositories\Report\ReportContract;
use App\Contracts\Repositories\UrlManagement\CrawlerContract;
use App\Contracts\Repositories\UrlManagement\ParserContract;
use App\Models\Crawler;
use App\Models\Domain;
use App\Models\Item;
use App\Models\Url;
use Carbon\Carbon;
use IvanCLI\Crawler\Repositories\DefaultCrawler;
use IvanCLI\Crawler\Repositories\EBAY\AccessToken;
use IvanCLI\Crawler\Repositories\EBAY\APICrawler;
//use IvanCLI\ItemGenerator\Repositories\DAVOLUCELIGHTING\MultipleItemGenerator;

use IvanCLI\ItemGenerator\Repositories\EBAY\MultipleItemGenerator;

class TestController extends Controller
{
    const ITEM_GENERATORS_PATH = 'IvanCLI\ItemGenerator\Repositories\\';
    protected $crawlerRepo;
    protected $parserRepo;

    /**
     * Create a new job instance.
     * @param CrawlerContract $crawlerContract
     * @param ParserContract $parserContract
     * @internal param ReportContract $reportContract
     */
    public function __construct(CrawlerContract $crawlerContract, ParserContract $parserContract)
    {
        $this->crawlerRepo = $crawlerContract;
        $this->parserRepo = $parserContract;
    }

    public function test()
    {
        auth()->user()->subscription->subscriptionCriteria;

    }

}