<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/7/2017
 * Time: 5:18 PM
 */

namespace App\Exceptions;


use Exception;
use Illuminate\Http\JsonResponse;

class JsonDecodeException extends Exception
{
    public $response;
    protected $errors;
    protected $redirectUrl;

    public function __construct($message = null)
    {
        if (!is_null($message)) {
            $this->setErrors($message);
        } else {
            $this->setErrors(__('exceptions.Subscription.JsonDecodeException'));
        }
        parent::__construct();
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

    /**
     * Get the underlying response instance.
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function getResponse()
    {
        return $this->response;
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
}