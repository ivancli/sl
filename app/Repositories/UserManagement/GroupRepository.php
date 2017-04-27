<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/20/2017
 * Time: 4:41 PM
 */

namespace App\Repositories\UserManagement;


use App\Contracts\Repositories\UserManagement\GroupContract;
use App\Models\Group;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupRepository implements GroupContract
{
    var $group;
    var $request;

    public function __construct(Group $group, Request $request)
    {
        $this->group = $group;

        $this->request = $request;
    }

    /**
     * Load all groups and filter them
     * @param array $data
     * @return mixed
     */
    public function filterAll(array $data = [])
    {
        $length = array_get($data, 'per_page', 25);
        $orderByColumn = array_get($data, 'orderBy', 'id');
        $orderByDirection = array_get($data, 'direction', 'asc');
        $builder = $this->group;
        $builder = $builder->orderBy($orderByColumn, $orderByDirection);
        if (array_has($data, 'key') && !empty(array_get($data, 'key'))) {
            $key = array_get($data, 'key');
            $builder->where('id', 'LIKE', "%{$key}%");
            $builder->orWhere('name', 'LIKE', "%{$key}%");
            $builder->orWhere('display_name', 'LIKE', "%{$key}%");
            $builder->orWhere('description', 'LIKE', "%{$key}%");
        }
        $groups = $builder->paginate($length);
        if ($groups->count() == 0) {
            $page = 1;
            $this->request->merge(compact(['page']));
            $groups = $builder->paginate($length);
        }
        return $groups;
    }

    /**
     * Load all groups
     * @return mixed
     */
    public function all()
    {
        return $this->group->all();
    }

    /**
     * Get group by ID
     * @param $group_id
     * @param bool $throw
     * @return Group
     */
    public function get($group_id, $throw = true)
    {
        if ($throw) {
            return $this->group->findOrFail($group_id);
        } else {
            return $this->group->find($group_id);
        }
    }

    /**
     * Create new group
     * @param array $data
     * @return Group
     * @throws Exception
     */
    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $group = $this->group->create($data);
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        DB::commit();
        return $group;
    }

    /**
     * Update existing group
     * @param Group $group
     * @param array $data
     * @return Group
     * @throws Exception
     */
    public function update(Group $group, array $data)
    {
        DB::beginTransaction();
        try {
            $group->update($data);
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        DB::commit();
        return $group;
    }

    /**
     * Remove an existing group
     * @param Group $group
     * @return bool|null
     * @throws Exception
     */
    public function destroy(Group $group)
    {
        DB::beginTransaction();
        try {
            $result = $group->delete();
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        DB::commit();
        return $result;
    }
}