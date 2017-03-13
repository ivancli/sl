<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 11/03/2017
 * Time: 12:54 PM
 */

namespace App\Repositories\UrlManagement;


use App\Contracts\Repositories\UrlManagement\CrawlerContract;
use App\Models\Crawler;

class CrawlerRepository implements CrawlerContract
{
    var $crawler;

    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * Load crawler by crawler ID
     * @param $crawler_id
     * @param bool $throw
     * @return Crawler
     */
    public function get($crawler_id, $throw = true)
    {
        if ($throw) {
            $crawler = $this->crawler->findOrFail($crawler_id);
        } else {
            $crawler = $this->crawler->find($crawler_id);
        }
        return $crawler;
    }

    /**
     * Fetch content of the provided URL
     * @param Crawler $crawler
     * @return mixed
     */
    public function fetch(Crawler $crawler)
    {
        $conf = $crawler->conf;
        $crawlerClassName = "DefaultCrawler";
        $crawlerClassPath = 'IvanCLI\Crawler\Repositories\\';
        if (!is_null($conf) && !is_null($conf->class)) {
            $crawlerClassName = $conf->class;
        }
        $crawlerClass = app()->make("{$crawlerClassPath}$crawlerClassName");
        $crawlerClass->setURL($crawler->url->full_path);
        $crawlerClass->fetch();
        $content = $crawlerClass->getContent();
        return $content;
    }

}