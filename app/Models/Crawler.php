<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Crawler extends Model
{
    protected $fillable = [
        'status'
    ];

    protected $with = [
        'url', 'conf'
    ];

    /**
     * relationship with URL
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function url()
    {
        return $this->belongsTo('App\Models\Url', 'url_id', 'id');
    }

    /**
     * relationship with Crawler Conf
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function conf()
    {
        return $this->hasOne('App\Models\CrawlerConf', 'crawler_id', 'id');
    }

    /*----------------------------------------------------------------------*/
    /* Helpers                                                              */
    /*----------------------------------------------------------------------*/
    /**
     * set status to picked
     */
    public function statusPicked()
    {
        $this->status = 'picked';
        $this->save();
    }

    /**
     * set status to queuing
     */
    public function statusQueuing()
    {
        $this->status = 'queuing';
        $this->save();
    }

    /**
     * set status to crawling
     */
    public function statusCrawling()
    {
        $this->status = 'crawling';
        $this->save();
    }

    /**
     * set status to crawled
     */
    public function statusCrawled()
    {
        $this->status = 'crawled';
        $this->save();
    }

    /**
     * set status to parsing
     */
    public function statusParsing()
    {
        $this->status = 'parsing';
        $this->save();
    }

    /**
     * set status to parsed
     */
    public function statusParsed()
    {
        $this->status = 'parsed';
        $this->save();
    }

    /**
     * set status to null
     */
    public function statusNull()
    {
        $this->status = null;
        $this->save();
    }

    /**
     * Set crawled_at to particular/current date time
     * @param string|null $dateTime
     */
    public function setCrawledAt($dateTime = null)
    {
        if (is_null($dateTime)) {
            $dateTime = Carbon::now()->toDateTimeString();
        }
        $this->crawled_at = $dateTime;
        $this->save();
    }

    /**
     * Create new configuration
     * @param array $data
     * @return Model
     */
    public function setConf(array $data = [])
    {
        if ($this->conf()->count() > 0) {
            $this->conf->update($data);
        } else {
            $this->conf()->save(new CrawlerConf($data));
        }
    }
}