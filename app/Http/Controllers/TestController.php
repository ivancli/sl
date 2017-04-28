<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 21/03/2017
 * Time: 9:31 PM
 */

namespace App\Http\Controllers;


use App\Contracts\Repositories\UrlManagement\CrawlerContract;
use App\Contracts\Repositories\UrlManagement\ItemMetaContract;
use App\Contracts\Repositories\UrlManagement\ParserContract;
use App\Contracts\Repositories\UrlManagement\UrlContract;
use Illuminate\Http\Request;

class TestController extends Controller
{
    protected $request;
    protected $crawlerRepo, $parserRepo, $urlRepo, $itemMetaRepo;

    public function __construct(Request $request,
                                ItemMetaContract $itemMetaContract,
                                CrawlerContract $crawlerContract,
                                ParserContract $parserContract,
                                UrlContract $urlContract)
    {
        $this->crawlerRepo = $crawlerContract;
        $this->parserRepo = $parserContract;
        $this->urlRepo = $urlContract;
        $this->itemMetaRepo = $itemMetaContract;
    }

    public function test()
    {
        $itemMeta = $this->itemMetaRepo->get(115);
        dd($itemMeta->confs->pluck('value', 'element'));


        $crawler = $this->crawlerRepo->get(7);
        $content = $this->crawlerRepo->fetch($crawler);
        $content = $content['content'];

        preg_match_all('#variants\[(\d+?)\]\[1\]\[\d+\]=(\d+?);#', $content, $matches);
        dd($matches);
    }
}