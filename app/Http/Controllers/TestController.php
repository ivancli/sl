<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 21/03/2017
 * Time: 9:31 PM
 */

namespace App\Http\Controllers;


use App\Contracts\Repositories\Report\ReportContract;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{


    /**
     * Create a new job instance.
     * @param ReportContract $reportContract
     */
    public function __construct()
    {

    }

    public function test()
    {
        Cache::forget('https://gmail-sandbox.chargify.com/.chargify.subscriptions.17932731"');
    }
}