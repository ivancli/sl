<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainMetaConf extends Model
{
    protected $fillable = [
        'element', 'value', 'order'
    ];

    /**
     * relationship with domain meta
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function domainMeta()
    {
        return $this->belongsTo('App\Models\DomainMeta', 'domain_meta_id', 'id');
    }
}
