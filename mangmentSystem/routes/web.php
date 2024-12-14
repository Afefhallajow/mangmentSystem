<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\ProjectController\ProjectController;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('/a')->group(function () {
    Route::get('/getall', [ProjectController::class, "getAll"]);
    Route::get('/getone/{id}', [ProjectController::class, "getOne"]);
    Route::post('/save', [ProjectController::class, "save"]);
    Route::post('/update', [ProjectController::class, "update"]);

    Route::post('/task/save', [\App\Http\Controllers\ProjectController\TaskController::class, "save"]);
    Route::post('/task/addtouser', [\App\Http\Controllers\ProjectController\TaskController::class, "addTaskToUsers"]);

});
