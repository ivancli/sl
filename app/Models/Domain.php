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
        'allMetas', 'modelUrls'
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


    /*----------------------------------------------------------------------*/
    /* Attributes                                                           */
    /*----------------------------------------------------------------------*/

    /**
     * Get all metas
     * @return mixed
     */
    public function getAllMetasAttribute()
    {
        return $this->metas->map->name;
    }

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
        ];
    }
}