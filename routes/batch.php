<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\surveyList\IPController;
use App\Http\Controllers\surveyController;
use App\Http\Controllers\Batch\FirstBatchController;







Route::prefix('first/batch')->middleware('auth.redirect')->group(function () {
    
    Route::get('/data/list', [FirstBatchController::class, 'firstTrechDatalist'])->name('firstTrechDatalist');
    Route::post('/remove/checkboxvalue', [FirstBatchController::class, 'remove_check_box_value'])->name('remove_check_box_value');
    Route::post('/fetch_data', [FirstBatchController::class, 'firsttrench_fetch_data'])->name('firsttrench_fetch_data');
    Route::post('/fetch_data/edit', [FirstBatchController::class, 'firsttrench_fetch_data_for_edit'])->name('firsttrench_fetch_data_for_edit');
    Route::post('/add', [FirstBatchController::class, 'add_first_batch'])->name('add_first_batch');
    Route::post('/add/session/ref', [FirstBatchController::class, 'add_ref_session'])->name('add_ref_session');
    Route::get('/list', [FirstBatchController::class, 'first_batch_list'])->name('first_batch_list');
    Route::get('/edit/{id}', [FirstBatchController::class, 'edit'])->name('firstbatch.edit');
    Route::post('/edit_batch_list_export', [FirstBatchController::class, 'edit_batch_list_export'])->name('edit_batch_list_export');
    Route::get('/complete/{id}', [FirstBatchController::class, 'complete'])->name('firstbatch.complete');
    Route::post('/update/{id}', [FirstBatchController::class, 'update_first_batch'])->name('update_first_batch');
    Route::get('/batch_detail/{id}', [FirstBatchController::class, 'batch_detail'])->name('batch_detail');
    Route::get('/main_batch', [FirstBatchController::class, 'main_batch'])->name('main_batch');
    Route::post('/main_batch_list', [FirstBatchController::class, 'main_batch_list'])->name('main_batch_list');
    Route::post('/batch_datalist_export', [FirstBatchController::class, 'batch_datalist_export'])->name('batch_datalist_export');
    
    
    
});



