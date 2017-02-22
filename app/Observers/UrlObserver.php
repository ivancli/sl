<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 22/02/2017
 * Time: 10:54 PM
 */

namespace App\Observers;


use App\Models\Domain;
use App\Models\Url;

class UrlObserver
{


    public function creating()
    {

    }

    public function created(Url $url)
    {
        /* create new domain if it's not yet in DB */
        if (Domain::where('full_path', $url->domainFullPath)->count() == 0) {
            $domain = new Domain();
            $domain->full_path = $url->domainFullPath;
            $domain->save();
        }
    }

    public function saving()
    {

    }

    public function saved(Url $url)
    {

    }

    public function updating(Url $url)
    {

    }

    public function updated(Url $url)
    {

    }

    public function deleting(Url $url)
    {

    }

    public function deleted()
    {

    }

    public function restoring()
    {

    }

    public function restored(Url $url)
    {

    }
}