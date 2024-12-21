<?php

use Illuminate\Support\Facades\Route;
use Modules\CRM\Http\Controllers\ClientController;
use Modules\InventoryManagement\Http\Controllers\InventoryManagementController;
use Modules\InventoryManagement\Http\Controllers\InvoiceController;
use Modules\InventoryManagement\Http\Controllers\ProductController;
use Modules\InventoryManagement\Http\Controllers\ReportController;

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
Route::get('product/market', [ProductController::class, "getMarketProducts"]);
Route::middleware(['auth:api', "role:Inventory"])->group(function () {
    Route::get('invoice/getOne/{id}', [InvoiceController::class, "getOne"]);
    Route::get('product/getOne/{id}', [ProductController::class, "getOne"]);
    Route::get('product/getAll', [ProductController::class, "getAll"]);
    Route::post('product/save', [ProductController::class, "save"]);
    Route::post('product/update', [ProductController::class, "update"]);
//    Route::get('product/market', [ProductController::class, "getMarketProducts"]);

    Route::post('report', [ReportController::class, "makeReport"]);

    Route::post('invoice/save', [InvoiceController::class, "save"]);
});

Route::middleware(['auth:api'])->group(function () {
    Route::post('client/save', [ClientController::class, "save"]);
    Route::delete('client/delete/{id}', [ClientController::class, "Delete"]);

});
