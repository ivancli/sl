<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkJob extends Model
{
    protected $fillable = [
        'type', 'content', 'status', 'archived', 'file_name', 'chunks', 'completed'
    ];

    /**
     * relationship with user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /*----------------------------------------------------------------------*/
    /* Helpers                                                              */
    /*----------------------------------------------------------------------*/

    public function statusNull()
    {
        $this->status = null;
        $this->save();
    }

    public function statusFailed()
    {
        $this->status = 'failed';
        $this->save();
    }

    public function statusWaiting()
    {
        $this->status = 'waiting';
        $this->save();
    }

    public function archive()
    {
        $this->archived = 'y';
        $this->save();
    }
}
