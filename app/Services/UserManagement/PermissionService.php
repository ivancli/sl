<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/12/2017
 * Time: 4:06 PM
 */

namespace App\Services\UserManagement;


use App\Contracts\Repositories\UserManagement\PermissionContract;
use App\Models\Permission;
use App\Validators\UserManagement\Permission\StoreValidator;
use App\Validators\UserManagement\Permission\UpdateValidator;

class PermissionService
{
    #region repositories

    protected $permissionRepo;

    #endregion

    #region validators

    protected $storeValidator;
    protected $updateValidator;

    #endregion

    public function __construct(PermissionContract $permissionContract,
                                StoreValidator $storeValidator, UpdateValidator $updateValidator)
    {
        #region repositories binding
        $this->permissionRepo = $permissionContract;
        #endregion

        #region validators binding
        $this->storeValidator = $storeValidator;
        $this->updateValidator = $updateValidator;
        #endregion
    }

    /**
     * Load all/filtered permissions
     * @param array $data
     * @return mixed
     */
    public function load(array $data = [])
    {
        if (array_has($data, 'page')) {
            $permissions = $this->permissionRepo->filterAll($data);
        } else {
            $permissions = $this->permissionRepo->all();
        }
        return $permissions;
    }

    /**
     * Create a new permission
     * @param array $data
     * @return Permission
     */
    public function store(array $data)
    {
        $this->storeValidator->validate($data);
        $permission = $this->permissionRepo->store($data);
        return $permission;
    }

    /**
     * Update an existing permission
     * @param Permission $permission
     * @param array $data
     * @return Permission
     */
    public function update(Permission $permission, array $data)
    {
        $data = array_set($data, 'id', $permission->getKey());
        $this->updateValidator->validate($data);
        $permission = $this->permissionRepo->update($permission, $data);
        return $permission;
    }

    /**
     * Delete an existing permission
     * @param Permission $permission
     * @return mixed
     */
    public function destroy(Permission $permission)
    {
        $result = $this->permissionRepo->destroy($permission);
        return $result;
    }
}