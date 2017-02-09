<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }


    protected function authenticated(Request $request, $user)
    {
        /* TODO redirect admin users to administration page */
        /* TODO redirect normal users to home page which is '/' */
        $redirect_path = $this->redirectTo;
        if ($request->ajax()) {
            $redirect_path = redirect($redirect_path)->getTargetUrl();
            return new JsonResponse(compact(['redirect_path']));
        } else {
            return redirect($redirect_path);
        }
    }
}
