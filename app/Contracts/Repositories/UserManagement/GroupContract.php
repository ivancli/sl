<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/20/2017
 * Time: 4:39 PM
 */

namespace App\Contracts\Repositories\UserManagement;


use App\Models\Group;

interface GroupContract
{
    /**
     * Load all groups and filter them
     * @param array $data
     * @return mixed
     */
    public function filterAll(array $data = []);

    /**
     * Load all groups
     * @return mixed
     */
    public function all();

    /**
     * Get group by ID
     * @param $group_id
     * @param bool $throw
     * @return Group
     */
    public function get($group_id, $throw = true);

    /**
     * Create new group
     * @param array $data
     * @return Group
     */
    public function store(array $data);

    /**
     * Update existing group
     * @param Group $group
     * @param array $data
     * @return Group
     */
    public function update(Group $group, array $data);

    /**
     * Remove an existing group
     * @param Group $group
     * @return
     */
    public function destroy(Group $group);
}