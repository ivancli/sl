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
use Illuminate\Support\Facades\DB;

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
        $user_id = $product->user_id;
        /*manually remove corresponding alert when a product is deleted*/
        $product->sites()->delete();

        /* delete all reports */
        DB::statement("
            DELETE reports
            FROM reports
            LEFT JOIN products ON(reports.reportable_type='product' AND reports.reportable_id=products.id AND products.user_id={$user_id})
            WHERE products.id={$product->getKey()} AND products.id IS NOT NULL 
        ");

        /* delete all historical alerts */
        DB::statement("
            DELETE historical_alerts
            FROM historical_alerts
            LEFT JOIN products ON(historical_alerts.alertable_type='product' AND historical_alerts.alertable_id=products.id AND products.user_id={$user_id})
            WHERE products.id={$product->getKey()} AND products.id IS NOT NULL 
        ");

        /* delete all alerts */
        DB::statement("
            DELETE alerts
            FROM alerts
            LEFT JOIN products ON(alerts.alertable_type='product' AND alerts.alertable_id=products.id AND products.user_id={$user_id})
            WHERE products.id={$product->getKey()} AND products.id IS NOT NULL 
        ");

        /*delete all widgets */
        DB::statement('
            DELETE widgets
            FROM widgets
            LEFT JOIN products ON(widgets.widgetable_type=\'product\' AND widgets.widgetable_id=products.id AND products.user_id=' . $user_id . ')
            LEFT JOIN sites ON(widgets.widgetable_type=\'site\' AND widgets.widgetable_id=sites.id)
            LEFT JOIN products temp_products ON (sites.product_id=temp_products.id AND temp_products.user_id=' . $user_id . ')
            WHERE (temp_products.id=' . $product->getKey() . ' OR products.id=' . $product->getKey() . ') AND ((sites.id IS NOT NULL and temp_products.id IS NOT NULL) OR products.id IS NOT NULL)
        ');





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