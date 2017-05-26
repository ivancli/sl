<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricalReport extends Model
{
    protected $fillable = [
        'file_name', 'content'
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
}
