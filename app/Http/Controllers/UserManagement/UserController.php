<?php

namespace App\Http\Controllers\UserManagement;

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
use App\Validators\UserManagement\User\StoreValidator;
use App\Validators\UserManagement\User\UpdateValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    var $request;
    var $userRepo;

    public function __construct(Request $request,
                                UserContract $userContract)
    {
        $this->request = $request;

        $this->userRepo = $userContract;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        event(new BeforeIndex());

        $users = $this->userRepo->filterAll($this->request->all());
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
        $status = true;

        event(new AfterStore($user));
        return compact(['user', 'status']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show($id)
    {
        $user = $this->userRepo->get($id);
        event(new BeforeShow($user));
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
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit($id)
    {
        $user = $this->userRepo->get($id);
        event(new BeforeEdit($user));
        $status = true;
        event(new AfterEdit($user));
        return view('app.user_management.user.edit')->with(compact(['user', 'status']));
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
        $user = $this->userRepo->get($id);
        event(new BeforeUpdate($user));
        $this->request->merge(compact(['id']));
        $updateValidator->validate($this->request->all());
        $user = $this->userRepo->update($id, $this->request->all());
        $status = true;
        event(new AfterUpdate($user));
        return compact(['user', 'status']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->userRepo->get($id);
        event(new BeforeDestroy($user));
        $this->userRepo->destroy($id);
        $status = true;
        event(new AfterDestroy());
        return compact(['status']);
    }
}
