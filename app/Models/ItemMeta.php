<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 16/02/2017
 * Time: 3:50 PM
 */

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ItemMeta extends Model
{
    protected $fillable = [
        'element', 'value', 'format_type', 'historical_type', 'status', 'parsed_at', 'is_supportive',
    ];

    protected $with = [
        'confs'
    ];

    protected $appends = [
        'urls',
    ];

    /**
     * relationship with item
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id', 'id');
    }

    /**
     * relationship with historical price
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function historicalPrices()
    {
        $query = $this->hasMany('App\Models\HistoricalPrice', 'item_meta_id', 'id');
        $interval = null;
        $length = null;
        $cancelled_at = Carbon::now();
        if (auth()->check()) {
            $user = auth()->user();
            if (!is_null($user->subscription) && !is_null($user->subscription->subscriptionCriteria)) {
                $subscriptionCriteria = $user->subscription->subscriptionCriteria;
                if (isset($subscriptionCriteria->frequency) && is_int($subscriptionCriteria->frequency)) {
                    $interval = $subscriptionCriteria->frequency;
                }
                if (isset($subscriptionCriteria->historic_pricing) && is_int($subscriptionCriteria->historic_pricing)) {
                    $length = $subscriptionCriteria->historic_pricing;
                }
                if (!is_null($user->subscription->cancelled_at)) {
                    $cancelled_at = Carbon::createFromFormat('Y-m-d H:i:s', $user->subscription->cancelled_at);
                }
            }
        }

        if (!is_null($interval)) {
            $query->whereIn('id', function ($query) use ($interval) {
                $query->select(DB::raw('MAX(id)'))
                    ->from(with(new HistoricalPrice)->getTable())
                    ->where('item_meta_id', $this->getKey())
                    ->groupBy(DB::raw('CEIL(UNIX_TIMESTAMP(created_at)/(' . $interval . ' * 60 * 60))'));
            });
        }

        if (!is_null($length) && $length != 0) {
            $query->where(DB::raw("created_at BETWEEN NOW() - INTERVAL {$length} MONTH AND NOW()"));
        }

        if (!is_null($cancelled_at)) {
            $query->where('created_at', '<', $cancelled_at);
        }

        return $query;
    }

    /**
     * relationship with item meta conf
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function confs()
    {
        return $this->hasMany('App\Models\ItemMetaConf', 'item_meta_id', 'id');
    }

    /*----------------------------------------------------------------------*/
    /* Attributes                                                           */
    /*----------------------------------------------------------------------*/

    public function getUrlsAttribute()
    {
        return [
            'show' => route('item-meta.show', $this->getKey()),
            'store' => route('item-meta.store'),
            'edit' => route('item-meta.edit', $this->getKey()),
            'update' => route('item-meta.update', $this->getKey()),
            'delete' => route('item-meta.destroy', $this->getKey()),
            'queue' => route('item-meta.queue', $this->getKey()),
            'conf_index' => route('item-meta-conf.index', ['item_meta_id' => $this->getKey()]),
            'conf_store' => route('item-meta-conf.store', ['item_meta_id' => $this->getKey()]),
            'test_crawl_parse' => route('item-meta.test', $this->getKey()),
        ];
    }

    /*----------------------------------------------------------------------*/
    /* Helpers                                                              */
    /*----------------------------------------------------------------------*/

//    /**
//     * Load historical prices by interval
//     * @param null $interval
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     */
//    public function historicalPricesByInterval($interval = null)
//    {
//        if (!is_null($this->interval)) {
//            return $this->historicalPrices()->whereIn('id', function ($query) {
//                $query->select(DB::raw('MAX(id)'))
//                    ->from(with(new HistoricalPrice)->getTable())
//                    ->where('item_meta_id', $this->getKey())
//                    ->groupBy(DB::raw('CEIL(UNIX_TIMESTAMP(created_at)/(' . $this->interval . ' * 60 * 60))'));
//            });
//        } else {
//            return $this->historicalPrices();
//        }
//    }

    /**
     * Remove all configurations
     */
    public function clearConf()
    {
        $this->confs()->delete();
    }

    /**
     * Create new configuration
     * @param $element
     * @param $value
     * @return Model
     */
    public function setConf($element, $value)
    {
        $conf = $this->confs()->create([
            'element' => $element,
            'value' => $value
        ]);
        return $conf;
    }

    /**
     * Load configuration by element
     * @param $element
     * @return mixed
     */
    public function getConfs($element)
    {
        return $this->confs()->where('element', $element)->get();
    }

    /**
     * Create new historical data in corresponding table
     * @param $value
     * @return Model|null
     */
    public function createHistoricalData($value)
    {
        $historicalData = null;
        switch ($this->historical_type) {
            case "price":
                $historicalData = $this->historicalPrices()->save(new HistoricalPrice([
                    "amount" => $value,
                ]));
                break;
            default:
        }

        return $historicalData;
    }

    /*----------------------------------------------------------------------*/
    /* Helpers                                                              */
    /*----------------------------------------------------------------------*/

    public function statusConfigFailed()
    {
        $this->status = 'config_failed';
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
     * Set status to format_failed
     */
    public function statusFormatFailed()
    {
        $this->status = 'format_failed';
        $this->save();
    }

    /**
     * Set status to parse_failed
     */
    public function statusParseFailed()
    {
        $this->status = 'parse_failed';
        $this->save();
    }

    /**
     * Set parsed timestamp
     * @param null $datetime
     */
    public function setParsedAt($datetime = null)
    {
        if (is_null($datetime)) {
            $datetime = Carbon::now()->toDateTimeString();
        }
        $this->parsed_at = $datetime;
        $this->save();
    }
}