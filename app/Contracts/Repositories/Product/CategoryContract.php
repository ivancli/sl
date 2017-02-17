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
    public function store(Array $data);

    /**
     * Editing existing category
     *
     * @param $id
     * @param array $data
     * @return Category
     */
    public function update($id, Array $data);
}