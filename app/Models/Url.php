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
        'domain', 'domainFullPath', 'urls', 'active'
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

    /*----------------------------------------------------------------------*/
    /* Attributes                                                           */
    /*----------------------------------------------------------------------*/

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

    public function getActiveAttribute()
    {
        return $this->is_active == 'y';
    }

    /**
     * Get related path to interact with Url object
     * @return array
     */
    public function getUrlsAttribute()
    {
        return [
            'show' => route('url.show', $this->getKey()),
            'store' => route('url.store'),
            'edit' => route('url.edit', $this->getKey()),
            'update' => route('url.update', $this->getKey()),
            'delete' => route('url.destroy', $this->getKey()),
            'item_index' => route('item.index', ['url_id' => $this->getKey()]),
            'item_store' => route('item.store', ['url_id' => $this->getKey()]),
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

}