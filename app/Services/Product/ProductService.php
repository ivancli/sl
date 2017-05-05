<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/19/2017
 * Time: 3:54 PM
 */

namespace App\Services\Product;


use App\Contracts\Repositories\Product\CategoryContract;
use App\Contracts\Repositories\Product\ProductContract;
use App\Models\Product;
use App\Validators\Product\Product\StoreValidator;
use App\Validators\Product\Product\UpdateValidator;

class ProductService
{
    #region repositories

    protected $categoryRepo;
    protected $productRepo;

    #endregion

    #region validators

    protected $storeValidator;
    protected $updateValidator;

    #endregion

    public function __construct(CategoryContract $categoryContract, ProductContract $productContract,
                                StoreValidator $storeValidator, UpdateValidator $updateValidator)
    {
        #region repositories binding
        $this->categoryRepo = $categoryContract;
        $this->productRepo = $productContract;
        #endregion

        #region validators binding
        $this->storeValidator = $storeValidator;
        $this->updateValidator = $updateValidator;
        #endregion
    }

    /**
     * load all/filtered products of a category
     * @param array $data
     * @return mixed
     */
    public function load(array $data = [])
    {
        $category = $this->categoryRepo->get(array_get($data, 'category_id'));
        if (array_has($data, 'key') && !empty(array_get($data, 'key'))) {
            $key = array_get($data, 'key');
            if (strpos(strtolower($category->category_name), strtolower($key)) === false) {
                $productsBuilder = $category->products()->where('product_name', 'like', "%{$key}%");
            } else {
                $productsBuilder = $category->products();
            }
        } else {
            $productsBuilder = $category->products();
        }
        if (array_has($data, 'offset') && !empty(array_get($data, 'offset'))) {
            $productsBuilder->skip(array_get($data, 'offset'));
        }

        if (array_has($data, 'length') && !empty(array_get($data, 'length'))) {
            $productsBuilder->limit(array_get($data, 'length'));
        }

        $products = $productsBuilder->get();

        return $products;
    }

    /**
     * create a new product
     * @param array $data
     * @return Product
     */
    public function store(array $data)
    {
        $this->storeValidator->validate($data);
        $user = auth()->user();
        $product = $this->productRepo->store($data);
        $user->products()->save($product);
        if (array_has($data, 'category_id')) {
            $category = $this->categoryRepo->get(array_get($data, 'category_id'));
            $category->products()->save($product);
        }

        if (array_has($data, 'meta')) {
            $this->productRepo->updateMeta($product, array_get($data, 'meta'));
        }

        return $product;
    }

    /**
     * update an existing product
     * @param Product $product
     * @param array $data
     * @return Product
     */
    public function update(Product $product, array $data)
    {
        $data = array_set($data, 'id', $product->getKey());
        $this->updateValidator->validate($data);
        $product = $this->productRepo->update($product, $data);

        if (array_has($data, 'meta')) {
            $this->productRepo->updateMeta($product, array_get($data, 'meta'));
        }

        return $product;
    }

    /**
     * Delete an existing product
     * @param Product $product
     * @return mixed
     */
    public function destroy(Product $product)
    {
        $result = $this->productRepo->destroy($product);
        return $result;
    }
}