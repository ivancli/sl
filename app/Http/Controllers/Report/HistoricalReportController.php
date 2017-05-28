<?php

namespace App\Http\Controllers\Report;

use App\Contracts\Repositories\Report\HistoricalReportContract;
use App\Events\HistoricalReport\AfterCreate;
use App\Events\HistoricalReport\AfterDestroy;
use App\Events\HistoricalReport\AfterEdit;
use App\Events\HistoricalReport\AfterIndex;
use App\Events\HistoricalReport\AfterShow;
use App\Events\HistoricalReport\AfterStore;
use App\Events\HistoricalReport\AfterUpdate;
use App\Events\HistoricalReport\BeforeCreate;
use App\Events\HistoricalReport\BeforeDestroy;
use App\Events\HistoricalReport\BeforeEdit;
use App\Events\HistoricalReport\BeforeIndex;
use App\Events\HistoricalReport\BeforeShow;
use App\Events\HistoricalReport\BeforeStore;
use App\Events\HistoricalReport\BeforeUpdate;
use App\Models\HistoricalReport;
use App\Services\Report\HistoricalReportService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HistoricalReportController extends Controller
{
    protected $request;
    protected $historicalReportService;

    public function __construct(Request $request, HistoricalReportService $historicalReportService)
    {
        $this->request = $request;
        $this->historicalReportService = $historicalReportService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        event(new BeforeIndex);

        $historicalReports = $this->historicalReportService->load($this->request->all());
        $status = true;

        event(new AfterIndex);

        return compact(['status', 'historicalReports']);
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
        event(new AfterStore);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HistoricalReport $historicalReport
     * @return \Illuminate\Http\Response
     */
    public function show(HistoricalReport $historicalReport)
    {
        event(new BeforeShow($historicalReport));

        event(new AfterShow($historicalReport));

        return response(base64_decode($historicalReport->content))
            ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename={$historicalReport->file_name}.xlsx")
            ->header('Expires', 0)
            ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->header('Cache-Control', 'private', false);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HistoricalReport $historicalReport
     * @return \Illuminate\Http\Response
     */
    public function edit(HistoricalReport $historicalReport)
    {
        event(new BeforeEdit($historicalReport));
        event(new AfterEdit($historicalReport));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\HistoricalReport $historicalReport
     * @return \Illuminate\Http\Response
     */
    public function update(HistoricalReport $historicalReport)
    {
        event(new BeforeUpdate($historicalReport));
        event(new AfterUpdate($historicalReport));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HistoricalReport $historicalReport
     * @return \Illuminate\Http\Response
     */
    public function destroy(HistoricalReport $historicalReport)
    {
        event(new BeforeDestroy($historicalReport));
        event(new AfterDestroy);
    }
}
