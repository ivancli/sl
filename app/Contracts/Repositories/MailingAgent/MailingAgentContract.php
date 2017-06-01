<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 1/06/2017
 * Time: 10:09 AM
 */

namespace App\Contracts\Repositories\MailingAgent;


interface MailingAgentContract
{
    /**
     * Load subscriber by email address
     * @param $email
     * @return mixed
     */
    public function getSubscriber($email);

    /**
     * create new subscriber
     * @param array $data
     * @return mixed
     */
    public function storeSubscriber(array $data);

    /**
     * update existing subscriber
     * @param $email
     * @param array $data
     * @return mixed
     */
    public function updateSubscriber($email, array $data = []);

    /**
     * delete a subscriber
     * @param $email
     * @return mixed
     */
    public function delete($email);

    /**
     * unsubscribe a subscriber
     * @param $email
     * @return mixed
     */
    public function unsubscribe($email);
}