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
        $sites = $product->sites()->with('item')->get();
        return $sites;
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
        $product->sites()->save($site);
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