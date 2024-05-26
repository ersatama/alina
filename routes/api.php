<?php

use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ReportFileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function() {

    Route::prefix('report')->group(function() {
        Route::post('import', [ReportController::class, 'import'])->name('report.import');
        Route::get('get', [ReportController::class, 'get'])->name('report.get');
        Route::put('update', [ReportController::class, 'update'])->name('report.update');
    });

    Route::prefix('reportFile')->group(function() {
        Route::get('get', [ReportFileController::class, 'get'])->name('reportFile.get');
        Route::get('getById/{id}', [ReportFileController::class, 'getById'])->name('reportFile.getById');
    });

});