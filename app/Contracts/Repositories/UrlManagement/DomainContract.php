<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 4/03/2017
 * Time: 3:16 PM
 */

namespace App\Contracts\Repositories\UrlManagement;


use App\Models\Domain;

interface DomainContract
{
    /**
     * Load all domains and filter them
     * @param array $data
     * @return mixed
     */
    public function filterAll(array $data);

    /**
     * Load all domains
     * @return mixed
     */
    public function all();

    /**
     * Get domain by ID
     * @param $domain_id
     * @param bool $throw
     * @return Domain
     */
    public function get($domain_id, $throw = true);

    /**
     * Create new domain
     * @param array $data
     * @return mixed
     */
    public function store(Array $data);

    /**
     * Update existing domain
     * @param Domain $domain
     * @param array $data
     * @return mixed
     */
    public function update(Domain $domain, array $data);

    /**
     * Remove an existing domain
     * @param Domain $domain
     * @return mixed
     */
    public function destroy(Domain $domain);
}