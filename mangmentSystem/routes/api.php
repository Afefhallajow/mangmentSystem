<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProjectController\ProjectController;
use App\Http\Controllers\ProjectController\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\testController::class, "test"]);

Route::prefix('/project')->middleware('auth:sanctum')->group(function () {
    Route::get('/getall', [ProjectController::class, "getAll"]);
    Route::get('/getone/{id}', [ProjectController::class, "getOne"]);
    Route::get('/getTasks/{id}', [ProjectController::class, "getTasksForProject"]);
    Route::post('/save', [ProjectController::class, "save"]);
    Route::post('/update', [ProjectController::class, "update"]);

});
Route::prefix('/task')->middleware('auth:sanctum')->group(function () {
    Route::get('/getall', [TaskController::class, "getAll"]);
    Route::get('/getone/{id}', [TaskController::class, "getOne"]);
    Route::post('/save', [TaskController::class, "save"]);
    Route::post('/addtouser', [TaskController::class, "addTaskToUsers"]);
    Route::post('/update', [TaskController::class, "update"]);
    Route::Delete('/delete', [TaskController::class, "update"]);
    Route::get('/taskStatus/getall', [TaskController::class, "getTaskStatus"]);
    Route::get('/taskPriority/getall', [TaskController::class, "getTaskPriority"]);
    Route::get('/task/report', [TaskController::class, "getReportForProjects"]);
})->middleware('auth:api');;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name("login");
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
