<?php

namespace App\Http\Controllers\UrlManagement;

use App\Contracts\Repositories\UrlManagement\DomainContract;
use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Events\UrlManagement\Domain\BeforeIndex;
use App\Events\UrlManagement\Domain\BeforeCreate;
use App\Events\UrlManagement\Domain\BeforeStore;
use App\Events\UrlManagement\Domain\BeforeShow;
use App\Events\UrlManagement\Domain\BeforeEdit;
use App\Events\UrlManagement\Domain\BeforeUpdate;
use App\Events\UrlManagement\Domain\BeforeDestroy;
use App\Events\UrlManagement\Domain\AfterIndex;
use App\Events\UrlManagement\Domain\AfterCreate;
use App\Events\UrlManagement\Domain\AfterStore;
use App\Events\UrlManagement\Domain\AfterShow;
use App\Events\UrlManagement\Domain\AfterEdit;
use App\Events\UrlManagement\Domain\AfterUpdate;
use App\Events\UrlManagement\Domain\AfterDestroy;
use App\Validators\UrlManagement\Domain\UpdateValidator;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    var $request;
    var $domainRepo;

    public function __construct(Request $request, DomainContract $domainContract)
    {
        $this->request = $request;
        $this->domainRepo = $domainContract;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        event(new BeforeIndex());
        if (!$this->request->has('page')) {
            $domains = $this->domainRepo->all();
        } else {
            $domains = $this->domainRepo->filterAll($this->request->all());
        }
        $status = true;

        event(new AfterIndex());
        if ($this->request->ajax()) {
            return compact(['status', 'domains']);
        } else {
            return view('app.url_management.domain.index');
        }
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
     * @param  \App\Models\Domain $domain
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show(Domain $domain)
    {
        event(new BeforeShow($domain));
        $status = true;
        event(new AfterShow($domain));
        if ($this->request->ajax()) {
            return compact(['status', 'domain']);
        } else {
            return view('app.url_management.domain.show')->with(compact(['status', 'domain']));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Domain $domain
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Domain $domain)
    {
        event(new BeforeEdit($domain));
        $status = true;
        event(new AfterEdit($domain));
        return view('app.url_management.domain.edit')->with(compact(['domain']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Domain $domain
     * @param UpdateValidator $updateValidator
     * @return \Illuminate\Http\Response
     */
    public function update(Domain $domain, UpdateValidator $updateValidator)
    {
        event(new BeforeUpdate($domain));
        $updateValidator->validate($this->request->all());
        $domain = $this->domainRepo->update($domain, $this->request->all());
        $status = true;
        event(new AfterUpdate($domain));

        return compact(['domain', 'status']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Domain $domain
     * @return \Illuminate\Http\Response
     */
    public function destroy(Domain $domain)
    {
        event(new BeforeDestroy($domain));
        $this->domainRepo->destroy($domain);
        $status = true;
        event(new AfterDestroy());
        return compact(['status']);
    }
}