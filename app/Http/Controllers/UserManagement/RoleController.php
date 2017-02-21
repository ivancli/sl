<?php

namespace App\Http\Controllers\UserManagement;

use App\Contracts\Repositories\UserManagement\PermissionContract;
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
use App\Models\Role;
use App\Validators\UserManagement\Role\StoreValidator;
use App\Validators\UserManagement\Role\UpdateValidator;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    var $request;
    var $roleRepo, $permissionRepo;

    public function __construct(Request $request,
                                RoleContract $roleContract,
                                PermissionContract $permissionContract)
    {
        $this->request = $request;
        $this->roleRepo = $roleContract;
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
            $roles = $this->roleRepo->all();
        } else {
            $roles = $this->roleRepo->filterAll($this->request->all());
        }
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

        return view('app.user_management.role.create');
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
        $role = $this->roleRepo->store($this->request->all());

        /* update permission */
        if ($this->request->has('permission_ids')) {
            $permissions = array();
            foreach ($this->request->get('permission_ids') as $permission_id) {
                $permissions[] = $this->permissionRepo->get($permission_id);
            }
            $this->roleRepo->updatePermissions($role, $permissions);
        }
        $status = true;
        event(new AfterStore($role));
        return compact(['status', 'role']);
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show(Role $role)
    {
        event(new BeforeShow($role));
        $status = true;
        $role->selectedPermissions = $role->perms;
        event(new AfterShow($role));

        if ($this->request->ajax()) {
            return compact(['status', 'role']);
        } else {
            return view('app.user_management.role.show')->with(compact(['status', 'role']));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Role $role)
    {
        event(new BeforeEdit($role));
        $status = true;
        event(new AfterEdit($role));
        return view('app.user_management.role.edit')->with(compact(['role', 'status']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Role $role
     * @param UpdateValidator $updateValidator
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Role $role, UpdateValidator $updateValidator)
    {
        event(new BeforeUpdate($role));
        $id = $role->getKey();
        $this->request->merge(compact(['id']));
        $updateValidator->validate($this->request->all());

        /* update role itself */
        $role = $this->roleRepo->update($role, $this->request->all());

        /* update permission */
        if ($this->request->has('permission_ids')) {
            $permissions = array();
            foreach ($this->request->get('permission_ids') as $permission_id) {
                $permissions[] = $this->permissionRepo->get($permission_id);
            }
            $this->roleRepo->updatePermissions($role, $permissions);
        }

        $status = true;
        event(new AfterUpdate($role));
        return compact(['role', 'status']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        event(new BeforeDestroy($role));
        $this->roleRepo->destroy($role);
        $status = true;
        event(new AfterDestroy());

        return compact(['status']);
    }
}
