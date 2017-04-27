<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 21/03/2017
 * Time: 9:31 PM
 */

namespace App\Http\Controllers;


use App\Contracts\Repositories\UrlManagement\CrawlerContract;
use App\Contracts\Repositories\UrlManagement\ParserContract;
use App\Contracts\Repositories\UrlManagement\UrlContract;
use Illuminate\Http\Request;
use IvanCLI\ItemGenerator\Repositories\WwwDavolucelightingComAu;

class TestController extends Controller
{
    protected $request;
    protected $crawlerRepo, $parserRepo, $urlRepo;

    public function __construct(Request $request,
                                CrawlerContract $crawlerContract,
                                ParserContract $parserContract,
                                UrlContract $urlContract)
    {
        $this->crawlerRepo = $crawlerContract;
        $this->parserRepo = $parserContract;
        $this->urlRepo = $urlContract;
    }

    public function test()
    {
        $crawler = $this->crawlerRepo->get(3);
        $content = $this->crawlerRepo->fetch($crawler);
        $content = $content['content'];

        $itemGenerator = new WwwDavolucelightingComAu();
        $itemGenerator->setContent($content);
        $itemGenerator->extractOptions();
        $itemGenerator->combinations($itemGenerator->getOptions());
        $items = $itemGenerator->getItems();
        dd($items);
    }
}