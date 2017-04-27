<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/26/2017
 * Time: 3:12 PM
 */

namespace App\Repositories\UrlManagement;


use App\Contracts\Repositories\UrlManagement\DomainConfContract;
use App\Models\Domain;
use Exception;
use Illuminate\Support\Facades\DB;

class DomainConfRepository implements DomainConfContract
{
    /**
     * Update domain configuration
     * @param Domain $domain
     * @param array $data
     * @return Domain
     * @throws Exception
     */
    public function update(Domain $domain, array $data)
    {
        DB::beginTransaction();
        try {
            $domain->clearConf();
            foreach ($data['confs'] as $domainMeta) {
                $meta = $domain->setConf(array_get($domainMeta, 'element'), array_get($domainMeta, 'value'));
            }
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        DB::commit();
        return $domain;
    }
}