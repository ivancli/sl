<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20/02/2017
 * Time: 10:08 PM
 */

namespace App\Repositories\UserManagement;


use App\Contracts\Repositories\UserManagement\RoleContract;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleRepository implements RoleContract
{
    var $role;
    var $request;

    public function __construct(Role $role, Request $request)
    {
        $this->role = $role;
        $this->request = $request;
    }

    /**
     * Load all roles and filter them
     * @param array $data
     * @return mixed
     */
    public function filterAll(array $data = [])
    {
        $length = array_get($data, 'per_page', 25);
        $orderByColumn = array_get($data, 'orderBy', 'id');
        $orderByDirection = array_get($data, 'direction', 'asc');
        $builder = $this->role;
        $builder = $builder->orderBy($orderByColumn, $orderByDirection);
        if (array_has($data, 'key') && !empty(array_get($data, 'key'))) {
            $key = array_get($data, 'key');
            $builder->where('id', 'LIKE', "%{$key}%");
            $builder->orWhere('name', 'LIKE', "%{$key}%");
            $builder->orWhere('display_name', 'LIKE', "%{$key}%");
            $builder->orWhere('description', 'LIKE', "%{$key}%");
        }
        $roles = $builder->paginate($length);
        if ($roles->count() == 0) {
            $page = 1;
            $this->request->merge(compact(['page']));
            $roles = $builder->paginate($length);
        }
        return $roles;
    }

    /**
     * Load all roles
     * @return mixed
     */
    public function all()
    {
        return $this->role->all();
    }

    /**
     * Get role by ID
     * @param $role_id
     * @param bool $throw
     * @return Role
     */
    public function get($role_id, $throw = true)
    {
        if ($throw) {
            return $this->role->findOrFail($role_id);
        } else {
            return $this->role->find($role_id);
        }
    }

    /**
     * Create new role
     * @param array $data
     * @return Role
     * @throws Exception
     */
    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $role = $this->role->create($data);
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        DB::commit();
        return $role;
    }

    /**
     * Update existing role
     * @param Role $role
     * @param array $data
     * @return Role
     * @throws Exception
     */
    public function update(Role $role, array $data)
    {
        DB::beginTransaction();
        try {
            $role->update($data);
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        DB::commit();
        return $role;
    }

    /**
     * Remove an existing role
     * @param Role $role
     * @return bool
     * @throws Exception
     */
    public function destroy(Role $role)
    {
        DB::beginTransaction();
        try {
            $result = $role->delete();
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        DB::commit();
        return $result;
    }

    /**
     * Update permission of a role
     * @param Role $role
     * @param array $permissions
     * @return mixed
     */
    public function updatePermissions(Role $role, array $permissions)
    {
        $role->detachPermissions();
        $role->attachPermissions($permissions);
    }
}