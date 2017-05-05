<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 18/04/2017
 * Time: 10:24 PM
 */

namespace App\Services\Account;


use App\Contracts\Repositories\UserManagement\UserContract;
use App\Validators\Account\Profile\UpdateValidator;

class ProfileService
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
     * @param $user_id
     * @param array $data
     */
    public function update($user_id, array $data)
    {
        $user = $this->userRepo->get($user_id);
        $this->updateValidator->validate($data);
        $this->userRepo->update($user, $data);
        $this->userRepo->updateMetas($user, $data);
    }
}