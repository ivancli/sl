<?php

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/17/2017
 * Time: 3:58 PM
 */

namespace App\Models\LoggingModels;

use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    protected $fillable = [
        'activity'
    ];

    protected $with = [
        'user'
    ];

    /**
     * relationship with user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}