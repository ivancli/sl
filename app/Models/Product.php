<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name',
    ];

    protected $appends = [
        'numberOfSites', 'hasReport', 'urls',
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

    /**
     * relationship with alert
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function alert()
    {
        return $this->morphOne('App\Models\Alert', 'alertable');
    }

    /**
     * relationship with historical alert
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function historicalAlerts()
    {
        return $this->morphMany('App\Models\HistoricalAlert', 'alertable');
    }

    /**
     * relationship with report
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function report()
    {
        return $this->morphOne('App\Models\Report', 'reportable');
    }

    /**
     * relationship with historical report
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function historicalReports()
    {
        return $this->morphMany('App\Models\HistoricalReport', 'reportable');
    }

    /**
     * relationship with widget
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function widgets()
    {
        return $this->morphMany('App\Models\Widget', 'widgetable');
    }

    /*----------------------------------------------------------------------*/
    /* Attributes                                                           */
    /*----------------------------------------------------------------------*/

    /**
     * get number of sites
     * @return integer
     */
    public function getNumberOfSitesAttribute()
    {
        return $this->sites()->count();
    }

    /**
     * check if product has report or not
     * @return bool
     */
    public function getHasReportAttribute()
    {
        return $this->report()->count() > 0;
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
            'report_show' => route('product.report.show', $this->getKey()),
        );
    }
}
