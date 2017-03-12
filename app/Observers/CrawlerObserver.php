<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 11/03/2017
 * Time: 1:39 PM
 */

namespace App\Observers;


use App\Models\Crawler;
use App\Models\CrawlerConf;

class CrawlerObserver
{


    public function creating()
    {

    }

    public function created(Crawler $crawler)
    {
        $crawler->conf()->save(new CrawlerConf);
    }

    public function saving()
    {

    }

    public function saved(Crawler $crawler)
    {

    }

    public function updating(Crawler $crawler)
    {

    }

    public function updated(Crawler $crawler)
    {

    }

    public function deleting(Crawler $crawler)
    {

    }

    public function deleted()
    {

    }

    public function restoring()
    {

    }

    public function restored(Crawler $crawler)
    {

    }
}