<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/13/2017
 * Time: 1:57 PM
 */

namespace App\Services\UrlManagement;


use App\Contracts\Repositories\UrlManagement\ItemContract;
use App\Contracts\Repositories\UrlManagement\ItemMetaContract;
use App\Jobs\Crawl as CrawlJob;
use App\Models\ItemMeta;
use App\Validators\UrlManagement\ItemMeta\StoreValidator;

class ItemMetaService
{
    #region repositories

    protected $itemRepo;
    protected $itemMetaRepo;

    #endregion

    #region validators

    protected $storeValidator;

    #endregion

    public function __construct(ItemContract $itemContract, ItemMetaContract $itemMetaContract,
                                StoreValidator $storeValidator)
    {
        #region repositories binding
        $this->itemRepo = $itemContract;
        $this->itemMetaRepo = $itemMetaContract;
        #endregion

        #region validators binding
        $this->storeValidator = $storeValidator;
        #endregion
    }

    /**
     * Get item by id
     * @param $item_id
     * @return \App\Models\Item|null
     */
    public function getItemById($item_id)
    {
        return $this->itemRepo->get($item_id);
    }

    /**
     * Load all/fitlered item metas
     * @param array $data
     * @return mixed
     */
    public function load(array $data = [])
    {
        $item = $this->getItemById(array_get($data, 'item_id'));
        if (array_has($data, 'page')) {
            $itemMetas = $this->itemMetaRepo->filterAll($data, $item);
        } else {
            $itemMetas = $this->itemMetaRepo->all();
        }
        return $itemMetas;
    }

    /**
     * Create a new item meta
     * @param array $data
     * @return ItemMeta
     */
    public function store(array $data)
    {
        $this->storeValidator->validate($data);
        $itemMeta = $this->itemMetaRepo->store($data);
        $item = $this->getItemById(array_get($data, 'item_id'));
        $item->metas()->save($itemMeta);
        return $itemMeta;
    }

    /**
     * Update an existing item meta
     * @param ItemMeta $itemMeta
     * @param array $data
     * @return ItemMeta|mixed
     */
    public function update(ItemMeta $itemMeta, array $data)
    {
        $itemMeta = $this->itemMetaRepo->update($itemMeta, $data);
        return $itemMeta;
    }

    /**
     * Delete an existing item meta
     * @param ItemMeta $itemMeta
     * @return bool
     */
    public function destroy(ItemMeta $itemMeta)
    {
        $result = $this->itemMetaRepo->destroy($itemMeta);
        return $result;
    }

    /**
     * Push an existing item meta to queue
     * @param ItemMeta $itemMeta
     */
    public function queue(ItemMeta $itemMeta)
    {
        $url = $itemMeta->item->url;
        dispatch((new CrawlJob($url))->onQueue("crawl"));
        $crawler = $url->crawler;
        $crawler->statusQueuing();
    }
}