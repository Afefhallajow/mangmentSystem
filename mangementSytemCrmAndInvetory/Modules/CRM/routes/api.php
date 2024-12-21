<?php

use Illuminate\Support\Facades\Route;
use Modules\CRM\Http\Controllers\CRMController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('crm', CRMController::class)->names('crm');
    Route::get("/report", [\Modules\CRM\Http\Controllers\ClientController::class, "getUsersCount"]);
});
