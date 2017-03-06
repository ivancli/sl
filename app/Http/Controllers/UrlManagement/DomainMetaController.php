<?php

namespace App\Http\Controllers\UrlManagement;

use App\Contracts\Repositories\UrlManagement\DomainContract;
use App\Contracts\Repositories\UrlManagement\DomainMetaContract;
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
use App\Validators\UrlManagement\DomainMeta\UpdateValidator;
use Illuminate\Http\Request;

class DomainMetaController extends Controller
{
    var $request;
    var $domainRepo;
    var $domainMetaRepo;

    public function __construct(Request $request,
                                DomainContract $domainContract,
                                DomainMetaContract $domainMetaContract)
    {
        $this->request = $request;
        $this->domainRepo = $domainContract;
        $this->domainMetaRepo = $domainMetaContract;
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $domain = $this->domainRepo->get($id);
        event(new BeforeShow($domain));
        event(new AfterShow($domain));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit($id)
    {
        $domain = $this->domainRepo->get($id);
        event(new BeforeEdit($domain));
        $status = true;
        event(new AfterEdit($domain));
        return view('app.url_management.domain_meta.edit')->with(compact(['status', 'domain']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param UpdateValidator $updateValidator
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateValidator $updateValidator)
    {
        $domain = $this->domainRepo->get($id);
        event(new BeforeUpdate($domain));
        $updateValidator->validate($this->request->all());

        $domain = $this->domainMetaRepo->update($domain, $this->request->get('metas'));
        $status = true;
        event(new AfterUpdate($domain));
        return compact(['status', 'domain']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $domain = $this->domainRepo->get($id);
        event(new BeforeDestroy($domain));
        event(new AfterDestroy());
    }
}
