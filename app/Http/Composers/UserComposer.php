<?php
namespace App\Http\Composers;

use Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Route;

class UserComposer {
    public function compose(View $view) {

        $route = Route::getCurrentRoute();
        $prefix = $route->getPrefix();

        if (strpos($prefix, '/'))
        {
            $prefix = explode('/', $prefix)[0];
        }

        if ($prefix == '')
        {
            $prefix = 'web';
        }

        if (Auth::guard($prefix)->check()) {
            $user = Auth::guard($prefix)->user();

            $view->with('user', $user);
        }
        else {
            $view->with('user', []);
        }
    }
}