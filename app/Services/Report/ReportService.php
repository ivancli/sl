<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 25/05/2017
 * Time: 11:18 AM
 */

namespace App\Services\Report;


use App\Contracts\Repositories\Product\CategoryContract;
use App\Contracts\Repositories\Product\ProductContract;
use App\Contracts\Repositories\Report\ReportContract;
use App\Models\Report;

class ReportService
{
    #region repositories

    protected $reportRepo;
    protected $productRepo;
    protected $categoryRepo;

    #endregion

    public function __construct(ReportContract $reportContract, ProductContract $productContract, CategoryContract $categoryContract)
    {
        #region repositories binding
        $this->reportRepo = $reportContract;
        $this->productRepo = $productContract;
        $this->categoryRepo = $categoryContract;
        #endregion
    }

    public function load(array $data = [])
    {
        if (array_has($data, 'page')) {
            $reports = $this->reportRepo->filterAll($data);
        } else {
            $reports = $this->reportRepo->all();
        }
        return $reports;
    }

    public function store(array $data)
    {
        /*TODO validation*/

        $user = auth()->user();

        $report = $this->reportRepo->store($data);
        $user->reports()->save($report);

        if (array_has($data, 'product_id')) {
            $product_id = array_get($data, 'product_id');
            $product = $this->productRepo->get($product_id);
            $product->report()->save($report);
        }

        if (array_has($data, 'category_id')) {
            $category_id = array_get($data, 'category_id');
            $category = $this->categoryRepo->get($category_id);
            $category->report()->save($report);
        }

        return $report;
    }

    public function update(Report $report, array $data)
    {
        $user = auth()->user();
        $report = $this->reportRepo->update($report, $data);
        $user->reports()->save($report);

        if (array_has($data, 'product_id')) {
            $product_id = array_get($data, 'product_id');
            $product = $this->productRepo->get($product_id);
            $product->report()->save($report);
        }

        if (array_has($data, 'category_id')) {
            $category_id = array_get($data, 'category_id');
            $category = $this->categoryRepo->get($category_id);
            $category->report()->save($report);
        }

        return $report;
    }
}