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
use App\Models\Crawler;
use App\Models\Domain;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use App\Models\Item;
use Illuminate\Http\Request;
use IvanCLI\Parser\Repositories\XPathParser;

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
        $domain = Domain::findOrFail(1);
        $crawler = Crawler::findOrFail(1);

        /* CHECK MULTI-ITEM DETERMINANT */
        $multiItemDeterminants = $domain->metas->filter(function ($meta, $index) {
            return $meta->usage == 'multi-item';
        });
        if ($multiItemDeterminants->count() > 0) {

            /* CRAWL HTML AND ANALYSE */
            $result = $this->crawlerRepo->fetch($crawler);
            if (array_get($result, 'status') != 200) {
                /*TODO go for another method of configuration*/
            }
            $htmlContent = array_get($result, 'content');
            foreach ($multiItemDeterminants as $multiItemDeterminant) {
                foreach ($multiItemDeterminant->confs as $conf) {
                    switch ($conf->element) {
                        case "XPATH":

                            $domCrawler = new DomCrawler($htmlContent);
                            $xpathNodes = $domCrawler->filterXPath("//*[@onchange=\"javascript: check_options();\"]");
                            $extractions = [];
                            if (count($xpathNodes) == 0) {
                                return false;
                            }
                            foreach ($xpathNodes as $xpathNode) {
                                dd($xpathNode->ownerDocument->saveHTML($xpathNode));
                                if ($xpathNode->nodeValue) {
                                    $extraction = $xpathNode->nodeValue;
                                } else {
                                    $extraction = $xpathNode->textContent;
                                }
                            }



                            break;
                        case "REGEX":
                            /*TODO implement regex multi item determinant*/
                            break;
                    }
                }
            }
        }
    }
}

