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
use App\Contracts\Repositories\UrlManagement\UrlContract;
use App\Jobs\Crawl as CrawlJob;
use App\Models\Site;
use App\Validators\Product\Site\StoreValidator;
use App\Validators\Product\Site\UpdateValidator;

class SiteService
{
    #region repositories

    protected $productRepo;
    protected $siteRepo;
    protected $urlRepo;

    #endregion

    #region validators

    protected $storeValidators;
    protected $updateValidators;

    #endregion

    public function __construct(ProductContract $productContract, SiteContract $siteContract, UrlContract $urlContract,
                                StoreValidator $storeValidator, UpdateValidator $updateValidator)
    {
        #region repositories binding
        $this->productRepo = $productContract;
        $this->siteRepo = $siteContract;
        $this->urlRepo = $urlContract;
        #endregion

        #region validators binding
        $this->storeValidators = $storeValidator;
        $this->updateValidators = $updateValidator;
        #endregion
    }

    /**
     * load all/filtered sites
     * @param array $data
     * @return mixed
     */
    public function load(array $data = [])
    {
        $product = $this->productRepo->get(array_get($data, 'product_id'));
        $sites = $product->sites;
        return $sites;
    }

    /**
     * create a new site
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        $this->storeValidators->validate($data);

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
        $this->updateValidators->validate($data);
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
}