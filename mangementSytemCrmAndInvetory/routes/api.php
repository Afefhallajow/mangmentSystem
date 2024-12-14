<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    $request->user()->assignRole('Inventory');
    if ($request->user()->can('view products')) {
        echo "User can view inventory!";
    }
    return \App\Http\Resources\PermissionResource::collection($request->user()->getAllPermissions());
})->middleware('auth:api')->middleware("permission:view products");

Route::post('register', [AuthController::class, 'register']);
// مسار تسجيل الدخول
Route::post('login', [AuthController::class, 'login'])->name("login");
