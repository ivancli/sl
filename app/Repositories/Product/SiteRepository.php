<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/14/2017
 * Time: 4:19 PM
 */

namespace App\Repositories\Product;


use App\Contracts\Repositories\Product\SiteContract;
use App\Models\Site;
use App\Models\User;

class SiteRepository implements SiteContract
{
    protected $site;

    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    /**
     * Load all sites
     *
     * @param User $user
     * @return mixed
     */
    public function all(User $user = null)
    {
        if (is_null($user)) {
            $sites = $this->site->all();
        } else {
            $sites = $user->sites;
        }
        return $sites;
    }

    /**
     * Load a site
     *
     * @param $site_id
     * @param bool $throw
     * @return mixed
     */
    public function get($site_id, $throw = true)
    {
        if ($throw) {
            return $this->site->findOrFail($site_id);
        } else {
            return $this->site->find($site_id);
        }
    }

    /**
     * Create a site
     *
     * @param array $data
     * @return mixed
     */
    public function store(Array $data)
    {
        $site = new $this->site($data);
        $site->save();
        return $site;
    }

    /**
     * Editing an existing site
     * @param Site $site
     * @param array $data
     * @return mixed
     */
    public function update(Site $site, Array $data)
    {
        $site->update($data);
        return $site;
    }

    /**
     * Deleting an existing site
     * @param Site $site
     * @return mixed
     */
    public function destroy(Site $site)
    {
        $site->delete();
    }
}