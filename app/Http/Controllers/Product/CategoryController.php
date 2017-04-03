<?php

namespace App\Http\Controllers\Product;

use App\Contracts\Repositories\Product\CategoryContract;
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
use App\Validators\Product\Category\StoreValidator;
use App\Validators\Product\Category\UpdateValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $request;
    protected $categoryRepo;

    public function __construct(Request $request,
                                CategoryContract $categoryContract)
    {
        $this->request = $request;

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
        $user = auth()->user();
        $categories = $this->categoryRepo->all($user);
        $status = true;

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
     * @param StoreValidator $storeValidator
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function store(StoreValidator $storeValidator)
    {
        event(new BeforeStore());

        $storeValidator->validate($this->request->all());
        $user = auth()->user();
        $category = $this->categoryRepo->store($this->request->all());
        $user->categories()->save($category);
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

        event(new AfterShow($category));
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
     * @param UpdateValidator $updateValidator
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function update(Category $category, UpdateValidator $updateValidator)
    {
        event(new BeforeUpdate($category));
        $id = $category->getKey();
        $this->request->merge(compact(['id']));
        $updateValidator->validate($this->request->all());

        $category = $this->categoryRepo->update($category, $this->request->all());
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
        $this->categoryRepo->destroy($category);
        $status = true;
        event(new AfterDestroy());

        if ($this->request->ajax()) {
            return compact(['status']);
        } else {
            return redirect()->route('product');
        }
    }
}
