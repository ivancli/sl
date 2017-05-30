<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 29/05/2017
 * Time: 11:34 PM
 */

namespace App\Http\Controllers\Subscription;


use App\Http\Controllers\Controller;
use App\Services\Subscription\CouponService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    protected $request;

    protected $couponService;

    public function __construct(Request $request, CouponService $couponService)
    {
        $this->request = $request;
        $this->couponService = $couponService;
    }

    public function verify()
    {
        $is_valid = $this->couponService->verify($this->request->all());
        $status = true;
        if ($this->request->has('callback')) {
            return response()->json(compact(['is_valid', 'status']))->withCallback($this->request->get('callback'));
        } else {
            return compact(['is_valid', 'status']);
        }
    }
}