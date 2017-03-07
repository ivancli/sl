<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainConf extends Model
{
    protected $fillable = [
        'element', 'value'
    ];

    /**
     * relationship with domain
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function domain()
    {
        return $this->belongsTo('App\Models\Domain', 'domain_id', 'id');
    }
}
