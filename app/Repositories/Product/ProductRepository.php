<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/14/2017
 * Time: 10:39 AM
 */

namespace App\Repositories\Product;


use App\Contracts\Repositories\Product\ProductContract;
use App\Models\Product;
use App\Models\User;

class ProductRepository implements ProductContract
{
    var $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Load all products
     *
     * @param User|null $user
     * @return mixed
     */
    public function all(User $user = null)
    {
        if (is_null($user)) {
            $products = $this->product->all();
        } else {
            $products = $user->products;
        }
        return $products;
    }

    /**
     * Get a product
     *
     * @param $product_id
     * @param bool $throw
     * @return mixed
     */
    public function get($product_id, $throw = true)
    {
        if ($throw) {
            return $this->product->findOrFail($product_id);
        } else {
            return $this->product->find($product_id);
        }
    }

    /**
     * Creating new product
     *
     * @param array $data
     * @return mixed
     */
    public function store(Array $data)
    {
        $product = new $this->product;
        $product->name = $data['name'];
        $product->save();
        return $product;
    }

    /**
     * Editing existing product
     *
     * @param Product $product
     * @param array $data
     * @return Product
     * @internal param $id
     */
    public function update(Product $product, Array $data)
    {
        $product->name = $data['name'];
        $product->save();
        return $product;
    }

    /**
     * Deleting a product
     * @param Product $product
     * @return mixed
     */
    public function destroy(Product $product)
    {
        $product->delete();
    }
}