<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 5/06/2017
 * Time: 11:49 AM
 */

namespace App\Repositories\Product;


use App\Contracts\Repositories\Product\BulkJobContract;
use App\Models\BulkJob;

class BulkJobRepository implements BulkJobContract
{
    protected $bulkJobModel;

    public function __construct(BulkJob $bulkJob)
    {
        $this->bulkJobModel = $bulkJob;
    }


    /**
     * Load all bulk jobs
     * @param array $data
     * @return mixed
     */
    public function all(array $data = [])
    {
        if (auth()->check()) {
            $user = auth()->uesr();
            $queryBuilder = $user->bulkJobs();
            if (array_has($data, 'archived')) {
                $queryBuilder->where('archived', array_get($data, 'archived'));
            }
            if (array_has($data, 'status')) {
                $queryBuilder->where('status', array_get($data, 'status'));
            }
        } else {
            /*TODO fucking hell, fix this part pls*/
            $queryBuilder = $this->bulkJobModel->where('archived', 'n')
                ->where('status', 'waiting');
//            $queryBuilder = $this->bulkJobModel;
//            if (array_has($data, 'archived')) {
//                $queryBuilder->where('archived', array_get($data, 'archived'));
//            }
//            if (array_has($data, 'status')) {
//                $queryBuilder->where('status', array_get($data, 'status'));
//            }
        }
        return $queryBuilder->get();
    }

    /**
     * Create a new bulk job
     * @param array $data
     * @return BulkJob
     */
    public function store(array $data = [])
    {
        $bulkJob = $this->bulkJobModel->create($data);
        return $bulkJob;
    }
}