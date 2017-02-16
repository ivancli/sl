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
        'full_path', 'status'
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


}