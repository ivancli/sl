<?php

namespace App\Http\Controllers\UrlManagement;

use App\Http\Controllers\Controller;
use App\Models\Url;
use App\Events\UrlManagement\Url\BeforeIndex;
use App\Events\UrlManagement\Url\AfterIndex;
use App\Events\UrlManagement\Url\BeforeCreate;
use App\Events\UrlManagement\Url\AfterCreate;
use App\Events\UrlManagement\Url\BeforeStore;
use App\Events\UrlManagement\Url\AfterStore;
use App\Events\UrlManagement\Url\BeforeShow;
use App\Events\UrlManagement\Url\AfterShow;
use App\Events\UrlManagement\Url\BeforeEdit;
use App\Events\UrlManagement\Url\AfterEdit;
use App\Events\UrlManagement\Url\BeforeUpdate;
use App\Events\UrlManagement\Url\AfterUpdate;
use App\Events\UrlManagement\Url\BeforeDestroy;
use App\Events\UrlManagement\Url\AfterDestroy;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    var $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        event(new BeforeIndex());
        event(new AfterIndex());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        event(new BeforeCreate());
        event(new AfterCreate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {

        event(new BeforeStore());
        event(new AfterStore());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Url $url
     * @return \Illuminate\Http\Response
     */
    public function show(Url $url)
    {

        event(new BeforeShow($url));
        event(new AfterShow($url));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Url $url
     * @return \Illuminate\Http\Response
     */
    public function edit(Url $url)
    {

        event(new BeforeEdit($url));
        event(new AfterEdit($url));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Url $url
     * @return \Illuminate\Http\Response
     */
    public function update(Url $url)
    {

        event(new BeforeUpdate($url));
        event(new AfterUpdate($url));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Url $url
     * @return \Illuminate\Http\Response
     */
    public function destroy(Url $url)
    {

        event(new BeforeDestroy($url));
        event(new AfterDestroy());
    }
}
