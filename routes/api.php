<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Apis\CheckApi;
use App\Http\Controllers\Apis\ImageApi;
use App\Http\Controllers\Apis\SelectApi;
use App\Http\Controllers\Apis\PublicApi;
use App\Http\Controllers\Apis\SystemTypeApi;
use App\Http\Controllers\Apis\UserControllerApi;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('image')->group(function () {
    Route::post('upload', [ImageApi::class, 'upload'])->name('api.image.upload');
    Route::post('remove', [ImageApi::class, 'remove'])->name('api.image.remove');
    Route::post('deleteImg', [ImageApi::class, 'deleteImg'])->name('api.image.deleteImg');
});

Route::prefix('system_types')->group(function () {
    Route::post('getGroupSystemTypes', [SystemTypeApi::class, 'getGroupSystemTypes'])->name('api.system_types.getGroupSystemTypes');
});

Route::prefix('selects')->group(function () {
    Route::post('getGroupRoles', [SelectApi::class, 'getGroupRoles'])->name('api.selects.getGroupRoles');
    Route::post('getOrganizationRole', [SelectApi::class, 'getOrganizationRole'])->name('api.selects.getOrganizationRole');
});

Route::prefix('checks')->group(function () {
    Route::post('checkStaffCodeExist', [CheckApi::class, 'checkStaffCodeExist'])->name('api.checks.checkStaffCodeExist');
    Route::post('checkStaffEmailExist', [CheckApi::class, 'checkStaffEmailExist'])->name('api.checks.checkStaffEmailExist');
});

Route::prefix('public')->group(function () {
    Route::post('getUserInfo', [PublicApi::class, 'getUserInfo'])->name('api.public.getUserInfo');
    Route::post('getOrganization', [PublicApi::class, 'getOrganization'])->name('api.public.getOrganization');
    Route::post('getOrganizationByIdentity', [PublicApi::class, 'getOrganizationByIdentity'])->name('api.public.getOrganizationByIdentity');
    Route::post('getOrganizationUsers', [PublicApi::class, 'getOrganizationUsers'])->name('api.public.getOrganizationUsers');
    Route::post('getContractRegulations', [PublicApi::class, 'getContractRegulations'])->name('api.public.getContractRegulations');
    Route::post('getContractProducts', [PublicApi::class, 'getContractProducts'])->name('api.public.getContractProducts');
    Route::post('getProjectProducts', [PublicApi::class, 'getProjectProducts'])->name('api.public.getProjectProducts');
    Route::post('getProjectRegulations', [PublicApi::class, 'getProjectRegulations'])->name('api.public.getProjectRegulations');
});

Route::post('auth', [UserControllerApi::class, 'login']);
