<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\GatewayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/upload-attachments', [GatewayController::class, 'upload']);

Route::post('register', [AuthController::class, 'register']);

Route::get('permission/get', [\App\Http\Controllers\PermissionController::class, 'getAllPermissionForUser']);

Route::post('login', [AuthController::class, 'login'])->name("login");
Route::get('home', [\App\Http\Controllers\GatewayController::class, 'getChartData'])->middleware("auth:api");


Route::prefix("/")->group(function (){
    Route::match(['get', 'post', 'put', 'delete'],
        '/{service}/{endpoint}/{extra?}',
        [GatewayController::class, 'handleRequest'])
        ->where('extra', '.*');
})->middleware('auth:api');
