<?php

namespace App\Http\Controllers\UserManagement;

use App\Contracts\Repositories\UserManagement\GroupContract;
use App\Events\UserManagement\Group\AfterCreate;
use App\Events\UserManagement\Group\AfterDestroy;
use App\Events\UserManagement\Group\AfterEdit;
use App\Events\UserManagement\Group\AfterIndex;
use App\Events\UserManagement\Group\AfterShow;
use App\Events\UserManagement\Group\AfterStore;
use App\Events\UserManagement\Group\AfterUpdate;
use App\Events\UserManagement\Group\BeforeCreate;
use App\Events\UserManagement\Group\BeforeDestroy;
use App\Events\UserManagement\Group\BeforeEdit;
use App\Events\UserManagement\Group\BeforeIndex;
use App\Events\UserManagement\Group\BeforeShow;
use App\Events\UserManagement\Group\BeforeStore;
use App\Events\UserManagement\Group\BeforeUpdate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    var $request;
    var $groupRepo;

    public function __construct(Request $request,
                                GroupContract $groupContract)
    {
        $this->request = $request;

        $this->groupRepo = $groupContract;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        event(new BeforeIndex());

        $groups = $this->groupRepo->filterAll($this->request->all());
        $status = true;

        event(new AfterIndex());
        if ($this->request->ajax()) {
            return compact(['status', 'groups']);
        } else {
            return view('app.user_management.group.index');
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

        return view('app.user_management.group.create');
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
        $group = $this->groupRepo->get($id);
        event(new BeforeDestroy($group));
        $this->groupRepo->destroy($id);
        $status = true;
        event(new AfterDestroy());

        return compact(['status']);
    }
}
