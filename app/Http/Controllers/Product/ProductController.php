<?php

namespace App\Http\Controllers\Product;

use App\Contracts\Repositories\Product\CategoryContract;
use App\Contracts\Repositories\Product\ProductContract;
use App\Events\Product\Product\AfterIndex;
use App\Events\Product\Product\AfterStore;
use App\Events\Product\Product\BeforeIndex;
use App\Events\Product\Product\BeforeStore;
use App\Http\Controllers\Controller;
use App\Validators\Product\Product\StoreValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    var $request;
    var $productRepo, $categoryRepo;

    public function __construct(Request $request, ProductContract $productContract, CategoryContract $categoryContract)
    {
        $this->request = $request;

        $this->productRepo = $productContract;
        $this->categoryRepo = $categoryContract;
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
            $category = $this->categoryRepo->get($this->request->get('category_id'));
            $products = $category->products;
            $status = true;
        }
        event(new AfterIndex());
        if ($this->request->ajax()) {
            return new JsonResponse(compact(['status', 'products']));
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
        $user = auth()->user();
        $product = $this->productRepo->store($this->request->all());
        $user->products()->save($product);
        if ($this->request->has('category_id')) {
            $category = $this->categoryRepo->get($this->request->get('category_id'));
            $category->products()->save($product);
        }
        $status = true;

        event(new AfterStore());
        if ($this->request->ajax()) {
            return new JsonResponse(compact(['product', 'status']));
        } else {
            return redirect()->route('product');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
