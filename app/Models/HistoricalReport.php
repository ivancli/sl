<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricalReport extends Model
{
    protected $fillable = [
        'file_name', 'content'
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
     * relationship with report
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function report()
    {
        return $this->belongsTo('App\Models\Report', 'report_id', 'id');
    }

    /**
     * relationship with category / product
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
            'index' => route('historical-report.index'),
            'show' => route('historical-report.show', $this->getKey()),
            'store' => route('historical-report.store'),
            'edit' => route('historical-report.edit', $this->getKey()),
            'update' => route('historical-report.update', $this->getKey()),
            'delete' => route('historical-report.destroy', $this->getKey()),
        ];
    }


}
