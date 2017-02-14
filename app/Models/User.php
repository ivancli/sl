<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'first_name', 'last_name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = [
        'fullName',
    ];

    /**
     * relationship with subscription
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function subscription()
    {
        return $this->hasOne('App\Models\Subscription', 'user_id', 'id');
    }

    /**
     * relationship with category
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany('App\Models\Category', 'user_id', 'id');
    }

    /**
     * relationship with product
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product', 'user_id', 'id');
    }

    public function sites()
    {
        return $this->hasManyThrough('App\Models\Site', 'App\Models\Product', 'user_id', 'product_id', 'id');
    }

    /*----------------------------------------------------------------------*/
    /* Attributes                                                           */
    /*----------------------------------------------------------------------*/

    /**
     * full name of user
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
