<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 31/05/2017
 * Time: 4:42 PM
 */

namespace App\Services\Dashboard;


use App\Contracts\Repositories\Dashboard\WidgetContract;
use App\Contracts\Repositories\Product\CategoryContract;
use App\Contracts\Repositories\Product\ProductContract;
use App\Contracts\Repositories\Product\SiteContract;
use App\Models\Widget;
use App\Validators\Dashboard\Widget\StoreValidator;
use App\Validators\Dashboard\Widget\UpdateValidator;

class WidgetService
{
    #region repositories

    protected $widgetRepo;
    protected $categoryRepo;
    protected $productRepo;
    protected $siteRepo;

    #endregion

    #region validators

    protected $storeValidator;
    protected $updateValidator;

    #endregion

    public function __construct(WidgetContract $widgetContract, CategoryContract $categoryContract, ProductContract $productContract, SiteContract $siteContract,
                                StoreValidator $storeValidator, UpdateValidator $updateValidator)
    {
        #region repositories binding
        $this->widgetRepo = $widgetContract;
        $this->categoryRepo = $categoryContract;
        $this->productRepo = $productContract;
        $this->siteRepo = $siteContract;
        #endregion

        #region validators binding
        $this->storeValidator = $storeValidator;
        $this->updateValidator = $updateValidator;
        #endregion
    }

    /**
     * Load all widgets from a user
     * @param array $data
     * @return mixed
     */
    public function load(array $data = [])
    {
        $user = auth()->user();
        return $user->widgets()->with('widgetable')->get();
    }

    /**
     * create a widget
     * @param array $data
     * @return mixed
     */
    public function store(array $data = [])
    {
        $this->storeValidator->validate($data);

        $user = auth()->user();

        $widget = $this->widgetRepo->store($data);
        $targetRelation = null;
        switch (array_get($data, 'type', 'category')) {
            case 'category':
                $targetRelation = $this->categoryRepo->get(array_get($data, 'id'));
                break;
            case 'product':
                $targetRelation = $this->productRepo->get(array_get($data, 'id'));
                break;
            case 'site':
                $targetRelation = $this->siteRepo->get(array_get($data, 'id'));
                break;
        }

        if (!is_null($targetRelation)) {
            $targetRelation->widgets()->save($widget);
            $user->widgets()->save($widget);
        }

        return $widget;
    }

    /**
     * update a widget
     * @param Widget $widget
     * @param array $data
     * @return Widget|mixed
     */
    public function update(Widget $widget, array $data = [])
    {
        $this->updateValidator->validate($data);

        $widget = $this->widgetRepo->update($widget, $data);
        return $widget;
    }

    /**
     * delete a widget
     * @param Widget $widget
     * @return mixed
     */
    public function destroy(Widget $widget)
    {
        $result = $this->widgetRepo->destroy($widget);
        return $result;
    }
}