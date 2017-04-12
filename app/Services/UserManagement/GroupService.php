<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/12/2017
 * Time: 11:28 AM
 */

namespace App\Services\UserManagement;


use App\Contracts\Repositories\UserManagement\GroupContract;
use App\Models\Group;
use App\Validators\UserManagement\Group\StoreValidator;
use App\Validators\UserManagement\Group\UpdateValidator;

class GroupService
{
    #region repositories

    protected $groupRepo;

    #endregion


    #region validators

    protected $storeValidator;
    protected $updateValidator;

    #endregion

    public function __construct(GroupContract $groupContract,
                                StoreValidator $storeValidator, UpdateValidator $updateValidator)
    {
        #region repositories binding
        $this->groupRepo = $groupContract;
        #endregion

        #region validators binding
        $this->storeValidator = $storeValidator;
        $this->updateValidator = $updateValidator;
        #endregion
    }

    /**
     * Load all/filtered groups
     * @param array $data
     * @return mixed
     */
    public function load(array $data = [])
    {
        if (array_has($data, 'page')) {
            $groups = $this->groupRepo->filterAll($data);
        } else {
            $groups = $this->groupRepo->all();
        }
        return $groups;
    }

    /**
     * Create a new group
     * @param array $data
     * @return \App\Models\Group
     */
    public function store(array $data)
    {
        $this->storeValidator->validate($data);
        $group = $this->groupRepo->store($data);
        return $group;
    }

    /**
     * Update an existing group
     * @param Group $group
     * @param array $data
     * @return Group
     */
    public function update(Group $group, array $data)
    {
        $data = array_set($data, 'id', $group->getKey());
        $this->updateValidator->validate($data);
        $group = $this->groupRepo->update($group, $data);
        return $group;
    }

    /**
     * Delete an existing group
     * @param Group $group
     * @return mixed
     */
    public function destroy(Group $group)
    {
        $result = $this->groupRepo->destroy($group);
        return $result;
    }
}