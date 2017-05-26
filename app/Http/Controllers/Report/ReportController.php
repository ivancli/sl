<?php

namespace App\Http\Controllers\Report;

use App\Events\Report\AfterCreate;
use App\Events\Report\AfterDestroy;
use App\Events\Report\AfterEdit;
use App\Events\Report\AfterIndex;
use App\Events\Report\AfterShow;
use App\Events\Report\AfterStore;
use App\Events\Report\AfterUpdate;
use App\Events\Report\BeforeCreate;
use App\Events\Report\BeforeDestroy;
use App\Events\Report\BeforeEdit;
use App\Events\Report\BeforeIndex;
use App\Events\Report\BeforeShow;
use App\Events\Report\BeforeStore;
use App\Events\Report\BeforeUpdate;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\Report\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $request;

    protected $reportService;

    public function __construct(Request $request, ReportService $reportService)
    {
        $this->request = $request;

        $this->reportService = $reportService;
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

            $reports = $this->reportService->load($this->request->all());

            $status = true;

        }

        event(new AfterIndex);

        if ($this->request->ajax()) {
            return compact(['status', 'reports']);
        } else {
            return view('app.report.index');
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

        $report = $this->reportService->store($this->request->all());
        $status = true;

        event(new AfterStore);

        return compact(['report', 'status']);
    }

    /**
     * Display the specified resource.
     *
     * @param Report $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        event(new BeforeShow($report));
        event(new AfterShow($report));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Report $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        event(new BeforeEdit($report));
        event(new AfterEdit($report));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Report $report
     * @return \Illuminate\Http\Response
     */
    public function update(Report $report)
    {
        event(new BeforeUpdate($report));

        $report = $this->reportService->update($report, $this->request->all());
        $status = true;

        event(new AfterUpdate($report));

        return compact(['status', 'report']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Report $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        event(new BeforeDestroy($report));

        $status = $report->delete();

        event(new AfterDestroy($report));

        return compact(['status']);
    }
}
