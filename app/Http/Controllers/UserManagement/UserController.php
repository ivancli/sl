<?php

namespace App\Http\Controllers\UserManagement;

use App\Contracts\Repositories\UserManagement\RoleContract;
use App\Contracts\Repositories\UserManagement\UserContract;
use App\Events\UserManagement\User\AfterCreate;
use App\Events\UserManagement\User\AfterDestroy;
use App\Events\UserManagement\User\AfterEdit;
use App\Events\UserManagement\User\AfterIndex;
use App\Events\UserManagement\User\AfterShow;
use App\Events\UserManagement\User\AfterStore;
use App\Events\UserManagement\User\AfterUpdate;
use App\Events\UserManagement\User\BeforeCreate;
use App\Events\UserManagement\User\BeforeDestroy;
use App\Events\UserManagement\User\BeforeEdit;
use App\Events\UserManagement\User\BeforeIndex;
use App\Events\UserManagement\User\BeforeShow;
use App\Events\UserManagement\User\BeforeStore;
use App\Events\UserManagement\User\BeforeUpdate;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Validators\UserManagement\User\StoreValidator;
use App\Validators\UserManagement\User\UpdateValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    var $request;
    var $userRepo, $roleRepo;

    public function __construct(Request $request,
                                UserContract $userContract,
                                RoleContract $roleContract)
    {
        $this->request = $request;

        $this->userRepo = $userContract;
        $this->roleRepo = $roleContract;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        event(new BeforeIndex());

        if (!$this->request->has('page')) {
            $users = $this->userRepo->all();
        } else {
            $users = $this->userRepo->filterAll($this->request->all());
        }
        $status = true;

        event(new AfterIndex());

        if ($this->request->ajax()) {
            return compact('users', 'status');
        } else {
            return view('app.user_management.user.index');
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
        return view('app.user_management.user.create');
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
        $user = $this->userRepo->store($this->request->all());

        if ($this->request->has('role_ids')) {
            $roles = array();
            foreach ($this->request->get('role_ids') as $role_id) {
                $role = $this->roleRepo->get($role_id);
                $roles[] = $role;
            }
            $this->userRepo->updateRoles($user, $roles);
        }
        $status = true;

        event(new AfterStore($user));
        return compact(['user', 'status']);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show(User $user)
    {
        event(new BeforeShow($user));
        $user->selectedRoles = $user->roles;
        $status = true;
        event(new AfterShow($user));
        if ($this->request->ajax()) {
            return compact(['status', 'user']);
        } else {
            return view('app.user_management.user.show')->with(compact(['status', 'user']));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @internal param int $id
     */
    public function edit(User $user)
    {
        event(new BeforeEdit($user));
        $status = true;
        event(new AfterEdit($user));
        return view('app.user_management.user.edit')->with(compact(['user', 'status']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param User $user
     * @param UpdateValidator $updateValidator
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(User $user, UpdateValidator $updateValidator)
    {
        event(new BeforeUpdate($user));
        $id = $user->getKey();
        $this->request->merge(compact(['id']));
        $updateValidator->validate($this->request->all());
        $user = $this->userRepo->update($user, $this->request->all());

        if ($this->request->has('role_ids')) {
            $roles = array();
            foreach ($this->request->get('role_ids') as $role_id) {
                $role = $this->roleRepo->get($role_id);
                $roles[] = $role;
            }
            $this->userRepo->updateRoles($user, $roles);
        }

        $status = true;
        event(new AfterUpdate($user));
        return compact(['user', 'status']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        event(new BeforeDestroy($user));
        $this->userRepo->destroy($user);
        $status = true;
        event(new AfterDestroy());
        return compact(['status']);
    }
}
