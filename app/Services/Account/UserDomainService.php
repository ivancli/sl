<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 28/05/2017
 * Time: 1:49 AM
 */

namespace App\Services\Account;


use App\Contracts\Repositories\UserManagement\UserContract;

class UserDomainService
{
    #region repositories
    protected $userRepo;

    #endregion

    public function __construct(UserContract $userContract)
    {
        $this->userRepo = $userContract;
    }

    public function load()
    {
        $user = auth()->user();
        $userDomains = $user->domains;
        return $userDomains;
    }

    public function store(array $data)
    {
        $user = auth()->user();
        $domains = $this->userRepo->updateUserDomains($user, $data);
        return $domains;
    }
}