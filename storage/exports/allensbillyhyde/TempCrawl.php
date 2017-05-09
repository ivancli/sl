<?php

namespace App\Jobs;

use App\Models\LoggingModels\UserActivityLog;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use IvanCLI\Crawler\Repositories\DefaultCrawler;
use IvanCLI\Parser\Repositories\XPathParser;
use Symfony\Component\DomCrawler\Crawler;

class TempCrawl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productLink;

    /**
     * Create a new job instance.
     *
     * @param $productLink
     */
    public function __construct($productLink)
    {
        $this->productLink = $productLink;
    }

    /**
     * Execute the job.
     *
     * @param DefaultCrawler $defaultCrawler
     * @return void
     * @internal param XPathParser $XPathParser
     */
    public function handle(DefaultCrawler $defaultCrawler)
    {
        $defaultCrawler->setURL($this->productLink);
        $defaultCrawler->fetch();
        $status = $defaultCrawler->getStatus();
        if ($status == 200) {
            $content = $defaultCrawler->getContent();
            /*product sku*/
            preg_match('#var prodid = \'(.*?)\';#', $content, $productSKUMatches);
            if (!empty($productSKUMatches)) {
                $productSKU = $productSKUMatches[1];
            }
            /*product price*/
            preg_match('#var prodprice = \'(.*?)\'#', $content, $productPriceMatches);
            if (!empty($productPriceMatches)) {
                $productPrice = $productPriceMatches[1];
                $productPrice = str_replace(',', '', $productPrice);
            }
            /*product name*/
            preg_match('#var prodName = \'(.*?)\'#', $content, $productNameMatches);
            if (!empty($productNameMatches)) {
                $productName = $productNameMatches[1];
            }
            /*product name*/
            preg_match('#var prodname = \'(.*?)\'#', $content, $productNameMatches);
            if (!empty($productNameMatches)) {
                $productName = $productNameMatches[1];
            }
            /*product brand*/
            preg_match('#var prodbrand = \'(.*?)\'#', $content, $productBrandMatches);
            if (!empty($productBrandMatches)) {
                $productBrand = $productBrandMatches[1];
            }

            /*product category*/
            preg_match('#var prodcat = \'(.*?)\'#', $content, $productCategoryMatches);
            if (!empty($productCategoryMatches)) {
                $productCategory = $productCategoryMatches[1];
            }

            /*product rrp*/
            preg_match('#var rrp = \'(.*?)\'#', $content, $productRRPMatches);
            if (!empty($productRRPMatches)) {
                $productRRP = $productRRPMatches[1];
                $productRRP = str_replace(',', '', $productRRP);
            }

            $xpathParser = new XPathParser();
            $xpathParser->setContent($content);
            $xpathParser->setOptions([
                'xpath' => '//*[@id="MainContent_ctl12"]/text()[2]'
            ]);
            $xpathParser->extract();
            $extractions = $xpathParser->getExtractions();
            if (is_array($extractions) && !empty($extractions)) {
                $productDescription = array_first($extractions);
            } else {
                $productDescription = $extractions;
            }

            $product = [
                'name' => $productName,
                'brand' => $productBrand,
                'sku' => $productSKU,
                'rrp' => $productRRP,
                'price' => $productPrice,
                'category' => $productCategory,
                'description' => $productDescription,
                'link' => $this->productLink,
            ];

            $log = new UserActivityLog([
                'activity' => json_encode($product)
            ]);
            $log->save();
        }
    }
}
