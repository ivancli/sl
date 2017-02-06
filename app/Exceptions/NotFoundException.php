<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 4/02/2017
 * Time: 3:41 PM
 */

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class NotFoundException extends Exception
{
    protected $errors;
    protected $redirectUrl;
    public $response;

    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->response = $this->buildFailedResponse($this->getErrors());
    }

    public function getErrors()
    {
        return $this->errors;
    }

    protected function setErrors($errors)
    {
        if (is_string($errors)) {
            $errors = ['error' => $errors];
        }
        $this->errors = $errors;
    }

    protected function buildFailedResponse(array $errors)
    {
        if (request()->expectsJson()) {
            return new JsonResponse($errors, 422);
        }

        return redirect()->to($this->getRedirectUrl())
            ->withInput(request()->input())
            ->withErrors($errors);
    }

    protected function getRedirectUrl()
    {
        if (isset($this->redirectUrl) && !empty($this->redirectUrl)) {
            return $this->redirectUrl;
        }
        return redirect()->back()->getTargetUrl();
    }

    /**
     * Get the underlying response instance.
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function getResponse()
    {
        return $this->response;
    }
}