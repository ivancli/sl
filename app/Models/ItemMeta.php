<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 16/02/2017
 * Time: 3:50 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ItemMeta extends Model
{
    protected $fillable = [
        'element', 'value'
    ];

    /**
     * relationship with item
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id', 'id');
    }
}