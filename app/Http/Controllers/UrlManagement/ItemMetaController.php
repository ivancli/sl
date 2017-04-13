<?php

namespace App\Http\Controllers\UrlManagement;

use App\Contracts\Repositories\UrlManagement\ItemContract;
use App\Contracts\Repositories\UrlManagement\ItemMetaContract;
use App\Events\UrlManagement\ItemMeta\AfterQueue;
use App\Events\UrlManagement\ItemMeta\BeforeQueue;
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
use App\Services\UrlManagement\ItemMetaService;
use App\Validators\UrlManagement\ItemMeta\StoreValidator;
use App\Jobs\Crawl as CrawlJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemMetaController extends Controller
{
    protected $request;
    protected $itemMetaService;

    public function __construct(Request $request, ItemMetaService $itemMetaService)
    {
        $this->request = $request;
        $this->itemMetaService = $itemMetaService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        event(new BeforeIndex());

        $item = $this->itemMetaService->getItemById($this->request->get('item_id'));
        $itemMetas = $this->itemMetaService->load($this->request->all());
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
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        event(new BeforeStore());

        $itemMeta = $this->itemMetaService->store($this->request->all());
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

        $itemMeta = $this->itemMetaService->update($itemMeta, $this->request->all());
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

        $status = $this->itemMetaService->destroy($itemMeta);

        event(new AfterDestroy());

        return compact(['status']);
    }

    /**
     * Push URL of the item meta to queue
     *
     * @param ItemMeta $itemMeta
     * @return array
     */
    public function queue(ItemMeta $itemMeta)
    {
        event(new BeforeQueue($itemMeta));

        $this->itemMetaService->queue($itemMeta);
        $status = true;

        event(new AfterQueue($itemMeta));

        return compact(['status']);
    }
}
