<?php

namespace App\Http\Controllers\UrlManagement;

use App\Contracts\Repositories\UrlManagement\ItemContract;
use App\Contracts\Repositories\UrlManagement\ItemMetaContract;
use App\Models\ItemMeta;
use App\Events\UrlManagement\ItemMeta\BeforeIndex;
use App\Events\UrlManagement\ItemMeta\AfterIndex;
use App\Events\UrlManagement\ItemMeta\BeforeCreate;
use App\Events\UrlManagement\ItemMeta\AfterCreate;
use App\Events\UrlManagement\ItemMeta\BeforeStore;
use App\Events\UrlManagement\ItemMeta\AfterStore;
use App\Events\UrlManagement\ItemMeta\BeforeShow;
use App\Events\UrlManagement\ItemMeta\AfterShow;
use App\Events\UrlManagement\ItemMeta\BeforeEdit;
use App\Events\UrlManagement\ItemMeta\AfterEdit;
use App\Events\UrlManagement\ItemMeta\BeforeUpdate;
use App\Events\UrlManagement\ItemMeta\AfterUpdate;
use App\Events\UrlManagement\ItemMeta\BeforeDestroy;
use App\Events\UrlManagement\ItemMeta\AfterDestroy;
use App\Validators\UrlManagement\ItemMeta\StoreValidator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemMetaController extends Controller
{
    protected $request;
    protected $itemRepo, $itemMetaRepo;

    public function __construct(Request $request,
                                ItemContract $itemContract, ItemMetaContract $itemMetaContract)
    {
        $this->request = $request;
        $this->itemRepo = $itemContract;
        $this->itemMetaRepo = $itemMetaContract;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        event(new BeforeIndex());
        $item = null;
        if ($this->request->has('item_id')) {
            $item = $this->itemRepo->get($this->request->get('item_id'));
        }
        $itemMetas = $this->itemMetaRepo->filterAll($this->request->all(), $item);
        $status = true;
        event(new AfterIndex());

        if ($this->request->ajax()) {
            return compact(['status', 'itemMetas']);
        } else {
            return view('app.url_management.item_meta.index')->with(compact(['item']));
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

        $itemMeta = $this->itemMetaRepo->store($this->request->all());

        if ($this->request->has('item_id')) {
            $item = $this->itemRepo->get($this->request->get('item_id'));
            $item->metas()->save($itemMeta);
        }

        $status = true;

        event(new AfterStore($itemMeta));

        return compact(['itemMeta', 'status']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemMeta $itemMeta
     * @return \Illuminate\Http\Response
     */
    public function show(ItemMeta $itemMeta)
    {
        event(new BeforeShow($itemMeta));
        event(new AfterShow($itemMeta));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemMeta $itemMeta
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemMeta $itemMeta)
    {
        event(new BeforeEdit($itemMeta));
        event(new AfterEdit($itemMeta));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\ItemMeta $itemMeta
     * @return \Illuminate\Http\Response
     */
    public function update(ItemMeta $itemMeta)
    {
        event(new BeforeUpdate($itemMeta));
        $itemMeta = $this->itemMetaRepo->update($itemMeta, $this->request->all());
        $status = true;
        event(new AfterUpdate($itemMeta));
        return compact(['itemMeta', 'status']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemMeta $itemMeta
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemMeta $itemMeta)
    {
        event(new BeforeDestroy($itemMeta));
        $status = $this->itemMetaRepo->destroy($itemMeta);
        event(new AfterDestroy());
        return compact(['status']);
    }
}
