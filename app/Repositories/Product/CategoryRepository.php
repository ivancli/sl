<?php

namespace App\Repositories\Product;

use App\Contracts\Repositories\Product\CategoryContract;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/13/2017
 * Time: 2:46 PM
 */
class CategoryRepository implements CategoryContract
{
    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }


    /**
     * Load filtered/all categories
     * @param User|null $user
     * @param array $data
     * @return mixed
     */
    public function filterAll(User $user = null, array $data = [])
    {
        $builder = null;
        if (is_null($user)) {
            $builder = $this->category;
        } else {
            $builder = $user->categories();
        }

        if (array_has($data, 'key') && !empty(array_get($data, 'key'))) {
            $key = array_get($data, 'key');
            //get number of products of each category matching the key
            $builder->withCount(['products' => function ($query) use ($key) {
                $query->join('product_metas', 'products.id', 'product_metas.product_id')
                    ->where('product_name', 'like', "%{$key}%")
                    ->orWhere('brand', 'like', "%{$key}%")
                    ->orWhere('supplier', 'like', "%{$key}%")
                    ->orWhere('cost_price', 'like', "%{$key}%");
            }]);
            //category name matches of one or more product names match
            $builder->having('category_name', 'like', "%{$key}%")
                ->orHaving('products_count', '>', 0);
        }

        if (array_has($data, 'with') && !empty(array_get($data, 'with'))) {
            $builder->with(array_get($data, 'with'));
        }

        $categories = $builder->get();

        return $categories;
    }

    /**
     * Load all categories
     *
     * @param User $user
     * @return mixed
     */
    public function all(User $user = null)
    {
        if (is_null($user)) {
            $categories = $this->category->all();
        } else {
            $categories = $user->categories;
        }
        return $categories;
    }

    /**
     * Get a category
     *
     * @param $category_id
     * @param bool $throw
     * @return Category
     */
    public function get($category_id, $throw = true)
    {
        if ($throw) {
            return $this->category->findOrFail($category_id);
        } else {
            return $this->category->find($category_id);
        }
    }

    /**
     * Creating new category
     *
     * @param array $data
     * @return Category
     */
    public function store(array $data)
    {
        $category = new $this->category;
        $category->category_name = $data['category_name'];
        $category->save();
        return $category;
    }

    /**
     * Editing existing category
     *
     * @param Category $category
     * @param array $data
     * @return Category
     */
    public function update(Category $category, array $data)
    {
        $category->category_name = $data['category_name'];
        $category->save();
        return $category;
    }

    /**
     * Deleting a category
     * @param Category $category
     * @return mixed
     */
    public function destroy(Category $category)
    {
        return $category->delete();
    }
}