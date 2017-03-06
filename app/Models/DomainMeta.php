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
        'name', 'type'
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
}