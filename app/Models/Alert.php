<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = [
        'alert_type', /* basic / advanced */
        'comp_type',  /* category / product */
        'comp_price', /* amount when comp_type is custom */
        'comp_operator', /* comparison operator when comp_type is custom, <, <=, =, >=, > */
        'last_active_at'
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
     * relationship with category, product and site
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function alertable()
    {
        return $this->morphTo();
    }
}
