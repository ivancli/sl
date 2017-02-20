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
    public function filterAll(Array $data = []);

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
    public function store(Array $data);

    /**
     * Update existing group
     * @param $group_id
     * @param array $data
     * @return Group
     */
    public function update($group_id, Array $data);

    /**
     * Remove an existing group
     * @param $group_id
     */
    public function destroy($group_id);
}