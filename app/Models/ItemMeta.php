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
        'element', 'value'
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
     * relationship with item meta conf
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function confs()
    {
        return $this->hasMany('App\Models\ItemMetaConf', 'item_meta_id', 'id');
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
}