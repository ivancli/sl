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
use Illuminate\Http\Request;

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
    public function filterAll(Array $data = [])
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
        // TODO: Implement all() method.
    }

    /**
     * Get role by ID
     * @param $role_id
     * @param bool $throw
     * @return Role
     */
    public function get($role_id, $throw = true)
    {
        // TODO: Implement get() method.
    }

    /**
     * Create new role
     * @param array $data
     * @return Role
     */
    public function store(Array $data)
    {
        // TODO: Implement store() method.
    }

    /**
     * Update existing role
     * @param $role_id
     * @param array $data
     * @return Role
     */
    public function update($role_id, Array $data)
    {
        // TODO: Implement update() method.
    }

    /**
     * Remove an existing role
     * @param $role_id
     */
    public function destroy($role_id)
    {
        // TODO: Implement destroy() method.
    }
}