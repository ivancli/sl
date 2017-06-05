<?php

namespace App\Http\Controllers\Report;

use App\Events\Positioning\AfterCreate;
use App\Events\Positioning\AfterDestroy;
use App\Events\Positioning\AfterEdit;
use App\Events\Positioning\AfterIndex;
use App\Events\Positioning\AfterShow;
use App\Events\Positioning\AfterStore;
use App\Events\Positioning\AfterUpdate;
use App\Events\Positioning\BeforeCreate;
use App\Events\Positioning\BeforeDestroy;
use App\Events\Positioning\BeforeEdit;
use App\Events\Positioning\BeforeIndex;
use App\Events\Positioning\BeforeShow;
use App\Events\Positioning\BeforeStore;
use App\Events\Positioning\BeforeUpdate;
use App\Services\Report\PositioningService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PositioningController extends Controller
{
    protected $request;

    protected $positioningService;

    public function __construct(Request $request, PositioningService $positioningService)
    {
        $this->request = $request;

        $this->positioningService = $positioningService;
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
            $products = $this->positioningService->load($this->request->all());
            $status = true;
        }

        event(new AfterIndex);
        if ($this->request->ajax()) {
            return compact(['status', 'products']);
        } else {
            return view('app.positioning.index');
        }
    }

    public function filter()
    {
        $options = $this->positioningService->filterOptions();
        $status = true;

        return compact(['status', 'options']);
    }

    public function export()
    {
        $this->positioningService->export($this->request->all());
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
