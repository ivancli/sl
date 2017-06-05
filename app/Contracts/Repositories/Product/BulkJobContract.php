<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 5/06/2017
 * Time: 11:47 AM
 */

namespace App\Contracts\Repositories\Product;


use App\Models\BulkJob;

interface BulkJobContract
{
    /**
     * Load all bulk jobs
     * @param array $data
     * @return mixed
     */
    public function all(array $data = []);

    /**
     * Create a new bulk job
     * @param array $data
     * @return BulkJob
     */
    public function store(array $data = []);

}