<?php

namespace App\Http\Controllers\UserManagement;

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
use App\Models\Group;
use App\Services\UserManagement\GroupService;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    protected $request;
    protected $groupService;

    public function __construct(Request $request, GroupService $groupService)
    {
        $this->request = $request;

        $this->groupService = $groupService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        event(new BeforeIndex());
        $groups = $this->groupService->load($this->request->all());
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

        $group = $this->groupService->store($this->request->all());
        $status = true;

        event(new AfterStore($group));

        return compact(['status', 'group']);
    }

    /**
     * Display the specified resource.
     *
     * @param Group $group
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show(Group $group)
    {
        event(new BeforeShow($group));

        $status = true;

        event(new AfterShow($group));

        if ($this->request->ajax()) {
            return compact(['status', 'group']);
        } else {
            return view('app.user_management.group.show')->with(compact(['status', 'group']));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Group $group
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Group $group)
    {
        event(new BeforeEdit($group));

        $status = true;

        event(new AfterEdit($group));

        return view('app.user_management.group.edit')->with(compact(['group', 'status']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function update(Group $group)
    {
        event(new BeforeUpdate($group));

        $group = $this->groupService->update($group, $this->request->all());
        $status = true;

        event(new AfterUpdate($group));

        return compact(['group', 'status']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        event(new BeforeDestroy($group));

        $status = $this->groupService->destroy($group);

        event(new AfterDestroy());

        return compact(['status']);
    }
}
