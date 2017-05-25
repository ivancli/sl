<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 25/05/2017
 * Time: 11:21 AM
 */

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'report_type',
        'frequency',
        'date',
        'day',
        'time',
        'weekday_only',
        'show_all',
        'last_active_at'
    ];

    protected $appends = [
        'urls'
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
     * relationship with category and report
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function reportable()
    {
        return $this->morphTo();
    }

    /*----------------------------------------------------------------------*/
    /* Attributes                                                           */
    /*----------------------------------------------------------------------*/

    public function getUrlsAttribute()
    {
        return [
            'index' => route('report.index'),
            'show' => route('report.show', $this->getKey()),
            'store' => route('report.store'),
            'edit' => route('report.edit', $this->getKey()),
            'update' => route('report.update', $this->getKey()),
            'delete' => route('report.destroy', $this->getKey()),
        ];
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