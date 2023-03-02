<?php

namespace App\Providers;

use App\Models\Menu;
use App\Policies\BasePolicy;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('menus')) {
            $menus = app(Menu::class)->get();
            
            if ($menus->count() > 0) {
                foreach ($menus as $menu) {
                    $this->policies[$menu->model] = BasePolicy::class;
                }
            }  
        }

        $this->registerPolicies();
    }
}
