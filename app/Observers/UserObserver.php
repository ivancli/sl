<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserMeta;

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 22/02/2017
 * Time: 10:29 PM
 */
class UserObserver
{
    public function creating()
    {

    }

    public function created(User $user)
    {
        $user->setPreference('DATE_FORMAT', 'Y-m-d');
        $user->setPreference('TIME_FORMAT', 'g:i a');
        $user->metas()->save(new UserMeta());
    }

    public function saving()
    {

    }

    public function saved(User $user)
    {

    }

    public function updating(User $user)
    {

    }

    public function updated(User $user)
    {

    }

    public function deleting(User $user)
    {
        /*remove corresponding alerts when deleting a user*/
        $user->alerts()->delete();
    }

    public function deleted()
    {

    }

    public function restoring()
    {

    }

    public function restored(User $user)
    {

    }
}