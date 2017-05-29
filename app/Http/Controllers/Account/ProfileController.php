<?php

namespace App\Http\Controllers\Account;

use App\Events\Account\Profile\AfterShow;
use App\Events\Account\Profile\AfterUpdate;
use App\Events\Account\Profile\BeforeShow;
use App\Events\Account\Profile\BeforeUpdate;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Account\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $request;
    protected $profileService;

    public function __construct(Request $request, ProfileService $profileService)
    {
        $this->request = $request;
        $this->profileService = $profileService;
    }

    /**
     * Load a user
     * @param $user_id
     * @return array|bool
     */
    public function show($user_id)
    {
        if ($user_id != auth()->user()->getKey()) {
            abort(403);
            return false;
        }

        $user = $this->profileService->getUserById($user_id);

        event(new BeforeShow($user));

        $status = true;

        event(new AfterShow($user));

        return compact(['user', 'status']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $user_id
     * @return bool|\Illuminate\Http\Response
     */
    public function update($user_id)
    {
        if ($user_id != auth()->user()->getKey()) {
            abort(403);
            return false;
        }

        $user = $this->profileService->getUserById($user_id);

        event(new BeforeUpdate($user));

        $this->profileService->update($user, $this->request->all());
        $status = true;

        event(new AfterUpdate($user));

        return compact(['status']);
    }

    public function password($user_id)
    {
        $user = $this->profileService->getUserById($user_id);

        $this->profileService->setPassword($user, $this->request->all());
        $status = true;

        return compact(['status']);
    }
}
