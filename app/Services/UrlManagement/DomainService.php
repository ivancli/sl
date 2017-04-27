<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/13/2017
 * Time: 11:19 AM
 */

namespace App\Services\UrlManagement;

use App\Contracts\Repositories\UrlManagement\DomainContract;
use App\Models\Domain;
use App\Validators\UrlManagement\Domain\StoreValidator;
use App\Validators\UrlManagement\Domain\UpdateValidator;

class DomainService
{
    #region repositories

    protected $domainRepo;

    #endregion

    #region validators

    protected $updateValidator;
    protected $storeValidator;

    #endregion

    public function __construct(DomainContract $domainContract,
                                StoreValidator $storeValidator, UpdateValidator $updateValidator)
    {
        #region repositories binding
        $this->domainRepo = $domainContract;
        #endregion

        #region validators binding
        $this->storeValidator = $storeValidator;
        $this->updateValidator = $updateValidator;
        #endregion
    }

    /**
     * Load all/filtered domains
     * @param array $data
     * @return mixed
     */
    public function load(array $data = [])
    {
        if (array_has($data, 'page')) {
            $domains = $this->domainRepo->filterAll($data);
        } else {
            $domains = $this->domainRepo->all();
        }
        return $domains;
    }

    /**
     * create a new domain
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        /*parse url into domain*/
        $parsedUrl = parse_url(array_get($data, 'full_path'));
        $scheme = array_get($parsedUrl, 'scheme');
        $host = array_get($parsedUrl, 'host');
        $domainPath = "{$scheme}://{$host}";
        $data = array_set($data, 'full_path', $domainPath);

        $this->storeValidator->validate($data);

        $domain = $this->domainRepo->store($data);
        return $domain;
    }

    /**
     * Update an existing domain
     * @param Domain $domain
     * @param array $data
     * @return Domain|mixed
     */
    public function update(Domain $domain, array $data)
    {
        $data = array_set($data, 'id', $domain->getKey());
        $this->updateValidator->validate($data);
        $domain = $this->domainRepo->update($domain, $data);
        return $domain;
    }

    /**
     * Delete an existing domain
     * @param Domain $domain
     * @return mixed
     */
    public function destroy(Domain $domain)
    {
        $result = $this->domainRepo->destroy($domain);
        return $result;
    }
}