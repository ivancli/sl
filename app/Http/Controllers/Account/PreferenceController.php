<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/23/2017
 * Time: 4:23 PM
 */

namespace App\Http\Controllers\Account;


use App\Events\Account\Preference\AfterUpdate;
use App\Events\Account\Preference\BeforeUpdate;
use App\Http\Controllers\Controller;
use App\Services\Account\PreferenceService;
use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    protected $request;
    protected $preferenceService;

    public function __construct(Request $request, PreferenceService $preferenceService)
    {
        $this->request = $request;
        $this->preferenceService = $preferenceService;
    }

    /**
     * @param $user_id
     * @return array
     */
    public function update($user_id)
    {
        $user = $this->preferenceService->getUserById($user_id);

        event(new BeforeUpdate($user));

        $this->preferenceService->update($user_id, $this->request->all());
        $status = true;

        event(new AfterUpdate($user));

        return compact(['status']);
    }
}