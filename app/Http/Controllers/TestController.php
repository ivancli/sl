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

class TestController extends Controller
{
    var $request;
    var $crawlerRepo, $parserRepo, $urlRepo;

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
        $url = $this->urlRepo->get(2);


        /* TODO fetch */
        $content = $this->crawlerRepo->fetch($url->crawler);

        foreach ($url->items as $item) {
            foreach ($item->metas as $meta) {
                foreach ($meta->confs as $conf) {
                    if ($conf->element == 'XPATH') {
                        /* TODO parse for each item */
                        $result = $this->parserRepo->extract($content, [
                            "xpath" => $conf->value
                        ]);
                        dump($result);
                    }
                }
            }
        }

        dd("called");
    }
}