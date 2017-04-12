<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/12/2017
 * Time: 11:47 AM
 */

namespace App\Services\UserManagement;


use App\Contracts\Repositories\UserManagement\RoleContract;
use App\Contracts\Repositories\UserManagement\UserContract;
use App\Models\User;
use App\Validators\UserManagement\User\StoreValidator;
use App\Validators\UserManagement\User\UpdateValidator;

class UserService
{
    #region repositories

    protected $userRepo;
    protected $roleRepo;

    #endregion

    #region validators

    protected $storeValidator;
    protected $updateValidator;

    #endregion

    public function __construct(UserContract $userContract, RoleContract $roleContract,
                                StoreValidator $storeValidator, UpdateValidator $updateValidator)
    {
        #region repositories binding
        $this->userRepo = $userContract;
        $this->roleRepo = $roleContract;
        #endregion

        #region validators binding
        $this->storeValidator = $storeValidator;
        $this->updateValidator = $updateValidator;
        #endregion
    }

    /**
     * Load all/filtered users
     * @param array $data
     * @return mixed
     */
    public function load(array $data = [])
    {
        if (array_has($data, 'page')) {
            $users = $this->userRepo->filterAll($data);
        } else {
            $users = $this->userRepo->all();
        }
        return $users;
    }

    /**
     * Create a new user (and assign roles)
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        $this->storeValidator->validate($data);
        $user = $this->userRepo->store($data);

        if (array_has($data, 'role_ids') && is_array(array_get($data, 'role_ids'))) {
            $roles = [];
            foreach (array_get($data, 'role_ids') as $role_id) {
                $role = $this->roleRepo->get($role_id);
                $roles[] = $role;
            }
            $this->userRepo->updateRoles($user, $roles);
        }
        return $user;
    }

    /**
     * Update an existing user (and assign roles)
     * @param User $user
     * @param array $data
     * @return User|mixed
     */
    public function update(User $user, array $data)
    {
        $data = array_set($data, 'id', $user->getKey());
        $this->updateValidator->validate($data);
        $user = $this->userRepo->update($user, $data);

        if (array_has($data, 'role_ids') && is_array(array_get($data, 'role_ids'))) {
            $roles = [];
            foreach (array_get($data, 'role_ids') as $role_id) {
                $role = $this->roleRepo->get($role_id);
                $roles[] = $role;
            }
            $this->userRepo->updateRoles($user, $roles);
        }
        return $user;
    }

    /**
     * Delete an existing user
     * @param User $user
     * @return bool
     */
    public function destroy(User $user)
    {
        $result = $this->userRepo->destroy($user);
        return $result;
    }
}