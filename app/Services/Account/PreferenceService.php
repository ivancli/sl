<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 18/04/2017
 * Time: 10:06 PM
 */

namespace App\Services\Account;

use App\Contracts\Repositories\UserManagement\UserContract;
use App\Validators\Account\Preference\UpdateValidator;

class PreferenceService
{
    #region repositories

    protected $userRepo;

    #endregion

    #region validators

    protected $updateValidator;

    #endregion

    public function __construct(UserContract $userContract,
                                UpdateValidator $updateValidator)
    {
        #region repositories binding
        $this->userRepo = $userContract;
        #endregion

        #region validators binding
        $this->updateValidator = $updateValidator;
        #endregion
    }

    /**
     * get user by user ID
     * @param $user_id
     * @return \App\Models\User
     */
    public function getUserById($user_id)
    {
        $user = $this->userRepo->get($user_id);
        return $user;
    }

    /**
     * update user preferences
     * @param $user_id
     * @param array $data
     * @return \App\Models\User
     */
    public function update($user_id, array $data)
    {
        $user = $this->userRepo->get($user_id);
        $this->updateValidator->validate($data);
        foreach ($data as $element => $value) {
            $user->setPreference($element, $value);
        }
        return $user;
    }
}