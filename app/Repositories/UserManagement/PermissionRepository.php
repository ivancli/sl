<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 21/02/2017
 * Time: 7:21 PM
 */

namespace App\Repositories\UserManagement;


use App\Contracts\Repositories\UserManagement\PermissionContract;
use App\Models\Permission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionRepository implements PermissionContract
{
    var $permission;
    var $request;

    public function __construct(Permission $permission, Request $request)
    {
        $this->permission = $permission;
        $this->request = $request;
    }

    /**
     * Load all permissions and filter them
     * @param array $data
     * @return mixed
     */
    public function filterAll(Array $data = [])
    {
        $length = array_get($data, 'per_page', 25);
        $orderByColumn = array_get($data, 'orderBy', 'id');
        $orderByDirection = array_get($data, 'direction', 'asc');
        $builder = $this->permission;
        $builder = $builder->orderBy($orderByColumn, $orderByDirection);
        if (array_has($data, 'key') && !empty(array_get($data, 'key'))) {
            $key = array_get($data, 'key');
            $builder->where('id', 'LIKE', "%{$key}%");
            $builder->orWhere('name', 'LIKE', "%{$key}%");
            $builder->orWhere('display_name', 'LIKE', "%{$key}%");
            $builder->orWhere('description', 'LIKE', "%{$key}%");
        }
        $permissions = $builder->paginate($length);
        if ($permissions->count() == 0) {
            $page = 1;
            $this->request->merge(compact(['page']));
            $permissions = $builder->paginate($length);
        }
        return $permissions;
    }

    /**
     * Load all permissions
     * @return mixed
     */
    public function all()
    {
        return $this->permission->all();
    }

    /**
     * Get permission by ID
     * @param $permission_id
     * @param bool $throw
     * @return Permission
     */
    public function get($permission_id, $throw = true)
    {
        if ($throw) {
            return $this->permission->findOrFail($permission_id);
        } else {
            return $this->permission->find($permission_id);
        }
    }

    /**
     * Create new permission
     * @param array $data
     * @return Permission
     * @throws Exception
     */
    public function store(Array $data)
    {
        DB::beginTransaction();
        try {
            $permission = $this->permission->create($data);
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        DB::commit();
        return $permission;
    }

    /**
     * Update existing permission
     * @param Permission $permission
     * @param array $data
     * @return Permission
     * @throws Exception
     */
    public function update(Permission $permission, Array $data)
    {
        DB::beginTransaction();
        try {
            $permission->update($data);
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        DB::commit();
        return $permission;
    }

    /**
     * Remove an existing permission
     * @param Permission $permission
     * @return bool|null
     * @throws Exception
     */
    public function destroy(Permission $permission)
    {
        DB::beginTransaction();
        try {
            $result = $permission->delete();
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        DB::commit();
        return $result;
    }
}