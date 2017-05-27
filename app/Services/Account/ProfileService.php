<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 18/04/2017
 * Time: 10:24 PM
 */

namespace App\Services\Account;


use App\Contracts\Repositories\Report\ReportContract;
use App\Contracts\Repositories\UserManagement\UserContract;
use App\Models\User;
use App\Validators\Account\Profile\UpdateValidator;

class ProfileService
{
    #region repositories

    protected $userRepo;
    protected $reportRepo;

    #endregion

    #region validators

    protected $updateValidator;

    #endregion

    public function __construct(UserContract $userContract, ReportContract $reportContract,
                                UpdateValidator $updateValidator)
    {
        #region repositories binding
        $this->userRepo = $userContract;
        $this->reportRepo = $reportContract;
        #endregion

        #region validators binding
        $this->updateValidator = $updateValidator;
        #endregion
    }

    /**
     * get user by user id
     * @param $user_id
     * @return \App\Models\User
     */
    public function getUserById($user_id)
    {
        $user = $this->userRepo->get($user_id, true, ['subscription']);
        return $user;
    }

    /**
     * update user profile
     * @param User $user
     * @param array $data
     */
    public function update(User $user, array $data)
    {
        $this->updateValidator->validate($data);

        if (array_has($data, 'profile')) {
            $this->userRepo->update($user, array_get($data, 'profile'));
        }

        if (array_has($data, 'company')) {
            $this->userRepo->updateMetas($user, array_get($data, 'company'));
        }

        if (array_has($data, 'display')) {
            foreach (array_get($data, 'display') as $element => $value) {
                $user->setPreference($element, $value);
            }
        }

        if (array_has($data, 'digest')) {
            $digest = array_get($data, 'digest');
            if (array_has($digest, 'is_active') && array_get($digest, 'is_active') == true) {
                if (array_has($digest, 'report_id') && !is_null(array_get($digest, 'report_id'))) {
                    $report = $this->reportRepo->get(array_get($digest, 'report_id'));
                    $report->update($digest);
                    $user->reports()->save($report);
                } else {
                    $report = $this->reportRepo->store($digest);
                    $user->reports()->save($report);
                }
            } else {
                $user->reports()->where('report_type', 'digest')->delete();
            }
        } else {
            $user->reports()->where('report_type', 'digest')->delete();
        }
    }
}