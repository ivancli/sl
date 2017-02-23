<?php

namespace App\Http\Controllers\Account;

use App\Contracts\Repositories\UserManagement\UserContract;
use App\Events\Account\Profile\AfterUpdate;
use App\Events\Account\Profile\BeforeUpdate;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Validators\Account\Profile\UpdateValidator;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    var $request;
    var $userRepo;

    public function __construct(Request $request, UserContract $userContract)
    {
        $this->request = $request;
        $this->userRepo = $userContract;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @param UpdateValidator $updateValidator
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateValidator $updateValidator)
    {
        $user = $this->userRepo->get($id);
        event(new BeforeUpdate($user));
        $updateValidator->validate($this->request->all());

        $this->userRepo->update($user, $this->request->all());
        $this->userRepo->updateMetas($user, $this->request->all());
        $status = true;

        event(new AfterUpdate($user));

        return compact(['status']);

    }
}
