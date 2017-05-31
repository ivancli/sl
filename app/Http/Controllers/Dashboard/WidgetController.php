<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Widget;
use App\Services\Dashboard\WidgetService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WidgetController extends Controller
{
    protected $request;
    protected $widgetService;

    public function __construct(Request $request, WidgetService $widgetService)
    {
        $this->request = $request;
        $this->widgetService = $widgetService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*TODO add events*/
        if ($this->request->ajax()) {
            $widgets = $this->widgetService->load($this->request->all());
        }
        $status = true;

        if ($this->request->ajax()) {
            return compact(['status', 'widgets']);
        } else {
            return view('app.dashboard.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $widget = $this->widgetService->store($this->request->all());
        $status = true;
        return compact(['status', 'widget']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Widget $widget
     * @return \Illuminate\Http\Response
     */
    public function show(Widget $widget)
    {
        $status = true;

        return compact(['widget', 'status']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Widget $widget
     * @return \Illuminate\Http\Response
     */
    public function edit(Widget $widget)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Widget $widget
     * @return \Illuminate\Http\Response
     */
    public function update(Widget $widget)
    {
        $widget = $this->widgetService->update($widget, $this->request->all());
        return compact(['status', 'widget']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Widget $widget
     * @return \Illuminate\Http\Response
     */
    public function destroy(Widget $widget)
    {
        $this->widgetService->destroy($widget);
        $status = true;

        return compact(['status']);
    }
}
