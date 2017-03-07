<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/6/2017
 * Time: 12:10 PM
 */

namespace App\Repositories\UrlManagement;


use App\Contracts\Repositories\UrlManagement\DomainMetaContract;
use App\Models\Domain;

class DomainMetaRepository implements DomainMetaContract
{

    /**
     * Update a list of meta of a domain
     * @param Domain $domain
     * @param array $data
     * @return mixed
     */
    public function update(Domain $domain, Array $data)
    {
        $domain->clearMeta();
        foreach ($data as $domainMeta) {
            $meta = $domain->setMeta($domainMeta['name'], $domainMeta['type']);
            $meta->clearConf();
            if (isset($domainMeta['confs']) && is_array($domainMeta['confs']) && !empty($domainMeta['confs'])) {
                foreach ($domainMeta['confs'] as $key => $metaConf) {
                    $conf = $meta->setConf($metaConf['element'], $metaConf['value'], $key);
                }
            }
        }
        return $domain;
    }
}