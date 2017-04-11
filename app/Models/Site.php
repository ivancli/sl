<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = [
        'is_active'
    ];

    protected $appends = [
        'siteUrl', 'urls'
    ];

    /**
     * relationship with product
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    /**
     * relationship with url
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function url()
    {
        return $this->belongsTo('App\Models\Url', 'url_id', 'id');
    }

    /**
     * relationship with item
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id', 'id');
    }

    /*----------------------------------------------------------------------*/
    /* Attributes                                                           */
    /*----------------------------------------------------------------------*/

    /**
     * an attribute of full path URL
     * @return mixed
     */
    public function getSiteUrlAttribute()
    {
        if (!is_null($this->url)) {
            return $this->url->full_path;
        }
    }

    /**
     * an attribute with an array of routes related to this object
     * @return array
     */
    public function getUrlsAttribute()
    {
        return array(
            'index' => route('site.index'),
            'show' => route('site.show', $this->getKey()),
            'store' => route('site.store'),
            'edit' => route('site.edit', $this->getKey()),
            'update' => route('site.update', $this->getKey()),
            'delete' => route('site.destroy', $this->getKey()),
        );
    }

    /*----------------------------------------------------------------------*/
    /* Helpers                                                              */
    /*----------------------------------------------------------------------*/

    /**
     * Set this site to be active
     * @return $this
     */
    public function setActive()
    {
        $this->is_active = true;
        $this->save();
        return $this;
    }

    /**
     * Set this site to be inactive
     * @return $this
     */
    public function setInactive()
    {
        $this->is_active = false;
        $this->save();
        return $this;
    }
}
