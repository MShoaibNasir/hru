<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\surveyList\IPController;
use App\Http\Controllers\surveyList\AyisDamageController;
use App\Http\Controllers\surveyController;


Route::get('/custom', function () {
    return 'This is a custom route!';
});




Route::prefix('survey')->middleware('auth.redirect')->group(function () {
    Route::get('/ip/list', [IPController::class, 'IPList'])->name('ips.list');
    Route::get('/fieldsupervisor/list', [IPController::class, 'FieldSuperVisorList'])->name('fieldSuperVisor.list');
    Route::get('/hru/list', [IPController::class, 'HRUList'])->name('hru.list');
    Route::get('pending/dammage/list', [IPController::class, 'dammagePendingList'])->name('dammage.pending.list');
    Route::get('pending/dammage/list/new', [IPController::class, 'dammagePendingList2'])->name('dammagePendingList2');
    Route::post('filter/pending/dammage/list/new', [IPController::class, 'filter_new_damage_assessment'])->name('filter_new_damage_assessment');
    Route::get('certify/list', [IPController::class, 'certifyList'])->name('certifyList');
    
    
    //New Damage Reporting
    Route::get('pending/damage/datalist', [AyisDamageController::class, 'getdamage_datalist'])->name('pending_damage_datalist');
    Route::get('approved/damage/datalist', [AyisDamageController::class, 'getdamage_datalist'])->name('approved_damage_datalist');
    Route::get('approved/ceo/damage/datalist', [AyisDamageController::class, 'getdamage_datalist'])->name('approved_ceo_damage_datalist');
    Route::get('rejected/damage/datalist', [AyisDamageController::class, 'getdamage_datalist'])->name('rejected_damage_datalist');
    Route::get('rejected/upper/damage/datalist', [AyisDamageController::class, 'getdamage_datalist'])->name('rejected_upper_damage_datalist');
    Route::get('hold/damage/datalist', [AyisDamageController::class, 'getdamage_datalist'])->name('hold_damage_datalist');
    Route::post('getdamage_datalist_fetch_data', [AyisDamageController::class, 'getdamage_datalist_fetch_data'])->name('getdamage_datalist_fetch_data');
    
    
    
    
    Route::get('ceo/pending/list',[IPController::class, 'ceo_pending_list'])->name('ceo.pending.list');
    Route::get('ceo/pending/list/two/{routecondition}',[IPController::class, 'ceo_pending_list_two'])->name('ceo.pending.list.two');
    
    
    Route::get('missing/document/list', [IPController::class, 'missing_document_list'])->name('missing_document_list');
    Route::get('missing/document/data/set', [IPController::class, 'missing_document_data_set'])->name('missing_document_data_set');
    Route::post('/total_missing_datalist_fetch_data', [IPController::class, 'total_missing_datalist_fetch_data'])->name('total_missing_datalist_fetch_data');
    
    
    Route::get('total/missing/document/datalist', [IPController::class, 'total_missing_document_datalist'])->name('total_missing_document_datalist');
    Route::post('total/missing/document/datalist/fetchdata', [IPController::class, 'total_missing_document_datalist_fetch_data'])->name('total_missing_document_datalist_fetch_data');
    

    Route::post('upload_missing_document_form', [IPController::class, 'upload_missing_document_form'])->name('upload_missing_document_form');
    Route::post('upload_missing_document_form_submit', [IPController::class, 'upload_missing_document_form_submit'])->name('upload_missing_document_form_submit');
    Route::get('missing/document/receive/list', [IPController::class, 'missing_document_receive_list'])->name('missing_document_receive_list');
    
    /*
    Route::get('rejected/total/dammage/datalist', [surveyController::class, 'rejected_dammage_datalist'])->name('rejected_dammage_datalist');
    Route::get('rejected/total/dammage/datalist_fetch_data', [surveyController::class, 'rejected_dammage_datalist_fetch_data'])->name('rejected_dammage_datalist_fetch_data');
    
    Route::get('approved/total/dammage/datalist', [surveyController::class, 'approved_dammage_datalist'])->name('approved_dammage_datalist');
    Route::get('approved/total/dammage/datalist_fetch_data', [surveyController::class, 'approved_dammage_datalist_fetch_data'])->name('approved_dammage_datalist_fetch_data');
    
    Route::get('hold/total/dammage/datalist', [surveyController::class, 'hold_dammage_datalist'])->name('hold_dammage_datalist');
    Route::get('hold/total/dammage/datalist_fetch_data', [surveyController::class, 'hold_dammage_datalist_fetch_data'])->name('hold_dammage_datalist_fetch_data');
    */
    
    
        
    Route::get('update/review/survey', [IPController::class, 'update_review_survey'])->name('update_review_survey');


    

});



