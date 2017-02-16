<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 16/02/2017
 * Time: 3:40 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProductMeta extends Model
{
    protected $fillable = [
        'sku', 'brand', 'supplier'
    ];

    /**
     * relationship with product
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }
}