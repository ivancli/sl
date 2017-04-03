<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/21/2017
 * Time: 5:14 PM
 */

namespace App\Repositories\UrlManagement;


use App\Contracts\Repositories\UrlManagement\ItemMetaConfContract;
use App\Models\ItemMeta;
use App\Models\ItemMetaConf;

class ItemMetaConfRepository implements ItemMetaConfContract
{
    protected $itemMetaConf;

    public function __construct(ItemMetaConf $itemMetaConf)
    {
        $this->itemMetaConf = $itemMetaConf;
    }

    /**
     * Load all item meta confs
     * @return mixed
     */
    public function all()
    {
        return $this->itemMetaConf->all();
    }

    /**
     *
     * @param ItemMeta $itemMeta
     * @param array $data
     * @return mixed
     */
    public function store(ItemMeta $itemMeta, Array $data = [])
    {
        $itemMeta->clearConf();
        if (isset($data['confs']) && is_array($data['confs'])) {
            foreach ($data['confs'] as $conf) {
                $itemMeta->setConf($conf['element'], $conf['value']);
            }
        }
        return true;
    }
}