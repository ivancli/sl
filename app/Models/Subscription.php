<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 6/02/2017
 * Time: 7:57 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use IvanCLI\Chargify\Chargify;

class Subscription extends Model
{
    protected $fillable = [
        'token', 'api_subscription_id'
    ];

    protected $appends = [
        'isValid', 'apiSubscription'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /*----------------------------------------------------------------------*/
    /* Attributes                                                           */
    /*----------------------------------------------------------------------*/

    /**
     * Get subscription details from API
     * @return \IvanCLI\Chargify\Models\Subscription|null
     */
    public function getApiSubscriptionAttribute()
    {
        return Chargify::subscription()->get($this->api_subscription_id);
    }

    /**
     * Check if subscription is valid
     * @return bool
     */
    public function getIsValidAttribute()
    {
        if (is_null($this->apiSubscription)) {
            return false;
        }
        return $this->apiSubscription->state == 'trialing' || $this->apiSubscription->state == 'active';
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