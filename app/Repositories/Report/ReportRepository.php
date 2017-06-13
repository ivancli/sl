<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 25/05/2017
 * Time: 11:20 AM
 */

namespace App\Repositories\Report;


use App\Contracts\Repositories\Report\HistoricalReportContract;
use App\Contracts\Repositories\Report\ReportContract;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportRepository implements ReportContract
{
    protected $report;

    protected $historicalReportRepo;

    protected $request;

    public function __construct(Report $report, HistoricalReportContract $historicalReportContract, Request $request)
    {
        $this->report = $report;

        $this->historicalReportRepo = $historicalReportContract;

        $this->request = $request;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function filterAll(array $data = [])
    {

        $length = array_get($data, 'per_page', 25);
        $orderByColumn = array_get($data, 'orderBy', 'id');
        $orderByDirection = array_get($data, 'direction', 'asc');
        $builder = auth()->user()->reports()->with(['reportable', 'historicalReports']);
        $builder = $builder->orderBy($orderByColumn, $orderByDirection);
        if (array_has($data, 'key') && !empty(array_get($data, 'key'))) {
            $key = array_get($data, 'key');
            $builder->where('id', 'LIKE', "%{$key}%");
            $builder->orWhere('frequency', 'LIKE', "%{$key}%");
            $builder->orWhere('report_type', 'LIKE', "%{$key}%");
            $builder->orWhere('date', 'LIKE', "%{$key}%");
            $builder->orWhere('day', 'LIKE', "%{$key}%");
            $builder->orWhere('time', 'LIKE', "%{$key}%");
            $builder->orWhere('weekday_only', 'LIKE', "%{$key}%");
            $builder->orWhere('show_all', 'LIKE', "%{$key}%");
            $builder->orWhere('last_active_at', 'LIKE', "%{$key}%");
        }
        $reports = $builder->paginate($length);
        if ($reports->count() == 0) {
            $page = 1;
            $this->request->merge(compact(['page']));
            $reports = $builder->paginate($length);
        }
        return $reports;
    }

    /**
     * Get all reports
     * @param array $data
     * @return mixed
     */
    public function all(array $data = [])
    {
        if (auth()->check()) {
            return auth()->user()->reports;
        } else {
            return $this->report->all();
        }
    }

    /**
     * Get report by report ID
     * @param $report_id
     * @param bool $throw
     * @return Report
     */
    public function get($report_id, $throw = true)
    {
        if ($throw == true) {
            return $this->report->findOrFail($report_id);
        } else {
            return $this->report->find($report_id);
        }
    }

    /**
     * create an report
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        $report = Report::create($data);
        return $report;
    }

    /**
     * update reports
     * @param array $data
     * @return mixed
     */
    public function massStore(array $data)
    {
        // TODO: Implement massStore() method.
    }

    /**
     * Update an existing report
     * @param Report $report
     * @param array $data
     * @return mixed
     */
    public function update(Report $report, array $data)
    {
        $report->update($data);
        return $report;
    }

    /**
     * Generate a report
     * @param Report $report
     * @return mixed
     */
    public function generate(Report $report)
    {
        switch ($report->report_type) {
            case 'product':
                switch ($report->reportable_type) {
                    case 'product':
                        $historicalReport = $this->_generateProductProductReport($report);
                        return $historicalReport;
                        break;
                    case 'category':
                        $historicalReport = $this->_generateProductCategoryReport($report);
                        return $historicalReport;
                        break;
                }
                break;
            case 'digest':
                $reportDetail = $this->_generateDigestReport($report);
                return $reportDetail;
                break;
        }
    }

    private function _generateProductProductReport(Report $report)
    {
        $product = $report->reportable;
        $user = $report->user;
        $userDomains = $user->domains->pluck('alias', 'domain')->all();

        $product->sites->each(function ($site) use ($userDomains) {
            $siteDomain = domain($site->siteUrl);

            if (!is_null($site->item) && !is_null($site->item->sellerUsername)) {
                $site->setAttribute('displayName', "eBay: {$site->item->sellerUsername}");
            } elseif (array_has($userDomains, $siteDomain) && !is_null(array_get($userDomains, $siteDomain))) {
                $site->setAttribute('displayName', array_get($userDomains, $siteDomain));
            } else {
                $site->setAttribute('displayName', $site->url->domainFullPath);
            }
        });

        $fileName = sanitizeFileName($product->product_name) . "_product_report";
        $excel = Excel::create($fileName, function ($excel) use ($product) {
            $excel->sheet($product->product_name, function ($sheet) use ($product) {
                $sheet->loadview('excel.reports.product_product_report', compact(['product']));
                $sheet->setColumnFormat(array(
                    'E' => \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
                    'F' => \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2,
                    'G' => \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
                    'H' => \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2,
                ));
                $sheet->setWidth('A', 30);
                $sheet->setWidth('B', 30);
                $sheet->setWidth('C', 30);
                $sheet->setWidth('D', 20);
                $sheet->setWidth('E', 20);
                $sheet->setWidth('F', 20);
                $sheet->setWidth('G', 20);
                $sheet->setWidth('H', 20);
            });
        });
        $excelFileContent = $excel->string('xlsx');
        $binaryExcelFileContent = base64_encode($excelFileContent);

        $historicalReport = $this->historicalReportRepo->store([
            'file_name' => $fileName,
            'content' => $binaryExcelFileContent
        ]);
        $user->historicalReports()->save($historicalReport);
        $report->historicalReports()->save($historicalReport);
        $product->historicalReports()->save($historicalReport);
        return $historicalReport;
    }

    private function _generateProductCategoryReport(Report $report)
    {
        $category = $report->reportable;
        $user = $report->user;

        $userDomains = $user->domains->pluck('alias', 'domain')->all();

        $category->products->each(function ($product) use ($userDomains) {
            $product->sites->each(function ($site) use ($userDomains) {
                $siteDomain = domain($site->siteUrl);

                if (!is_null($site->item) && !is_null($site->item->sellerUsername)) {
                    $site->setAttribute('displayName', "eBay: {$site->item->sellerUsername}");
                } elseif (array_has($userDomains, $siteDomain) && !is_null(array_get($userDomains, $siteDomain))) {
                    $site->setAttribute('displayName', array_get($userDomains, $siteDomain));
                } else {
                    $site->setAttribute('displayName', $site->url->domainFullPath);
                }
            });
        });

        $fileName = sanitizeFileName($category->category_name) . "_category_report";
        $excel = Excel::create($fileName, function ($excel) use ($category) {
            $excel->sheet($category->category_name, function ($sheet) use ($category) {
                $sheet->loadview('excel.reports.product_category_report', compact(['category']));
                $sheet->setColumnFormat(array(
                    'E' => \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
                    'F' => \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2,
                    'G' => \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
                    'H' => \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2,
                ));
                $sheet->setWidth('A', 30);
                $sheet->setWidth('B', 30);
                $sheet->setWidth('C', 30);
                $sheet->setWidth('D', 20);
                $sheet->setWidth('E', 20);
                $sheet->setWidth('F', 20);
                $sheet->setWidth('G', 20);
                $sheet->setWidth('H', 20);
            });
        });
        $excelFileContent = $excel->string('xlsx');
        $binaryExcelFileContent = base64_encode($excelFileContent);

        $historicalReport = $this->historicalReportRepo->store([
            'file_name' => $fileName,
            'content' => $binaryExcelFileContent
        ]);
        $user->historicalReports()->save($historicalReport);
        $report->historicalReports()->save($historicalReport);
        $category->historicalReports()->save($historicalReport);

        return $historicalReport;
    }

    private function _generateDigestReport(Report $report)
    {

        $user = $report->user;
        $userDomains = $user->domains->pluck('alias', 'domain')->all();
        $frequency = $report->frequency;
        $now = Carbon::now();
        $cancelled_at = null;
        $type = "enterprise";

        if (!is_null($user->subscription)) {
            if (!is_null($user->subscription->cancelled_at)) {
                $cancelled_at = $user->subscription->cancelled_at;
            }
            if (!is_null($user->subscription->subscriptionPlan)) {
                $type = $user->subscription->subscriptionPlan->handle;
            }
        }

        $products = $user->products;

        $totalProducts = $user->products()->count();


        $cheapestProduct = DB::table('cheapest_sites')
            ->join('products', 'products.id', 'cheapest_sites.product_id')
            ->join('urls', 'urls.id', 'cheapest_sites.url_id')
            ->join('items', 'cheapest_sites.item_id', 'items.id')
            ->join('item_metas', function ($join) {
                $join->on('item_metas.item_id', 'items.id')
                    ->where('item_metas.element', 'SELLER_USERNAME');
            })
            ->join('users', 'users.id', 'products.user_id')
            ->join('user_metas', 'user_metas.user_id', 'users.id')
            ->where('products.user_id', $user->getKey())
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereNotNull('item_metas.value')
                        ->where('user_metas.ebay_username', 'item_metas.value');
                })->orWhere(function ($query) {
                    $query->whereNotNull('user_metas.company_url')
                        ->where('urls.full_path', 'LIKE', DB::raw('CONCAT("%", user_metas.company_url, "%")'));
                });
            })
            ->select('cheapest_sites.*');


        $mostExpensiveProducts = DB::table('most_expensive_sites')
            ->join('products', 'products.id', 'most_expensive_sites.product_id')
            ->join('urls', 'urls.id', 'most_expensive_sites.url_id')
            ->join('items', 'most_expensive_sites.item_id', 'items.id')
            ->join('item_metas', function ($join) {
                $join->on('item_metas.item_id', 'items.id')
                    ->where('item_metas.element', 'SELLER_USERNAME');
            })
            ->join('users', 'users.id', 'products.user_id')
            ->join('user_metas', 'user_metas.user_id', 'users.id')
            ->where('products.user_id', $user->getKey())
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereNotNull('item_metas.value')
                        ->where('user_metas.ebay_username', 'item_metas.value');
                })->orWhere(function ($query) {
                    $query->whereNotNull('user_metas.company_url')
                        ->where('urls.full_path', 'LIKE', DB::raw('CONCAT("%", user_metas.company_url, "%")'));
                });
            })
            ->select('most_expensive_sites.*');


        $failedCrawlSites = DB::table('failed_crawl_sites')
            ->join('products', 'failed_crawl_sites.product_id', 'products.id')
            ->where('products.user_id', $user->getKey());

        $cheapestProductCount = $cheapestProduct->count();
        $mostExpensiveProductCount = $mostExpensiveProducts->count();
        $crawlFailCount = $failedCrawlSites->count();

        $query = DB::table('sites')
            ->join('urls', 'urls.id', 'sites.url_id')
            ->join('products', 'sites.product_id', 'products.id')
            ->join('categories', 'products.category_id', 'categories.id')
            ->join('users', 'products.user_id', 'users.id')
            ->join('user_domains', function ($join) {
                $join->on('user_domains.user_id', 'users.id')
                    ->where('urls.full_path', 'LIKE', DB::raw("CONCAT('%', user_domains.domain, '%')"));
            })
            ->join('items', 'sites.item_id', 'items.id')
            ->join('item_metas AS price', function ($join) {
                $join->on('price.item_id', 'items.id')
                    ->where('price.element', 'PRICE');
            })
            ->leftJoin('item_metas AS ebay', function ($join) {
                $join->on('ebay.item_id', 'items.id')
                    ->where('ebay.element', 'SELLER_USERNAME');
            })
            ->leftJoin('my_sites', 'sites.id', 'my_sites.id');
        $addHour = 1;
        switch ($type) {
            case 'professional':
                $addHour = 12;
                $query->leftJoin('previous_prices_professional AS previous_price', function ($join) use ($frequency, $cancelled_at) {
                    $join->on('previous_price.item_meta_id', 'price.id');

                    switch ($frequency) {
                        case 'day':
                            $join->where("previous_price.created_at", '>=', Carbon::now()->subHours(24)->format('Y-m-d H:i:s'));
                            break;
                        case 'week':
                            $join->where("previous_price.created_at", '>=', Carbon::now()->subDays(7)->format('Y-m-d H:i:s'));
                            break;
                    }

                    if (!is_null($cancelled_at)) {
                        $join->where('previous_price.created_at', '<', $cancelled_at);
                    }
                });
                break;
            case 'business':
                $addHour = 4;
                $query->leftJoin('previous_prices_business AS previous_price', function ($join) use ($frequency, $cancelled_at) {
                    $join->on('previous_price.item_meta_id', 'price.id');

                    switch ($frequency) {
                        case 'day':
                            $join->where("previous_price.created_at", '>=', Carbon::now()->subHours(24)->format('Y-m-d H:i:s'));
                            break;
                        case 'week':
                            $join->where("previous_price.created_at", '>=', Carbon::now()->subDays(7)->format('Y-m-d H:i:s'));
                            break;
                    }

                    if (!is_null($cancelled_at)) {
                        $join->where('previous_price.created_at', '<', $cancelled_at);
                    }
                });
                break;
            case 'enterprise':
            default:
                $query->leftJoin('previous_prices_enterprise AS previous_price', function ($join) use ($frequency, $cancelled_at) {
                    $join->on('previous_price.item_meta_id', 'price.id');
                    switch ($frequency) {
                        case 'day':
                            $join->where("previous_price.created_at", '>=', Carbon::now()->subHours(24)->format('Y-m-d H:i:s'));
                            break;
                        case 'week':
                            $join->where("previous_price.created_at", '>=', Carbon::now()->subDays(7)->format('Y-m-d H:i:s'));
                            break;
                    }

                    if (!is_null($cancelled_at)) {
                        $join->where('previous_price.created_at', '<', $cancelled_at);
                    }
                });
        }

        $query->where('users.id', $user->getKey());

        $query->select([
            "products.product_name",
            "categories.category_name",
            "price.value AS recent_price",
            "ebay.value AS ebay_username",
            "previous_price.amount AS previous_price",
            "urls.full_path",
            "urls.status",
            DB::raw("DATE_ADD(previous_price.created_at, INTERVAL +{$addHour} HOUR) AS price_changed_at")
//            DB::raw("my_sites.id IS NOT NULL AS is_my_site"),
//            DB::raw("SUBSTRING_INDEX(REPLACE(REPLACE(REPLACE(urls.full_path,'http://',''),'https://',''),'www.',''),'/',1) AS domain"),
//            DB::raw("IFNULL(ebay.value, IFNULL(user_domains.alias, SUBSTRING_INDEX(REPLACE(REPLACE(REPLACE(urls.full_path,'http://',''),'https://',''),'www.',''),'/',1))) AS display_name"),
        ]);

        $countQuery = clone $query;
        $countQuery->where(function ($query) {
            $query->whereNotNull('previous_price.id');
        });
        $priceChangeCount = $countQuery->count();

        if ($report->show_all == 'n') {
            $query->where(function ($query) {
                $query->where('urls.status', 'crawl_failed')
                    ->orWhere(function ($query) {
                        $query->whereNotNull('previous_price.amount');
                    });
            });
        }

        $query->orderBy('products.id', 'sites.id');


        $displayProducts = $query->get();

        $ebayUsername = $user->metas->ebay_username;
        $companyUrl = $user->metas->company_url;


        $displayProducts->each(function ($product) use ($ebayUsername, $companyUrl, $userDomains) {
            if (!is_null($product->ebay_username)) {
                $product->display_name = $product->ebay_username;
            } elseif (!is_null(array_get($userDomains, domain($product->full_path)))) {
                $product->display_name = array_get($userDomains, domain($product->full_path));
            } else {
                $product->display_name = domain($product->full_path);
            }


            if (!is_null($ebayUsername)) {
                if ($ebayUsername == $product->ebay_username) {
                    $product->is_my_site = true;
                    return true;
                }
            }

            if (!is_null($companyUrl)) {
                if (str_contains($product->full_path, $companyUrl)) {
                    $product->is_my_site = true;
                    return true;
                }
            }
            $product->is_my_site = false;
        });


        $reportDetail = collect();

        $reportDetail->put('cheapest_product_count', $cheapestProductCount);
        $reportDetail->put('most_expensive_product_count', $mostExpensiveProductCount);
        $reportDetail->put('crawl_fail_count', $crawlFailCount);
        $reportDetail->put('price_change_count', $priceChangeCount);
        $reportDetail->put('products', $displayProducts);
        #region generate excel
        /*TODO generate excel spreadsheet*/
        #endregion

        return $reportDetail;
    }
}