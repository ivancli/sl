<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/4/2017
 * Time: 9:18 AM
 */

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Ixudra\Curl\Facades\Curl;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\DomCrawler\Crawler;

class Portmacguitars extends Command
{
    var $urlRepo;
    var $urls = [
        "http://www.portmacguitars.com.au/electric-guitars/",
        "http://www.portmacguitars.com.au/electric-guitar-showcase/",
        "http://www.portmacguitars.com.au/electric-guitars/fender-electric-guitars/",
        "http://www.portmacguitars.com.au/MAGNIFICENT-7-LIMITED-EDITION-RANGE-buy-australia",
        "http://www.portmacguitars.com.au/Fender-American-Professional-Series-Electric-Guitar",
        "http://www.portmacguitars.com.au/American-Elite-Series",
        "http://www.portmacguitars.com.au/electric-guitars/fender-electric-guitars/american-standard-series/",
        "http://www.portmacguitars.com.au/electric-guitars/fender-electric-guitars/american-special-series/",
        "http://www.portmacguitars.com.au/electric-guitars/fender-electric-guitars/american-vintage-series/",
        "http://www.portmacguitars.com.au/electric-guitars/fender-electric-guitars/artist-series/",
        "http://www.portmacguitars.com.au/electric-guitars/fender-electric-guitars/custom-shop/",
        "http://www.portmacguitars.com.au/electric-guitars/fender-electric-guitars/standard-series/",
        "http://www.portmacguitars.com.au/fender-offset-series",
        "http://www.portmacguitars.com.au/electric-guitars/fender-electric-guitars/classic-series/",
        "http://www.portmacguitars.com.au/electric-guitars/fender-electric-guitars/roadworn-series/",
        "http://www.portmacguitars.com.au/electric-guitars/fender-electric-guitars/deluxe-series/",
        "http://www.portmacguitars.com.au/electric-guitars/fender-electric-guitars/",
        "http://www.portmacguitars.com.au/electric-guitars/fender-electric-guitars/pawn-shop-series/",
        "http://www.portmacguitars.com.au/electric-guitars/fender-electric-guitars/classic-player-series/",
        "http://www.portmacguitars.com.au/electric-guitars/ibanez-electric-guitars/",
        "http://www.portmacguitars.com.au/electric-guitars/ibanez-electric-guitars/signature-series/",
        "http://www.portmacguitars.com.au/electric-guitars/ibanez-electric-guitars/iron-label/",
        "http://www.portmacguitars.com.au/electric-guitars/ibanez-electric-guitars/rg-series/",
        "http://www.portmacguitars.com.au/electric-guitars/ibanez-electric-guitars/s-series/",
        "http://www.portmacguitars.com.au/talman-series/",
        "http://www.portmacguitars.com.au/electric-guitars/ibanez-electric-guitars/rgd-series/",
        "http://www.portmacguitars.com.au/electric-guitars/ibanez-electric-guitars/arz-series/",
        "http://www.portmacguitars.com.au/electric-guitars/ibanez-electric-guitars/sa-series/",
        "http://www.portmacguitars.com.au/electric-guitars/ibanez-electric-guitars/fr-series/",
        "http://www.portmacguitars.com.au/electric-guitars/ibanez-electric-guitars/ar-series/",
        "http://www.portmacguitars.com.au/electric-guitars/ibanez-electric-guitars/roadcore-series/",
        "http://www.portmacguitars.com.au/electric-guitars/ibanez-electric-guitars/mikro-series/",
        "http://www.portmacguitars.com.au/electric-guitars/squier/",
        "http://www.portmacguitars.com.au/electric-guitars/squier/classic-vibe/",
        "http://www.portmacguitars.com.au/electric-guitars/squier/affinity-series/",
        "http://www.portmacguitars.com.au/electric-guitars/squier/vintage-modified-series/",
        "http://www.portmacguitars.com.au/electric-guitars/squier/artist-series/",
        "http://www.portmacguitars.com.au/electric-guitars/squier/standard-series/",
        "http://www.portmacguitars.com.au/electric-guitars/squier/bullet-series/",
        "http://www.portmacguitars.com.au/electric-guitars/squier/deluxe-series/",
        "http://www.portmacguitars.com.au/prs/",
        "http://www.portmacguitars.com.au/PRS-Mini-site",
        "http://www.portmacguitars.com.au/electric-guitars/prs/core-range/",
        "http://www.portmacguitars.com.au/electric-guitars/prs/s2-range/",
        "http://www.portmacguitars.com.au/electric-guitars/prs/se-range/",
        "http://www.portmacguitars.com.au/electric-guitars/gretsch/",
        "http://www.portmacguitars.com.au/Jackson-electric-Guitars",
        "http://www.portmacguitars.com.au/electric-guitars/evh/",
        "http://www.portmacguitars.com.au/Charvel-Guitars",
        "http://www.portmacguitars.com.au/electric-guitars/squier/squier-xmas-electric-and-bass-packs/",
        "http://www.portmacguitars.com.au/bass-guitars/",
        "http://www.portmacguitars.com.au/bass-guitar-showcase/",
        "http://www.portmacguitars.com.au/bass-guitars/fender/",
        "http://www.portmacguitars.com.au/Fender-American-Professional-Series-Bass-Guitar",
        "http://www.portmacguitars.com.au/bass-guitars/fender/american-standard-series/",
        "http://www.portmacguitars.com.au/bass-guitars/fender/road-worn-series/",
        "http://www.portmacguitars.com.au/bass-guitars/fender/standard-series/",
        "http://www.portmacguitars.com.au/bass-guitars/fender/american-deluxe-series/",
        "http://www.portmacguitars.com.au/bass-guitars/fender/american-vintage-series/",
        "http://www.portmacguitars.com.au/bass-guitars/fender/modern-player-series/",
        "http://www.portmacguitars.com.au/bass-guitars/fender/artist-series/",
        "http://www.portmacguitars.com.au/bass-guitars/fender/deluxe-series/",
        "http://www.portmacguitars.com.au/bass-guitars/fender/acoustic-bass-guitars/",
        "http://www.portmacguitars.com.au/fender-offset-series-bass",
        "http://www.portmacguitars.com.au/bass-guitars/ibanez/",
        "http://www.portmacguitars.com.au/bass-guitars/ibanez/sr-series/",
        "http://www.portmacguitars.com.au/bass-guitars/ibanez/atk-series/",
        "http://www.portmacguitars.com.au/bass-guitars/ibanez/btb-series/",
        "http://www.portmacguitars.com.au/bass-guitars/ibanez/acoustic-basses/",
        "http://www.portmacguitars.com.au/bass-guitars/ibanez/mikro-series/",
        "http://www.portmacguitars.com.au/bass-guitars/ibanez/signature-series/",
        "http://www.portmacguitars.com.au/bass-guitars/ibanez/artcore-series-basses/",
        "http://www.portmacguitars.com.au/bass-guitars/ibanez/talman-series/",
        "http://www.portmacguitars.com.au/bass-guitars/squier/",
        "http://www.portmacguitars.com.au/bass-guitars/squier/artist-series/",
        "http://www.portmacguitars.com.au/bass-guitars/squier/vintage-modified-series/",
        "http://www.portmacguitars.com.au/bass-guitars/squier/classic-vibe-series/",
        "http://www.portmacguitars.com.au/bass-guitars/squier/deluxe-series/",
        "http://www.portmacguitars.com.au/bass-guitars/squier/affinity-series/",
        "http://www.portmacguitars.com.au/bass-guitars/gretsch/",
        "http://www.portmacguitars.com.au/electric-guitars/squier/squier-xmas-electric-and-bass-packs/",
        "http://www.portmacguitars.com.au/acoustic-guitars/",
        "http://www.portmacguitars.com.au/acoustic-guitar-showcase/",
        "http://www.portmacguitars.com.au/acoustic-guitars/martin/",
        "http://www.portmacguitars.com.au/acoustic-guitars/maton/",
        "http://www.portmacguitars.com.au/acoustic-guitars/ibanez/",
        "http://www.portmacguitars.com.au/acoustic-guitars/ibanez/aw-series/",
        "http://www.portmacguitars.com.au/acoustic-guitars/ibanez/artwood-exotic-series/",
        "http://www.portmacguitars.com.au/acoustic-guitars/ibanez/artwood-vintage-series/",
        "http://www.portmacguitars.com.au/acoustic-guitars/ibanez/ew-series/",
        "http://www.portmacguitars.com.au/acoustic-guitars/ibanez/ael-series/",
        "http://www.portmacguitars.com.au/acoustic-guitars/ibanez/aeg-series/",
        "http://www.portmacguitars.com.au/acoustic-guitars/ibanez/performance-series/",
        "http://www.portmacguitars.com.au/acoustic-guitars/ibanez/signature-series/",
        "http://www.portmacguitars.com.au/acoustic-guitars/ibanez/classical-series/",
        "http://www.portmacguitars.com.au/acoustic-guitars/ibanez/talman-series/",
        "http://www.portmacguitars.com.au/acoustic-guitars/cole-clark/",
        "http://www.portmacguitars.com.au/acoustic-guitars/fender/",
        "http://www.portmacguitars.com.au/acoustic-guitars/gretsch/",
        "http://www.portmacguitars.com.au/XMAS-Acoustic-Electric-and-Bass-Packs",
        "http://www.portmacguitars.com.au/hollowbody-guitars/",
        "http://www.portmacguitars.com.au/hollowbody-guitars/ibanez/",
        "http://www.portmacguitars.com.au/hollowbody-guitars/ibanez/signature-series/",
        "http://www.portmacguitars.com.au/artcore-vintage/",
        "http://www.portmacguitars.com.au/hollowbody-guitars/ibanez/artcore-jazz-series/",
        "http://www.portmacguitars.com.au/hollowbody-guitars/ibanez/af-series/",
        "http://www.portmacguitars.com.au/hollowbody-guitars/ibanez/as-series/",
        "http://www.portmacguitars.com.au/hollowbody-guitars/ibanez/ag-series/",
        "http://www.portmacguitars.com.au/hollowbody-guitars/gretsch/",
        "http://www.portmacguitars.com.au/amplifiers/",
        "http://www.portmacguitars.com.au/amplifiers/electric-guitar-amplifiers/",
        "http://www.portmacguitars.com.au/amplifiers/electric-guitar-amplifiers/marshall/",
        "http://www.portmacguitars.com.au/amplifiers/electric-guitar-amplifiers/blackstar/",
        "http://www.portmacguitars.com.au/amplifiers/electric-guitar-amplifiers/fender/",
        "http://www.portmacguitars.com.au/amplifiers/electric-guitar-amplifiers/ibanez/",
        "http://www.portmacguitars.com.au/amplifiers/electric-guitar-amplifiers/evh/",
        "http://www.portmacguitars.com.au/roland/",
        "http://www.portmacguitars.com.au/amplifiers/bass-amplifiers/",
        "http://www.portmacguitars.com.au/amplifiers/bass-amplifiers/ibanez/",
        "http://www.portmacguitars.com.au/amplifiers/bass-amplifiers/fender/",
        "http://www.portmacguitars.com.au/amplifiers/bass-amplifiers/eden/",
        "http://www.portmacguitars.com.au/amplifiers/bass-amplifiers/markbass/",
        "http://www.portmacguitars.com.au/amplifiers/bass-amplifiers/ampeg/",
        "http://www.portmacguitars.com.au/amplifiers/acoustic-guitar-amplifiers/",
        "http://www.portmacguitars.com.au/amplifiers/acoustic-guitar-amplifiers/fender/",
        "http://www.portmacguitars.com.au/amplifiers/acoustic-guitar-amplifiers/ibanez/",
        "http://www.portmacguitars.com.au/effects-pedals/",
        "http://www.portmacguitars.com.au/effects-pedals/mxr/",
        "http://www.portmacguitars.com.au/effects-pedals/blackstar/",
        "http://www.portmacguitars.com.au/effects-pedals/boss/",
        "http://www.portmacguitars.com.au/effects-pedals/boss/compact-pedals/",
        "http://www.portmacguitars.com.au/effects-pedals/boss/double-pedals/",
        "http://www.portmacguitars.com.au/effects-pedals/boss/multi-effects-pedals/",
        "http://www.portmacguitars.com.au",
        "http://www.portmacguitars.com.au/effects-pedals/dunlop/",
        "http://www.portmacguitars.com.au/effects-pedals/dunlop/standard/",
        "http://www.portmacguitars.com.au/effects-pedals/dunlop/wah-pedals/",
        "http://www.portmacguitars.com.au/effects-pedals/digitech/",
        "http://www.portmacguitars.com.au/effects-pedals/electro-harmonix/",
        "http://www.portmacguitars.com.au/effects-pedals/ibanez/",
        "http://www.portmacguitars.com.au/effects-pedals/way-huge/",
        "http://www.portmacguitars.com.au/effects-pedals/rocktron/",
        "http://www.portmacguitars.com.au/accessories/",
        "http://www.portmacguitars.com.au/Clothes-and-Promo-Gear",
        "http://www.portmacguitars.com.au/accessories/hard-cases/",
        "http://www.portmacguitars.com.au/accessories/leads/",
        "http://www.portmacguitars.com.au/accessories/pedal-boards/",
        "http://www.portmacguitars.com.au/accessories/pickups/",
        "http://www.portmacguitars.com.au/accessories/spare-parts/",
        "http://www.portmacguitars.com.au/accessories/straps/",
        "http://www.portmacguitars.com.au/accessories/strings/",
        "http://www.portmacguitars.com.au/accessories/tuners-and-metronomes/",
        "http://www.portmacguitars.com.au/accessories/guitar-picks/",
        "http://www.portmacguitars.com.au/accessories/strap-locks/",
        "http://www.portmacguitars.com.au/guitar-maintenance/",
        "http://www.portmacguitars.com.au/Stools",
        "http://www.portmacguitars.com.au/Guitar-Stands",
        "http://www.portmacguitars.com.au/other-stuff/",
        "http://www.portmacguitars.com.au/p.a/",
        "http://www.portmacguitars.com.au/recording-equipment/",
        "http://www.portmacguitars.com.au/folk-instruments/",
    ];
    var $products = [];
    var $baseUrl = "http://www.allansbillyhyde.com.au";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler-port';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command runs crawler for particular url or all urls if no parameters given.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $bar = $this->output->createProgressBar(count($this->urls));
        foreach ($this->urls as $index=>$url) {
            $response = Curl::to($url)
                ->withHeaders([
                    'Accept-Language: en-us',
                    'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.15) Gecko/20110303 Firefox/3.6.15',
                    'Connection: Keep-Alive',
                    'Cache-Control: no-cache',
                ])
                ->withOption("FOLLOWLOCATION", true)
                ->returnResponseObject()
                ->get();
            $this->products = array_merge($this->products, $this->loadOnePageData($response, $url));
            /*pagination*/
            $crawler = new Crawler($response->content);
            $crawler->filterXPath('//*[@class="pagination"]/li[not(@class)]/a')->each(function (Crawler $node, $i) use ($url) {
                $pageLink = $node->attr('href');
                $response = Curl::to("http://www.portmacguitars.com.au".$pageLink)
                    ->withHeaders([
                        'Accept-Language: en-us',
                        'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.15) Gecko/20110303 Firefox/3.6.15',
                        'Connection: Keep-Alive',
                        'Cache-Control: no-cache',
                    ])
                    ->withOption("FOLLOWLOCATION", true)
                    ->returnResponseObject()
                    ->get();
                $this->products = array_merge($this->products, $this->loadOnePageData($response, $url));
            });
            $bar->advance();
        }
        Excel::create('portmacguitars', function ($excel) {
            $excel->sheet('sheet1', function ($sheet) {
                $sheet->fromArray($this->products);
            });
        })->store('csv');

        $bar->finish();

        dd("End of Crawl");
    }


    private function loadOnePageData($response, $url)
    {
        if($response->status != 200){return [];}
        $crawler = new Crawler($response->content);
        $products = [];
        $crawler->filterXPath('//*[@itemtype="http://schema.org/Product"]')->each(function (Crawler $node, $i) use ($crawler, &$products, $url) {
            $productHref = $node->filterXPath('//*[@itemprop="name"]/a')->attr("href");
            $productName = $node->filterXPath('//*[@itemprop="name"]/a')->text();
            $categoryLink = $url;
            $productName = trim(str_replace("\r\n", '', $productName));


            $products[] = [
                "name" => $productName,
                "link" => $productHref,
                "category_link" => $categoryLink
            ];
        });
        return $products;
    }
}
