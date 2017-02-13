<?php
namespace App\Repositories\Product;

use App\Contracts\Repositories\Product\CategoryContract;
use App\Models\Category;
use App\Models\User;

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/13/2017
 * Time: 2:46 PM
 */
class CategoryRepository implements CategoryContract
{
    var $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
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
    public function store(Array $data)
    {
        $category = new $this->category;
        $category->name = $data['name'];
        $category->save();
        return $category;
    }
}