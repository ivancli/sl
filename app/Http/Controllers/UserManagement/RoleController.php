<?php

namespace App\Http\Controllers\UserManagement;

use App\Contracts\Repositories\UserManagement\RoleContract;
use App\Events\UserManagement\Role\AfterCreate;
use App\Events\UserManagement\Role\AfterDestroy;
use App\Events\UserManagement\Role\AfterEdit;
use App\Events\UserManagement\Role\AfterIndex;
use App\Events\UserManagement\Role\AfterShow;
use App\Events\UserManagement\Role\AfterStore;
use App\Events\UserManagement\Role\AfterUpdate;
use App\Events\UserManagement\Role\BeforeCreate;
use App\Events\UserManagement\Role\BeforeDestroy;
use App\Events\UserManagement\Role\BeforeEdit;
use App\Events\UserManagement\Role\BeforeIndex;
use App\Events\UserManagement\Role\BeforeShow;
use App\Events\UserManagement\Role\BeforeStore;
use App\Events\UserManagement\Role\BeforeUpdate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    var $request;
    var $roleRepo;

    public function __construct(Request $request, RoleContract $roleContract)
    {
        $this->request = $request;
        $this->roleRepo = $roleContract;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        event(new BeforeIndex());

        $roles = $this->roleRepo->filterAll($this->request->all());
        $status = true;

        event(new AfterIndex());
        if ($this->request->ajax()) {
            return compact(['status', 'roles']);
        } else {
            return view('app.user_management.role.index');
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        event(new BeforeShow());
        event(new AfterShow());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        event(new BeforeEdit());
        event(new AfterEdit());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        event(new BeforeUpdate());
        event(new AfterUpdate());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        event(new BeforeDestroy());
        event(new AfterDestroy());
    }
}
