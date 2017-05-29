<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 29/05/2017
 * Time: 10:56 AM
 */

namespace App\Contracts\Repositories\API;


interface TokenContract
{
    /**
     * generate a new token for api request
     * @return mixed
     */
    public function generateToken();

    /**
     * validate the provided token
     * @param $token
     * @return mixed
     */
    public function verifyToken($token);
}