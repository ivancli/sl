<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/22/2017
 * Time: 4:42 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class DomainItem extends Model
{
    protected $fillable = [
        'name', 'is_active'
    ];

    /**
     * relationship with domain
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function domain()
    {
        return $this->belongsTo('App\Models\Domain', 'domain_id', 'id');
    }
}