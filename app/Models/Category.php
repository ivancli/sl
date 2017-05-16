<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'category_name'
    ];

    protected $appends = [
        'owner', 'numberOfProducts', 'numberOfSites', 'urls',
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

    /**
     * relationship with site
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function sites()
    {
        return $this->hasManyThrough('App\Models\Site', 'App\Models\Product', 'category_id', 'product_id', 'id');
    }

    /**
     * relationship with alert
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function alert()
    {
        return $this->morphOne('App\Models\Alert', 'alertable');
    }

    /*----------------------------------------------------------------------*/
    /* Attributes                                                           */
    /*----------------------------------------------------------------------*/

    /**
     * owner of this category
     * @return User
     */
    public function getOwnerAttribute()
    {
        return $this->user;
    }

    /**
     * total number of products in relationship
     * @return integer
     */
    public function getNumberOfProductsAttribute()
    {
        return $this->products()->count();
    }

    /**
     * total number of sites in relationship
     * @return integer
     */
    public function getNumberOfSitesAttribute()
    {
        return $this->sites()->count();
    }

    /**
     * an attribute with an array of routes related to this object
     * @return array
     */
    public function getUrlsAttribute()
    {
        return array(
            'index' => route('category.index'),
            'show' => route('category.show', $this->getKey()),
            'store' => route('category.store'),
            'edit' => route('category.edit', $this->getKey()),
            'update' => route('category.update', $this->getKey()),
            'delete' => route('category.destroy', $this->getKey()),
        );
    }
}
