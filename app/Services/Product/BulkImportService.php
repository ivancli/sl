<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 5/06/2017
 * Time: 11:47 AM
 */

namespace App\Services\Product;


use App\Contracts\Repositories\Product\BulkJobContract;
use App\Validators\Product\BulkImport\StoreValidator;
use Maatwebsite\Excel\Facades\Excel;

class BulkImportService
{
    #region repositories

    protected $bulkJobRepo;

    #endregion

    #region validators

    protected $storeValidator;

    #endregion

    public function __construct(BulkJobContract $bulkJobContract, StoreValidator $storeValidator)
    {
        #region repositories binding
        $this->bulkJobRepo = $bulkJobContract;
        #endregion

        #region validators binding
        $this->storeValidator = $storeValidator;
        #endregion
    }

    public function load(array $data = [])
    {
        $user = auth()->user();
        if (array_get($data, 'last') == "true") {
            $bulkJobs = $user->bulkJobs()->orderBy('id', 'desc')->first();
        } else {
            $bulkJobs = $user->bulkJobs()->where('archived', 'n')->get();
        }
        return $bulkJobs;
    }

    public function store(array $data = [])
    {
        $user = auth()->user();

        /*TODO add validation*/
        $this->storeValidator->validate($data);

        $file = array_get($data, 'file');
        $records = $this->loadExcelFile($file);

        /*TODO format data*/
        switch (array_get($data, 'type')) {
            case 'product':
                $errors = $this->validateProduct($data);
                break;
            case 'url':
                $errors = $this->validateUrl($data);
                break;
            default:
                $errors = collect();
        }

        if ($errors->count() > 0) {
            $status = false;
            return compact(['errors', 'status']);
        }

        $chunks = ceil(count($records) / config('bulk_job.import.chunk_size', 500));

        array_set($data, 'chunks', $chunks);
        array_set($data, 'completed', 0);
        array_set($data, 'content', json_encode($records));
        array_set($data, 'status', 'waiting');
        array_set($data, 'file_name', $file->getClientOriginalName());

        $bulkJob = $this->bulkJobRepo->store($data);

        $user->bulkJobs()->save($bulkJob);
        $status = true;
        return compact(['status', 'bulkJob']);
    }

    protected function validateProduct(array $data = [])
    {
        $user = auth()->user();

        $file = array_get($data, 'file');
        $products = $this->loadExcelFile($file);

        $errors = collect();

        if (is_null($products) || !is_array($products)) {
            $errors->push('Excel file is not in a correct format.');
            return $errors;
        }

        /* validate product and categories*/
        foreach ($products as $index => $product) {
            $rowNumber = $index + 1;
            if (!array_has($product, 'category') || empty(array_get($product, 'category'))) {
                $errors->push('Category is missing in row# ' . $rowNumber);
            }
            if (!array_has($product, 'product') || empty(array_get($product, 'product'))) {
                $errors->push('Product is missing in row# ' . $rowNumber);
            }
        }

        $collectedProduct = collect($products);

        $numberOfImportProducts = $collectedProduct->pluck('product')->unique()->count();

        /*TODO validation need to be enhance here*/

        if (!is_null($user->subscription) && !is_null($user->subscription->subscriptionCriteria)) {
            $criteria = $user->subscription->subscriptionCriteria;
            if (isset($criteria->product) && $criteria->product != 0) {
                $totalProductsAfterImport = $numberOfImportProducts + $user->numberOfProducts;
                if ($totalProductsAfterImport > $criteria->product) {
                    $errors->push('Number of products exceeds your subscription limitation. Please upgrade to import more products.');
                }
            }
        }
        return $errors;
    }

    protected function validateUrl(array $data = [])
    {

        /*TODO improve validation here*/

        $user = auth()->user();

        $file = array_get($data, 'file');
        $urls = $this->loadExcelFile($file);

        $errors = collect();

        if (is_null($urls) || !is_array($urls)) {
            $errors->push('Excel file is not in a correct format.');
            return $errors;
        }

        /* validate product and categories*/
        foreach ($urls as $index => $url) {
            $rowNumber = $index + 1;
            if (!array_has($url, 'category') || empty(array_get($url, 'category'))) {
                $errors->push('Category is missing in row# ' . $rowNumber);
            }
            if (!array_has($url, 'product') || empty(array_get($url, 'product'))) {
                $errors->push('Product is missing in row# ' . $rowNumber);
            }
        }

        $collectedProduct = collect($urls);

        $numberOfImportProducts = $collectedProduct->pluck('product')->unique()->count();

        if (!is_null($user->subscription) && !is_null($user->subscription->subscriptionCriteria)) {
            $criteria = $user->subscription->subscriptionCriteria;
            if (isset($criteria->product) && $criteria->product != 0) {
                $totalProductsAfterImport = $numberOfImportProducts + $user->numberOfProducts;
                if ($totalProductsAfterImport > $criteria->product) {
                    $errors->push('Number of products exceeds your subscription limitation. Please upgrade to import more products.');
                }
            }
        }
        return $errors;
    }

    /**
     * load data from excel file
     * @param $file
     * @return null
     */
    protected function loadExcelFile($file)
    {
        $result = null;
        Excel::load($file->getPathname(), function ($reader) use (&$result) {
            $dataSet = $reader->all();
            $outputDataSet = [];
            foreach ($dataSet as $index => $data) {
                $outputData = $data->all();
                $outputDataSet [] = $outputData;
            }
            $result = $outputDataSet;
        }, 'Windows-1252');
        return $result;
    }
}