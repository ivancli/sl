<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/3/2017
 * Time: 11:35 AM
 */

namespace App\Observers;


use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryObserver
{
    public function creating()
    {

    }

    public function created(Category $category)
    {

    }

    public function saving()
    {

    }

    public function saved(Category $category)
    {

    }

    public function updating(Category $category)
    {

    }

    public function updated(Category $category)
    {

    }

    public function deleting(Category $category)
    {
        $user_id = $category->user_id;
        $category->products()->delete();

        /* delete all reports */
        DB::statement("
            DELETE reports
            FROM reports
            LEFT JOIN categories ON(reports.reportable_type='category' AND reports.reportable_id=categories.id AND categories.user_id={$user_id})
            LEFT JOIN products ON(reports.reportable_type='product' AND reports.reportable_id=products.id AND products.user_id={$user_id})
            WHERE (categories.id={$category->getKey()} OR products.category_id={$category->getKey()}) AND (products.id IS NOT NULL OR categories.id IS NOT NULL) 
        ");

        /* delete all historical alerts */
        DB::statement("
            DELETE historical_alerts
            FROM historical_alerts
            LEFT JOIN categories ON(historical_alerts.alertable_type='category' AND historical_alerts.alertable_id=categories.id AND categories.user_id={$user_id})
            LEFT JOIN products ON(historical_alerts.alertable_type='product' AND historical_alerts.alertable_id=products.id AND products.user_id={$user_id})
            WHERE (categories.id={$category->getKey()} OR products.category_id={$category->getKey()}) AND (products.id IS NOT NULL OR categories.id IS NOT NULL) 
        ");

        /* delete all alerts */
        DB::statement("
            DELETE alerts
            FROM alerts
            LEFT JOIN categories ON(alerts.alertable_type='category' AND alerts.alertable_id=categories.id AND categories.user_id={$user_id})
            LEFT JOIN products ON(alerts.alertable_type='product' AND alerts.alertable_id=products.id AND products.user_id={$user_id})
            WHERE (categories.id={$category->getKey()} OR products.category_id={$category->getKey()}) AND (products.id IS NOT NULL OR categories.id IS NOT NULL) 
        ");

        /*delete all widgets */
        DB::statement('
            DELETE widgets
            FROM widgets
            LEFT JOIN categories ON(widgets.widgetable_type=\'category\' AND widgets.widgetable_id=categories.id AND categories.user_id=' . $user_id . ')
            LEFT JOIN products ON(widgets.widgetable_type=\'product\' AND widgets.widgetable_id=products.id AND products.user_id=' . $user_id . ')
            
            LEFT JOIN sites ON(widgets.widgetable_type=\'site\' AND widgets.widgetable_id=sites.id)
            LEFT JOIN products temp_products ON (sites.product_id=temp_products.id AND temp_products.user_id=' . $user_id . ')
            WHERE (temp_products.category_id=' . $category->getKey() . ' OR products.category_id=' . $category->getKey() . ' OR categories.id=' . $category->getKey() . ') AND ((sites.id IS NOT NULL and temp_products.id IS NOT NULL) OR products.id IS NOT NULL OR categories.id IS NOT NULL)
        ');


    }

    public function deleted()
    {

    }

    public function restoring()
    {

    }

    public function restored(Category $category)
    {

    }
}