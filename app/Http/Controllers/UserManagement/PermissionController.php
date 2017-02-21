<?php

namespace App\Http\Controllers\UserManagement;

use App\Contracts\Repositories\UserManagement\PermissionContract;
use App\Events\UserManagement\Permission\BeforeIndex;
use App\Events\UserManagement\Permission\BeforeCreate;
use App\Events\UserManagement\Permission\BeforeStore;
use App\Events\UserManagement\Permission\BeforeShow;
use App\Events\UserManagement\Permission\BeforeEdit;
use App\Events\UserManagement\Permission\BeforeUpdate;
use App\Events\UserManagement\Permission\BeforeDestroy;
use App\Events\UserManagement\Permission\AfterIndex;
use App\Events\UserManagement\Permission\AfterCreate;
use App\Events\UserManagement\Permission\AfterStore;
use App\Events\UserManagement\Permission\AfterShow;
use App\Events\UserManagement\Permission\AfterEdit;
use App\Events\UserManagement\Permission\AfterUpdate;
use App\Events\UserManagement\Permission\AfterDestroy;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Validators\UserManagement\Permission\StoreValidator;
use App\Validators\UserManagement\Permission\UpdateValidator;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    var $request;
    var $permissionRepo;

    public function __construct(Request $request,
                                PermissionContract $permissionContract)
    {
        $this->request = $request;
        $this->permissionRepo = $permissionContract;
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
            $permissions = $this->permissionRepo->all();
        } else {
            $permissions = $this->permissionRepo->filterAll($this->request->all());
        }
        $status = true;

        event(new AfterIndex());

        if ($this->request->ajax()) {
            return compact(['status', 'permissions']);
        } else {
            return view('app.user_management.permission.index');
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

        return view('app.user_management.permission.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreValidator $storeValidator
     * @return \Illuminate\Http\Response
     */
    public function store(StoreValidator $storeValidator)
    {
        event(new BeforeStore());
        $storeValidator->validate($this->request->all());
        $permission = $this->permissionRepo->store($this->request->all());
        $status = true;
        event(new AfterStore($permission));
        return compact(['status', 'permission']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission $permission
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show(Permission $permission)
    {
        event(new BeforeShow($permission));
        $status = true;
        event(new AfterShow($permission));

        if ($this->request->ajax()) {
            return compact(['status', 'permission']);
        } else {
            return view('app.user_management.permission.show')->with(compact(['status', 'permission']));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission $permission
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Permission $permission)
    {
        event(new BeforeEdit($permission));
        $status = true;
        event(new AfterEdit($permission));
        return view('app.user_management.permission.edit')->with(compact(['permission', 'status']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Permission $permission
     * @param UpdateValidator $updateValidator
     * @return \Illuminate\Http\Response
     */
    public function update(Permission $permission, UpdateValidator $updateValidator)
    {
        event(new BeforeUpdate($permission));
        $id = $permission->getKey();
        $this->request->merge(compact(['id']));
        $updateValidator->validate($this->request->all());
        $permission = $this->permissionRepo->update($permission, $this->request->all());
        $status = true;
        event(new AfterUpdate($permission));
        return compact(['permission', 'status']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        event(new BeforeDestroy($permission));
        $this->permissionRepo->destroy($permission);
        $status = true;
        event(new AfterDestroy());
        return compact(['status']);
    }
}
