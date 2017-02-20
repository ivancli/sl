<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20/02/2017
 * Time: 10:06 PM
 */

namespace App\Contracts\Repositories\UserManagement;


use App\Models\Role;

interface RoleContract
{
    /**
     * Load all roles and filter them
     * @param array $data
     * @return mixed
     */
    public function filterAll(Array $data = []);

    /**
     * Load all roles
     * @return mixed
     */
    public function all();

    /**
     * Get role by ID
     * @param $role_id
     * @param bool $throw
     * @return Role
     */
    public function get($role_id, $throw = true);

    /**
     * Create new role
     * @param array $data
     * @return Role
     */
    public function store(Array $data);

    /**
     * Update existing role
     * @param $role_id
     * @param array $data
     * @return Role
     */
    public function update($role_id, Array $data);

    /**
     * Remove an existing role
     * @param $role_id
     * @return
     */
    public function destroy($role_id);
}