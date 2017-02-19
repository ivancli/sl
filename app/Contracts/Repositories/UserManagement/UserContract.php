<?php
namespace App\Contracts\Repositories\UserManagement;

use App\Models\User;

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 19/02/2017
 * Time: 10:07 PM
 */
interface UserContract
{

    /**
     * @return mixed
     */
    public function all();

    /**
     * Get user by ID
     * @param $user_id
     * @param bool $throw
     * @return User
     */
    public function get($user_id, $throw = true);

    /**
     * Create new user
     * @param array $data
     * @return mixed
     */
    public function store(Array $data);

    /**
     * Update existing user
     * @param $user_id
     * @param array $data
     * @return mixed
     */
    public function update($user_id, Array $data);

    /**
     * Remove an existing user
     * @param $user_id
     */
    public function destroy($user_id);
}