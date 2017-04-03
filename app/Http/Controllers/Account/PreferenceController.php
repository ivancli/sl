<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/23/2017
 * Time: 4:23 PM
 */

namespace App\Http\Controllers\Account;


use App\Contracts\Repositories\UserManagement\UserContract;
use App\Events\Account\Preference\AfterUpdate;
use App\Events\Account\Preference\BeforeUpdate;
use App\Http\Controllers\Controller;
use App\Validators\Account\Preference\UpdateValidator;
use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    protected $request;
    protected $userRepo;

    public function __construct(Request $request, UserContract $userContract)
    {
        $this->request = $request;
        $this->userRepo = $userContract;
    }

    /**
     * @param $id
     * @param UpdateValidator $updateValidator
     * @return array
     */
    public function update($id, UpdateValidator $updateValidator)
    {
        $user = $this->userRepo->get($id);
        event(new BeforeUpdate($user));
        $updateValidator->validate($this->request->all());

        foreach ($this->request->all() as $element => $value) {
            $user->setPreference($element, $value);
        }
        $status = true;

        event(new AfterUpdate($user));
        return compact(['status']);
    }
}