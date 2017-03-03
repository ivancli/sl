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

    public function show($id)
    {
        if ($id != auth()->user()->getKey()) {
            abort(403);
            return false;
        }
        $user = $this->userRepo->get($id);
        event(new BeforeShow($user));
        $user = auth()->user();
        $status = true;
        event(new AfterShow($user));
        return compact(['user', 'status']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @param UpdateValidator $updateValidator
     * @return bool|\Illuminate\Http\Response
     */
    public function update($id, UpdateValidator $updateValidator)
    {
        if ($id != auth()->user()->getKey()) {
            abort(403);
            return false;
        }
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
