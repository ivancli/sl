<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemMetaConf extends Model
{
    protected $fillable = [
        'element', 'value'
    ];

    /**
     * relationship with item meta
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function itemMeta()
    {
        return $this->belongsTo('App\Models\ItemMeta', 'item_meta_id', 'id');
    }
}
