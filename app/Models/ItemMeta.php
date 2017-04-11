<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 16/02/2017
 * Time: 3:50 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ItemMeta extends Model
{
    protected $fillable = [
        'element', 'value', 'format_type', 'historical_type', 'status', 'parsed_at',
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
        return $this->hasMany('App\Models\HistoricalPrice', 'item_meta_id', 'id');
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
}