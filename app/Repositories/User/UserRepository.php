<?php
namespace App\Repositories\User;

use App\Contracts\Repositories\User\UserContract;
use App\Models\User;

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/7/2017
 * Time: 5:30 PM
 */
class UserRepository implements UserContract
{
    var $userModel;

    public function __construct(User $user)
    {
        $this->userModel = $user;
    }

    /**
     * Get user by user id
     *
     * @param $user_id
     * @param bool $throw
     * @return mixed
     */
    public function get($user_id, $throw = false)
    {
        if ($throw) {
            return $this->userModel->findOrFail($user_id);
        } else {
            return $this->userModel->find($user_id);
        }
    }
}