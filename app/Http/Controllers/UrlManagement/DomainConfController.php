<?php

namespace App\Http\Controllers\UrlManagement;

use App\Events\UrlManagement\DomainConf\BeforeIndex;
use App\Events\UrlManagement\DomainConf\AfterIndex;
use App\Events\UrlManagement\DomainConf\BeforeCreate;
use App\Events\UrlManagement\DomainConf\AfterCreate;
use App\Events\UrlManagement\DomainConf\BeforeStore;
use App\Events\UrlManagement\DomainConf\AfterStore;
use App\Events\UrlManagement\DomainConf\BeforeShow;
use App\Events\UrlManagement\DomainConf\AfterShow;
use App\Events\UrlManagement\DomainConf\BeforeEdit;
use App\Events\UrlManagement\DomainConf\AfterEdit;
use App\Events\UrlManagement\DomainConf\BeforeUpdate;
use App\Events\UrlManagement\DomainConf\AfterUpdate;
use App\Events\UrlManagement\DomainConf\BeforeDestroy;
use App\Events\UrlManagement\DomainConf\AfterDestroy;
use App\Http\Controllers\Controller;
use App\Models\DomainConf;
use App\Services\UrlManagement\DomainConfService;
use Illuminate\Http\Request;

class DomainConfController extends Controller
{
    protected $request;
    protected $domainConfService;

    public function __construct(Request $request, DomainConfService $domainConfService)
    {
        $this->request = $request;
        $this->domainConfService = $domainConfService;
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
     * @param $domain_id
     * @return \Illuminate\Http\Response
     */
    public function show($domain_id)
    {
        $domain = $this->domainConfService->getDomainById($domain_id);
        event(new BeforeShow($domain));
        event(new AfterShow($domain));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $domain_id
     * @return \Illuminate\Http\Response
     */
    public function edit($domain_id)
    {
        $domain = $this->domainConfService->getDomainById($domain_id);
        event(new BeforeEdit($domain));
        event(new AfterEdit($domain));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $domain_id
     * @return \Illuminate\Http\Response
     */
    public function update($domain_id)
    {
        $domain = $this->domainConfService->getDomainById($domain_id);
        event(new BeforeUpdate($domain));

        $domain = $this->domainConfService->update($domain_id, $this->request->all());
        $status = true;

        event(new AfterUpdate($domain));

        return compact(['status', 'domain']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $domain_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain_id)
    {
        $domain = $this->domainConfService->getDomainById($domain_id);
        event(new BeforeDestroy($domain));
        event(new AfterDestroy());
    }
}
