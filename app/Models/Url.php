<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 16/02/2017
 * Time: 3:12 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $fillable = [
        'full_path', 'status', 'is_active'
    ];

    protected $appends = [
        'domain', 'domainFullPath', 'active', 'itemsCount', 'sitesCount', 'sitesNoItemCount', 'failMetasCount', 'urls'
    ];

    /**
     * relationship with url
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sites()
    {
        return $this->hasMany('App\Models\Site', 'url_id', 'id');
    }

    /**
     * relationship with item
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany('App\Models\Item', 'url_id', 'id');
    }

    /**
     * relationship with crawler
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function crawler()
    {
        return $this->hasOne('App\Models\Crawler', 'url_id', 'id');
    }

    /**
     * relationship with item meta
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function itemMetas()
    {
        return $this->hasManyThrough('App\Models\ItemMeta', 'App\Models\Item', 'url_id', 'item_id', 'id');
    }

    /*----------------------------------------------------------------------*/
    /* Attributes                                                           */
    /*----------------------------------------------------------------------*/

    /**
     * Get domain object
     * @return mixed
     */
    public function getDomainAttribute()
    {
        return Domain::where('full_path', $this->domainFullPath)->first();
    }

    /**
     * Get Domain
     * @return mixed
     */
    public function getDomainFullPathAttribute()
    {
        $urlSegments = parse_url($this->full_path);
        return "{$urlSegments['scheme']}://{$urlSegments['host']}";
    }

    /**
     * get if the url is active
     * @return bool
     */
    public function getActiveAttribute()
    {
        return $this->is_active == 'y';
    }

    /**
     * get number of items available for this url
     * @return mixed
     */
    public function getItemsCountAttribute()
    {
        return $this->items()->count();
    }

    /**
     * get number of sites referencing this url
     * @return mixed
     */
    public function getSitesCountAttribute()
    {
        return $this->sites()->count();
    }

    /**
     * get number of sites selected referencing this url without item
     * @return mixed
     */
    public function getSitesNoItemCountAttribute()
    {
        return $this->sites()->whereNull('item_id')->count();
    }

    public function getFailMetasCountAttribute()
    {
        return $this->itemMetas()->where('status', '!=', 'standby')->count();
    }

    /**
     * Get related path to interact with Url object
     * @return array
     */
    public function getUrlsAttribute()
    {
        return [
            'index' => route('url.index'),
            'show' => route('url.show', $this->getKey()),
            'store' => route('url.store'),
            'edit' => route('url.edit', $this->getKey()),
            'update' => route('url.update', $this->getKey()),
            'delete' => route('url.destroy', $this->getKey()),
            'queue' => route('url.queue', $this->getKey()),
            'assign' => route('url.assign', $this->getKey()),
            'item_index' => route('item.index', ['url_id' => $this->getKey()]),
            'item_store' => route('item.store', ['url_id' => $this->getKey()]),
            'test_crawl_parse' => route('url.test', $this->getKey()),
        ];
    }

    /*----------------------------------------------------------------------*/
    /* Helpers                                                              */
    /*----------------------------------------------------------------------*/

    /**
     * Set URL's activeness
     * @param $is_active
     */
    public function setActive($is_active)
    {
        $this->is_active = $is_active == 'n' ? 'n' : 'y';
        $this->save();
    }

    /**
     * Set status to standby
     */
    public function statusStandby()
    {
        $this->status = 'standby';
        $this->save();
    }

    /**
     * Set status to waiting
     */
    public function statusWaiting()
    {
        $this->status = 'waiting';
        $this->save();
    }

    /**
     * Set status to crawl_failed
     */
    public function statusCrawlFailed()
    {
        $this->status = 'crawl_failed';
        $this->save();
    }
}