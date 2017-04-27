<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20/02/2017
 * Time: 10:06 PM
 */

namespace App\Contracts\Repositories\UserManagement;


use App\Models\Role;
use phpDocumentor\Reflection\Types\Array_;

interface RoleContract
{
    /**
     * Load all roles and filter them
     * @param array $data
     * @return mixed
     */
    public function filterAll(array $data = []);

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
    public function store(array $data);

    /**
     * Update existing role
     * @param Role $role
     * @param array $data
     * @return Role
     */
    public function update(Role $role, array $data);

    /**
     * Remove an existing role
     * @param Role $role
     * @return
     */
    public function destroy(Role $role);

    /**
     * Update permission of a role
     * @param Role $role
     * @param array $permissions
     * @return mixed
     */
    public function updatePermissions(Role $role, array $permissions);
}