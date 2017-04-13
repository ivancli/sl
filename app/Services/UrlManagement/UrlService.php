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

class UrlService
{
    protected $urlRepo;

    public function __construct(UrlContract $urlContract)
    {
        $this->urlRepo = $urlContract;
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
}