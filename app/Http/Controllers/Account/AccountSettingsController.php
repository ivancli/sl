<?php
namespace App\Http\Controllers\Account;
use App\Http\Controllers\Controller;

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/23/2017
 * Time: 11:13 AM
 */
class AccountSettingsController extends Controller
{
    public function index()
    {
        return view('app.account_settings.index');
    }
}