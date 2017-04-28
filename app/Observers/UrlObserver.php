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
    const ITEM_GENERATORS_PATH = 'IvanCLI\ItemGenerator\Repositories\\';
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
        $crawler = new Crawler([
            'class' => $crawlerName
        ]);
        $url->crawler()->save($crawler);


        /* TODO check domain configuration */

        /* TODO check if there are any domain metas which affect prices on page */

        /* TODO if not, find price meta*/


        $domain = $url->domain;
        if (!is_null($domain)) {
            $customItemGenerator = $domain->getConf('CUSTOM_ITEM_GENERATOR');
            if (!is_null($customItemGenerator) && !is_null($customItemGenerator->value) && !empty($customItemGenerator->value)) {
                $content = $this->crawlerRepo->fetch($crawler);
                if ($content['status'] == 200) {
                    $itemGenerator = app(self::ITEM_GENERATORS_PATH . $customItemGenerator->value);

                    $itemGenerator->setContent($content['content']);
                    $hasMultipleItems = $itemGenerator->extractOptions();
                    if ($hasMultipleItems) {
                        $itemGenerator->combinations($itemGenerator->getOptions());
                        $items = $itemGenerator->getItems();
                        foreach ($items as $targetItem) {
                            $names = [];
                            foreach ($targetItem as $label => $option) {
                                $names[] = "{$label}: {$option->text}";
                            }
                            $item = new Item([
                                'name' => implode(", ", $names)
                            ]);
                            $url->items()->save($item);
                            foreach ($targetItem as $label => $option) {
                                $itemMeta = $item->setMeta($label, $option->text);
                            }
                            $targetItemOptionValues = array_pluck($targetItem, 'value');
                            foreach ($url->domain->metas as $domainMeta) {
                                $itemMeta = $item->setMeta($domainMeta->element, null, $domainMeta->format_type, $domainMeta->historical_type);
                                foreach ($domainMeta->confs as $domainMetaConf) {
                                    $itemMeta->setConf($domainMetaConf->element, $domainMetaConf->value);
                                }

                                $customParser = $domain->getConf('CUSTOM_PARSER');
                                if (!is_null($customParser)) {
                                    $itemMeta->setConf('PARSER_CLASS', $customParser->value);
                                    foreach ($targetItemOptionValues as $targetItemOptionValue) {
                                        $itemMeta->setConf('OPTION_VALUE', $targetItemOptionValue);
                                    }
                                }
                            }
                        }
                    } else {
                        /* CREATE AN ITEM */
                        $item = $this->__createItemWithDomainReplication($url);
                    }
                } else {
                    /* CREATE AN ITEM */
                    $item = $this->__createItemWithDomainReplication($url);
                }
            } else {
                /* CREATE AN ITEM */
                $item = $this->__createItemWithDomainReplication($url);
            }
        } else {
            /* CREATE AN ITEM */
            $item = new Item;
            $url->items()->save($item);
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

    /**
     * create an item and replicate domain configuration
     * @param Url $url
     * @return Item
     */
    private function __createItemWithDomainReplication(Url $url)
    {
        $item = new Item;
        $url->items()->save($item);
        /* REPLICATE DOMAIN META IN URL ITEM META*/
        /* if domain has meta data set up*/
        if ($url->domain->metas()->count() > 0) {
            foreach ($url->domain->metas as $domainMeta) {
                $itemMeta = $item->setMeta($domainMeta->element, null, $domainMeta->format_type, $domainMeta->historical_type);
                foreach ($domainMeta->confs as $domainMetaConf) {
                    $itemMeta->setConf($domainMetaConf->element, $domainMetaConf->value);
                }
            }
        } else {
            /*standard entities - price and availability*/
            $priceMeta = $item->setMeta('PRICE', null, 'decimal', 'price');
            $availabilityMeta = $item->setMeta('AVAILABILITY', null, 'boolean');
        }
        return $item;
    }
}