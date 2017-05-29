<?php

namespace App\Http\Controllers\Subscription;

use App\Contracts\Repositories\Subscription\SubscriptionManagementContract;
use App\Exceptions\Subscription\ProductFamilyNotFoundException;
use App\Exceptions\Subscription\ProductNoFoundException;
use App\Exceptions\Subscription\ProductNotFoundException;
use App\Http\Controllers\Controller;
use App\Services\Subscription\ProductService;
use Illuminate\Http\Request;

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/2/2017
 * Time: 12:05 PM
 */
class ProductController extends Controller
{
    protected $request;
    protected $productService;

    public function __construct(Request $request, ProductService $productService)
    {
        $this->request = $request;
        $this->productService = $productService;
    }

    /**
     * Load product families / pricing tables
     * @return ProductController|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $productFamilies = $this->productService->prices($this->request->all());
        $status = true;

        if ($this->request->has('callback')) {
            return response()->json(compact(['productFamilies', 'status']))->withCallback($this->request->get('callback'));
        }
        return compact(['productFamilies', 'status']);
    }
}