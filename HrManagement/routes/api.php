<?php

use App\Http\Controllers\AttendaceController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return "afgegsg";
});

Route::prefix('/')->middleware('auth:api')->group(function () {
    Route::prefix('/employee')->group(function () {
        Route::post('/save', [EmployeeController::class, "save"]);
        Route::delete('/delete/{id}', [EmployeeController::class, "Delete"]);

    });
    Route::prefix('/attendance')->group(function () {
        Route::post('/save', [AttendaceController::class, "save"]);

    });
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name("login");
