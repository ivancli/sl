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

        $users = $this->userRepo->all();
        $status = true;

        event(new AfterIndex());

        if ($this->request->ajax()) {
            return new JsonResponse(compact('users', 'status'));
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
