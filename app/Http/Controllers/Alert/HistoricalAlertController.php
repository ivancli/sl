<?php

namespace App\Http\Controllers\Alert;

use App\Events\HistoricalAlert\AfterCreate;
use App\Events\HistoricalAlert\AfterDestroy;
use App\Events\HistoricalAlert\AfterEdit;
use App\Events\HistoricalAlert\AfterIndex;
use App\Events\HistoricalAlert\AfterShow;
use App\Events\HistoricalAlert\AfterStore;
use App\Events\HistoricalAlert\AfterUpdate;
use App\Events\HistoricalAlert\BeforeCreate;
use App\Events\HistoricalAlert\BeforeDestroy;
use App\Events\HistoricalAlert\BeforeEdit;
use App\Events\HistoricalAlert\BeforeIndex;
use App\Events\HistoricalAlert\BeforeShow;
use App\Events\HistoricalAlert\BeforeStore;
use App\Events\HistoricalAlert\BeforeUpdate;
use App\Services\Alert\HistoricalAlertService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HistoricalAlertController extends Controller
{
    protected $request;

    protected $historicalAlertService;

    public function __construct(Request $request, HistoricalAlertService $historicalAlertService)
    {
        $this->request = $request;
        $this->historicalAlertService = $historicalAlertService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        event(new BeforeIndex);

        $historicalAlerts = $this->historicalAlertService->load($this->request->all());
        $status = true;

        event(new AfterIndex);

        return compact(['status', 'historicalAlerts']);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        event(new BeforeStore);
        event(new AfterStore);
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
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
