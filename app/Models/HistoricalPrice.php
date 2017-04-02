<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 16/02/2017
 * Time: 3:32 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class HistoricalPrice extends Model
{
    protected $fillable = [
        'amount'
    ];

    /**
     * relationship with item
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function itemMeta()
    {
        return $this->belongsTo('App\Models\ItemMeta', 'item_meta_id', 'id');
    }
}