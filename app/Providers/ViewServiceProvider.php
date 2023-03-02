<?php

namespace App\Providers;

use App\Http\Composers\MenuComposer;
use App\Http\Composers\UserComposer;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', MenuComposer::class);
        view()->composer('*', UserComposer::class);
    }
}
