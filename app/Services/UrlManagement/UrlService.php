<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/13/2017
 * Time: 12:19 PM
 */

namespace App\Services\UrlManagement;


use App\Contracts\Repositories\UrlManagement\UrlContract;
use App\Jobs\Crawl as CrawlJob;
use App\Models\Url;
use App\Validators\UrlManagement\Url\StoreValidator;

class UrlService
{
    #region repositories

    protected $urlRepo;

    #endregion

    #region validators

    protected $storeValidator;

    #endregion

    public function __construct(UrlContract $urlContract, StoreValidator $storeValidator)
    {
        #region repositories binding
        $this->urlRepo = $urlContract;
        #endregion

        #region validators binding
        $this->storeValidator = $storeValidator;
        #endregion
    }

    /**
     * Load all/filtered URLs
     * @param array $data
     * @return mixed
     */
    public function load(array $data = [])
    {
        if (array_has($data, 'page')) {
            $urls = $this->urlRepo->filterAll($data);
        } else {
            $urls = $this->urlRepo->all();
        }
        return $urls;
    }

    /**
     * Create a new URL
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        $this->storeValidator->validate($data);
        $url = $this->urlRepo->store($data);
        return $url;
    }

    /**
     * Delete an existing URL
     * @param Url $url
     * @return mixed
     */
    public function destroy(Url $url)
    {
        $result = $this->urlRepo->destroy($url);
        return $result;
    }

    /**
     * Push an existing URL to queue
     * @param Url $url
     */
    public function queue(Url $url)
    {
        dispatch((new CrawlJob($url))->onQueue("crawl"));
        $crawler = $url->crawler;
        $crawler->statusQueuing();
    }

    /**
     * Assign referencing sites with Url's only item
     * @param Url $url
     */
    public function assign(Url $url)
    {
        if ($url->itemsCount != 1) {
            abort(402, 'Unable to assign multiple items to a site.');
        }
        $item = $url->items()->first();
        $sitesWithoutItem = $url->sites()->whereNull('item_id')->get();
        foreach ($sitesWithoutItem as $siteWithoutItem) {
            $item->sites()->save($siteWithoutItem);
        }
    }
}