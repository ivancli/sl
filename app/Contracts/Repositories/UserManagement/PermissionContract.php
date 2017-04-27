<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20/02/2017
 * Time: 10:06 PM
 */

namespace App\Contracts\Repositories\UserManagement;


use App\Models\Permission;

interface PermissionContract
{
    /**
     * Load all permissions and filter them
     * @param array $data
     * @return mixed
     */
    public function filterAll(array $data = []);

    /**
     * Load all permissions
     * @return mixed
     */
    public function all();

    /**
     * Get permission by ID
     * @param $permission_id
     * @param bool $throw
     * @return Permission
     */
    public function get($permission_id, $throw = true);

    /**
     * Create new permission
     * @param array $data
     * @return Permission
     */
    public function store(array $data);

    /**
     * Update existing permission
     * @param Permission $permission
     * @param array $data
     * @return Permission
     */
    public function update(Permission $permission, array $data);

    /**
     * Remove an existing permission
     * @param Permission $permission
     * @return
     */
    public function destroy(Permission $permission);
}