<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 26/05/2017
 * Time: 10:18 AM
 */

namespace App\Repositories\Report;


use App\Contracts\Repositories\Report\HistoricalReportContract;
use App\Models\HistoricalReport;
use Illuminate\Http\Request;

class HistoricalReportRepository implements HistoricalReportContract
{
    protected $request;
    protected $historicalReport;

    public function __construct(Request $request, HistoricalReport $historicalReport)
    {
        $this->request = $request;
        $this->historicalReport = $historicalReport;
    }

    /**
     * load all / filtered historical reports
     * @param array $data
     * @return mixed
     */
    public function filterAll(array $data = [])
    {
        $length = array_get($data, 'per_page', 25);
        $orderByColumn = array_get($data, 'orderBy', 'id');
        $orderByDirection = array_get($data, 'direction', 'asc');
        $builder = auth()->user()->historicalReports()->with(['reportable']);
        $builder = $builder->orderBy($orderByColumn, $orderByDirection);
        if (array_has($data, 'key') && !empty(array_get($data, 'key'))) {
            $key = array_get($data, 'key');
            $builder->where('file_name', 'LIKE', "%{$key}%");
            $builder->orWhere('created_at', 'LIKE', "%{$key}%");
        }
        $historicalReports = $builder->paginate($length);
        if ($historicalReports->count() == 0) {
            $page = 1;
            $this->request->merge(compact(['page']));
            $historicalReports = $builder->paginate($length);
        }
        return $historicalReports;
    }

    /**
     *
     * @param array $data
     * @return \App\Models\HistoricalReport
     */
    public function store(array $data)
    {
        $historicalRepo = $this->historicalReport->create($data);
        return $historicalRepo;
    }
}