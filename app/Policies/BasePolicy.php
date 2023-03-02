<?php

namespace App\Policies;

use App\Models\UserAuth;
use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization;

    public function __call($action, $arguments) {
        if (count($arguments) <= 1) {
            return response()->view('alerts.error', [
                'msg' => 'BasePolicy arguments is error',
                'redirectURL' => route('dashboard')
            ]);
        }

        $user = $arguments[0];

        return $this->checkPermission($user, $action);
    }

    public function checkPermission(UserAuth $user, $action) {
        return $user->hasPermission($action);
    }
}
