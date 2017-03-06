<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/6/2017
 * Time: 12:10 PM
 */

namespace App\Contracts\Repositories\UrlManagement;


use App\Models\Domain;

interface DomainMetaContract
{
    /**
     * Update a list of meta of a domain
     * @param Domain $domain
     * @param array $data
     * @return mixed
     */
    public function update(Domain $domain, Array $data);
}