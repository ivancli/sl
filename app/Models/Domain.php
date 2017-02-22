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
    public function items()
    {
        return $this->hasMany('App\Models\DomainItem', 'domain_id', 'id');
    }
}