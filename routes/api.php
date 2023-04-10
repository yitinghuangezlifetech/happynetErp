<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Apis\ImageApi;
use App\Http\Controllers\Apis\SelectApi;
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

Route::prefix('image')->group(function(){
    Route::post('upload', [ImageApi::class, 'upload'])->name('api.image.upload');
    Route::post('remove', [ImageApi::class, 'remove'])->name('api.image.remove');
    Route::post('deleteImg', [ImageApi::class, 'deleteImg'])->name('api.image.deleteImg');
});

Route::prefix('system_types')->group(function(){
    Route::post('getGroupSystemTypes', [SystemTypeApi::class, 'getGroupSystemTypes'])->name('api.system_types.getGroupSystemTypes');
});

Route::prefix('selects')->group(function(){
    Route::post('getGroupRoles', [SelectApi::class, 'getGroupRoles'])->name('api.selects.getGroupRoles');
});

Route::post('auth', [UserControllerApi::class, 'login']);
