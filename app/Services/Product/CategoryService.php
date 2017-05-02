<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/19/2017
 * Time: 2:36 PM
 */

namespace App\Services\Product;


use App\Contracts\Repositories\Product\CategoryContract;
use App\Models\Category;
use App\Validators\Product\Category\StoreValidator;
use App\Validators\Product\Category\UpdateValidator;

class CategoryService
{
    #region repositories

    protected $categoryRepo;

    #endregion

    #region validators

    protected $storeValidator;
    protected $updateValidator;

    #endregion

    public function __construct(CategoryContract $categoryContract,
                                StoreValidator $storeValidator, UpdateValidator $updateValidator)
    {
        #region repositories binding
        $this->categoryRepo = $categoryContract;
        #endregion

        #region validators binding
        $this->storeValidator = $storeValidator;
        $this->updateValidator = $updateValidator;
        #endregion
    }

    /**
     * Load all/filtered categories
     * @param array $data
     * @return mixed
     */
    public function load(array $data = [])
    {
        $user = auth()->user();
        $categories = $this->categoryRepo->filterAll($user, $data);
        return $categories;
    }

    /**
     * create a new category
     * @param array $data
     * @return Category
     */
    public function store(array $data)
    {
        $this->storeValidator->validate($data);
        $user = auth()->user();
        $category = $this->categoryRepo->store($data);
        $user->categories()->save($category);
        return $category;
    }

    /**
     * update an existing category
     * @param Category $category
     * @param array $data
     * @return Category
     */
    public function update(Category $category, array $data)
    {
        $data = array_set($data, 'id', $category->getKey());
        $this->updateValidator->validate($data);
        $category = $this->categoryRepo->update($category, $data);
        return $category;
    }

    /**
     * delete an existing category
     * @param Category $category
     * @return mixed
     */
    public function destroy(Category $category)
    {
        $result = $this->categoryRepo->destroy($category);
        return $result;
    }
}