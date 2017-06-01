<?php

namespace App\Http\Controllers\Alert;

use App\Events\Alert\AfterCreate;
use App\Events\Alert\AfterDestroy;
use App\Events\Alert\AfterEdit;
use App\Events\Alert\AfterIndex;
use App\Events\Alert\AfterShow;
use App\Events\Alert\AfterStore;
use App\Events\Alert\AfterUpdate;
use App\Events\Alert\BeforeCreate;
use App\Events\Alert\BeforeDestroy;
use App\Events\Alert\BeforeEdit;
use App\Events\Alert\BeforeIndex;
use App\Events\Alert\BeforeShow;
use App\Events\Alert\BeforeStore;
use App\Events\Alert\BeforeUpdate;
use App\Http\Controllers\Controller;
use App\Services\Alert\AlertService;
use App\Services\MailingAgent\CampaignMonitor\MailingAgentService;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    protected $request;

    protected $alertService;

    protected $mailingAgentService;

    public function __construct(Request $request, AlertService $alertService, MailingAgentService $mailingAgentService)
    {
        $this->request = $request;

        $this->alertService = $alertService;

        $this->mailingAgentService = $mailingAgentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        event(new BeforeIndex);

        if ($this->request->ajax()) {
            $alerts = $this->alertService->load();
            $status = true;
        }

        event(new AfterIndex);

        if ($this->request->ajax()) {
            return compact(['alerts', 'status']);
        } else {
            return view('app.alert.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        event(new BeforeCreate);
        event(new AfterCreate);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        event(new BeforeStore);

        $this->alertService->store($this->request->all());
        $status = true;

        $this->mailingAgentService->updateLastSetupAlertDate(auth()->user());

        event(new AfterStore);

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

        event(new BeforeShow);
        event(new AfterShow);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        event(new BeforeEdit);
        event(new AfterEdit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {

        event(new BeforeUpdate);
        event(new AfterUpdate);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        event(new BeforeDestroy);
        event(new AfterDestroy);
    }
}
