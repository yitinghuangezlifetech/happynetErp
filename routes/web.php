<?php

use Illuminate\Support\Facades\Route;

use App\Models\Menu;

use App\Http\Controllers\BasicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\SortableController;
use App\Http\Controllers\SelectionController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AuditRouteController;
use App\Http\Controllers\AuditRecordController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PendingCheckController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\proxyAccountController;
use App\Http\Controllers\BonusGroupController;
use App\Http\Controllers\SpeedApplySetController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectRegulationController;
use App\Http\Controllers\ContractController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('login', [AuthController::class, 'loginForm'])->name('loginForm');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth:web')->group(function(){
    
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('menus')->group(function(){
        Route::post('sort', [MenuController::class, 'sort'])->name('menus.sort');
    });
    Route::prefix('bouns_groups')->group(function(){
        Route::post('sort', [BonusGroupController::class, 'sort'])->name('bouns_groups.sort');
    });
    
    Route::prefix('users')->group(function(){
        Route::get('profile', [UserController::class, 'profile'])->name('users.profile');
        Route::post('updateProfile', [UserController::class, 'updateProfile'])->name('users.updateProfile');
    });

    Route::prefix('components')->group(function(){
        Route::post('getRolePermissionComponent', [ComponentController::class, 'getRolePermissionComponent'])->name('components.getRolePermissionComponent');
        Route::post('getGroupPermissionComponent', [ComponentController::class, 'getGroupPermissionComponent'])->name('components.getGroupPermissionComponent');
    });

    Route::prefix('status')->group(function(){
        Route::post('changeStatus', [StatusController::class, 'changeStatus'])->name('status.changeStatus');
    });

    Route::prefix('proxy_accounts')->group(function(){
        Route::get('proxyLogin/{id}', [proxyAccountController::class, 'proxyLogin'])->name('proxy_accounts.proxyLogin');
    });

    Route::prefix('sortables')->group(function(){
        Route::post('sort', [SortableController::class, 'sort'])->name('sortables.sort');
        Route::post('hierarchySort', [SortableController::class, 'hierarchySort'])->name('sortables.hierarchySort');
    });

    Route::prefix('speed_apply_sets')->group(function(){
        Route::post('preview', [SpeedApplySetController::class, 'preview'])->name('speed_apply_sets.preview');
    });

    Route::prefix('project_regulations')->group(function(){
        Route::post('getProjects', [ProjectRegulationController::class, 'getProjects'])->name('project_regulations.getProjects');
    });

    Route::prefix('contracts')->group(function(){
        Route::post('getProducts', [ContractController::class, 'getProducts'])->name('contracts.getProducts');
    });

    Route::prefix('tables')->group(function(){
        Route::post('getProductsByFilters', [ProjectController::class, 'getProductsByFilters'])->name('tables.getProductsByFilters');
    });

    if (Schema::hasTable('menus'))
    {
        $menuItems = app(Menu::class)
            ->whereNotNull('slug')
            ->orderBy('sort', 'ASC')
            ->get();

        if ($menuItems->count() > 0)
        {
            foreach ($menuItems as $item)
            {
                $controller = $item->controller??BasicController::class;

                if (preg_match('/\?/', $item->slug)) {
                    $slug = explode('?', $item->slug);
                    Route::resource($slug[0], $controller);
                } else {
                    Route::resource($item->slug, $controller);
                }

                Route::prefix($item->slug)->group(function()use($item, $controller){
                    Route::post('multipleDestroy', $controller.'@multipleDestroy')->name($item->slug.'.multipleDestroy');
                    Route::post('importData', $controller.'@importData')->name($item->slug.'.importData');
                });
            }
        }
    }
});