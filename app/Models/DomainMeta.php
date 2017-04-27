<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/22/2017
 * Time: 4:42 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class DomainMeta extends Model
{
    protected $fillable = [
        'element', 'format_type', 'historical_type', 'multi'
    ];

    protected $with = [
        'confs'
    ];

    /**
     * relationship with domain
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function domain()
    {
        return $this->belongsTo('App\Models\Domain', 'domain_id', 'id');
    }

    /**
     * relationship with domain meta conf
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function confs()
    {
        return $this->hasMany('App\Models\DomainMetaConf', 'domain_meta_id', 'id');
    }

    /*----------------------------------------------------------------------*/
    /* Helpers                                                              */
    /*----------------------------------------------------------------------*/

    /**
     * Remove all configuration
     */
    public function clearConf()
    {
        $this->confs()->delete();
    }

    /**
     * Create new configuration
     * @param $element
     * @param $value
     * @param int $order
     * @return Model
     */
    public function setConf($element, $value, $order = 0)
    {
        $conf = $this->confs()->create([
            'element' => $element,
            'value' => $value,
            'order' => $order,
        ]);
        return $conf;
    }
}