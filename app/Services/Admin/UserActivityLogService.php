<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 18/04/2017
 * Time: 10:59 PM
 */

namespace App\Services\Admin;


use App\Contracts\Repositories\Admin\UserActivityLogContract;

class UserActivityLogService
{
    #region repositories

    protected $userActivityLogRepo;

    #endregion

    public function __construct(UserActivityLogContract $userActivityLogContract)
    {
        #region repositories binding
        $this->userActivityLogRepo = $userActivityLogContract;
        #endregion
    }

    /**
     * get all/filtered user activity logs
     * @param array $data
     * @return mixed
     */
    public function load(array $data = [])
    {
        $userActivityLogs = $this->userActivityLogRepo->filterAll($data);
        return $userActivityLogs;
    }
}