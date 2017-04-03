<?php

namespace App\Http\Controllers\UrlManagement;

use App\Http\Controllers\Controller;
use App\Models\DomainConf;
use Illuminate\Http\Request;

class DomainConfController extends Controller
{
    protected $request;

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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DomainConf $domainConf
     * @return \Illuminate\Http\Response
     */
    public function show(DomainConf $domainConf)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DomainConf $domainConf
     * @return \Illuminate\Http\Response
     */
    public function edit(DomainConf $domainConf)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\DomainConf $domainConf
     * @return \Illuminate\Http\Response
     */
    public function update(DomainConf $domainConf)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DomainConf $domainConf
     * @return \Illuminate\Http\Response
     */
    public function destroy(DomainConf $domainConf)
    {
        //
    }
}
