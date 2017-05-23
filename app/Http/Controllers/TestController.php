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
use App\Models\Alert;
use App\Models\Site;
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
        $alert = Alert::first();
        dd($alert->alertable_type);
    }
}