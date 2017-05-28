<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricalAlert extends Model
{
    protected $fillable = [
        'alert_type', 'comp_type', 'comp_price', 'comp_operator', 'email'
    ];

    /**
     * relationship with user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * relationship with category or product
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function alertable()
    {
        return $this->morphTo();
    }

}
