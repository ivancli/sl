<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 16/02/2017
 * Time: 3:14 PM
 */

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    protected $fillable = [
        'name', 'is_active', 'processed_at',
    ];

    protected $appends = [
        'recentPrice', 'recentPriceAt', 'availability', 'previousPrice', 'priceChange', 'lastChangedAt', 'urls'
    ];

    /**
     * relationship with url
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function url()
    {
        return $this->belongsTo('App\Models\Url', 'url_id', 'id');
    }

    /**
     * relationship with meta
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function metas()
    {
        return $this->hasMany('App\Models\ItemMeta', 'item_id', 'id');
    }

    /**
     * relationship with site
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sites()
    {
        return $this->hasMany('App\Models\Site', 'item_id', 'id');
    }

    /*----------------------------------------------------------------------*/
    /* Attributes                                                           */
    /*----------------------------------------------------------------------*/

    /**
     * an attribute of latest price from selected item
     * @return null
     */
    public function getRecentPriceAttribute()
    {
        $priceItemMeta = $this->metas()->where('element', 'PRICE')->first();
        if (!is_null($priceItemMeta)) {
            $price = $priceItemMeta->historicalPrices()->orderBy('id', 'desc')->first();
            if(!is_null($price)){
                return $price->amount;
            }
        }
        return null;
    }

    public function getRecentPriceAtAttribute()
    {
        $priceItemMeta = $this->metas()->where('element', 'PRICE')->first();
        if (!is_null($priceItemMeta)) {
            $price = $priceItemMeta->historicalPrices()->orderBy('id', 'desc')->first();
            if(!is_null($price)){
                return $price->created_at;
            }
        }
        return null;
    }

    /**
     * an attribute of item availability
     * @return bool|null
     */
    public function getAvailabilityAttribute()
    {
        $priceItemMeta = $this->metas()->where('element', 'AVAILABILITY')->first();
        if (!is_null($priceItemMeta)) {
            switch ($priceItemMeta->value) {
                case '0':
                    return false;
                    break;
                case '1':
                    return true;
                    break;
                default:
                    return null;
            }
        }
        return null;
    }

    /**
     * get previous price
     * @return float|null
     */
    public function getPreviousPriceAttribute()
    {
        $priceItemMeta = $this->metas()->where('element', 'PRICE')->first();
        if (!is_null($priceItemMeta)) {
            $previousPrice = $priceItemMeta->historicalPrices()->where('amount', '<>', $this->recentPrice)->orderBy('id', 'desc')->first();
            if (!is_null($previousPrice)) {
                return $previousPrice->amount;
            }
        }
        return null;
    }

    /**
     * get the price difference
     * @return float|null
     */
    public function getPriceChangeAttribute()
    {
        if (!is_null($this->recentPrice) && !is_null($this->previousPrice)) {
            return floatval($this->recentPrice) - floatval($this->previousPrice);
        }
        return null;
    }

    /**
     * get last price change date and time
     * @return string|null
     */
    public function getLastChangedAtAttribute()
    {
        $priceItemMeta = $this->metas()->where('element', 'PRICE')->first();
        if (!is_null($priceItemMeta)) {
            $previousPrice = $priceItemMeta->historicalPrices()->where('amount', '<>', $priceItemMeta->value)->orderBy('id', 'desc')->first();
            if (!is_null($previousPrice)) {
                $lastChangedPrice = $priceItemMeta->historicalPrices()
                    ->where('amount', $priceItemMeta->value)
                    ->where('id', '>', $previousPrice->getKey())
                    ->orderBy('id')->first();
                if (!is_null($lastChangedPrice) && !is_null($lastChangedPrice->created_at)) {
                    return $lastChangedPrice->created_at->toDateTimeString();
                }
            }
        }
        return null;
    }

    /**
     * get all related routes
     * @return array
     */
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
            'price' => route('item.price', $this->getKey()),
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
     * @param bool $isSupportive
     * @param null $format_type
     * @param null $historical_type
     * @param string $status
     * @return Model
     */
    public function setMeta($element, $value, $isSupportive = false, $format_type = null, $historical_type = null, $status = 'standby')
    {
        $meta = $this->metas()->create([
            'element' => $element,
            'value' => $value,
            'format_type' => $format_type,
            'historical_type' => $historical_type,
            'is_supportive' => $isSupportive ? 'y' : 'n',
            'status' => $status,
        ]);
        return $meta;
    }

    /**
     * Update processed timestamp
     * @param null $datetime
     */
    public function setProcessedAt($datetime = null)
    {
        if (is_null($datetime)) {
            $datetime = Carbon::now()->toDateTimeString();
        }
        $this->processed_at = $datetime;
        $this->save();
    }
}