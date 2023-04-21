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

Route::get('login', [AuthController::class, 'loginForm'])->name('proxy.loginForm');
Route::post('login', [AuthController::class, 'login'])->name('proxy.login');
Route::get('logout', [AuthController::class, 'logout'])->name('proxy.logout');


Route::middleware('auth:proxy')->group(function(){
    
    Route::get('/', [DashboardController::class, 'index'])->name('proxy.dashboard');

    Route::prefix('proxy_accounts')->group(function(){
        Route::get('login/{id}', [proxyAccountController::class, 'login'])->name('proxy.proxy_accounts.login');
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
                    Route::resource($slug[0], $controller, [
                        'as' => 'proxy'
                    ]);
                } else {
                    Route::resource($item->slug, $controller, [
                        'as' => 'proxy'
                    ]);
                }

                Route::prefix($item->slug)->group(function()use($item, $controller){
                    Route::post('multipleDestroy', $controller.'@multipleDestroy')->name('proxy.'.$item->slug.'.multipleDestroy');
                    Route::post('importData', $controller.'@importData')->name('proxy.'.$item->slug.'.importData');
                });
            }
        }
    }
});