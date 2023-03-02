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
    
    Route::prefix('users')->group(function(){
        Route::get('profile', [UserController::class, 'profile'])->name('users.profile');
        Route::post('updateProfile', [UserController::class, 'updateProfile'])->name('users.updateProfile');
    });

    Route::prefix('components')->group(function(){
        Route::get('getPermissionComponent', [ComponentController::class, 'getPermissionComponent'])->name('components.getPermissionComponent');
        Route::post('getGroupPermissionComponent', [ComponentController::class, 'getGroupPermissionComponent'])->name('components.getGroupPermissionComponent');
    });

    Route::prefix('audit_routes')->group(function(){
        Route::get('downloadReport/{routeId}', [AuditRouteController::class, 'downloadReport'])->name('audit_routes.downloadReport');
        Route::post('hasAuditCompleted', [AuditRouteController::class, 'hasAuditCompleted'])->name('audit_routes.hasAuditCompleted');
        Route::post('hasPhotoUpload', [AuditRouteController::class, 'hasPhotoUpload'])->name('audit_routes.hasPhotoUpload');
        Route::post('sendEmail', [AuditRouteController::class, 'sendEmail'])->name('audit_routes.sendEmail');
    });

    Route::prefix('audit_records')->group(function(){
        Route::get('{routeId}', [AuditRecordController::class, 'index'])->name('audit_records.index');
        Route::get('photos/{routeId}', [AuditRecordController::class, 'photos'])->name('audit_records.photos');
        Route::get('completed/{routeId}', [AuditRecordController::class, 'completed'])->name('audit_records.completed');
        Route::get('finishRecord/{routeId}', [AuditRecordController::class, 'finishRecord'])->name('audit_records.finishRecord');
        Route::get('failPreview/{routeId}', [AuditRecordController::class, 'failPreview'])->name('audit_records.failPreview');
        Route::get('getFailItems/{routeId}', [AuditRecordController::class, 'getFailItems'])->name('audit_records.getFailItems');
        Route::get('getItems/{routeId}/{recordId}', [AuditRecordController::class, 'getItems'])->name('audit_records.getItems');
        Route::get('failPage/{routeId}/{recordId}', [AuditRecordController::class, 'failPage'])->name('audit_records.failPage');
        Route::get('regulations/{routeId}/{mainAttributeId}', [AuditRecordController::class, 'regulations'])->name('audit_records.regulations');
        Route::post('changeStatus', [AuditRecordController::class, 'changeStatus'])->name('audit_records.changeStatus');
        Route::post('recordItems', [AuditRecordController::class, 'recordItems'])->name('audit_records.recordItems');
        Route::post('recordFails', [AuditRecordController::class, 'recordFails'])->name('audit_records.recordFails');
        Route::post('recordFailItems/{routeId}', [AuditRecordController::class, 'recordFailItems'])->name('audit_records.recordFailItems');
        Route::post('removeFailRecord', [AuditRecordController::class, 'removeFailRecord'])->name('audit_records.removeFailRecord');
        Route::post('uploadRecord/{routeId}', [AuditRecordController::class, 'uploadRecord'])->name('audit_records.uploadRecord');
        Route::post('uploadPhoto/{routeId}', [AuditRecordController::class, 'uploadPhoto'])->name('audit_records.uploadPhoto');
        Route::post('setMainPhoto', [AuditRecordController::class, 'setMainPhoto'])->name('audit_records.setMainPhoto');
        Route::post('removePhoto', [AuditRecordController::class, 'removePhoto'])->name('audit_records.removePhoto');
        Route::post('changeFailType', [AuditRecordController::class, 'changeFailType'])->name('audit_records.changeFailType');
    });

    Route::prefix('pending_checks')->group(function(){
        Route::get('fail/{checkId}/{logId}', [PendingCheckController::class, 'fail'])->name('pending_checks.fail');
        Route::get('success/{checkId}/{logId}', [PendingCheckController::class, 'success'])->name('pending_checks.success');
        Route::post('successRecord', [PendingCheckController::class, 'successRecord'])->name('pending_checks.successRecord');
        Route::post('failRecord', [PendingCheckController::class, 'failRecord'])->name('pending_checks.failRecord');
        Route::post('removeResult', [PendingCheckController::class, 'removeResult'])->name('pending_checks.removeResult');
        Route::post('checkRecord', [PendingCheckController::class, 'checkRecord'])->name('pending_checks.checkRecord');
    });

    Route::prefix('counselings')->group(function(){
        Route::get('{routeId}', [CounselingController::class, 'index'])->name('counselings.index');
        Route::post('createOrUpdate/{routeId}', [CounselingController::class, 'createOrUpdate'])->name('counselings.createOrUpdate');
    });

    Route::prefix('reports')->group(function(){
        Route::get('download', [ReportController::class, 'download'])->name('reports.download');
    });

    Route::prefix('status')->group(function(){
        Route::post('changeStatus', [StatusController::class, 'changeStatus'])->name('status.changeStatus');
        Route::post('changePendingCheckStatus', [StatusController::class, 'changePendingCheckStatus'])->name('status.changePendingCheckStatus');
        Route::post('changePendingCheckLogStatus', [StatusController::class, 'changePendingCheckLogStatus'])->name('status.changePendingCheckLogStatus');
    });

    Route::prefix('sortables')->group(function(){
        Route::post('sort', [SortableController::class, 'sort'])->name('sortables.sort');
        Route::post('hierarchySort', [SortableController::class, 'hierarchySort'])->name('sortables.hierarchySort');
    });

    Route::prefix('questionnaires')->group(function(){
        Route::post('preview', [QuestionnaireController::class, 'preview'])->name('questionnaires.preview');
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