<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name',
    ];

    protected $appends = [
        'owner', 'numberOfSites', 'urls',
    ];

    protected $with = [
        'meta'
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
     * relationship with category
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id', 'id');
    }

    /**
     * relationship with site
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sites()
    {
        return $this->hasMany('App\Models\Site', 'product_id', 'id');
    }

    /**
     * relationship with product meta
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function meta()
    {
        return $this->hasOne('App\Models\ProductMeta', 'product_id', 'id');
    }

    /*----------------------------------------------------------------------*/
    /* Attributes                                                           */
    /*----------------------------------------------------------------------*/

    /**
     * owner of this product
     * @return User
     */
    public function getOwnerAttribute()
    {
        return $this->user;
    }

    /**
     * get number of sites
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
            'index' => route('product.index'),
            'show' => route('product.show', $this->getKey()),
            'store' => route('product.store'),
            'edit' => route('product.edit', $this->getKey()),
            'update' => route('product.update', $this->getKey()),
            'delete' => route('product.destroy', $this->getKey()),
        );
    }
}
