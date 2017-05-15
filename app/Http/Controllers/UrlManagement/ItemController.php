<?php

namespace App\Http\Controllers\UrlManagement;

use App\Events\UrlManagement\Item\AfterPrices;
use App\Events\UrlManagement\Item\AfterQueue;
use App\Events\UrlManagement\Item\BeforePrices;
use App\Events\UrlManagement\Item\BeforeQueue;
use App\Http\Controllers\Controller;
use App\Events\UrlManagement\Item\BeforeIndex;
use App\Events\UrlManagement\Item\AfterIndex;
use App\Events\UrlManagement\Item\BeforeCreate;
use App\Events\UrlManagement\Item\AfterCreate;
use App\Events\UrlManagement\Item\BeforeStore;
use App\Events\UrlManagement\Item\AfterStore;
use App\Events\UrlManagement\Item\BeforeShow;
use App\Events\UrlManagement\Item\AfterShow;
use App\Events\UrlManagement\Item\BeforeEdit;
use App\Events\UrlManagement\Item\AfterEdit;
use App\Events\UrlManagement\Item\BeforeUpdate;
use App\Events\UrlManagement\Item\AfterUpdate;
use App\Events\UrlManagement\Item\BeforeDestroy;
use App\Events\UrlManagement\Item\AfterDestroy;
use App\Models\Item;
use App\Services\UrlManagement\ItemService;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    protected $request;
    protected $itemService;

    public function __construct(Request $request, ItemService $itemService)
    {
        $this->request = $request;

        $this->itemService = $itemService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        event(new BeforeIndex());

        $url = $this->itemService->getUrlById($this->request->get('url_id'));
        $items = $this->itemService->load($this->request->all());
        $status = true;

        event(new AfterIndex());

        if ($this->request->ajax()) {
            return compact(['status', 'items']);
        } else {
            return view('app.url_management.item.index')->with(compact(['url']));
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

        $item = $this->itemService->store($this->request->all());
        $status = true;

        event(new AfterStore($item));

        return compact(['status', 'item']);
    }

    /**
     * Display the specified resource.
     *
     * @param Item $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        event(new BeforeShow($item));

        $status = true;

        event(new AfterShow($item));

        return compact(['status', 'item']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Item $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        event(new BeforeEdit($item));
        event(new AfterEdit($item));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Item $item
     * @return \Illuminate\Http\Response
     */
    public function update(Item $item)
    {
        event(new BeforeUpdate($item));

        $item = $this->itemService->update($item, $this->request->all());
        $status = true;

        event(new AfterUpdate($item));

        return compact(['status', 'item']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Item $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        event(new BeforeDestroy($item));

        $status = $this->itemService->destroy($item);

        event(new AfterDestroy());

        return compact(['status']);
    }

    /**
     * Push URL of the item to queue
     *
     * @param Item $item
     * @return array
     */
    public function queue(Item $item)
    {
        event(new BeforeQueue($item));

        $this->itemService->queue($item);
        $status = true;

        event(new AfterQueue($item));

        return compact(['status']);
    }

    /**
     * Retrieve historical prices of an item
     *
     * @param Item $item
     * @return array
     */
    public function prices(Item $item)
    {
        event(new BeforePrices($item));

        $historicalPrices = $this->itemService->loadHistoricalPrices($item);
        $status = !is_null($historicalPrices);

        event(new AfterPrices($item));

        return compact(['status', 'historicalPrices']);
    }
}
