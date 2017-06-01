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

    public function store(User $user)
    {
        $this->mailingAgentRepo->storeSubscriber([
            'EmailAddress' => $user->email,
            'Name' => $user->fullName,
        ]);
    }

    public function destroy(User $user)
    {
        $result = $this->mailingAgentRepo->delete($user->email);
        return $result;
    }

    public function unsubscribe(User $user)
    {
        $result = $this->mailingAgentRepo->unsubscribe($user->email);
        return $result;
    }

    public function updateNumberOfCategories(User $user)
    {
        $result = $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                [
                    "Key" => "NumberofCategories",
                    "Value" => $user->numberOfCategories,
                ]
            ]
        ]);
        return $result;
    }

    public function updateNumberOfProducts(User $user)
    {
        $result = $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                [
                    "Key" => "NumberofProducts",
                    "Value" => $user->numberOfProducts,
                ]
            ]
        ]);
        return $result;
    }

    public function updateNumberOfSites(User $user)
    {
        $result = $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                [
                    "Key" => "NumberofSites",
                    "Value" => $user->numberOfSites,
                ]
            ]
        ]);
        return $result;
    }

    public function updateLastAddCategoryDate(User $user)
    {
        $result = $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                [
                    "Key" => "LastAddedCategoryDate",
                    "Value" => date('Y/m/d'),
                ]
            ]
        ]);
        return $result;
    }

    public function updateLastAddProductDate(User $user)
    {
        $result = $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                [
                    "Key" => "LastAddedProductDate",
                    "Value" => date('Y/m/d'),
                ]
            ]
        ]);
        return $result;
    }

    public function updateLastAddSiteDate(User $user)
    {
        $result = $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                [
                    "Key" => "LastAddedSiteDate",
                    "Value" => date('Y/m/d'),
                ]
            ]
        ]);
        return $result;
    }

    public function updateLastSetupAlertDate(User $user)
    {
        $result = $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                [
                    "Key" => "LastSetupAlertDate",
                    "Value" => date('Y/m/d'),
                ]
            ]
        ]);
        return $result;
    }

    public function updateLastSetupReportDate(User $user)
    {
        $result = $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                [
                    "Key" => "LastSetupReportDate",
                    "Value" => date('Y/m/d'),
                ]
            ]
        ]);
        return $result;
    }

    public function updateLastConfiguredDashboardDate(User $user)
    {
        $result = $this->mailingAgentRepo->updateSubscriber($user->email, [
            'CustomFields' => [
                [
                    "Key" => "LastConfiguredDashboardDate",
                    "Value" => date('Y/m/d'),
                ]
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

    public function syncUser(User $user)
    {
        /*
         *
         *
        Name
        NumberofSites
        SubscriptionPlan
        NumberofProducts
        NumberofCategories
        LastAddedCategoryDate
        LastAddedProductDate
        LastAddedSiteDate
        SubscribedDate
        LastSubscriptionUpdatedDate
        LastNominatedMySiteDate
        LastSetupAlertDate
        LastSetupReportDate
        TrialExpiry
        SubscriptionCancelledDate
        MaximumNumberofSites
        LastLoginDate
        MaximumNumberofProducts
        Industry
        CompanyType
        CompanyName
        LastConfiguredDashboardDate
        CancelledBeforeEndofTrial
        CancelledAfterEndofTrial
        NextLevelSubscriptionPlan
        NextLevelMaximumNumberofSites
        NextLevelMaximumNumberofProducts
        NextLevelSubscriptionPlanDescription
        SubscriptionLocation
        */
        $subscriptionPlan = null;
        $subscribedDate = null;
        $subscriptionUpdatedDate = null;
        $subscriptionTrialEndedAt = null;
        if (!is_null($user->subscription)) {
            $subscribedDate = Carbon::parse($user->subscription->created_at)->format('Y/m/d');
            $subscriptionUpdatedDate = Carbon::parse($user->subscription->updated_at)->format('Y/m/d');
            if (!is_null($user->subscription->trial_ended_at)) {
                $subscriptionTrialEndedAt = Carbon::parse($user->subscription->trial_ended_at)->format('Y/m/d');
            }
            if (!is_null($user->subscription->subscriptionPlan)) {
                $subscriptionPlan = $user->subscription->subscriptionPlan->name;
            }
        }
        $lastAddedCategoryDate = null;
        if (!is_null($user->categories()->max('created_at'))) {
            $lastAddedCategoryDate = Carbon::parse($user->categories()->max('created_at'))->format('Y/m/d');
        }
        $lastAddedProductDate = null;
        if (!is_null($user->products()->max('created_at'))) {
            $lastAddedCategoryDate = Carbon::parse($user->products()->max('created_at'))->format('Y/m/d');
        }
        $lastAddedSiteDate = null;
        if (!is_null($user->sites()->max('created_at'))) {
            $lastAddedCategoryDate = Carbon::parse($user->sites()->max('created_at'))->format('Y/m/d');
        }
        $lastSetupAlertDate = null;
        if (!is_null($user->sites()->max('updated_at'))) {
            $lastSetupAlertDate = Carbon::parse($user->alerts()->max('updated_at'))->format('Y/m/d');
        }
        $lastSetupReportDate = null;
        if (!is_null($user->sites()->max('updated_at'))) {
            $lastSetupAlertDate = Carbon::parse($user->reports()->max('updated_at'))->format('Y/m/d');
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
                $this->__customField('SubscriptionCancelledDate', ''),
                $this->__customField('MaximumNumberofSites', ''),
                $this->__customField('LastLoginDate', ''),
                $this->__customField('MaximumNumberofProducts', ''),
                $this->__customField('Industry', ''),
                $this->__customField('CompanyType', ''),
                $this->__customField('CompanyName', ''),
                $this->__customField('LastConfiguredDashboardDate', ''),
                $this->__customField('CancelledBeforeEndofTrial', ''),
                $this->__customField('CancelledAfterEndofTrial', ''),
                $this->__customField('NextLevelSubscriptionPlan', ''),
                $this->__customField('NextLevelMaximumNumberofSites', ''),
                $this->__customField('NextLevelMaximumNumberofProducts', ''),
                $this->__customField('NextLevelSubscriptionPlanDescription', ''),
                $this->__customField('SubscriptionLocation', ''),
            ]
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