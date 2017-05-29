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
use Illuminate\Support\Facades\Cache;
use IvanCLI\Chargify\Chargify;

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
     * @return array
     */
    public function edit(Subscription $subscription)
    {
        $link = $this->subscriptionService->updateLink($subscription);
        $status = true;
        return compact(['link', 'status']);
    }

    /**
     * updated credit card
     */
    public function updated()
    {
        $user = auth()->user();
        $subscription = $user->subscription;
        if (!is_null($subscription)) {
            Cache::forget("{$subscription->location}.chargify.subscriptions.{$subscription->api_subscription_id}");
            $result = Chargify::subscription($subscription->location)->reactivate($subscription->apiSubscription->id);
            Cache::forget("{$subscription->location}.chargify.subscriptions.{$subscription->api_subscription_id}");
        }

        return redirect()->route('account-settings.index');
    }

    /**
     * reactivate subscription
     * @param Subscription $subscription
     * @return array
     */
    public function reactivate(Subscription $subscription)
    {
        Cache::forget("{$subscription->location}.chargify.subscriptions.{$subscription->api_subscription_id}");
        $result = Chargify::subscription($subscription->location)->reactivate($subscription->apiSubscription->id);
        Cache::forget("{$subscription->location}.chargify.subscriptions.{$subscription->api_subscription_id}");
        $status = true;

        return compact(['status']);
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

        Cache::forget("{$subscription->location}.chargify.subscriptions.{$subscription->api_subscription_id}");

        if ($this->request->ajax()) {
            return $resultData;
        } else {
            return redirect()->route('account-settings.index');
        }
    }

    /**
     * Cancel subscription - either keep or not keep profiles
     * @param Subscription $subscription
     * @return array
     */
    public function destroy(Subscription $subscription)
    {
        $this->subscriptionService->cancel($subscription, $this->request->all());
        $status = true;

        return compact(['status']);
    }
}