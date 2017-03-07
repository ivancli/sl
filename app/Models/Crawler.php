<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Crawler extends Model
{
    protected $fillable = [
        'class', 'status', 'last_active_at'
    ];

    /**
     * relationship with URL
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function url()
    {
        return $this->belongsTo('App\Models\Url', 'url_id', 'id');
    }
}