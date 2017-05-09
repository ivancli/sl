<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 9/05/2017
 * Time: 11:54 AM
 */

namespace App\Console\Commands;


use App\Models\LoggingModels\UserActivityLog;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class Formatter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'formatter';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command runs crawler for particular url or all urls if no parameters given.';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $logs = UserActivityLog::all();
        $products = $logs->pluck('activity');
        $formattedProducts = [];
        foreach ($products as $product) {
            $formattedProduct = json_decode($product, true);
            $formattedProducts[] = $formattedProduct;
        }

        Excel::create('test', function ($excel) use ($formattedProducts) {
            $excel->sheet('test1', function ($sheet) use ($formattedProducts) {
                $sheet->fromArray($formattedProducts);
            });
        })->store('csv');
    }
}
