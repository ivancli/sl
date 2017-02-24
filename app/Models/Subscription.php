<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 6/02/2017
 * Time: 7:57 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Invigor\Chargify\Chargify;

class Subscription extends Model
{
    protected $fillable = [
        'token', 'api_subscription_id'
    ];

    protected $appends = [
        'isValid'
];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /*----------------------------------------------------------------------*/
    /* Attributes                                                           */
    /*----------------------------------------------------------------------*/

    public function getIsValidAttribute()
    {
        $api_subscription = Chargify::subscription()->get($this->api_subscription_id);
        return $api_subscription->state == 'trialing' || $api_subscription->state == 'active';
    }

    /* ---------------------------------------------------------------------- */
    /* helper functions */
    /* ---------------------------------------------------------------------- */

    /**
     * set token to subscription
     *
     * @param $token
     * @return mixed
     */
    public function setToken($token)
    {
        $this->token = $token;
        $this->save();
        return $this->token;
    }

}