<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 22/02/2017
 * Time: 10:54 PM
 */

namespace App\Observers;


use App\Models\Crawler;
use App\Models\Domain;
use App\Models\Item;
use App\Models\Url;

class UrlObserver
{


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
        $url->crawler()->save(new Crawler([
            'class' => $crawlerName
        ]));


        /* TODO check domain configuration */

        /* TODO check if there are any domain metas which affect prices on page */

        /* TODO if not, find price meta*/
        /* CREATE AN ITEM */
        $item = $url->items()->save(new Item);

        $domain = $url->domain;
        if ($domain->metas->count() > 0) {
            /* REPLICATE DOMAIN META IN URL ITEM META*/
            foreach ($url->domain->metas as $domainMeta) {
                $itemMeta = $item->setMeta($domainMeta->name, null);
                foreach ($domainMeta->confs as $domainMetaConf) {
                    $itemMeta->setConf($domainMetaConf->element, $domainMetaConf->value);
                }
            }
        } else {
            /* price */
            $priceMeta = $item->setMeta('PRICE', null);
            $availabilityMeta = $item->setMeta('AVAILABILITY', null);
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