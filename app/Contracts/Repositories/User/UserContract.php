<?php
namespace App\Contracts\Repositories\User;

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/7/2017
 * Time: 5:28 PM
 */
interface UserContract
{
    /**
     * Get user by user id
     *
     * @param $user_id
     * @param bool $throw
     * @return mixed
     */
    public function get($user_id, $throw = false);
}