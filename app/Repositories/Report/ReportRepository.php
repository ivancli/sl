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

            if(!is_null($site->item) && !is_null($site->item->sellerUsername)){
                $site->setAttribute('displayName', "eBay: {$site->item->sellerUsername}");
            }elseif (array_has($userDomains, $siteDomain) && !is_null(array_get($userDomains, $siteDomain))) {
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
                    'D' => \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
                    'E' => \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2,
                    'F' => \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
                    'G' => \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2,
                ));
                $sheet->setWidth('A', 30);
                $sheet->setWidth('B', 30);
                $sheet->setWidth('C', 30);
                $sheet->setWidth('D', 20);
                $sheet->setWidth('E', 20);
                $sheet->setWidth('F', 20);
                $sheet->setWidth('G', 20);
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

                if(!is_null($site->item) && !is_null($site->item->sellerUsername)){
                    $site->setAttribute('displayName', "eBay: {$site->item->sellerUsername}");
                }elseif (array_has($userDomains, $siteDomain) && !is_null(array_get($userDomains, $siteDomain))) {
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
                    'D' => \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
                    'E' => \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2,
                    'F' => \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
                    'G' => \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2,
                ));
                $sheet->setWidth('A', 30);
                $sheet->setWidth('B', 30);
                $sheet->setWidth('C', 30);
                $sheet->setWidth('D', 20);
                $sheet->setWidth('E', 20);
                $sheet->setWidth('F', 20);
                $sheet->setWidth('G', 20);
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

        $products = $user->products()->with(['sites', 'sites.item', 'sites.url'])->get();

        $displayProducts = collect();

        foreach ($products as $product) {
            $sites = $product->sites;

            $cheapestPrice = $sites->min('item.recentPrice');
            $mostExpensivePrice = $sites->max('item.recentPrice');

            foreach ($sites as $site) {
                $item = $site->item;
                $siteDomain = domain($site->siteUrl);
                if (is_null($item)) {
                    continue;
                }
                if(!is_null($item->sellerUsername)){
                    $site->setAttribute('displayName', "eBay: {$item->sellerUsername}");
                }elseif (array_has($userDomains, $siteDomain) && !is_null(array_get($userDomains, $siteDomain))) {
                    $site->setAttribute('displayName', array_get($userDomains, $siteDomain));
                } else {
                    $site->setAttribute('displayName', $site->url->domainFullPath);
                }
                $recentPrice = $item->recentPrice;

                #region check cheapest price
                $isCheapest = $recentPrice == $cheapestPrice;
                $site->setAttribute('is_cheapest', $isCheapest);
                #endregion

                #region check most expensive price
                $isMostExpensive = $recentPrice == $mostExpensivePrice;
                $site->setAttribute('is_most_expensive', $isMostExpensive);
                #endregion

                #region check crawler failed
                /*TODO need to enhance the definition of crawler failed*/
                /*TODO because there are many situation causing item meta not fetchable*/
                /*TODO such as product unavailability, product URL change, incorrect xpath etc*/
                $isCrawlFailed = $site->url->status == 'crawl_failed';
                $site->setAttribute('is_crawl_failed', $isCrawlFailed);
                #endregion

                #region check my site
                $isMySite = false;
                if (!is_null($user->metas) && !is_null($user->metas->company_url)) {
                    $isMySite = sameDomain($user->metas->company_url, $site->siteUrl);
                    /*TODO add ebay later on*/
                }
                $site->setAttribute('is_my_site', $isMySite);
                #endregion

                #region check price has change within frequency period
                $hasPriceChange = false;
                if (!is_null($item->lastChangedAt)) {
                    $lastChangedAt = Carbon::createFromFormat('Y-m-d H:i:s', $item->lastChangedAt);
                    switch ($frequency) {
                        case 'day':
                            $hasPriceChange = $lastChangedAt->isSameDay($now);
                            break;
                        case 'week':
                            $hasPriceChange = $lastChangedAt->weekOfYear == $now->weekOfYear;
                            break;
                    }
                }
                $site->setAttribute('has_price_change', $hasPriceChange);
                #endregion
            }

            $displayProducts->push($product);
        }

        #region summarise results

        //cheapest product count
        $cheapestProductCount = $products->filter(function ($product) {
            $cheapestSiteCount = $product->sites->filter(function ($site) {
                return $site->is_cheapest == true && $site->is_my_site == true;
            })->count();
            return $cheapestSiteCount > 0;
        })->count();

        //most expensive product count
        $mostExpensiveProductCount = $products->filter(function ($product) {
            $cheapestSiteCount = $product->sites->filter(function ($site) {
                return $site->is_most_expensive == true && $site->is_my_site == true;
            })->count();
            return $cheapestSiteCount > 0;
        })->count();

        $sites = $products->pluck('sites')->flatten();

        //fail crawler count
        $crawlFailCount = $sites->filter(function ($site) {
            return $site->is_crawl_failed == true;
        })->count();

        //price change count
        $priceChangeCount = $sites->filter(function ($site) {
            return $site->has_price_change == true;
        })->count();

        #endregion

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