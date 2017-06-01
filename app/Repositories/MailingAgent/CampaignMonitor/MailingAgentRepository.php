<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 1/06/2017
 * Time: 10:14 AM
 */

namespace App\Repositories\MailingAgent\CampaignMonitor;


use App\Contracts\Repositories\MailingAgent\MailingAgentContract;

class MailingAgentRepository implements MailingAgentContract
{
    protected $listId;

    protected $auth;

    public function __construct()
    {
        $this->auth = [
            'api_key' => config('campaign_monitor.api_key')
        ];

        $this->listId = config('campaign_monitor.list_id');
    }

    /**
     * Load subscriber by email address
     * @param $email
     * @return mixed
     */
    public function getSubscriber($email)
    {
        $wrap = new \CS_REST_Subscribers($this->listId, $this->auth);
        $result = $wrap->get($email);
        if (isset($result->http_status_code) && $result->http_status_code == 200) {
            return $result->response;
        } else {
            return null;
        }
    }

    /**
     * create new subscriber
     * @param array $data
     * @return mixed
     */
    public function storeSubscriber(array $data)
    {
        $wrap = new \CS_REST_Subscribers($this->listId, $this->auth);
        $result = $wrap->add($data);
        if ($result->http_status_code == 201) {
            return $result->response;
        } else {
            return null;
        }
    }

    /**
     * update existing subscriber
     * @param $email
     * @param array $data
     * @return mixed
     */
    public function updateSubscriber($email, array $data = [])
    {
        $wrap = new \CS_REST_Subscribers($this->listId, $this->auth);
        $result = $wrap->update($email, $data);
        if (isset($result->http_status_code) && $result->http_status_code == 200) {
            return $result;
        } else {
            return null;
        }
    }

    /**
     * delete a subscriber
     * @param $email
     * @return mixed
     */
    public function delete($email)
    {
        $wrap = new \CS_REST_Subscribers($this->listId, $this->auth);
        $result = $wrap->delete($email);
        if (isset($result->http_status_code) && $result->http_status_code == 200) {
            return true;
        } else {
            return null;
        }
    }

    /**
     * unsubscribe a subscriber
     * @param $email
     * @return mixed
     */
    public function unsubscribe($email)
    {
        $wrap = new \CS_REST_Subscribers($this->listId, $this->auth);
        $result = $wrap->unsubscribe($email);
        if (isset($result->http_status_code) && $result->http_status_code == 200) {
            return true;
        } else {
            return null;
        }
    }
}