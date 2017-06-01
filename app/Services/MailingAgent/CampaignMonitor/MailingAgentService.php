<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 1/06/2017
 * Time: 10:20 AM
 */

namespace App\Services\MailingAgent\CampaignMonitor;


use App\Contracts\Repositories\MailingAgent\MailingAgentContract;
use App\Models\User;
use Carbon\Carbon;

class MailingAgentService
{
    #region repositories

    protected $mailingAgentRepo;

    #endregion


    public function __construct(MailingAgentContract $mailingAgentContract)
    {
        #region repositories binding
        $this->mailingAgentRepo = $mailingAgentContract;
        #endregion
    }

    /**
     * create new subscriber
     * @param User $user
     */
    public function store(User $user)
    {
        $this->mailingAgentRepo->storeSubscriber([
            'EmailAddress' => $user->email,
            'Name' => $user->fullName,
        ]);
    }

    /**
     * delete a subscriber
     * @param User $user
     * @return mixed
     */
    public function destroy(User $user)
    {
        $result = $this->mailingAgentRepo->delete($user->email);
        return $result;
    }

    /**
     * unsubscribe a subscriber
     * @param User $user
     * @return mixed
     */
    public function unsubscribe(User $user)
    {
        $result = $this->mailingAgentRepo->unsubscribe($user->email);
        return $result;
    }

    public function updateNumberOfCategories(User $user)
    {
        $result = $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                $this->__customField('NumberofCategories', $user->numberOfCategories),
            ]
        ]);
        return $result;
    }

    public function updateNumberOfProducts(User $user)
    {
        $result = $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                $this->__customField('NumberofProducts', $user->numberOfProducts),
            ]
        ]);
        return $result;
    }

    public function updateNumberOfSites(User $user)
    {
        $result = $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                $this->__customField('NumberofSites', $user->numberOfSites),
            ]
        ]);
        return $result;
    }

    public function updateLastAddCategoryDate(User $user)
    {
        $result = $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                $this->__customField('LastAddedCategoryDate', date('Y/m/d')),
            ]
        ]);
        return $result;
    }

    public function updateLastAddProductDate(User $user)
    {
        $result = $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                $this->__customField('LastAddedProductDate', date('Y/m/d')),
            ]
        ]);
        return $result;
    }

    public function updateLastAddSiteDate(User $user)
    {
        $result = $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                $this->__customField('LastAddedSiteDate', date('Y/m/d')),
            ]
        ]);
        return $result;
    }

    public function updateLastSetupAlertDate(User $user)
    {
        $result = $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                $this->__customField('LastSetupAlertDate', date('Y/m/d')),
            ]
        ]);
        return $result;
    }

    public function updateLastSetupReportDate(User $user)
    {
        $result = $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                $this->__customField('LastSetupReportDate', date('Y/m/d')),
            ]
        ]);
        return $result;
    }

    public function updateLastConfiguredDashboardDate(User $user)
    {
        $result = $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                $this->__customField('LastConfiguredDashboardDate', date('Y/m/d')),
            ]
        ]);
        return $result;
    }

    public function updateNextLevelSubscriptionPlan(User $user)
    {
        /*
         * TODO this task is too heavy resource consuming
         * TODO will leave this if it's not causing issue
         * */
    }

    public function updateLastLoginDate(User $user)
    {

        $lastLoginDate = null;
        if (!is_null($user->last_login_at)) {
            $lastLoginDate = Carbon::parse($user->last_login_at)->format('Y/m/d');
        }
        $result = $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                $this->__customField('LastLoginDate', $lastLoginDate),
            ]
        ]);
    }

    public function updateSubscriptionPlan(User $user)
    {
        $subscriptionPlan = null;
        if (!is_null($user->subscription)) {
            if (!is_null($user->subscription->subscriptionPlan)) {
                $subscriptionPlan = $user->subscription->subscriptionPlan->name;
            }
        }

        $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                $this->__customField('SubscriptionPlan', $subscriptionPlan),
            ]
        ]);
    }

    public function updateSubscriptionCancelledDate(User $user)
    {
        $subscriptionCancelledAt = null;
        if (!is_null($user->subscription)) {
            if (!is_null($user->subscription->apiSubscription)) {
                if (!is_null($user->subscription->apiSubscription->canceled_at)) {
                    $subscriptionCancelledAt = Carbon::parse($user->subscription->apiSubscription->canceled_at);
                }
            }
        }

        $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                $this->__customField('SubscriptionCancelledDate', $subscriptionCancelledAt),
            ]
        ]);
    }

    public function updateSubscribedDate(User $user)
    {
        $subscribedDate = null;
        if (!is_null($user->subscription)) {
            $subscribedDate = Carbon::parse($user->subscription->created_at)->format('Y/m/d');
        }
        $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                $this->__customField('SubscribedDate', $subscribedDate),
            ]
        ]);
    }

    public function updateLastSubscriptionUpdatedDate(User $user)
    {
        $subscriptionUpdatedDate = null;

        if (!is_null($user->subscription)) {
            $subscriptionUpdatedDate = Carbon::parse($user->subscription->updated_at)->format('Y/m/d');
        }
        $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                $this->__customField('LastSubscriptionUpdatedDate', $subscriptionUpdatedDate)
            ]
        ]);
    }

    public function updateTrialExpiry(User $user)
    {
        $subscriptionTrialEndedAt = null;

        if (!is_null($user->subscription)) {
            if (!is_null($user->subscription->apiSubscription)) {
                if (!is_null($user->subscription->apiSubscription->trial_ended_at)) {
                    $subscriptionTrialEndedAt = Carbon::parse($user->subscription->apiSubscription->trial_ended_at)->format('Y/m/d');
                }
            }
        }

        $this->mailingAgentRepo->updateSubscriber($user->email, [
            'Name' => $user->fullName,
            'CustomFields' => [
                $this->__customField('TrialExpiry', $subscriptionTrialEndedAt),
            ],
        ]);
    }

    /**
     * synchronise all user data in one go
     * @param User $user
     */
    public function syncUser(User $user)
    {
        $subscriptionPlan = null;
        $subscribedDate = null;
        $subscriptionUpdatedDate = null;
        $subscriptionTrialEndedAt = null;
        $subscriptionCancelledAt = null;
        $maxNumberOfProducts = null;
        $maxNumberOfSites = null;
        $subscriptionLocation = null;

        if (!is_null($user->subscription)) {
            $subscribedDate = Carbon::parse($user->subscription->created_at)->format('Y/m/d');
            $subscriptionUpdatedDate = Carbon::parse($user->subscription->updated_at)->format('Y/m/d');
            $subscriptionLocation = $user->subscription->location;
            if (!is_null($user->subscription->apiSubscription)) {
                if (!is_null($user->subscription->apiSubscription->trial_ended_at)) {
                    $subscriptionTrialEndedAt = Carbon::parse($user->subscription->apiSubscription->trial_ended_at)->format('Y/m/d');
                }
                if (!is_null($user->subscription->apiSubscription->canceled_at)) {
                    $subscriptionCancelledAt = Carbon::parse($user->subscription->apiSubscription->canceled_at);
                }
            }
            if (!is_null($user->subscription->subscriptionCriteria) && $user->subscription->subscriptionCriteria->product > 0) {
                $maxNumberOfProducts = $user->subscription->subscriptionCriteria->product;
            }
            if (!is_null($user->subscription->subscriptionCriteria) && $user->subscription->subscriptionCriteria->site > 0) {
                $maxNumberOfSites = $user->subscription->subscriptionCriteria->site;
            }

            if (!is_null($user->subscription->subscriptionPlan)) {
                $subscriptionPlan = $user->subscription->subscriptionPlan->name;
            }
        }
        $lastAddedCategoryDate = null;
        if (!is_null($user->categories()->max('created_at'))) {
            $lastAddedCategoryDate = Carbon::parse($user->categories()->max('categories.created_at'))->format('Y/m/d');
        }
        $lastAddedProductDate = null;
        if (!is_null($user->products()->max('created_at'))) {
            $lastAddedCategoryDate = Carbon::parse($user->products()->max('products.created_at'))->format('Y/m/d');
        }
        $lastAddedSiteDate = null;
        if (!is_null($user->sites()->max('created_at'))) {
            $lastAddedCategoryDate = Carbon::parse($user->sites()->max('sites.created_at'))->format('Y/m/d');
        }
        $lastSetupAlertDate = null;
        if (!is_null($user->sites()->max('updated_at'))) {
            $lastSetupAlertDate = Carbon::parse($user->alerts()->max('alerts.updated_at'))->format('Y/m/d');
        }
        $lastSetupReportDate = null;
        if (!is_null($user->sites()->max('updated_at'))) {
            $lastSetupAlertDate = Carbon::parse($user->reports()->max('reports.updated_at'))->format('Y/m/d');
        }
        $lastConfiguredDashboardDate = null;
        if (!is_null($user->widgets()->max('updated_at'))) {
            $lastConfiguredDashboardDate = Carbon::parse($user->widgets()->max('widgets.updated_at'))->format('Y/m/d');
        }
        $lastLoginDate = null;
        if (!is_null($user->last_login_at)) {
            $lastLoginDate = Carbon::parse($user->last_login_at)->format('Y/m/d');
        }


        $this->mailingAgentRepo->updateSubscriber($user->email, [
            'Name' => $user->fullName,
            'CustomFields' => [
                $this->__customField('NumberofSites', $user->numberOfSites),
                $this->__customField('NumberofProducts', $user->numberOfProducts),
                $this->__customField('NumberofCategories', $user->numberOfCategories),
                $this->__customField('SubscriptionPlan', $subscriptionPlan),
                $this->__customField('LastAddedCategoryDate', $lastAddedCategoryDate),
                $this->__customField('LastAddedProductDate', $lastAddedProductDate),
                $this->__customField('LastAddedSiteDate', $lastAddedSiteDate),
                $this->__customField('SubscribedDate', $subscribedDate),
                $this->__customField('LastSubscriptionUpdatedDate', $subscriptionUpdatedDate),
                $this->__customField('LastSetupAlertDate', $lastSetupAlertDate),
                $this->__customField('LastSetupReportDate', $lastSetupReportDate),
                $this->__customField('TrialExpiry', $subscriptionTrialEndedAt),
                $this->__customField('SubscriptionCancelledDate', $subscriptionCancelledAt),
                $this->__customField('LastLoginDate', $lastLoginDate),
                $this->__customField('MaximumNumberofSites', $maxNumberOfSites),
                $this->__customField('MaximumNumberofProducts', $maxNumberOfProducts),
                $this->__customField('Industry', $user->metas->industry),
                $this->__customField('CompanyType', $user->metas->company_type),
                $this->__customField('LastConfiguredDashboardDate', $lastConfiguredDashboardDate),
                $this->__customField('SubscriptionLocation', $subscriptionLocation),
            ],
        ]);
    }

    private function __customField($key, $value)
    {
        return [
            "Key" => $key,
            "Value" => $value,
        ];
    }
}