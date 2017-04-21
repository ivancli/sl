<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 22/02/2017
 * Time: 10:54 PM
 */

namespace App\Observers;


use App\Contracts\Repositories\UrlManagement\CrawlerContract;
use App\Models\Crawler;
use App\Models\Domain;
use App\Models\Item;
use App\Models\Url;

class UrlObserver
{
    protected $crawlerRepo;

    public function __construct(CrawlerContract $crawlerContract)
    {
        $this->crawlerRepo = $crawlerContract;
    }

    public function creating()
    {

    }

    public function created(Url $url)
    {
        /* CREATE DOMAIN */
        if (Domain::where('full_path', $url->domainFullPath)->count() == 0) {
            $domain = new Domain;
            $domain->full_path = $url->domainFullPath;
            $domain->save();
        }

        /* CREATE CRAWLER */
        $crawlerConf = $url->domain->getConf('CRAWLER');
        $crawlerName = !is_null($crawlerConf) ? $crawlerConf->value : null;
        $crawler = $url->crawler()->save(new Crawler([
            'class' => $crawlerName
        ]));


        /* TODO check domain configuration */

        /* TODO check if there are any domain metas which affect prices on page */

        /* TODO if not, find price meta*/


        $domain = $url->domain;
        if (!is_null($domain) && $domain->metas->count() > 0) {



            /* CREATE AN ITEM */
            $item = $url->items()->save(new Item);
            /* REPLICATE DOMAIN META IN URL ITEM META*/
            foreach ($url->domain->metas as $domainMeta) {
                $itemMeta = $item->setMeta($domainMeta->element, null, $domainMeta->format_type, $domainMeta->historical_type);
                foreach ($domainMeta->confs as $domainMetaConf) {
                    $itemMeta->setConf($domainMetaConf->element, $domainMetaConf->value);
                }
            }
        } else {

            /* CREATE AN ITEM */
            $item = $url->items()->save(new Item);
            /*standard entities - price and availability*/
            $priceMeta = $item->setMeta('PRICE', null, 'decimal', 'price');
            $availabilityMeta = $item->setMeta('AVAILABILITY', null, 'boolean');
        }
        /* TODO check common crawler configuration */
    }

    public function saving()
    {

    }

    public function saved(Url $url)
    {

    }

    public function updating(Url $url)
    {

    }

    public function updated(Url $url)
    {

    }

    public function deleting(Url $url)
    {

    }

    public function deleted()
    {

    }

    public function restoring()
    {

    }

    public function restored(Url $url)
    {

    }
}