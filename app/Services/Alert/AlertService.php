<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 19/05/2017
 * Time: 11:33 AM
 */

namespace App\Services\Alert;


use App\Contracts\Repositories\Alert\AlertContract;
use App\Contracts\Repositories\Product\CategoryContract;
use App\Contracts\Repositories\Product\ProductContract;
use App\Validators\Alert\StoreValidator;

class AlertService
{
    #region repositories

    protected $alertRepo;
    protected $categoryRepo;
    protected $productRepo;

    #endregion

    #region validators

    protected $storeValidator;

    #endregion

    public function __construct(AlertContract $alertContract, CategoryContract $categoryContract, ProductContract $productContract,
                                StoreValidator $storeValidator)
    {
        #region repositories binding
        $this->alertRepo = $alertContract;
        $this->categoryRepo = $categoryContract;
        $this->productRepo = $productContract;
        #endregion

        #region validators binding
        $this->storeValidator = $storeValidator;
        #endregion
    }

    public function load(array $data = [])
    {
        $user = auth()->user();
        $alerts = $user->alerts;
        return $alerts;
    }

    public function store(array $data)
    {
        $this->storeValidator->validate($data);

        $user = auth()->user();
        $user->alerts()->delete();

        #region saving category alerts
        if (array_has($data, 'category_alerts') && is_array(array_get($data, 'category_alerts'))) {
            $categoryAlerts = array_get($data, 'category_alerts');
            foreach ($categoryAlerts as $categoryAlert) {
                if (array_has($categoryAlert, 'is_selected') && array_get($categoryAlert, 'is_selected') == true) {
                    $category_id = array_get($categoryAlert, 'category_id');
                    $category = $this->categoryRepo->get($category_id);
                    $alertData = [
                        'comp_type' => array_get($categoryAlert, 'type'),
                    ];
                    if ($category->alert()->count() == 0) {
                        $alert = $this->alertRepo->store($alertData);
                        $category->alert()->save($alert);
                        $user->alerts()->save($alert);
                    } else {
                        $alert = $category->alert;
                        $alert->update($alertData);
                    }
                }
            }
        }
        #endregion

        #region saving product alerts
        if (array_has($data, 'product_alerts') && is_array(array_get($data, 'product_alerts'))) {
            $productAlerts = array_get($data, 'product_alerts');
            foreach ($productAlerts as $productAlert) {
                if (array_has($productAlert, 'is_selected') && array_get($productAlert, 'is_selected') == true) {
                    $product_id = array_get($productAlert, 'product_id');
                    $product = $this->productRepo->get($product_id);
                    $alertData = [
                        'comp_type' => array_get($productAlert, 'type'),
                        'comp_price' => array_get($productAlert, 'price'),
                    ];
                    if ($product->alert()->count() == 0) {
                        $alert = $this->alertRepo->store($alertData);
                        $product->alert()->save($alert);
                        $user->alerts()->save($alert);
                    } else {
                        $alert = $product->alert;
                        $alert->update($alertData);
                    }
                }
            }
        }
        #endregion
    }
}