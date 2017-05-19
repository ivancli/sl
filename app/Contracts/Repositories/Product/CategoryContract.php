<?php
namespace App\Contracts\Repositories\Product;

use App\Models\Category;
use App\Models\User;

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/13/2017
 * Time: 2:44 PM
 */
interface CategoryContract
{

    /**
     * Load filtered/all categories
     * @param User|null $user
     * @param array $data
     * @return mixed
     */
    public function filterAll(User $user = null, array $data = []);

    /**
     * Load all categories
     *
     * @param User $user
     * @return mixed
     */
    public function all(User $user = null);

    /**
     * Get a category
     *
     * @param $category_id
     * @param bool $throw
     * @return Category
     */
    public function get($category_id, $throw = true);

    /**
     * Creating new category
     *
     * @param array $data
     * @return Category
     */
    public function store(array $data);

    /**
     * Editing existing category
     *
     * @param Category $category
     * @param array $data
     * @return Category
     */
    public function update(Category $category, array $data);

    /**
     * Deleting a category
     * @param Category $category
     * @return mixed
     */
    public function destroy(Category $category);
}