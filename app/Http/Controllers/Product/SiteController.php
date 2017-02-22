<?php

namespace App\Http\Controllers\Product;

use App\Contracts\Repositories\Product\ProductContract;
use App\Contracts\Repositories\Product\SiteContract;
use App\Contracts\Repositories\Product\UrlContract;
use App\Events\Product\Site\AfterDestroy;
use App\Events\Product\Site\AfterEdit;
use App\Events\Product\Site\AfterIndex;
use App\Events\Product\Site\AfterShow;
use App\Events\Product\Site\AfterStore;
use App\Events\Product\Site\AfterUpdate;
use App\Events\Product\Site\BeforeDestroy;
use App\Events\Product\Site\BeforeEdit;
use App\Events\Product\Site\BeforeIndex;
use App\Events\Product\Site\BeforeShow;
use App\Events\Product\Site\BeforeStore;
use App\Events\Product\Site\BeforeUpdate;
use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Validators\Product\Site\StoreValidator;
use App\Validators\Product\Site\UpdateValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    var $request;
    var $productRepo, $siteRepo, $urlRepo;

    public function __construct(Request $request,
                                ProductContract $productContract,
                                SiteContract $siteContract,
                                UrlContract $urlContract)
    {
        $this->request = $request;
        $this->productRepo = $productContract;
        $this->siteRepo = $siteContract;
        $this->urlRepo = $urlContract;
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
            $product = $this->productRepo->get($this->request->get('product_id'));
            $sites = $product->sites;
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
     * @param StoreValidator $storeValidator
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function store(StoreValidator $storeValidator)
    {
        event(new BeforeStore());
        $storeValidator->validate($this->request->all());

        $site = $this->siteRepo->store($this->request->all());
        $url = $this->urlRepo->getByFullPathOrCreate($this->request->all());

        $url->sites()->save($site);
        $product = $this->productRepo->get($this->request->get('product_id'));
        $product->sites()->save($site);

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

        event(new AfterShow($site));
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

        event(new AfterEdit($site));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Site $site
     * @param UpdateValidator $updateValidator
     * @return \Illuminate\Http\Response
     */
    public function update(Site $site, UpdateValidator $updateValidator)
    {
        event(new BeforeUpdate($site));
        $id = $site->getKey();
        $this->request->merge(compact(['id']));
        $updateValidator->validate($this->request->all());


        $site = $this->siteRepo->update($site, $this->request->all());
        $url = $this->urlRepo->getByFullPathOrCreate($this->request->all());

        $url->sites()->save($site);
        $product = $this->productRepo->get($this->request->get('product_id'));
        $product->sites()->save($site);

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
        $this->siteRepo->destroy($site);
        $status = true;
        event(new AfterDestroy());

        if ($this->request->ajax()) {
            return compact(['status']);
        } else {
            return redirect()->route('product');
        }
    }
}
