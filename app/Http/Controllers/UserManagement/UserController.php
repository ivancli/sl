<?php

namespace App\Http\Controllers\UserManagement;

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
use App\Services\UserManagement\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $request;

    protected $userService;

    public function __construct(Request $request, UserService $userService)
    {
        $this->request = $request;

        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        event(new BeforeIndex());

        $users = $this->userService->load($this->request->all());

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
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        event(new BeforeStore());

        $user = $this->userService->store($this->request->all());

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
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(User $user)
    {
        event(new BeforeUpdate($user));

        $user = $this->userService->update($user, $this->request->all());

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

        $status = $this->userService->destroy($user);

        event(new AfterDestroy());

        return compact(['status']);
    }
}
