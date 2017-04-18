<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 18/04/2017
 * Time: 10:45 PM
 */

namespace App\Services\Admin;

use App\Contracts\Repositories\Admin\AppPrefContract;

class AppPrefService
{
    #region repositories

    protected $appPrefRepo;

    #endregion

    public function __construct(AppPrefContract $appPrefContract)
    {
        #region repositories binding
        $this->appPrefRepo = $appPrefContract;
        #endregion
    }

    /**
     * get all/filtered app preferences
     * @param array $data
     * @return mixed
     */
    public function load(array $data = [])
    {
        $appPrefs = $this->appPrefRepo->all();
        return $appPrefs;
    }

    /**
     * create/update app preferences
     * @param array $data
     */
    public function store(array $data)
    {
        foreach (array_get($data, 'appPrefs') as $appPref) {
            $pref = $this->appPrefRepo->store($appPref);
        }
    }
}