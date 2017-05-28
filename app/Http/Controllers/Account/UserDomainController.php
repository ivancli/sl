<?php

namespace App\Http\Controllers\Account;

use App\Events\UserManagement\UserDomain\AfterCreate;
use App\Events\UserManagement\UserDomain\AfterDestroy;
use App\Events\UserManagement\UserDomain\AfterEdit;
use App\Events\UserManagement\UserDomain\AfterIndex;
use App\Events\UserManagement\UserDomain\AfterShow;
use App\Events\UserManagement\UserDomain\AfterStore;
use App\Events\UserManagement\UserDomain\AfterUpdate;
use App\Events\UserManagement\UserDomain\BeforeCreate;
use App\Events\UserManagement\UserDomain\BeforeDestroy;
use App\Events\UserManagement\UserDomain\BeforeEdit;
use App\Events\UserManagement\UserDomain\BeforeIndex;
use App\Events\UserManagement\UserDomain\BeforeShow;
use App\Events\UserManagement\UserDomain\BeforeStore;
use App\Events\UserManagement\UserDomain\BeforeUpdate;
use App\Models\UserDomain;
use App\Services\Account\UserDomainService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserDomainController extends Controller
{
    protected $request;
    protected $userDomainService;

    public function __construct(Request $request, UserDomainService $userDomainService)
    {
        $this->request = $request;
        $this->userDomainService = $userDomainService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        event(new BeforeIndex);

        $userDomains = $this->userDomainService->load();
        $status = true;

        event(new AfterIndex);
        return compact(['status', 'userDomains']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        event(new BeforeCreate);
        event(new AfterCreate);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        event(new BeforeStore);
        $userDomains = $this->userDomainService->store($this->request->all());
        $status = true;

        event(new AfterStore);

        return compact(['status', 'userDomains']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserDomain $userDomain
     * @return \Illuminate\Http\Response
     */
    public function show(UserDomain $userDomain)
    {
        event(new BeforeShow);
        event(new AfterShow);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserDomain $userDomain
     * @return \Illuminate\Http\Response
     */
    public function edit(UserDomain $userDomain)
    {
        event(new BeforeEdit);
        event(new AfterEdit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\UserDomain $userDomain
     * @return \Illuminate\Http\Response
     */
    public function update(UserDomain $userDomain)
    {
        event(new BeforeUpdate);
        event(new AfterUpdate);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserDomain $userDomain
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserDomain $userDomain)
    {
        event(new BeforeDestroy);
        event(new AfterDestroy);
    }
}
