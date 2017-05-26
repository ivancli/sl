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
        return auth()->user()->reports;
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
        $user = $report->user;

        switch ($report->report_type) {
            case 'product':
                switch ($report->reportable_type) {
                    case 'product':
                        $product = $report->reportable;
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
                        break;
                    case 'category':
                        $category = $report->reportable;

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
                        break;
                }
                break;
            case 'digest':

                break;
        }
    }
}