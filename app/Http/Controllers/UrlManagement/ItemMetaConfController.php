<?php

namespace App\Http\Controllers\UrlManagement;

use App\Contracts\Repositories\UrlManagement\ItemMetaConfContract;
use App\Contracts\Repositories\UrlManagement\ItemMetaContract;
use App\Models\ItemMetaConf;
use App\Events\UrlManagement\ItemMetaConf\BeforeIndex;
use App\Events\UrlManagement\ItemMetaConf\AfterIndex;
use App\Events\UrlManagement\ItemMetaConf\BeforeCreate;
use App\Events\UrlManagement\ItemMetaConf\AfterCreate;
use App\Events\UrlManagement\ItemMetaConf\BeforeStore;
use App\Events\UrlManagement\ItemMetaConf\AfterStore;
use App\Events\UrlManagement\ItemMetaConf\BeforeShow;
use App\Events\UrlManagement\ItemMetaConf\AfterShow;
use App\Events\UrlManagement\ItemMetaConf\BeforeEdit;
use App\Events\UrlManagement\ItemMetaConf\AfterEdit;
use App\Events\UrlManagement\ItemMetaConf\BeforeUpdate;
use App\Events\UrlManagement\ItemMetaConf\AfterUpdate;
use App\Events\UrlManagement\ItemMetaConf\BeforeDestroy;
use App\Events\UrlManagement\ItemMetaConf\AfterDestroy;
use App\Validators\UrlManagement\ItemMetaConf\StoreValidator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemMetaConfController extends Controller
{
    protected $request;
    protected $itemMetaRepo, $itemMetaConfRepo;

    public function __construct(Request $request,
                                ItemMetaContract $itemMetaContract, ItemMetaConfContract $itemMetaConfContract)
    {
        $this->request = $request;
        $this->itemMetaRepo = $itemMetaContract;
        $this->itemMetaConfRepo = $itemMetaConfContract;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        event(new BeforeIndex());
        if ($this->request->has('item_meta_id')) {
            $itemMeta = $this->itemMetaRepo->get($this->request->get('item_meta_id'));
            $itemMetaConfs = $itemMeta->confs;
        } else {
            $itemMetaConfs = $this->itemMetaConfRepo->all();
        }
        $status = true;

        event(new AfterIndex());
        return compact(['status', 'itemMetaConfs']);
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
        $itemMeta = $this->itemMetaRepo->get($this->request->get('item_meta_id'));
        $status = $this->itemMetaConfRepo->store($itemMeta, $this->request->all());
        event(new AfterStore());
        return compact(['status']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemMetaConf $itemMetaConf
     * @return \Illuminate\Http\Response
     */
    public function show(ItemMetaConf $itemMetaConf)
    {
        event(new BeforeShow($itemMetaConf));
        event(new AfterShow($itemMetaConf));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemMetaConf $itemMetaConf
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemMetaConf $itemMetaConf)
    {
        event(new BeforeEdit($itemMetaConf));
        event(new AfterEdit($itemMetaConf));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\ItemMetaConf $itemMetaConf
     * @return \Illuminate\Http\Response
     */
    public function update(ItemMetaConf $itemMetaConf)
    {
        event(new BeforeUpdate($itemMetaConf));
        event(new AfterUpdate($itemMetaConf));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemMetaConf $itemMetaConf
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemMetaConf $itemMetaConf)
    {
        event(new BeforeDestroy($itemMetaConf));
        event(new AfterDestroy());
    }
}
