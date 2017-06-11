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
use Illuminate\Support\Facades\DB;
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
        $query = DB::table('sites')
            ->join('products', 'sites.product_id', 'products.id')
            ->join('categories', 'products.category_id', 'categories.id')
            ->join('users', 'users.id', 'products.user_id')
            ->join('items', 'sites.item_id', 'items.id')
            ->join('urls', 'urls.id', 'sites.url_id')
            ->join('item_metas AS price', function ($join) {
                $join->on('price.item_id', 'items.id')
                    ->where('price.element', 'PRICE');
            })
            ->leftJoin('item_metas AS ebay', function ($join) {
                $join->on('ebay.item_id', 'items.id')
                    ->where('ebay.element', 'SELLER_USERNAME');
            })
            ->leftJoin('user_domains', function ($join) {
                $join->on('users.id', 'user_domains.user_id')
                    ->where('user_domains.domain', 'LIKE', DB::raw('CONCAT("%", urls.full_path, "%")'));
            })
            ->join('previous_prices_professional', 'previous_prices_professional.item_meta_id', 'price.id')
            ->where('products.user_id', 1);

        $query->select([
            'sites.*',
            'price.value AS recent_price',
            'previous_prices_professional.amount AS previous_price',
            'ebay.value AS ebay_username',
            'user_domains.alias AS site_name',
            'urls.full_path',
            'products.product_name',
            'categories.category_name'
        ]);
        dd($query->first());

    }

}