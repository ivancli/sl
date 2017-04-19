<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 19/04/2017
 * Time: 10:35 PM
 */

namespace App\Observers;


use App\Contracts\Repositories\UrlManagement\UrlContract;
use App\Models\Site;

class SiteObserver
{
    protected $urlRepo;

    public function __construct(UrlContract $urlContract)
    {
        $this->urlRepo = $urlContract;
    }

    public function creating()
    {

    }

    public function created(Site $site)
    {

    }

    public function saving()
    {

    }

    public function saved(Site $site)
    {

    }

    public function updating(Site $site)
    {

    }

    public function updated(Site $site)
    {

    }

    public function deleting(Site $site)
    {

    }

    public function deleted()
    {

    }

    public function restoring()
    {

    }

    public function restored(Site $site)
    {

    }
}