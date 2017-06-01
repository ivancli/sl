<?php

namespace App\Http\Controllers\Product;

use App\Events\Product\Product\AfterDestroy;
use App\Events\Product\Product\AfterEdit;
use App\Events\Product\Product\AfterIndex;
use App\Events\Product\Product\AfterReportShow;
use App\Events\Product\Product\AfterShow;
use App\Events\Product\Product\AfterStore;
use App\Events\Product\Product\AfterUpdate;
use App\Events\Product\Product\BeforeDestroy;
use App\Events\Product\Product\BeforeEdit;
use App\Events\Product\Product\BeforeIndex;
use App\Events\Product\Product\BeforeReportShow;
use App\Events\Product\Product\BeforeShow;
use App\Events\Product\Product\BeforeStore;
use App\Events\Product\Product\BeforeUpdate;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\MailingAgent\CampaignMonitor\MailingAgentService;
use App\Services\Product\ProductService;
use App\Validators\Product\Product\UpdateValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $request;
    protected $productService;
    protected $mailingAgentService;

    public function __construct(Request $request, ProductService $productService, MailingAgentService $mailingAgentService)
    {
        $this->request = $request;

        $this->productService = $productService;
        $this->mailingAgentService = $mailingAgentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        event(new BeforeIndex());

        if ($this->request->has('category_id')) {
            $products = $this->productService->load($this->request->all());
            $status = true;
        }

        event(new AfterIndex());

        if ($this->request->ajax()) {
            return compact(['status', 'products']);
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

        $product = $this->productService->store($this->request->all());
        $status = true;

        $this->mailingAgentService->updateNumberOfProducts(auth()->user());
        $this->mailingAgentService->updateLastAddProductDate(auth()->user());

        event(new AfterStore($product));

        if ($this->request->ajax()) {
            return compact(['product', 'status']);
        } else {
            return redirect()->route('product');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        event(new BeforeShow($product));

        $status = true;

        event(new AfterShow($product));

        return compact(['product', 'status']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function edit(Product $product)
    {
        event(new BeforeEdit($product));

        event(new AfterEdit($product));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Product $product
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function update(Product $product)
    {
        event(new BeforeUpdate($product));

        $product = $this->productService->update($product, $this->request->all());
        $status = true;

        event(new AfterUpdate($product));

        if ($this->request->ajax()) {
            return compact(['product', 'status']);
        } else {
            return redirect()->route('product');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        event(new BeforeDestroy($product));

        $status = $this->productService->destroy($product);

        $this->mailingAgentService->updateNumberOfProducts(auth()->user());
        $this->mailingAgentService->updateNumberOfSites(auth()->user());

        event(new AfterDestroy());

        if ($this->request->ajax()) {
            return compact(['status']);
        } else {
            return redirect()->route('product');
        }
    }

    /**
     * Load alert by product
     *
     * @param Product $product
     * @return array
     */
    public function report(Product $product)
    {
        event(new BeforeReportShow($product));

        $report = $product->report;
        $status = true;

        event(new AfterReportShow($product));

        return compact(['report', 'status']);
    }
}
