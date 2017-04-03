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
use App\Models\Group;
use App\Validators\UserManagement\Group\StoreValidator;
use App\Validators\UserManagement\Group\UpdateValidator;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    protected $request;
    protected $groupRepo;

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

        if (!$this->request->has('page')) {
            $groups = $this->groupRepo->all();
        } else {
            $groups = $this->groupRepo->filterAll($this->request->all());
        }
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
     * @param StoreValidator $storeValidator
     * @return \Illuminate\Http\Response
     */
    public function store(StoreValidator $storeValidator)
    {
        event(new BeforeStore());
        $storeValidator->validate($this->request->all());
        $group = $this->groupRepo->store($this->request->all());
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
     * @param UpdateValidator $updateValidator
     * @return \Illuminate\Http\Response
     */
    public function update(Group $group, UpdateValidator $updateValidator)
    {
        event(new BeforeUpdate($group));
        $id = $group->getKey();
        $this->request->merge(compact(['id']));
        $updateValidator->validate($this->request->all());
        $group = $this->groupRepo->update($group, $this->request->all());
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
        $this->groupRepo->destroy($group);
        $status = true;
        event(new AfterDestroy());

        return compact(['status']);
    }
}
