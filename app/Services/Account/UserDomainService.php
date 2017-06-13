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
        $siteUrls = $user->sites->pluck('siteUrl');
        $domains = collect();
        foreach ($siteUrls as $siteUrl) {
            $domains->push(domain($siteUrl));
        }
        $domains = $domains->unique();

        $domains->each(function ($domain) use (&$userDomains) {
            $exist = $userDomains->filter(function ($userDomain) use ($domain) {
                return $userDomain->domain == $domain;
            })->count();
            if ($exist == false) {
                $newUserDomain = new \stdClass();
                $newUserDomain->domain = $domain;
                $newUserDomain->alias = null;
                $userDomains->push($newUserDomain);
            }
        });

        return $userDomains;
    }

    public function store(array $data)
    {
        $user = auth()->user();
        $domains = $this->userRepo->updateUserDomains($user, $data);
        return $domains;
    }
}