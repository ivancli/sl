<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 5/4/2017
 * Time: 11:27 AM
 */

namespace App\Observers;


use App\Models\Product;
use App\Models\ProductMeta;

class ProductObserver
{
    public function creating()
    {

    }

    public function created(Product $product)
    {
        $product->meta()->save(new ProductMeta);
    }

    public function saving()
    {

    }

    public function saved(Product $product)
    {

    }

    public function updating(Product $product)
    {

    }

    public function updated(Product $product)
    {

    }

    public function deleting(Product $product)
    {
        /*manually remove corresponding alert when a product is deleted*/
        $product->widgets()->delete();
        $product->alert()->delete();
        $product->historicalAlerts()->delete();
        $product->report()->delete();
        $product->sites()->delete();
    }

    public function deleted()
    {

    }

    public function restoring()
    {

    }

    public function restored(Product $product)
    {

    }
}