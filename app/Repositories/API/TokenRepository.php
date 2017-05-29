<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 29/05/2017
 * Time: 10:57 AM
 */

namespace App\Repositories\API;


use App\Contracts\Repositories\API\TokenContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class TokenRepository implements TokenContract
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * generate a new token for api request
     * @return mixed
     */
    public function generateToken()
    {
        $token = csrf_token();
        $ip = $this->request->ip();
        Cache::put($token, bcrypt($ip . $token), 15);
        return $token;
    }

    /**
     * validate the provided token
     * @param $token
     * @return mixed
     */
    public function verifyToken($token)
    {
        $ip = $this->request->ip();
        $cachedToken = Cache::pull($token);
        return Hash::check($ip . $token, $cachedToken);
    }
}