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
        $cheapestProduct = DB::table('my_sites')
            ->join('products', 'products.id', 'my_sites.product_id')
            ->join('cheapest_sites', 'my_sites.id', 'cheapest_sites.id')
            ->where('products.user_id', $user->getKey())
            ->select('my_sites.*');
        $mostExpensiveProducts = DB::table('my_sites')
            ->join('products', 'products.id', 'my_sites.product_id')
            ->join('most_expensive_sites', 'my_sites.id', 'most_expensive_sites.id')
            ->where('products.user_id', $user->getKey())
            ->select('my_sites.*');
        $failedCrawlSites = DB::table('failed_crawl_sites')
            ->join('products', 'failed_crawl_sites.product_id', 'products.id')
            ->where('products.user_id', $user->getKey());
        $cheapestProductCount = $cheapestProduct->count();
        $mostExpensiveProductCount = $mostExpensiveProducts->count();
        $crawlFailCount = $failedCrawlSites->count();

        /*
         SELECT
products.product_name,
categories.category_name,
price.value AS recent_price,
previous_prices.amount AS previous_price,
urls.full_path,
SUBSTRING_INDEX(REPLACE(REPLACE(REPLACE(urls.full_path,'http://',''),'https://',''),'www.',''),'/',1) AS domain,
IFNULL(ebay.value, IFNULL(user_domains.alias, SUBSTRING_INDEX(REPLACE(REPLACE(REPLACE(urls.full_path,'http://',''),'https://',''),'www.',''),'/',1))) AS display_name,
DATE_FORMAT(STR_TO_DATE(previous_price_changes.created_at, '%Y-%m-%d %H:%i:%s'), '%u') week_of_year
FROM sites
JOIN urls ON(urls.id=sites.url_id)
JOIN products ON(sites.product_id=products.id)
JOIN categories ON(products.category_id=categories.id)
JOIN users ON(products.user_id=users.id)
JOIN user_domains ON(user_domains.user_id=users.id)
JOIN items ON(sites.item_id=items.id)
JOIN item_metas price ON(price.item_id=items.id AND price.element='PRICE')
LEFT JOIN item_metas ebay ON(ebay.item_id=items.id AND ebay.element='SELLER_USERNAME')
JOIN previous_prices_professional previous_prices ON(previous_prices.item_meta_id=price.id)
JOIN previous_price_changes_professional previous_price_changes ON(previous_price_changes.item_meta_id=price.id)

         * */
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

        switch ($frequency) {
            case 'day':
                $query->where("previous_price_changes.created_at", '>=', Carbon::now()->subHours(24)->format('Y-m-d H:i:s'));
                break;
            case 'week':
                $query->where("previous_price_changes.created_at", '>=', Carbon::now()->subDays(7)->format('Y-m-d H:i:s'));
                break;
        }

        switch ($type) {
            case 'professional':
                $query->leftJoin('previous_price_changes_professional AS previous_price_changes', 'previous_price_changes.item_meta_id', 'price.id');
                $query->leftJoin('previous_prices_professional AS previous_price', 'previous_price.item_meta_id', 'price.id');
                break;
            case 'business':
                $query->leftJoin('previous_price_changes_business AS previous_price_changes', 'previous_price_changes.item_meta_id', 'price.id');
                $query->leftJoin('previous_prices_business AS previous_price', 'previous_price.item_meta_id', 'price.id');
                break;
            case 'enterprise':
            default:
                $query->leftJoin('previous_price_changes_enterprise AS previous_price_changes', 'previous_price_changes.item_meta_id', 'price.id');
                $query->leftJoin('previous_prices_enterprise AS previous_price', 'previous_price.item_meta_id', 'price.id');
        }
        $query->where('users.id', $user->getKey());

        if (!is_null($cancelled_at)) {
            $query->where('previous_price_changes.created_at', '<', $cancelled_at);
            $query->where('previous_price.created_at', '<', $cancelled_at);
        }

        $query->select([
            "products.product_name",
            "categories.category_name",
            "price.value AS recent_price",
            "previous_price.amount AS previous_price",
            "urls.full_path",
            "urls.status",
            "previous_price_changes.created_at AS price_changed_at",
            DB::raw("my_sites.id IS NOT NULL AS is_my_site"),
            DB::raw("SUBSTRING_INDEX(REPLACE(REPLACE(REPLACE(urls.full_path,'http://',''),'https://',''),'www.',''),'/',1) AS domain"),
            DB::raw("IFNULL(ebay.value, IFNULL(user_domains.alias, SUBSTRING_INDEX(REPLACE(REPLACE(REPLACE(urls.full_path,'http://',''),'https://',''),'www.',''),'/',1))) AS display_name"),
        ]);

        $countQuery = $query;
        $countQuery->where(function ($query) {
            $query->whereNotNull('previous_price.amount');
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


        $displayProducts = $query->get();

        $displayProducts = $displayProducts->unique();

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