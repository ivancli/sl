<?php

namespace App\Http\Controllers\Chart;

use App\Services\Chart\ChartService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChartController extends Controller
{
    protected $request;
    protected $chartService;

    public function __construct(Request $request, ChartService $chartService)
    {
        $this->request = $request;
        $this->chartService = $chartService;
    }

    /**
     * return formatted historical prices
     */
    public function sitePrice()
    {
        $data = $this->chartService->sitePrices($this->request->all());
        $status = true;

        return compact(['data', 'status']);
    }

    public function productPrice()
    {
        $data = $this->chartService->productPrices($this->request->all());
        $status = true;

        return compact(['data', 'status']);
    }

    public function CategoryPrice()
    {
        $data = $this->chartService->categoryPrices($this->request->all());
        $status = true;

        return compact(['data', 'status']);
    }
}
