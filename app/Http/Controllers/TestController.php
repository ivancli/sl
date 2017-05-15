<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 21/03/2017
 * Time: 9:31 PM
 */

namespace App\Http\Controllers;


use App\Contracts\Repositories\UrlManagement\CrawlerContract;
use App\Contracts\Repositories\UrlManagement\ItemContract;
use App\Contracts\Repositories\UrlManagement\ItemMetaContract;
use App\Contracts\Repositories\UrlManagement\ParserContract;
use App\Contracts\Repositories\UrlManagement\UrlContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    protected $request;
    protected $crawlerRepo, $parserRepo, $urlRepo, $itemRepo, $itemMetaRepo;

    public function __construct(Request $request,
                                ItemContract $itemContract,
                                ItemMetaContract $itemMetaContract,
                                CrawlerContract $crawlerContract,
                                ParserContract $parserContract,
                                UrlContract $urlContract)
    {
        $this->crawlerRepo = $crawlerContract;
        $this->parserRepo = $parserContract;
        $this->urlRepo = $urlContract;
        $this->itemRepo = $itemContract;
        $this->itemMetaRepo = $itemMetaContract;
    }

    public function test()
    {
        $item = $this->itemRepo->get(2);



        $itemMeta = $item->metas()->where('id', 5)->first();

        DB::enableQueryLog();
        ($itemMeta->historicalPrices);
        dd(DB::getQueryLog());
        dump($item->recentPrice);
        $item->interval = 12;
        dd($item->recentPrice);


        $crawler = $this->crawlerRepo->get(7);
        $content = $this->crawlerRepo->fetch($crawler);
        $content = $content['content'];

        preg_match_all('#variants\[(\d+?)\]\[1\]\[\d+\]=(\d+?);#', $content, $matches);
        dd($matches);
    }
}