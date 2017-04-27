<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/14/2017
 * Time: 10:38 AM
 */

namespace App\Contracts\Repositories\Product;


use App\Models\Product;
use App\Models\User;

interface ProductContract
{

    /**
     * Load all products
     *
     * @param User|null $user
     * @return mixed
     */
    public function all(User $user = null);

    /**
     * Get a product
     *
     * @param $product_id
     * @param bool $throw
     * @return mixed
     */
    public function get($product_id, $throw = true);

    /**
     * Creating new product
     *
     * @param array $data
     * @return Product
     */
    public function store(array $data);

    /**
     * Editing existing product
     *
     * @param Product $product
     * @param array $data
     * @return Product
     */
    public function update(Product $product, array $data);

    /**
     * Deleting a product
     * @param Product $product
     * @return mixed
     */
    public function destroy(Product $product);
}