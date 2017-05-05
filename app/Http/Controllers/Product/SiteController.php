<?php

namespace App\Http\Controllers\Product;

use App\Contracts\Repositories\Product\ProductContract;
use App\Contracts\Repositories\Product\SiteContract;
use App\Contracts\Repositories\UrlManagement\UrlContract;
use App\Events\Product\Site\AfterAssignItem;
use App\Events\Product\Site\AfterDestroy;
use App\Events\Product\Site\AfterEdit;
use App\Events\Product\Site\AfterIndex;
use App\Events\Product\Site\AfterShow;
use App\Events\Product\Site\AfterStore;
use App\Events\Product\Site\AfterUpdate;
use App\Events\Product\Site\BeforeAssignItem;
use App\Events\Product\Site\BeforeDestroy;
use App\Events\Product\Site\BeforeEdit;
use App\Events\Product\Site\BeforeIndex;
use App\Events\Product\Site\BeforeShow;
use App\Events\Product\Site\BeforeStore;
use App\Events\Product\Site\BeforeUpdate;
use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Services\Product\SiteService;
use App\Validators\Product\Site\StoreValidator;
use App\Validators\Product\Site\UpdateValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    protected $request;
    protected $siteService;

    public function __construct(Request $request, SiteService $siteService)
    {
        $this->request = $request;
        $this->siteService = $siteService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        event(new BeforeIndex());

        if ($this->request->has('product_id')) {
            $sites = $this->siteService->load($this->request->all());
            $status = true;
        }

        event(new AfterIndex());

        if ($this->request->ajax()) {
            return compact(['status', 'sites']);
        } else {
            return view('app.product.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function store()
    {
        event(new BeforeStore());

        /*TODO enhance add site process*/
        $site = $this->siteService->store($this->request->all());
        $status = true;

        event(new AfterStore($site));

        if ($this->request->ajax()) {
            return compact(['status', 'site']);
        } else {
            return redirect()->route('product');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Site $site
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        event(new BeforeShow($site));

        $status = true;

        event(new AfterShow($site));

        return compact(['site', 'status']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Site $site
     * @return \Illuminate\Http\Response
     */
    public function edit(Site $site)
    {
        event(new BeforeEdit($site));

        $status = true;

        event(new AfterEdit($site));

        return compact(['site', 'status']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Site $site
     * @return \Illuminate\Http\Response
     */
    public function update(Site $site)
    {
        event(new BeforeUpdate($site));

        $site = $this->siteService->update($site, $this->request->all());
        $status = true;

        event(new AfterUpdate($site));

        if ($this->request->ajax()) {
            return compact(['status', 'site']);
        } else {
            return redirect()->route('product');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Site $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site)
    {
        event(new BeforeDestroy($site));

        $status = $this->siteService->destroy($site);

        event(new AfterDestroy());

        if ($this->request->ajax()) {
            return compact(['status']);
        } else {
            return redirect()->route('product');
        }
    }

    public function assignItem(Site $site)
    {
        event(new BeforeAssignItem($site));

        $site = $this->siteService->assignItem($site, $this->request->all());
        $status = true;

        event(new AfterAssignItem($site));

        return compact(['site', 'status']);
    }
}
