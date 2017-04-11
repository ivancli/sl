<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/6/2017
 * Time: 11:44 AM
 */

namespace App\Http\Controllers\UrlManagement;


use App\Contracts\Repositories\UrlManagement\CrawlerContract;
use App\Contracts\Repositories\UrlManagement\ParserContract;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemMeta;
use App\Models\Url;
use Illuminate\Http\Request;

class TestController extends Controller
{
    protected $request;
    protected $crawlerRepo;
    protected $parserRepo;

    public function __construct(Request $request,
                                CrawlerContract $crawlerContract, ParserContract $parserContract)
    {
        $this->request = $request;
        $this->crawlerRepo = $crawlerContract;
        $this->parserRepo = $parserContract;
    }

    /**
     * @param ItemMeta $itemMeta
     * @return array
     */
    public function crawlParseItemMeta(ItemMeta $itemMeta)
    {
        $crawlResult = $this->crawlerRepo->fetch($itemMeta->item->url->crawler);

        if ($crawlResult['status'] != 200) {
            $error = "Target URL respond with status {$crawlResult['status']}";
            $status = false;
            return compact(['status', 'error']);
        }

        $content = $crawlResult['content'];

        $results = $this->parserRepo->parseMeta($itemMeta, $content);
        if ($itemMeta->format_type == 'boolean') {
            $results = [$results != false && is_array($results) && count($results) > 0];
        } else {
            if ($results != false && is_array($results) && count($results) > 0) {
                foreach ($results as $index => $result) {
                    switch ($itemMeta->format_type) {
                        case "decimal":
                            $results[$index] = $this->parserRepo->formatMetaValue([
                                'strip_text', 'currency'
                            ], $result);
                            break;
                        default:
                    }
                }
            }
        }
        $status = true;
        return compact(['results', 'status']);
    }

    public function crawlParseItem(Item $item)
    {
        /*
         * TODO find a way to refine $item->url->crawler
         */
        $crawlResult = $this->crawlerRepo->fetch($item->url->crawler);

        if ($crawlResult['status'] != 200) {
            $error = "Target URL respond with status {$crawlResult['status']}";
            $status = false;
            return compact(['status', 'error']);
        }

        $content = $crawlResult['content'];

        $results = [];

        foreach ($item->metas as $metaIndex => $meta) {
            $parseResults = $this->parserRepo->parseMeta($meta, $content);

            if ($meta->format_type == 'boolean') {
                $parseResults = [$parseResults != false && is_array($parseResults) && count($parseResults) > 0];
            } else {
                if ($parseResults != false && is_array($parseResults) && count($parseResults) > 0) {
                    foreach ($parseResults as $index => $parseResult) {
                        switch ($meta->format_type) {
                            case "decimal":
                                $parseResults[$index] = $this->parserRepo->formatMetaValue([
                                    'strip_text', 'currency'
                                ], $parseResult);
                                break;
                            default:
                        }
                    }
                } else {
                    $parseResults = false;
                }
            }
            $results[$meta->getKey()] = [
                'item_meta' => $meta,
                'results' => $parseResults,
            ];
        }
        $status = true;
        return compact(['status', 'results']);
    }

    public function crawlParseUrl(Url $url)
    {
        /*
         * TODO find a way to refine $item->url->crawler
         */
        $crawlResult = $this->crawlerRepo->fetch($url->crawler);

        if ($crawlResult['status'] != 200) {
            $error = "Target URL respond with status {$crawlResult['status']}";
            $status = false;
            return compact(['status', 'error']);
        }

        $content = $crawlResult['content'];

        $results = [];

        foreach ($url->items as $itemIndex => $item) {
            $itemResults = [];
            foreach ($item->metas as $metaIndex => $meta) {
                $parseResults = $this->parserRepo->parseMeta($meta, $content);
                if ($meta->format_type == 'boolean') {
                    $parseResults = [$parseResults != false && is_array($parseResults) && count($parseResults) > 0];
                } else {
                    if ($parseResults != false && is_array($parseResults) && count($parseResults) > 0) {
                        foreach ($parseResults as $index => $parseResult) {
                            switch ($meta->format_type) {
                                case "decimal":
                                    $parseResults[$index] = $this->parserRepo->formatMetaValue([
                                        'strip_text', 'currency'
                                    ], $parseResult);
                                    break;
                                default:
                            }
                        }
                    } else {
                        $parseResults = false;
                    }
                }
                $itemResults[$meta->getKey()] = [
                    'item_meta' => $meta,
                    'results' => $parseResults,
                ];
            }
            $results[$item->getKey()] = [
                'item' => $item,
                'results' => $itemResults
            ];
        }

        $status = true;
        return compact(['status', 'results']);
    }
}