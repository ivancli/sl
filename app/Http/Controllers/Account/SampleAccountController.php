<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 30/05/2017
 * Time: 9:41 AM
 */

namespace App\Http\Controllers\Account;


use App\Http\Controllers\Controller;
use App\Services\Account\SampleAccountService;
use Illuminate\Http\Request;

class SampleAccountController extends Controller
{
    protected $request;

    protected $sampleAccountService;

    public function __construct(Request $request, SampleAccountService $sampleAccountService)
    {
        $this->request = $request;

        $this->sampleAccountService = $sampleAccountService;
    }

    /**
     * Load sample account data
     */
    public function index()
    {
        $categories = $this->sampleAccountService->load();
        $status = true;
        return compact(['categories', 'status']);
    }

    /**
     * Clone sample account data across to user account
     */
    public function store()
    {
        $this->sampleAccountService->store($this->request->all());
        $status = true;
        return compact(['status']);
    }
}