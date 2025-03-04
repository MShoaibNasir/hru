<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\surveyList\IPController;
use App\Http\Controllers\surveyController;
use App\Http\Controllers\ALLModuleReportController;


Route::prefix('all-modules')->middleware('auth.redirect')->group(function () {
    Route::get('/report', [ALLModuleReportController::class, 'report'])->name('report');
});



