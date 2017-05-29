<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 29/05/2017
 * Time: 10:58 AM
 */

namespace App\Services\API;


use App\Contracts\Repositories\API\TokenContract;

class TokenService
{
    #region repositories

    protected $tokenRepo;

    #endregion

    public function __construct(TokenContract $tokenContract)
    {
        #region repositories binding
        $this->tokenRepo = $tokenContract;
        #endregion
    }

    /**
     * retrieve new token
     * @return mixed
     */
    public function generateToken()
    {
        return $this->tokenRepo->generateToken();
    }

    /**
     * validate provided token
     * @param $token
     * @return mixed
     */
    public function verifyToken($token)
    {
        return $this->tokenRepo->verifyToken($token);
    }
}