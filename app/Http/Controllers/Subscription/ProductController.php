<?php
namespace App\Http\Controllers\Subscription;

use App\Contracts\Repositories\Subscription\SubscriptionManagementContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/2/2017
 * Time: 12:05 PM
 */
class ProductController extends Controller
{
    var $request;
    var $subscriptionManagementRepo;

    public function __construct(Request $request, SubscriptionManagementContract $subscriptionManagementContract)
    {
        $this->request = $request;
        $this->subscriptionManagementRepo = $subscriptionManagementContract;
    }

    public function index()
    {
        return $this->subscriptionManagementRepo->getPricingTables();
    }
}