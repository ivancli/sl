<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/26/2017
 * Time: 2:30 PM
 */

namespace App\Services\UrlManagement;


use App\Contracts\Repositories\UrlManagement\DomainConfContract;
use App\Contracts\Repositories\UrlManagement\DomainContract;
use App\Models\Domain;
use App\Validators\UrlManagement\DomainConf\UpdateValidator;

class DomainConfService
{
    #region repositories

    protected $domainRepo;
    protected $domainConfRepo;

    #endregion

    #region validators

    protected $updateValidator;

    #endregion

    public function __construct(DomainContract $domainContract, DomainConfContract $domainConfContract,
                                UpdateValidator $updateValidator)
    {
        #region repositories binding
        $this->domainRepo = $domainContract;
        $this->domainConfRepo = $domainConfContract;
        #endregion

        #region validators binding
        $this->updateValidator = $updateValidator;
        #endregion
    }

    /**
     * load domain by its ID
     * @param $domain_id
     * @return mixed
     */
    public function getDomainById($domain_id)
    {
        $domain = $this->domainRepo->get($domain_id);
        return $domain;
    }

    /**
     * update configurations of a domain
     * @param $domain_id
     * @param array $data
     * @return mixed
     */
    public function update($domain_id, array $data)
    {
        $domain = $this->getDomainById($domain_id);
        $this->updateValidator->validate($data);
        $domain = $this->domainConfRepo->update($domain, $data);
        return $domain;
    }
}