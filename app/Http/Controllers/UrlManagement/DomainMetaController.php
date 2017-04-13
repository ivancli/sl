<?php

namespace App\Http\Controllers\UrlManagement;

use App\Events\UrlManagement\DomainMeta\BeforeIndex;
use App\Events\UrlManagement\DomainMeta\AfterIndex;
use App\Events\UrlManagement\DomainMeta\BeforeCreate;
use App\Events\UrlManagement\DomainMeta\AfterCreate;
use App\Events\UrlManagement\DomainMeta\BeforeStore;
use App\Events\UrlManagement\DomainMeta\AfterStore;
use App\Events\UrlManagement\DomainMeta\BeforeShow;
use App\Events\UrlManagement\DomainMeta\AfterShow;
use App\Events\UrlManagement\DomainMeta\BeforeEdit;
use App\Events\UrlManagement\DomainMeta\AfterEdit;
use App\Events\UrlManagement\DomainMeta\BeforeUpdate;
use App\Events\UrlManagement\DomainMeta\AfterUpdate;
use App\Events\UrlManagement\DomainMeta\BeforeDestroy;
use App\Events\UrlManagement\DomainMeta\AfterDestroy;
use App\Http\Controllers\Controller;
use App\Services\UrlManagement\DomainMetaService;
use Illuminate\Http\Request;

class DomainMetaController extends Controller
{
    protected $request;
    protected $domainMetaService;

    public function __construct(Request $request, DomainMetaService $domainMetaService)
    {
        $this->request = $request;
        $this->domainMetaService = $domainMetaService;
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
        $domain = $this->domainMetaService->getDomainById($domain_id);

        event(new BeforeShow($domain));
        event(new AfterShow($domain));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $domain_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit($domain_id)
    {
        $domain = $this->domainMetaService->getDomainById($domain_id);

        event(new BeforeEdit($domain));

        $status = true;

        event(new AfterEdit($domain));

        return view('app.url_management.domain_meta.edit')->with(compact(['status', 'domain']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $domain_id
     * @return \Illuminate\Http\Response
     */
    public function update($domain_id)
    {
        $domain = $this->domainMetaService->getDomainById($domain_id);

        event(new BeforeUpdate($domain));

        $domain = $this->domainMetaService->update($domain, $this->request->all());
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
        $domain = $this->domainMetaService->getDomainById($domain_id);

        event(new BeforeDestroy($domain));
        event(new AfterDestroy());
    }
}
