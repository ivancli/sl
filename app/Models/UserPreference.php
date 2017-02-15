<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 15/02/2017
 * Time: 11:48 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $fillable = [
        'element', 'value'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

}