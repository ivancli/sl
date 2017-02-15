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
        'fullName', 'allPreferences',
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

    /**
     * relationship with sites
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function sites()
    {
        return $this->hasManyThrough('App\Models\Site', 'App\Models\Product', 'user_id', 'product_id', 'id');
    }

    public function preferences()
    {
        return $this->hasMany('App\Models\UserPreference', 'user_id', 'id');
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

    /**
     * all user preferences
     * @return mixed
     */
    public function getAllPreferencesAttribute()
    {
        $preferences = $this->preferences->pluck('value', 'element')->all();
        return $preferences;
    }

    /*----------------------------------------------------------------------*/
    /* Helpers                                                              */
    /*----------------------------------------------------------------------*/

    /**
     * Load user preference
     * @param $element
     * @return mixed
     */
    public function getPreference($element)
    {
        return $this->preferences()->where('element', $element)->first();
    }

    /**
     * Update or create preference
     * @param $element
     * @param $value
     * @return UserPreference
     */
    public function setPreference($element, $value)
    {
        $preference = $this->getPreference($element);
        if (!is_null($preference)) {
            $preference->value = $value;
            $preference->save();
        } else {
            $preference = new UserPreference();
            $preference->element = $element;
            $preference->value = $value;
            $preference->save();
            $this->preferences()->save($preference);
        }
        return $preference;
    }
}
