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
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    var $request;
    var $subscriptionRepo;
    var $productRepo;

    public function __construct(Request $request,
                                SubscriptionContract $subscriptionContract,
                                ProductContract $productContract)
    {
        $this->request = $request;
        $this->subscriptionRepo = $subscriptionContract;
        $this->productRepo = $productContract;
    }

    public function store()
    {
        /* TODO validation here */

        $subscriptionPlanId = $this->request->get('subscription_plan_id');
        $subscriptionPlan = $this->productRepo->getProductByProductId($subscriptionPlanId);
        /*TODO get product from chargify*/


            if ($product->require_credit_card) {
                if (!is_null(auth()->user()->subscription)) {
                    $previousSubscription = auth()->user()->subscription;
                    $previousAPISubscription = Chargify::subscription()->get($previousSubscription->api_subscription_id);
                    if (!is_null($previousAPISubscription)) {
                        $paymentProfile = $previousAPISubscription->paymentProfile();
                        if (!isset($paymentProfile->errors) && !is_null($paymentProfile)) {
                            if ($paymentProfile->expiration_year > date("Y") || ($paymentProfile->expiration_year == date("Y") && $paymentProfile->expiration_month >= date('n'))) {
                                $newSubscription = Chargify::subscription()->create(array(
                                    "product_id" => $product->id,
                                    "customer_id" => $previousSubscription->api_customer_id,
                                    "payment_profile_id" => $paymentProfile->id,
                                    "coupon_code" => $couponCode,
                                ));
                                $user->clearCache();
//                                $this->mailingAgentRepo->updateNextLevelSubscriptionPlan(auth()->user());
                                if (!isset($newSubscription->errors)) {
                                    $previousSubscription->api_product_id = $newSubscription->product_id;
                                    $previousSubscription->api_subscription_id = $newSubscription->id;
                                    $previousSubscription->api_customer_id = $newSubscription->customer_id;
                                    $previousSubscription->cancelled_at = null;
                                    $previousSubscription->save();
                                    return redirect()->route('account.index');
                                }
                            }
                        }
                    }
                }
                /* redirect to Chargify payment gateway (signup page) */
                $chargifyLink = array_first($product->public_signup_pages)->url;
                $verificationCode = str_random(10);
                $user->verification_code = $verificationCode;
                $user->save();
                $reference = array(
                    "user_id" => $user->getKey(),
                    "verification_code" => $verificationCode
                );

                $encryptedReference = rawurlencode(json_encode($reference));
                $chargifyLink = $chargifyLink . "?reference=$encryptedReference&first_name={$user->first_name}&last_name={$user->last_name}&email={$user->email}&organization={$user->company_name}&coupon_code={$couponCode}";
                $user->clearCache();
                return redirect()->to($chargifyLink);
            } else {
                $newSubscription = Chargify::subscription()->create(array(
                    "product_id" => $product->id,
                    "customer_attributes" => array(
                        "first_name" => $user->first_name,
                        "last_name" => $user->last_name,
                        "email" => $user->email
                    )
                ));

                if (!isset($newSubscription->errors)) {
                    /* clear verification code*/
                    $user->verification_code = null;
                    $user->save();
                    try {
                        /* update subscription record */
                        $expiry_datetime = $newSubscription->expires_at;
                        $sub = new Subscription();
                        $sub->user_id = $user->getKey();
                        $sub->api_product_id = $newSubscription->product_id;
                        $sub->api_customer_id = $newSubscription->customer_id;
                        $sub->api_subscription_id = $newSubscription->id;
                        $sub->expiry_date = date('Y-m-d H:i:s', strtotime($expiry_datetime));
                        $sub->save();

                        $criteria = auth()->user()->subscriptionCriteria();
                        $this->mailingAgentRepo->editSubscriber($user->email, array(
                            "CustomFields" => array(
                                array(
                                    "Key" => "SubscribedDate",
                                    "Value" => date("Y/m/d")
                                ),
                                array(
                                    "Key" => "SubscriptionPlan",
                                    "Value" => $product->name
                                ),
                                array(
                                    "Key" => "TrialExpiry",
                                    "Value" => date('Y/m/d', strtotime($newSubscription->trial_ended_at))
                                ),
                                array(
                                    "Key" => "NumberofSites",
                                    "Value" => 0
                                ),
                                array(
                                    "Key" => "NumberofProducts",
                                    "Value" => 0
                                ),
                                array(
                                    "Key" => "NumberofCategories",
                                    "Value" => 0
                                ),
                                array(
                                    "Key" => "MaximumNumberofProducts",
                                    "Value" => isset($criteria->product) && $criteria->product != 0 ? $criteria->product : null
                                ),
                                array(
                                    "Key" => "MaximumNumberofSites",
                                    "Value" => isset($criteria->site) && $criteria->site != 0 ? $criteria->site : null
                                ),
                                array(
                                    "Key" => "LastLoginDate",
                                    "Value" => date('Y/m/d')
                                ),
                            ),
                            'Resubscribe' => true
                        ));

                        event(new SubscriptionCompleted($sub));
                        $user->clearCache();
                        $this->mailingAgentRepo->updateNextLevelSubscriptionPlan($user);
                        return redirect()->route('account.index');
                    } catch (Exception $e) {
                        /*TODO need to handle exception properly*/
                        return $user;
                    }
                }
            }
    }
}