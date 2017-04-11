<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 16/02/2017
 * Time: 3:14 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name', 'is_active', 'processed_at',
    ];

    protected $appends = [
        'urls'
    ];

    /**
     * relationship with url
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function url()
    {
        return $this->belongsTo('App\Models\Url', 'url_id', 'id');
    }

    public function metas()
    {
        return $this->hasMany('App\Models\ItemMeta', 'item_id', 'id');
    }

    /*----------------------------------------------------------------------*/
    /* Attributes                                                           */
    /*----------------------------------------------------------------------*/

    public function getUrlsAttribute()
    {
        return [
            'index' => route('item.index', ['url_id' => $this->url_id]),
            'show' => route('item.show', $this->getKey()),
            'store' => route('item.store'),
            'edit' => route('item.edit', $this->getKey()),
            'update' => route('item.update', $this->getKey()),
            'delete' => route('item.destroy', $this->getKey()),
            'queue' => route('item.queue', $this->getKey()),
            'meta_index' => route('item-meta.index', ['item_id' => $this->getKey()]),
            'meta_store' => route('item-meta.store', ['item_id' => $this->getKey()]),
            'test_crawl_parse' => route('item.test', $this->getKey()),
        ];
    }

    /*----------------------------------------------------------------------*/
    /* Helpers                                                              */
    /*----------------------------------------------------------------------*/

    /**
     * Remove all meta data
     */
    public function clearMeta()
    {
        $this->metas()->delete();
    }

    /**
     * Create new meta data
     * @param $element
     * @param $value
     * @param null $format_type
     * @param null $historical_type
     * @param string $status
     * @return Model
     */
    public function setMeta($element, $value, $format_type = null, $historical_type = null, $status = 'standby')
    {
        $meta = $this->metas()->create([
            'element' => $element,
            'value' => $value,
            'format_type' => $format_type,
            'historical_type' => $historical_type,
            'status' => $status,
        ]);
        return $meta;
    }

}