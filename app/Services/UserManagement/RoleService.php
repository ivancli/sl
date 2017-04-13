<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/13/2017
 * Time: 10:45 AM
 */

namespace App\Services\UserManagement;


use App\Contracts\Repositories\UserManagement\PermissionContract;
use App\Contracts\Repositories\UserManagement\RoleContract;
use App\Models\Role;
use App\Validators\UserManagement\Role\StoreValidator;
use App\Validators\UserManagement\Role\UpdateValidator;

class RoleService
{
    #region repositories

    protected $roleRepo;
    protected $permissionRepo;

    #endregion

    #region validators

    protected $storeValidator;
    protected $updateValidator;

    #endregion

    public function __construct(RoleContract $roleContract, PermissionContract $permissionContract,
                                StoreValidator $storeValidator, UpdateValidator $updateValidator)
    {
        #region repositories binding
        $this->roleRepo = $roleContract;
        $this->permissionRepo = $permissionContract;
        #endregion

        #region validators binding
        $this->storeValidator = $storeValidator;
        $this->updateValidator = $updateValidator;
        #endregion
    }

    /**
     * Load all/filtered roles
     * @param array $data
     * @return mixed
     */
    public function load(array $data = [])
    {
        if (array_has($data, 'page')) {
            $roles = $this->roleRepo->filterAll($data);
        } else {
            $roles = $this->roleRepo->all();
        }
        return $roles;
    }

    /**
     * Create a new role (and assign permissions)
     * @param array $data
     * @return Role
     */
    public function store(array $data)
    {
        $this->storeValidator->validate($data);
        $role = $this->roleRepo->store($data);

        if (array_has($data, 'permission_ids') && is_array(array_get($data, 'permission_ids'))) {
            $permissions = [];
            foreach (array_get($data, 'permission_ids') as $permission_id) {
                $permission = $this->permissionRepo->get($permission_id);
                if (!is_null($permission)) {
                    $permissions[] = $permission;
                }
            }
            $this->roleRepo->updatePermissions($role, $permissions);
        }
        return $role;
    }

    /**
     * Update an existing role (and assign permissions)
     * @param Role $role
     * @param array $data
     * @return Role
     */
    public function update(Role $role, array $data)
    {
        $data = array_set($data, 'id', $role->getKey());
        $this->updateValidator->validate($data);
        $role = $this->roleRepo->update($role, $data);

        if (array_has($data, 'permission_ids') && is_array(array_get($data, 'permission_ids'))) {
            $permissions = [];
            foreach (array_get($data, 'permission_ids') as $permission_id) {
                $permission = $this->permissionRepo->get($permission_id);
                if (!is_null($permission)) {
                    $permissions[] = $permission;
                }
            }
            $this->roleRepo->updatePermissions($role, $permissions);
        }
        return $role;
    }

    /**
     * Deleting an existing role
     * @param Role $role
     * @return mixed
     */
    public function destroy(Role $role)
    {
        $result = $this->roleRepo->destroy($role);
        return $result;
    }
}