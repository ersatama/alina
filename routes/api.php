<?php

use App\Http\Controllers\Api\ReportController;
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


Route::prefix('V1')->group(function() {
    Route::prefix('report')->group(function() {
        Route::post('import', [ReportController::class, 'import'])->name('report.import');
        Route::get('get', [ReportController::class, 'get'])->name('report.get');
        Route::put('update', [ReportController::class, 'update'])->name('report.update');
    });
});