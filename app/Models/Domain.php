<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/22/2017
 * Time: 4:40 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = [
        'full_path', 'name'
    ];

    protected $appends = [
        'modelUrls'
    ];

    protected $with = [
        'metas', 'confs'
    ];

    /**
     * relationship with url
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function urls()
    {
        return $this->hasMany('App\Models\Url', 'domain_id', 'id');
    }

    /**
     * relationship with domain item
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function metas()
    {
        return $this->hasMany('App\Models\DomainMeta', 'domain_id', 'id');
    }

    /**
     * relationship with domain configuration
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function confs()
    {
        return $this->hasMany('App\Models\DomainConf', 'domain_id', 'id');
    }


    /*----------------------------------------------------------------------*/
    /* Attributes                                                           */
    /*----------------------------------------------------------------------*/

    /**
     * Get related path to interact with Domain object
     * @return array
     */
    public function getModelUrlsAttribute()
    {
        return [
            'show' => route('domain.show', $this->getKey()),
            'store' => route('domain.store'),
            'edit' => route('domain.edit', $this->getKey()),
            'update' => route('domain.update', $this->getKey()),
            'delete' => route('domain.destroy', $this->getKey()),
            'meta_show' => route('domain-meta.show', $this->getKey()),
            'meta_store' => route('domain-meta.store'),
            'meta_edit' => route('domain-meta.edit', $this->getKey()),
            'meta_update' => route('domain-meta.update', $this->getKey()),
            'meta_delete' => route('domain-meta.destroy', $this->getKey()),
            'conf_show' => route('domain-conf.show', $this->getKey()),
            'conf_store' => route('domain-conf.store'),
            'conf_edit' => route('domain-conf.edit', $this->getKey()),
            'conf_update' => route('domain-conf.update', $this->getKey()),
            'conf_delete' => route('domain-conf.destroy', $this->getKey()),
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
     * @param $format_type
     * @param $historical_type
     * @return Model
     */
    public function setMeta($element, $format_type = null, $historical_type = null)
    {
        $meta = $this->metas()->create([
            'element' => $element,
            'format_type' => $format_type,
            'historical_type' => $historical_type
        ]);
        return $meta;
    }

    /**
     * retrieve meta by element
     * @param $element
     * @return mixed
     */
    public function getMeta($element)
    {
        return $this->metas()->where('element', $element)->first();
    }

    /**
     * remove all configurations
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
            'value' => $value,
        ]);
        return $conf;
    }

    public function getConf($element)
    {
        return $this->confs()->where('element', $element)->first();
    }
}