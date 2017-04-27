<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/17/2017
 * Time: 5:30 PM
 */

namespace App\Repositories\Admin;


use App\Contracts\Repositories\Admin\UserActivityLogContract;
use App\Models\LoggingModels\UserActivityLog;
use Illuminate\Http\Request;

class UserActivityLogRepository implements UserActivityLogContract
{
    protected $request;
    protected $userActivityLog;

    public function __construct(Request $request, UserActivityLog $userActivityLog)
    {
        $this->request = $request;
        $this->userActivityLog = $userActivityLog;
    }

    /**
     * Load all user activity logs
     * @param array $data
     * @return mixed
     */
    public function filterAll(array $data = [])
    {
        $length = array_get($data, 'per_page', 25);
        $orderByColumn = array_get($data, 'orderBy', 'id');
        $orderByDirection = array_get($data, 'direction', 'asc');
        $builder = $this->userActivityLog;
        $builder = $builder->orderBy($orderByColumn, $orderByDirection);
        if (array_has($data, 'key') && !empty(array_get($data, 'key'))) {
            $key = array_get($data, 'key');
            $builder->where('id', 'LIKE', "%{$key}%");
            $builder->orWhere('activity', 'LIKE', "%{$key}%");
            $builder->orWhere('created_at', 'LIKE', "%{$key}%");
            $builder->orWhere('updated_at', 'LIKE', "%{$key}%");
        }
        $userActivityLogs = $builder->paginate($length);
        if ($userActivityLogs->count() == 0) {
            $page = 1;
            $this->request->merge(compact(['page']));
            $userActivityLogs = $builder->paginate($length);
        }
        return $userActivityLogs;
    }

    /**
     * Create new user activity log
     * @param array $data
     * @return mixed
     */
    public function store(array $data = [])
    {
        $userActivityLog = new $this->userActivityLog;
        $userActivityLog->activity = $data['activity'];
        $userActivityLog->save();
        return $userActivityLog;
    }
}