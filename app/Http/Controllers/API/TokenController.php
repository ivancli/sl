<?php

namespace App\Http\Controllers\API;

use App\Services\API\TokenService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TokenController extends Controller
{
    protected $request;
    protected $tokenService;

    public function __construct(Request $request, TokenService $tokenService)
    {
        $this->request = $request;
        $this->tokenService = $tokenService;
    }

    /**
     * retrieve new token
     * @return TokenController|\Illuminate\Http\JsonResponse
     */
    public function get()
    {
        $token = $this->tokenService->generateToken();
        $ip = $this->request->ip();
        $key = bcrypt($ip . $token);
        $status = true;
        if ($this->request->has('callback')) {
            return response()->json(compact(['status', 'token', 'ip', 'key']))->setCallback($this->request->get('callback'));
        } else {
            return compact(['status', 'token', 'ip', 'key']);
        }
    }

    /**
     * verify provided token
     */
    public function verify()
    {
        $isValid = $this->tokenService->verifyToken($this->request->all());
        $status = true;

        if ($this->request->has('callback')) {
            return response()->json(compact(['status', 'isValid']))->setCallback($this->request->get('callback'));
        } else {
            return compact(['status', 'isValid']);
        }
    }
}
