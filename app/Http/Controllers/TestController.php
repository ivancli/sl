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
        $url = $this->urlRepo->get(4);


        $crawler = $url->crawler;

        /* TODO fetch */
        $content = $this->crawlerRepo->fetch($crawler);

        /*TODO check if content==false*/

        /* TODO parse for each item */
        $items = $url->items;
//        $result = $parserRepo->extract($content, [
//            "xpath" => "//*[@class='price-now']"
//        ]);
        foreach ($items as $item) {
            foreach ($item->metas as $meta) {
                $parserResult = $this->parserRepo->parseMeta($meta, $content);
                /*TODO save data to meta data and historical prices*/
                if ($parserResult !== false && is_array($parserResult) && count($parserResult) > 0) {
                    $firstConf = array_first($parserResult);
                    $firstConfResult = array_get($firstConf, 'result');
                    if (!is_null($firstConfResult) && is_array($firstConfResult)) {
                        $firstResult = array_first($firstConfResult);
                        if (!is_null($firstResult) && is_array($firstResult)) {
                            $resultFirstPart = array_first($firstResult);
                            if (!is_null($resultFirstPart)) {
                                $resultFirstPart = $this->parserRepo->formatMetaValue([
                                    'strip_text', 'currency'
                                ], $resultFirstPart);
                                if (!empty($resultFirstPart)) {
                                    $meta->value = $resultFirstPart;
                                    $meta->save();
                                }
                            }
                        }
                    }
                } else {
                    /* parser has no result*/
                }
            }
        }

        dd("End of Crawl");
    }
}