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
     * Load all users and filter them
     * @param array $data
     * @return mixed
     */
    public function filterAll(Array $data = []);

    /**
     * Load all users
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
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function update(User $user, Array $data);

    /**
     * Remove an existing user
     * @param User $user
     * @return
     */
    public function destroy(User $user);
}