<?php
namespace App\Repositories\UserManagement;

use App\Contracts\Repositories\UserManagement\UserContract;
use App\Models\User;

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 19/02/2017
 * Time: 10:15 PM
 */
class UserRepository implements UserContract
{
    var $user;

    public function __construct(User $user)
    {
        $this->user = $user;
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
    public function get($user_id, $throw = true)
    {
        if ($throw) {
            return $this->user->findOrFail($user_id);
        } else {
            return $this->user->find($user_id);
        }
    }

    /**
     * Create new user
     * @param array $data
     * @return mixed
     */
    public function store(Array $data)
    {
        $user = new $this->user;
        $user->first_name = array_get($data, 'first_name');
        $user->last_name = array_get($data, 'last_name');
        $user->email = array_get($data, 'email');
        $user->password = bcrypt(array_get($data, 'password', 'secret'));
        $user->save();
        return $user;
    }

    /**
     * Update existing user
     * @param $user_id
     * @param array $data
     * @return mixed
     */
    public function update($user_id, Array $data)
    {
        $user = $this->get($user_id);
        $data = array_except($data, ['password']);
        $user->update($data);
        return $user;
    }

    /**
     * Remove an existing user
     * @param $user_id
     * @return mixed
     */
    public function destroy($user_id)
    {
        $user = $this->get($user_id);
        $user->delete();
    }
}