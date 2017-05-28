<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDomain extends Model
{
    protected $fillable = [
        'domain', 'alias',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
