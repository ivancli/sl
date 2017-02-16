<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 16/02/2017
 * Time: 3:14 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name', 'is_active'
    ];

    /**
     * relationship with url
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function url()
    {
        return $this->belongsTo('App\Models\Url', 'url_id', 'id');
    }

    /**
     * relationship with historical price
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function historicalPrices()
    {
        return $this->hasMany('App\Models\HistoricalPrice', 'item_id', 'id');
    }
}