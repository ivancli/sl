<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name'
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
     * relationship with product
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product', 'category_id', 'id');
    }

    /*----------------------------------------------------------------------*/
    /* Attributes                                                           */
    /*----------------------------------------------------------------------*/

    /**
     * an attribute with an array of routes related to this object
     * @return array
     */
    public function getUrlsAttribute()
    {
        return array(
            'index' => route('category.index'),
            'show' => route('category.show', $this->getKey()),
            'create' => route('category.create'),
            'store' => route('category.store'),
            'edit' => route('category.edit', $this->getKey()),
            'update' => route('category.update', $this->getKey()),
            'delete' => route('category.destroy', $this->getKey()),
        );
    }
}
