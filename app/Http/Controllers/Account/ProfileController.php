<?php

namespace App\Http\Controllers\Account;

use App\Contracts\Repositories\UserManagement\UserContract;
use App\Events\Account\Profile\AfterShow;
use App\Events\Account\Profile\AfterUpdate;
use App\Events\Account\Profile\BeforeShow;
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

    public function show(User $user)
    {
        event(new BeforeShow($user));
        $status = true;
        event(new AfterShow($user));
        return compact(['user', 'status']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param User $user
     * @param UpdateValidator $updateValidator
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, UpdateValidator $updateValidator)
    {
        event(new BeforeUpdate($user));
        $updateValidator->validate($this->request->all());

        $this->userRepo->update($user, $this->request->all());
        $this->userRepo->updateMetas($user, $this->request->all());
        $status = true;

        event(new AfterUpdate($user));

        return compact(['status']);

    }
}
