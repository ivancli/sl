<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 5/06/2017
 * Time: 11:46 AM
 */

namespace App\Http\Controllers\Product;


use App\Http\Controllers\Controller;
use App\Services\Product\BulkImportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BulkImportController extends Controller
{
    protected $request;
    protected $bulkImportService;

    public function __construct(Request $request, BulkImportService $bulkImportService)
    {
        $this->request = $request;

        $this->bulkImportService = $bulkImportService;
    }

    public function index()
    {
        $bulkJobs = $this->bulkImportService->load($this->request->all());
        $status = true;

        return compact(['bulkJobs', 'status']);

    }

    public function store()
    {

        $file = $this->request->file('file');
        $this->request->merge(compact(['file']));
        $result = $this->bulkImportService->store($this->request->all());
        if (array_get($result, 'status') !== true) {
            return new JsonResponse(array_get($result, 'errors'), 422);
        }

        $status = true;

        return $result;
    }
}