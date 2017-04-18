<?php

namespace App\Http\Controllers\Admin;


use App\Events\Admin\AppPref\AfterCreate;
use App\Events\Admin\AppPref\AfterDestroy;
use App\Events\Admin\AppPref\AfterEdit;
use App\Events\Admin\AppPref\AfterIndex;
use App\Events\Admin\AppPref\AfterShow;
use App\Events\Admin\AppPref\AfterStore;
use App\Events\Admin\AppPref\AfterUpdate;
use App\Events\Admin\AppPref\BeforeCreate;
use App\Events\Admin\AppPref\BeforeDestroy;
use App\Events\Admin\AppPref\BeforeEdit;
use App\Events\Admin\AppPref\BeforeIndex;
use App\Events\Admin\AppPref\BeforeShow;
use App\Events\Admin\AppPref\BeforeStore;
use App\Events\Admin\AppPref\BeforeUpdate;
use App\Http\Controllers\Controller;
use App\Services\Admin\AppPrefService;
use Illuminate\Http\Request;

class AppPrefController extends Controller
{
    protected $request;
    protected $appPrefService;

    public function __construct(Request $request,
                                AppPrefService $appPrefService)
    {
        $this->request = $request;
        $this->appPrefService = $appPrefService;
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
            $appPrefs = $this->appPrefService->load();
            $status = true;
        }

        event(new AfterIndex());

        if ($this->request->ajax()) {
            return compact(['status', 'appPrefs']);
        } else {
            return view('app.admin.app_pref.index');
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

        /*TODO add validation to appPrefs*/

        $this->appPrefService->store($this->request->all());
        $status = true;

        event(new AfterStore());

        return compact(['status']);
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
