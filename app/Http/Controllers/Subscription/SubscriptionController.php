<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/3/2017
 * Time: 5:21 PM
 */

namespace App\Http\Controllers\Subscription;


use App\Contracts\Repositories\Subscription\ProductContract;
use App\Contracts\Repositories\Subscription\SubscriptionContract;
use App\Contracts\Repositories\UserManagement\UserContract;
use App\Exceptions\JsonDecodeException;
use App\Exceptions\Subscription\SubscriptionNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    protected $request;
    protected $subscriptionService;

    public function __construct(Request $request, SubscriptionService $subscriptionService)
    {
        $this->request = $request;
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Load subscription information
     * @param Subscription $subscription
     * @return array
     */
    public function show(Subscription $subscription)
    {
        /*TODO add event listeners to this function*/

        $viewData = $this->subscriptionService->show($subscription);
        $viewData = array_set($viewData, 'status', true);

        return $viewData;
    }

    /**
     * Accept subscription update from Chargify
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws JsonDecodeException
     * @throws SubscriptionNotFoundException
     */
    public function create()
    {
        /*TODO add event listeners to this function*/

        $data = $this->request->all();
        /* TODO validation here */
        if (!isset($data['ref']) || !isset($data['id'])) {
            abort(403);
        }

        $this->subscriptionService->create($this->request->all());

        return redirect()->route('home.get');
    }

    /**
     * User without subscription will come to this page and reactivate/subscribe
     * @param Subscription $subscription
     */
    public function edit(Subscription $subscription)
    {

    }

    /**
     * Subscription Upgrade or Downgrade
     * @param Subscription $subscription
     * @return array
     */
    public function update(Subscription $subscription)
    {
        /**
         * If subscription is not active, that means subscription profile does not actively charging from credit card/payment profile
         * Updating product in subscription will not cause pro-rata issue.
         *
         * However, for trialing subscriptions which do not have credit card/payment details.
         * It is impossible to migrate, in which will cause pro-rata calculation and charge money from users
         */
        /*TODO add event listeners to this function*/

        $resultData = $this->subscriptionService->update($subscription, $this->request->all());
        $resultData = array_set($resultData, 'status', true);

        return $resultData;
    }
}