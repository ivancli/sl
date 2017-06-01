<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 19/04/2017
 * Time: 9:58 PM
 */

namespace App\Services\Product;


use App\Contracts\Repositories\Product\ProductContract;
use App\Contracts\Repositories\Product\SiteContract;
use App\Contracts\Repositories\UrlManagement\ItemContract;
use App\Contracts\Repositories\UrlManagement\UrlContract;
use App\Jobs\Crawl as CrawlJob;
use App\Models\Site;
use App\Validators\Product\Site\AssignItemValidator;
use App\Validators\Product\Site\StoreValidator;
use App\Validators\Product\Site\UpdateValidator;
use Illuminate\Support\Facades\DB;

class SiteService
{
    #region repositories

    protected $productRepo;
    protected $siteRepo;
    protected $urlRepo;
    protected $itemRepo;

    #endregion

    #region validators

    protected $storeValidator;
    protected $updateValidator;
    protected $assignItemValidator;

    #endregion

    public function __construct(ProductContract $productContract, SiteContract $siteContract, UrlContract $urlContract, ItemContract $itemContract,
                                StoreValidator $storeValidator, UpdateValidator $updateValidator, AssignItemValidator $assignItemValidator)
    {
        #region repositories binding
        $this->productRepo = $productContract;
        $this->siteRepo = $siteContract;
        $this->urlRepo = $urlContract;
        $this->itemRepo = $itemContract;
        #endregion

        #region validators binding
        $this->storeValidator = $storeValidator;
        $this->updateValidator = $updateValidator;
        $this->assignItemValidator = $assignItemValidator;
        #endregion
    }

    /**
     * load all/filtered sites
     * @param array $data
     * @return mixed
     */
    public function load(array $data = [])
    {
        /* TODO make this function to accept parameters and dynamic */
        $product = $this->productRepo->get(array_get($data, 'product_id'));
        $sitesBuilder = $product->sites()->with('item');

        $sites = $sitesBuilder->get();
        if (array_has($data, 'sorting_column') && !empty(array_get($data, 'sorting_column'))) {
            $column = array_get($data, 'sorting_column', 'site');
            $sequence = array_get($data, 'sorting_sequence', 'asc');
            switch ($column) {
                case 'site':
                    switch ($sequence) {
                        case 'desc':
                            $sites = $sites->sortByDesc('siteUrl')->values();
                            break;
                        case 'asc':
                        default:
                            $sites = $sites->sortBy('siteUrl')->values();
                    }
                    break;
                case 'availability':
                    switch ($sequence) {
                        case 'desc':
                            $sites = $sites->sortByDesc('item.availability')->values();
                            break;
                        case 'asc':
                        default:
                            $sites = $sites->sortBy('item.availability')->values();
                    }
                    break;
                case 'previous_price':
                    switch ($sequence) {
                        case 'desc':
                            $sites = $sites->sortByDesc(function ($site, $key) {
                                return !is_null($site->item) ? floatval($site->item->previousPrice) : null;
                            })->values();
                            break;
                        case 'asc':
                        default:
                            $sites = $sites->sortBy(function ($site, $key) {
                                return !is_null($site->item) ? floatval($site->item->previousPrice) : null;
                            })->values();
                    }
                    break;
                case 'price_change':
                    switch ($sequence) {
                        case 'desc':
                            $sites = $sites->sortByDesc(function ($site, $key) {
                                return !is_null($site->item) ? (floatval($site->item->recentPrice) - floatval($site->item->previousPrice)) : null;
                            })->values();
                            break;
                        case 'asc':
                        default:
                            $sites = $sites->sortBy(function ($site, $key) {
                                return !is_null($site->item) ? (floatval($site->item->recentPrice) - floatval($site->item->previousPrice)) : null;
                            })->values();
                    }
                    break;
                case 'last_changed_at':
                    switch ($sequence) {
                        case 'desc':
                            $sites = $sites->sortByDesc('item.lastChangedAt')->values();
                            break;
                        case 'asc':
                        default:
                            $sites = $sites->sortBy('item.lastChangedAt')->values();
                    }
                    break;
                case 'recent_price':
                default:
                    switch ($sequence) {
                        case 'desc':
                            $sites = $sites->sortByDesc(function ($site, $key) {
                                return !is_null($site->item) ? floatval($site->item->recentPrice) : null;
                            })->values();
                            break;
                        case 'asc':
                        default:
                            $sites = $sites->sortBy(function ($site, $key) {
                                return !is_null($site->item) ? floatval($site->item->recentPrice) : null;
                            })->values();
                    }
            }
        }

        if (array_has($data, 'offset') && !empty(array_get($data, 'offset'))) {
//            $sitesBuilder->skip(array_get($data, 'offset'));
            $sites->slice(array_get($data, 'offset'));
        }

        if (array_has($data, 'length') && !empty(array_get($data, 'length'))) {
//            $sitesBuilder->limit(array_get($data, 'length'));
            $sites = $sites->take(array_get($data, 'length'));
        }

        return $sites;
    }

    /**
     * Format a single site
     * @param Site $site
     * @return Site
     */
    public function get(Site $site)
    {
        $site->load('item');
        return $site;
    }

    /**
     * create a new site
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        $this->storeValidator->validate($data);

        $site = $this->siteRepo->store($data);

        $url = $this->urlRepo->getByFullPathOrCreate($data);

        $url->sites()->save($site);
        $product = $this->productRepo->get(array_get($data, 'product_id'));
        //run crawler immediately
        dispatch((new CrawlJob($url))->onQueue("crawl")->onConnection('sync'));

        if ($url->itemsCount == 1) {
            $theOnlyItem = $url->items->first();
            if (!is_null($theOnlyItem->recentPrice)) {
                $theOnlyItem->sites()->save($site);
            }
        }

        $product->sites()->save($site);
        $site->load('item');
        return $site;
    }

    /**
     * update an existing site
     * @param Site $site
     * @param array $data
     * @return Site|mixed
     */
    public function update(Site $site, array $data)
    {
        $data = array_set($data, 'id', $site->getKey());
        $this->updateValidator->validate($data);
        $site = $this->siteRepo->update($site, $data);
        $url = $this->urlRepo->getByFullPathOrCreate($data);
        $url->sites()->save($site);
        $product = $this->productRepo->get(array_get($data, 'product_id'));
        $product->sites()->save($site);

        dispatch((new CrawlJob($url))->onQueue("crawl")->onConnection('sync'));

        return $site;
    }

    /**
     * delete an existing site
     * @param Site $site
     * @return mixed
     */
    public function destroy(Site $site)
    {
        $result = $this->siteRepo->destroy($site);
        return $result;
    }

    /**
     * @param Site $site
     * @param array $data
     * @return Site
     */
    public function assignItem(Site $site, array $data)
    {
        $this->assignItemValidator->validate($data);
        $item = $this->itemRepo->get(array_get($data, 'item_id'));
        $item->sites()->save($site);
        $site->fresh();
        return $site;
    }
}