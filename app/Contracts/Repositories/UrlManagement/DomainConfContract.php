<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/26/2017
 * Time: 3:11 PM
 */

namespace App\Contracts\Repositories\UrlManagement;


use App\Models\Domain;

interface DomainConfContract
{
    /**
     * update domain configurations
     * @param Domain $domain
     * @param array $data
     * @return mixed
     */
    public function update(Domain $domain, array $data);
}