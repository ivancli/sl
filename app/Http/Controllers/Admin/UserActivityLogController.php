<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\Admin\UserActivityLogContract;
use App\Events\Admin\UserActivityLog\BeforeIndex;
use App\Events\Admin\UserActivityLog\AfterIndex;
use App\Events\Admin\UserActivityLog\BeforeCreate;
use App\Events\Admin\UserActivityLog\AfterCreate;
use App\Events\Admin\UserActivityLog\BeforeStore;
use App\Events\Admin\UserActivityLog\AfterStore;
use App\Events\Admin\UserActivityLog\BeforeShow;
use App\Events\Admin\UserActivityLog\AfterShow;
use App\Events\Admin\UserActivityLog\BeforeEdit;
use App\Events\Admin\UserActivityLog\AfterEdit;
use App\Events\Admin\UserActivityLog\BeforeUpdate;
use App\Events\Admin\UserActivityLog\AfterUpdate;
use App\Events\Admin\UserActivityLog\BeforeDestroy;
use App\Events\Admin\UserActivityLog\AfterDestroy;
use App\Models\LoggingModels\UserActivityLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserActivityLogController extends Controller
{
    var $request;
    var $userActivityLogRepo;

    public function __construct(Request $request, UserActivityLogContract $userActivityLogContract)
    {
        $this->request = $request;
        $this->userActivityLogRepo = $userActivityLogContract;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        event(new BeforeIndex());
        if ($this->request->ajax()) {
            $userActivityLogs = $this->userActivityLogRepo->filterAll($this->request->all());
            $status = true;
        }

        event(new AfterIndex());

        if ($this->request->ajax()) {
            return compact('userActivityLogs', 'status');
        } else {
            return view('app.admin.user_activity_log.index');
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
     * @param  \App\Models\LoggingModels\UserActivityLog $userActivityLog
     * @return \Illuminate\Http\Response
     */
    public function show(UserActivityLog $userActivityLog)
    {
        event(new BeforeShow($userActivityLog));
        event(new AfterShow($userActivityLog));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LoggingModels\UserActivityLog $userActivityLog
     * @return \Illuminate\Http\Response
     */
    public function edit(UserActivityLog $userActivityLog)
    {
        event(new BeforeEdit($userActivityLog));
        event(new AfterEdit($userActivityLog));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\LoggingModels\UserActivityLog $userActivityLog
     * @return \Illuminate\Http\Response
     */
    public function update(UserActivityLog $userActivityLog)
    {
        event(new BeforeUpdate($userActivityLog));
        event(new AfterUpdate($userActivityLog));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LoggingModels\UserActivityLog $userActivityLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserActivityLog $userActivityLog)
    {
        event(new BeforeDestroy($userActivityLog));
        event(new AfterDestroy());
    }
}
