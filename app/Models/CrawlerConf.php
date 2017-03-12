<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 11/03/2017
 * Time: 1:46 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CrawlerConf extends Model
{
    protected $fillable = [
        'class'
    ];

    /**
     * relationship with crawler
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function crawler()
    {
        return $this->belongsTo('App\Models\Crawler', 'crawler_id', 'id');
    }
}