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


        $url = Url::findOrFail(10);
        $crawler = $url->crawler;
        $result = $this->crawlerRepo->fetch($crawler);
        $content = $result['content'];
        $items = $url->items;
        foreach ($items as $item) {
            foreach ($item->metas as $meta) {
                if ($meta->is_supportive == 'y') {
                    continue;
                }
                if ($meta->confs()->count() == 0) {
                    continue;
                }
                $parserResult = $this->parserRepo->parseMeta($meta, $content);
                dd($parserResult);

                if ($meta->format_type == 'boolean') {
                    #region Boolean Meta
                    $boolean = $parserResult !== false && is_array($parserResult) && count($parserResult) > 0;

                    event(new BeforeSaveMeta($meta));

                    $valueDifferent = $meta->value != $boolean;
                    $meta->value = $boolean;
                    $meta->save();
                    $meta->createHistoricalData($boolean);

                    if ($valueDifferent) {
                        event(new MetaChanged($meta));
                    }

                    $meta->statusStandby();
                    event(new AfterSaveMeta($meta));
                    #endregion
                } else {
                    if ($parserResult !== false && is_array($parserResult) && count($parserResult) > 0) {
                        if (count($parserResult) == 1) {
                            $firstResult = array_first($parserResult);
                            if (!is_null($firstResult)) {

                                #region format parsed data
                                switch ($meta->format_type) {
                                    case "decimal":
                                        $firstResult = $parserRepo->formatMetaValue([
                                            'strip_text', 'currency'
                                        ], $firstResult);
                                        break;
                                    default:
                                }
                                #endregion

                                if (!empty($firstResult)) {

                                    #region save final result
                                    event(new BeforeSaveMeta($meta));

                                    $valueDifferent = $meta->value != $firstResult;
                                    $meta->value = $firstResult;
                                    $meta->save();
                                    $meta->createHistoricalData($firstResult);

                                    if ($valueDifferent) {
                                        event(new MetaChanged($meta));
                                    }

                                    $meta->statusStandby();
                                    event(new AfterSaveMeta($meta));
                                    #endregion

                                } else {
                                    /* empty result after formatted */
                                    $meta->statusFormatFailed();
                                    event(new NoFormatResult($meta));
                                }
                            } else {
                                /* first result is null */
                                $meta->statusParseFailed();
                                event(new NoFirstResult($meta));
                            }
                        } else {
                            /* there are multiple results/nodes */
                            /* json-ise the result */
                        }
                    } else {
                        /* parser has no result*/
                        $meta->statusParseFailed();
                        event(new NoParseResult($meta));
                    }
                }
            }
        }

//
//        /* CREATE CRAWLER */
//        $crawlerConf = $url->domain->getConf('CUSTOM_CRAWLER');
//        $crawlerName = !is_null($crawlerConf) ? $crawlerConf->value : null;
//        $crawler = new Crawler;
//        $url->crawler()->save($crawler);
//        $crawler->setConf([
//            'class' => $crawlerName
//        ]);
//
//
//        $domain = $url->domain;
//        if (!is_null($domain)) {
//            $customItemGenerator = $domain->getConf('CUSTOM_ITEM_GENERATOR');
//            if (!is_null($customItemGenerator) && !is_null($customItemGenerator->value) && !empty($customItemGenerator->value)) {
//                $content = $this->crawlerRepo->fetch($crawler);
//                if ($content['status'] == 200) {
//                    $itemGenerator = app(self::ITEM_GENERATORS_PATH . $customItemGenerator->value);
//
//                    $itemGenerator->setContent($content['content']);
//                    $hasMultipleItems = $itemGenerator->extractOptions();
//                    if ($hasMultipleItems) {
//                        $itemGenerator->combinations($itemGenerator->getOptions());
//                        $items = $itemGenerator->getItems();
//                        foreach ($items as $targetItem) {
//                            $names = [];
//                            foreach ($targetItem as $label => $option) {
//                                if (isset($option->text)) {
//                                    $names[] = "{$label}: {$option->text}";
//                                }
//                            }
//                            $item = new Item([
//                                'name' => implode(", ", $names)
//                            ]);
//                            $url->items()->save($item);
//                            foreach ($targetItem as $label => $option) {
//                                /* creating supportive meta */
//                                if (isset($option->text)) {
//                                    $itemMeta = $item->setMeta($label, $option->text, true);
//                                }
//                            }
//                            $targetItemOptionValues = array_pluck($targetItem, 'value');
//                            foreach ($url->domain->metas as $domainMeta) {
//                                $itemMeta = $item->setMeta($domainMeta->element, null, false, $domainMeta->format_type, $domainMeta->historical_type);
//                                foreach ($domainMeta->confs as $domainMetaConf) {
//                                    $itemMeta->setConf($domainMetaConf->element, $domainMetaConf->value);
//                                }
//                                $customParser = $domain->getConf('CUSTOM_PARSER');
//                                if (!is_null($customParser)) {
//                                    $itemMeta->setConf('PARSER_CLASS', $customParser->value);
//                                    foreach ($targetItemOptionValues as $targetItemOptionValue) {
//                                        $itemMeta->setConf('OPTION_VALUE', $targetItemOptionValue);
//                                    }
//                                }
//                            }
//                        }
//                    } else {
//                        /* CREATE AN ITEM */
//                        $item = $this->__createItemWithDomainReplication($url);
//                    }
//                } else {
//                    /* CREATE AN ITEM */
//                    $item = $this->__createItemWithDomainReplication($url);
//                }
//            } else {
//                /* CREATE AN ITEM */
//                $item = $this->__createItemWithDomainReplication($url);
//            }
//        } else {
//            /* CREATE AN ITEM */
//            $item = new Item;
//            $url->items()->save($item);
//            /*standard entities - price and availability*/
//            $priceMeta = $item->setMeta('PRICE', null, false, 'decimal', 'price');
//            $availabilityMeta = $item->setMeta('AVAILABILITY', null, false, 'boolean');
//        }
//        /* TODO check common crawler configuration */
//
//
//        $apiClass = app(APICrawler::class);
////        $apiClass->setUrl('http://www.ebay.com/itm/Apple-iPhone-6-6S-16GB-Space-Grey-Gold-Silver-Rose-Gold-Unlocked-Smartphone-/361918015137?var=&hash=item5443fea6a1:m:mz5ZCWTlejC9vrcJndV2lHw');
//        $apiClass->setUrl('http://www.ebay.com/itm/Samsung-Galaxy-S8-VERIZON-GSM-UNLOCKED-BRAND-NEW-1-Year-Warranty-USA-MODEL-/162483126750?var=461492888113&_trkparms=%26rpp_cid%3D58dc0402e4b01fcc357c5e94%26rpp_icid%3D58f94b2ee4b0d69a4e1bde8e');
////        $apiClass->setUrl('http://www.ebay.com/itm/Nike-SB-Benassi-SolarSoft-Mens-Slide-840067-601-Red-White-Size-7/182601211361');
//        $apiClass->fetch();
//
//        $status = $apiClass->getStatus();
//        $content = $apiClass->getContent();
//        dd(json_decode($content));
//        $itemGenerator = app(MultipleItemGenerator::class);
//        $itemGenerator->setContent($content);
//        $itemGenerator->extractOptions();
//        $itemGenerator->combinations($itemGenerator->getOptions());

//        $apiClass = app(DefaultCrawler::class);
//        $apiClass->setUrl('http://www.davolucelighting.com.au/12W-LED-Ceiling-Downight-By-Loomi-Lighting.html');
////        $apiClass->setUrl('http://www.ebay.com/itm/Samsung-Galaxy-S8-VERIZON-GSM-UNLOCKED-BRAND-NEW-1-Year-Warranty-USA-MODEL-/162483126750?var=461492888113&_trkparms=%26rpp_cid%3D58dc0402e4b01fcc357c5e94%26rpp_icid%3D58f94b2ee4b0d69a4e1bde8e');
////        $apiClass->setUrl('http://www.ebay.com/itm/Nike-SB-Benassi-SolarSoft-Mens-Slide-840067-601-Red-White-Size-7/182601211361');
//        $apiClass->fetch();
//
//        $status = $apiClass->getStatus();
//        $content = $apiClass->getContent();
//
//        $itemGenerator = app(MultipleItemGenerator::class);
//        $itemGenerator->setContent($content);
//        $itemGenerator->extractOptions();
//        $itemGenerator->combinations($itemGenerator->getOptions());
        dd($itemGenerator->getItems());
    }

    private function __createItemWithDomainReplication(Url $url)
    {
        $item = new Item;
        $url->items()->save($item);
        /* REPLICATE DOMAIN META IN URL ITEM META*/
        /* if domain has meta data set up*/
        if ($url->domain->metas()->count() > 0) {
            foreach ($url->domain->metas as $domainMeta) {
                $itemMeta = $item->setMeta($domainMeta->element, null, false, $domainMeta->format_type, $domainMeta->historical_type);
                foreach ($domainMeta->confs as $domainMetaConf) {
                    $itemMeta->setConf($domainMetaConf->element, $domainMetaConf->value);
                }
            }
        } else {
            /*standard entities - price and availability*/
            $priceMeta = $item->setMeta('PRICE', null, false, 'decimal', 'price');
            $availabilityMeta = $item->setMeta('AVAILABILITY', null, false, 'boolean');
        }
        return $item;
    }
}