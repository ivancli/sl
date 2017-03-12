<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 11/03/2017
 * Time: 12:49 PM
 */

namespace App\Contracts\Repositories\UrlManagement;


use App\Models\Crawler;

interface CrawlerContract
{
    /**
     * Load crawler by crawler ID
     * @param $crawler_id
     * @param bool $throw
     * @return Crawler
     */
    public function get($crawler_id, $throw = true);

    /**
     * Fetch content of the provided URL
     * @param Crawler $crawler
     * @return mixed
     */
    public function fetch(Crawler $crawler);
}