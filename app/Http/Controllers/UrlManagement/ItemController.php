<?php

namespace App\Http\Controllers\UrlManagement;

use App\Contracts\Repositories\UrlManagement\ItemContract;
use App\Contracts\Repositories\UrlManagement\UrlContract;
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
use App\Validators\UrlManagement\Item\StoreValidator;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    var $request;
    var $urlRepo;
    var $itemRepo;

    public function __construct(Request $request,
                                UrlContract $urlContract,
                                ItemContract $itemContract)
    {
        $this->request = $request;

        $this->urlRepo = $urlContract;
        $this->itemRepo = $itemContract;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        event(new BeforeIndex());
        $url = null;
        if ($this->request->has('url_id')) {
            $url = $this->urlRepo->get($this->request->get('url_id'));
        }
        $items = $this->itemRepo->filterAll($this->request->all(), $url);
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
     * @param StoreValidator $storeValidator
     * @return \Illuminate\Http\Response
     */
    public function store(StoreValidator $storeValidator)
    {

        event(new BeforeStore());
        $storeValidator->validate($this->request->all());

        $url = $this->urlRepo->get($this->request->get('url_id'));
        $item = $this->itemRepo->store($this->request->all());
        $url->items()->save($item);
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
        $item = $this->itemRepo->update($item, $this->request->all());
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
        $this->itemRepo->destroy($item);
        $status = true;
        event(new AfterDestroy());
        return compact(['status']);
    }
}
