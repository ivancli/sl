<?php

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/7/2017
 * Time: 5:12 PM
 */

namespace App\Repositories\Admin;

use App\Contracts\Repositories\Admin\AppPrefContract;
use App\Models\AppPref;

class AppPrefRepository implements AppPrefContract
{
    protected $appPref;

    public function __construct(AppPref $appPref)
    {
        $this->appPref = $appPref;
    }

    /**
     * Load all application preferences
     * @return mixed
     */
    public function all()
    {
        return $this->appPref->all()->pluck('value', 'element');
    }

    /**
     * Load an application preference by element
     * @param $element
     * @return mixed
     */
    public function get($element)
    {
        return $this->appPref->where('element', $element)->first();
    }

    /**
     * Create or update an application preference
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        $pref = $this->get($data['element']);
        if (is_null($pref)) {
            $pref = new $this->appPref([
                'element' => $data['element'],
                'value' => $data['value']
            ]);
            $pref->save();
        } else {
            $pref->value = $data['value'];
            $pref->save();
        }
        return $pref;
    }

    /**
     * Remove an existing application preference
     * @param AppPref $appPref
     * @return mixed
     */
    public function destroy(AppPref $appPref)
    {
        $appPref->delete();
    }
}