<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/14/2017
 * Time: 4:16 PM
 */

namespace App\Contracts\Repositories\Product;


use App\Models\Site;
use App\Models\User;

interface SiteContract
{
    /**
     * Load all sites
     *
     * @param User $user
     * @return mixed
     */
    public function all(User $user = null);

    /**
     * Load a site
     *
     * @param $site_id
     * @param bool $throw
     * @return mixed
     */
    public function get($site_id, $throw = true);

    /**
     * Create a site
     *
     * @param array $data
     * @return mixed
     */
    public function store(Array $data);

    /**
     * Editing an existing site
     * @param Site $site
     * @param array $data
     * @return mixed
     */
    public function update(Site $site, Array $data);

    /**
     * Deleting an existing site
     * @param Site $site
     * @return mixed
     */
    public function destroy(Site $site);
}