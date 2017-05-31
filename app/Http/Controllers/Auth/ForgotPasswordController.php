<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('guest');
    }

    protected function sendResetLinkResponse($response)
    {
        $status = true;
        $message = trans($response);
        return compact(['status', 'message']);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {

        if (!is_array($response)) {
            $message = [trans($response)];
        } else {
            $message = trans($response);
        }
        return new JsonResponse($message, 422);
    }
}
