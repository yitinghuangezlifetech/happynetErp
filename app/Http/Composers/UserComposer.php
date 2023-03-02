<?php
namespace App\Http\Composers;

use Auth;
use Illuminate\View\View;

class UserComposer {
    public function compose(View $view) {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            $view->with('user', $user);
        } else {
            $view->with('user', []);
        }
    }
}