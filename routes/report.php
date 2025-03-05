<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\surveyList\IPController;
use App\Http\Controllers\surveyController;
use App\Http\Controllers\ALLModuleReportController;


Route::prefix('all-modules')->middleware('auth.redirect')->group(function () {
    Route::get('/report', [ALLModuleReportController::class, 'report'])->name('report');
    Route::post('/overall_fetch_report_data', [ALLModuleReportController::class, 'overall_fetch_report_data'])->name('overall_fetch_report_data');
    Route::post('/get_users_according_to_role', [ALLModuleReportController::class, 'get_users_according_to_roleget_users'])->name('get_users_according_to_role');
});



