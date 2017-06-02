<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 21/03/2017
 * Time: 9:31 PM
 */

namespace App\Http\Controllers;


use App\Contracts\Repositories\Report\ReportContract;
use IvanCLI\Crawler\Repositories\DefaultCrawler;
use IvanCLI\Crawler\Repositories\EBAY\AccessToken;
use IvanCLI\Crawler\Repositories\EBAY\APICrawler;
//use IvanCLI\ItemGenerator\Repositories\DAVOLUCELIGHTING\MultipleItemGenerator;

use IvanCLI\ItemGenerator\Repositories\EBAY\MultipleItemGenerator;

class TestController extends Controller
{


    /**
     * Create a new job instance.
     * @param ReportContract $reportContract
     */
    public function __construct()
    {

    }

    public function test()
    {
        $apiClass = app(APICrawler::class);
//        $apiClass->setUrl('http://www.ebay.com/itm/Apple-iPhone-6-6S-16GB-Space-Grey-Gold-Silver-Rose-Gold-Unlocked-Smartphone-/361918015137?var=&hash=item5443fea6a1:m:mz5ZCWTlejC9vrcJndV2lHw');
        $apiClass->setUrl('http://www.ebay.com/itm/Samsung-Galaxy-S8-VERIZON-GSM-UNLOCKED-BRAND-NEW-1-Year-Warranty-USA-MODEL-/162483126750?var=461492888113&_trkparms=%26rpp_cid%3D58dc0402e4b01fcc357c5e94%26rpp_icid%3D58f94b2ee4b0d69a4e1bde8e');
//        $apiClass->setUrl('http://www.ebay.com/itm/Nike-SB-Benassi-SolarSoft-Mens-Slide-840067-601-Red-White-Size-7/182601211361');
        $apiClass->fetch();

        $status = $apiClass->getStatus();
        $content = $apiClass->getContent();

        $itemGenerator = app(MultipleItemGenerator::class);
        $itemGenerator->setContent($content);
        $itemGenerator->extractOptions();
        $itemGenerator->combinations($itemGenerator->getOptions());

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
}