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

        if (!is_null($user->subscription) && !is_null($user->subscription->subscriptionCriteria)) {
            $criteria = $user->subscription->subscriptionCriteria;
            if (isset($criteria->product) && $criteria->product != 0) {


                $categoryProductNamesCombinations = $user->categories->mapWithKeys(function ($category) {
                    $output = [];
                    foreach ($category->products as $product) {
                        array_push($output, $category->category_name . "$#$" . $product->product_name);
                    }
                    return $output;
                });

                $collectedProducts = collect($products);

                $importingCategoryProductNameCombinations = $collectedProducts->map(function ($product) {
                    return array_get($product, 'category') . "$#$" . array_get($product, 'product');
                });

                $newItems = $importingCategoryProductNameCombinations->diff($categoryProductNamesCombinations);

                if (array_has($data, 'no_new_categories') && array_get($data, 'no_new_categories') == "true") {
                    $uniqueCategories = $user->categories->pluck('category_name')->unique();
                    $importingUniqueCategories = $collectedProducts->pluck('category')->unique();
                    $diffCategory = $importingUniqueCategories->diff($uniqueCategories);
                    if ($diffCategory->count() > 0) {
                        $errors->push('Categories: ' . $diffCategory->implode(', ') . " are not in your account.");
                    }
                }

                if (array_has($data, 'no_new_products') && array_get($data, 'no_new_products') == "true") {
                    $newProductsString = $newItems->map(function ($collectedString) {
                        list($category, $product) = explode('$#$', $collectedString);
                        return "Product-{$product} in Category-{$category}, ";
                    });
                    if ($newItems->count() > 0) {
                        $errorMsg = $newProductsString->implode(', ');
                        $errorMsg = strlen($errorMsg) > 50 ? substr($errorMsg, 0, 50) . "..." : $errorMsg;
                        $errors->push($errorMsg . " are not in your account.");
                    }
                }

                $totalProductsAfterImport = $newItems->count() + $user->numberOfProducts;
                if ($totalProductsAfterImport > $criteria->product) {
                    $errors->push('Number of products exceeds your subscription limitation. Please upgrade to import more products.');
                }
            }
        }
        return $errors;
    }

    protected function validateUrl(array $data = [])
    {


        $user = auth()->user();

        $file = array_get($data, 'file');
        $urls = $this->loadExcelFile($file);

        $errors = $this->validateProduct($data);

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

        if (!is_null($user->subscription) && !is_null($user->subscription->subscriptionCriteria)) {
            $criteria = $user->subscription->subscriptionCriteria;
            if (isset($criteria->site) && $criteria->site != 0) {

                $categoryProductSiteCombinations = $user->categories->mapWithKeys(function ($category) {
                    $keyedProducts = $category->products->mapWithKeys(function ($product) {
                        return [$product->product_name => $product->sites->pluck('siteUrl')];
                    });
                    $output = [];
                    foreach ($keyedProducts as $product_name => $sites) {
                        array_set($output, $category->category_name . "$#$" . $product_name, $sites);
                    }
                    return $output;
                });
                foreach ($urls as $url) {
                    $category_name = array_get($url, 'category');
                    $product_name = array_get($url, 'product');
                    $site_url = array_get($url, 'url');
                    $key = "{$category_name}\$#\${$product_name}";
                    if ($categoryProductSiteCombinations->has($key)) {
                        $categoryProductSiteCombinations->get($key)->push($site_url);
                        $categoryProductSiteCombinations->put($key, $categoryProductSiteCombinations->get($key)->unique());
                    } else {
                        $categoryProductSiteCombinations->put($key, collect([
                            $site_url
                        ]));
                    }
                }

                foreach ($categoryProductSiteCombinations as $categoryProductSiteCombination => $sites) {
                    if ($sites->count() > $criteria->site) {
                        list($category_name, $product_name) = explode('$#$', $categoryProductSiteCombination);
                        $errors->push("Number of URLs exceeds your subscription limitation in Category {$category_name}, Product {$product_name}. Please upgrade to import more URLs.");
                    }
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