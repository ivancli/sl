<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = [
        'alert_type', /* basic / advanced */
        'comp_type',  /* category / product */
        'comp_price', /* amount when comp_type is custom */
        'comp_operator', /* comparison operator when comp_type is custom, <, <=, =, >=, > */
        'last_active_at'
    ];


    /**
     * relationship with user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * relationship with category, product and site
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function alertable()
    {
        return $this->morphTo();
    }

    /*----------------------------------------------------------------------*/
    /* Helpers                                                              */
    /*----------------------------------------------------------------------*/

    /**
     * Set last active date time
     * @param string $dateTime
     */
    public function setLastActiveAt(string $dateTime = null)
    {
        if (is_null($dateTime)) {
            $dateTime = Carbon::now()->toDateTimeString();
        }
        $this->last_active_at = $dateTime;
        $this->save();
    }
}
