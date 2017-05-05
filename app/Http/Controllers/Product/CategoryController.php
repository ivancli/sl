<?php

namespace App\Http\Controllers\Product;

use App\Events\Product\Category\AfterDestroy;
use App\Events\Product\Category\AfterEdit;
use App\Events\Product\Category\AfterIndex;
use App\Events\Product\Category\AfterShow;
use App\Events\Product\Category\AfterStore;
use App\Events\Product\Category\AfterUpdate;
use App\Events\Product\Category\BeforeDestroy;
use App\Events\Product\Category\BeforeEdit;
use App\Events\Product\Category\BeforeIndex;
use App\Events\Product\Category\BeforeShow;
use App\Events\Product\Category\BeforeStore;
use App\Events\Product\Category\BeforeUpdate;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\Product\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $request;
    protected $categoryService;

    public function __construct(Request $request, CategoryService $categoryService)
    {
        $this->request = $request;

        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        event(new BeforeIndex());

        if ($this->request->ajax()) {
            $categories = $this->categoryService->load($this->request->all());
            $status = true;
        }

        event(new AfterIndex());

        if ($this->request->ajax()) {
            return compact(['categories', 'status']);
        } else {
            return redirect()->route('product');
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

        $category = $this->categoryService->store($this->request->all());
        $status = true;

        event(new AfterStore($category));

        if ($this->request->ajax()) {
            return compact(['category', 'status']);
        } else {
            return redirect()->route('product');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show(Category $category)
    {
        event(new BeforeShow($category));

        $status = true;

        event(new AfterShow($category));

        return compact(['category', 'status']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Category $category
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function edit(Category $category)
    {
        event(new BeforeEdit($category));

        event(new AfterEdit($category));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Category $category
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function update(Category $category)
    {
        event(new BeforeUpdate($category));

        $category = $this->categoryService->update($category, $this->request->all());
        $status = true;

        event(new AfterUpdate($category));

        if ($this->request->ajax()) {
            return compact(['category', 'status']);
        } else {
            return redirect()->route('product');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        event(new BeforeDestroy($category));

        $status = $this->categoryService->destroy($category);

        event(new AfterDestroy());

        if ($this->request->ajax()) {
            return compact(['status']);
        } else {
            return redirect()->route('product');
        }
    }
}
