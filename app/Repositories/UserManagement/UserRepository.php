<?php

namespace App\Repositories\UserManagement;

use App\Contracts\Repositories\UserManagement\UserContract;
use App\Models\User;
use App\Models\UserDomain;
use App\Models\UserMeta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 19/02/2017
 * Time: 10:15 PM
 */
class UserRepository implements UserContract
{
    var $user;
    var $request;

    public function __construct(User $user, Request $request)
    {
        $this->user = $user;

        $this->request = $request;
    }

    /**
     * Load all users and filter them
     * @param $data
     * @return mixed
     */
    public function filterAll(array $data = [])
    {
        $length = array_get($data, 'per_page', 25);
        $orderByColumn = array_get($data, 'orderBy', 'id');
        $orderByDirection = array_get($data, 'direction', 'asc');
        $builder = $this->user;
        $builder = $builder->orderBy($orderByColumn, $orderByDirection);
        if (array_has($data, 'key') && !empty(array_get($data, 'key'))) {
            $key = array_get($data, 'key');
            $builder->where('id', 'LIKE', "%{$key}%");
            $builder->orWhere('first_name', 'LIKE', "%{$key}%");
            $builder->orWhere('last_name', 'LIKE', "%{$key}%");
            $builder->orWhere('email', 'LIKE', "%{$key}%");
            $builder->orWhere('created_at', 'LIKE', "%{$key}%");
            $builder->orWhere('updated_at', 'LIKE', "%{$key}%");
        }
        $users = $builder->paginate($length);
        if ($users->count() == 0) {
            $page = 1;
            $this->request->merge(compact(['page']));
            $users = $builder->paginate($length);
        }
        return $users;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->user->all();
    }

    /**
     * Get user by ID
     * @param $user_id
     * @param bool $throw
     * @return User
     */
    public function get($user_id, $throw = true, $with = [])
    {
        if ($throw) {
            if (!empty($with)) {
                return $this->user->with($with)->findOrFail($user_id);
            } else {
                return $this->user->findOrFail($user_id);
            }
        } else {
            if (!empty($with)) {
                return $this->user->with($with)->find($user_id);
            } else {
                return $this->user->find($user_id);
            }
        }
    }

    /**
     * Create new user
     * @param array $data
     * @return User
     * @throws Exception
     */
    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            array_set($data, 'password', bcrypt(array_get($data, 'password', 'secret')));
            $user = $this->user->create($data);
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        DB::commit();
        return $user;
    }

    /**
     * Update existing user
     * @param User $user
     * @param array $data
     * @return User
     * @throws Exception
     */
    public function update(User $user, array $data)
    {
        DB::beginTransaction();
        try {
            $data = array_except($data, ['email']);
            if (array_has($data, 'password') && !empty(array_get($data, 'password'))) {
                array_set($data, 'password', bcrypt(array_get($data, 'password')));
            }

            $user->update($data);
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        DB::commit();
        return $user;
    }

    /**
     * update user meta info
     * @param User $user
     * @param array $data
     * @return UserMeta
     * @throws Exception
     */
    public function updateMetas(User $user, array $data)
    {
        DB::beginTransaction();
        try {
            $metas = $user->metas->update($data);
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        DB::commit();
        return $metas;
    }

    /**
     * Remove an existing user
     * @param User $user
     * @return bool|void
     * @throws Exception
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            $result = $user->delete();
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        DB::commit();
        return $result;
    }

    /**
     * Update roles of a user
     * @param User $user
     * @param array $roles
     * @return mixed|void
     */
    public function updateRoles(User $user, array $roles)
    {
        $user->detachRoles();
        $user->attachRoles($roles);
    }

    /**
     * update user domains
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function updateUserDomains(User $user, array $data)
    {
        $domains = collect();
        foreach ($data as $userDomain) {
            $domainFullPath = array_get($userDomain, 'domain');
            $domain = $user->domains()->where('domain', $domainFullPath)->first();
            if (is_null($domain)) {
                $domain = $user->domains()->save(new UserDomain($userDomain));
                $domains->push($domain);
            } else {
                $domain->update($userDomain);
            }
            $domains->push($domain);
        }
        return $domains;
    }
}