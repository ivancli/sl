<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/13/2017
 * Time: 12:18 PM
 */

namespace App\Services\UrlManagement;


use App\Contracts\Repositories\UrlManagement\ItemContract;
use App\Contracts\Repositories\UrlManagement\UrlContract;
use App\Models\HistoricalPrice;
use App\Models\Item;
use App\Jobs\Crawl as CrawlJob;
use App\Validators\UrlManagement\Item\StoreValidator;
use Illuminate\Support\Facades\DB;

class ItemService
{
    #region repositories

    protected $urlRepo;
    protected $itemRepo;

    #endregion

    #region validators

    protected $storeValidator;

    #endregion

    public function __construct(UrlContract $urlContract, ItemContract $itemContract,
                                StoreValidator $storeValidator)
    {
        #region repositories binding
        $this->urlRepo = $urlContract;
        $this->itemRepo = $itemContract;
        #endregion

        #region validators binding
        $this->storeValidator = $storeValidator;
        #endregion
    }

    /**
     * Load URL by ID
     * @param $url_id
     * @return mixed
     */
    public function getUrlById($url_id)
    {
        return $this->urlRepo->get($url_id);
    }

    /**
     * Load all/filtered item
     * @param array $data
     * @return mixed
     */
    public function load(array $data = [])
    {
        $url = $this->getUrlById(array_get($data, 'url_id'));
        if (array_has($data, 'page')) {
            $items = $this->itemRepo->filterAll($data, $url);
        } else {
            $items = $url->items;
        }
        return $items;
    }

    public function loadHistoricalPrices(Item $item)
    {
        $user = auth()->user();
        $intervalHour = 1;
        if (!is_null($user->subscription) && !is_null($user->subscription->subscriptionCriteria)) {
            $subscription = $user->subscription;
            $criteria = $subscription->subscriptionCriteria;
            $intervalHour = $criteria->frequency;
        }

        $priceItemMeta = $item->metas()->where('element', 'PRICE')->first();
        if (!is_null($priceItemMeta)) {
            $historicalPrices = $priceItemMeta->historicalPrices()->whereIn('id', function ($query) use ($priceItemMeta, $intervalHour) {
                $query->select(DB::raw('MAX(id)'))
                    ->from(with(new HistoricalPrice)->getTable())
                    ->where('item_meta_id', $priceItemMeta->getKey())
                    ->groupBy(DB::raw('CEIL(UNIX_TIMESTAMP(created_at)/(' . $intervalHour . ' * 60 * 60))'));
            })->get();
            return $historicalPrices;
        }
        return null;
    }

    /**
     * Create new item
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        $this->storeValidator->validate($data);
        $url = $this->getUrlById(array_get($data, 'url_id'));
        $item = $this->itemRepo->store($data);
        $url->items()->save($item);
        return $item;
    }

    /**
     * Update an existing item
     * @param Item $item
     * @param array $data
     * @return Item|mixed
     */
    public function update(Item $item, array $data)
    {
        $item = $this->itemRepo->update($item, $data);
        return $item;
    }

    /**
     * Delete an existing item
     * @param Item $item
     * @return mixed
     */
    public function destroy(Item $item)
    {
        $result = $this->itemRepo->destroy($item);
        return $result;
    }

    /**
     * Push an existing item to queue
     * @param Item $item
     */
    public function queue(Item $item)
    {
        $url = $item->url;
        dispatch((new CrawlJob($url))->onQueue("crawl"));
        $crawler = $url->crawler;
        $crawler->statusQueuing();
    }
}