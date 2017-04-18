<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/13/2017
 * Time: 11:33 AM
 */

namespace App\Services\UrlManagement;


use App\Contracts\Repositories\UrlManagement\DomainContract;
use App\Contracts\Repositories\UrlManagement\DomainMetaContract;
use App\Models\Domain;
use App\Validators\UrlManagement\DomainMeta\UpdateValidator;

class DomainMetaService
{
    #region repositories

    protected $domainRepo;
    protected $domainMetaRepo;

    #endregion

    #region validators

    protected $updateValidator;

    #endregion

    public function __construct(DomainContract $domainContract, DomainMetaContract $domainMetaContract,
                                UpdateValidator $updateValidator)
    {
        #region repositories binding
        $this->domainRepo = $domainContract;
        $this->domainMetaRepo = $domainMetaContract;
        #endregion

        #region validators binding
        $this->updateValidator = $updateValidator;
        #endregion
    }

    /**
     * get domain by domain id
     * @param $domain_id
     * @return mixed
     */
    public function getDomainById($domain_id)
    {
        $domain = $this->domainRepo->get($domain_id);
        return $domain;
    }

    /**
     * update domain meta data
     * @param $domain_id
     * @param array $data
     * @return mixed
     */
    public function update($domain_id, array $data)
    {
        $domain = $this->domainRepo->get($domain_id);
        $this->updateValidator->validate($data);
        $domain = $this->domainMetaRepo->update($domain, array_get($data, 'metas'));
        return $domain;
    }
}