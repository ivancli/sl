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
    public function filterAll(array $data = []);

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
    public function store(array $data);

    /**
     * Update existing user
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function update(User $user, array $data);

    /**
     * update user meta info
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function updateMetas(User $user, array $data);

    /**
     * Remove an existing user
     * @param User $user
     * @return bool
     */
    public function destroy(User $user);

    /**
     * Update roles of a user
     * @param User $user
     * @param array $roles
     * @return mixed
     */
    public function updateRoles(User $user, array $roles);
}