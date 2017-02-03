<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/3/2017
 * Time: 5:21 PM
 */

namespace App\Http\Controllers\Subscription;


use App\Contracts\Repositories\Subscription\SubscriptionContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    var $request;
    var $subscriptionRepo;

    public function __construct(Request $request, SubscriptionContract $subscriptionContract)
    {
        $this->request = $request;
        $this->subscriptionRepo = $subscriptionContract;
    }

    public function store()
    {

    }
}