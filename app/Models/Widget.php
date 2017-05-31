<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $fillable = [
        'name', 'widget_type', 'timespan', 'resolution', 'order'
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
    public function widgetable()
    {
        return $this->morphTo();
    }
}
