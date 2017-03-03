<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/3/2017
 * Time: 11:35 AM
 */

namespace App\Observers;


use App\Models\Category;

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
        $category->products()->delete();
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