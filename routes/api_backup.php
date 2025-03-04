<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\SurveyController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\MNEController;
use App\Http\Controllers\API\ConstructionController;
use App\Http\Controllers\API\VRCConrolller;
use App\Http\Controllers\API\EnvironmentController;
use App\Http\Controllers\API\GenderSafeguardController;
use App\Http\Controllers\API\EnvironemntCaseController;
use App\Http\Controllers\API\SocialSafeGuardController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/






// Route::middleware('auth:sanctum')->group(function () {


// });



Route::post('ndma/verifications', [SurveyController::class, 'ndma_verifications']);
Route::get('beneficiary/couting/{userId}', [SurveyController::class, 'couting_beneficiary']);
Route::post('ndma/verifications/vrc', [SurveyController::class, 'ndma_data_for_vrc']);

Route::post('form_data_upload', [SurveyController::class, 'form_data_upload']);
Route::post('ip_signup', [SurveyController::class, 'ip_signup']);
Route::get('survey/section/data', [SurveyController::class, 'survey_section_data']);
Route::post('survey/form', [SurveyController::class, 'survey_form']);
Route::post('survey/form/testing', [SurveyController::class, 'survey_form_testing']);
Route::post('survey/form/demo', [SurveyController::class, 'survey_form_demo']);
Route::post('vrc/survey/form', [SurveyController::class, 'survey_form_vrc']);
Route::get('modified_survey_form_second', [SurveyController::class, 'modified_survey_form_second']);
Route::get('survey_form_latest_data', [SurveyController::class, 'survey_form_latest_data']);
Route::post('rejected/form',[SurveyController::class, 'rejectedForm']);
Route::get('rejectedCount/{id}', [SurveyController::class, 'rejectedFormCount']);
Route::get('reject/chunks', [SurveyController::class, 'rejectedWithChunks']);

Route::post('rejected/form/test',[SurveyController::class, 'rejectedFormTest']);

Route::post('submited/form',[SurveyController::class, 'submittedForm']);
Route::post('submited/form/two',[SurveyController::class, 'submittedForm2']);
Route::post('approved/form',[SurveyController::class, 'approvedForm']);
Route::post('upload/vrc/form',[VRCConrolller::class, 'uploadVRC']);
Route::post('vrc/form/attendence', [SurveyController::class, 'vrc_attendence']);
Route::post('uploadvrc/attendece',[VRCConrolller::class, 'uploadVrcAttendece']);
Route::post('vrc/committee',[VRCConrolller::class, 'vrc_committee']);
Route::get('user/uc/{user_id}',[RegisterController::class, 'user_uc']);






//Ayaz Construction Routes    
Route::get('construction/survey/form/stage1', [ConstructionController::class, 'survey_form_construction_stage1']);
Route::get('construction/survey/form/stage2', [ConstructionController::class, 'survey_form_construction_stage2']);
Route::get('construction/survey/form/stage3', [ConstructionController::class, 'survey_form_construction_stage3']);

Route::post('construction/survey/form/stage1/upload', [ConstructionController::class, 'survey_form_construction_stage1_upload']);
Route::post('construction/survey/form/stage2/upload', [ConstructionController::class, 'survey_form_construction_stage2_upload']);
Route::post('construction/survey/form/stage3/upload', [ConstructionController::class, 'survey_form_construction_stage3_upload']);
Route::post('/storeImages', [SurveyController::class, 'storeImages']);
Route::post('/ayisstoreImages', [SurveyController::class, 'ayisstoreImages']);
Route::post('/reportinganswers', [ReportController::class, 'reportinganswers']);
Route::post('/construction/updatte', [SurveyController::class, 'constructionupdatte']);
//Ayaz END



Route::get('survey/form/data', [SurveyController::class, 'modified_survey_form']);
Route::get('beneficiary/couting/{userId}', [SurveyController::class, 'couting_beneficiary']);
Route::controller(RegisterController::class)->group(function () {
Route::post('register', 'register');
Route::post('login', 'login');
Route::post('loginTest', 'loginTest');
});

// MNE logics
Route::post('ndma/data/for/mne', [MNEController::class, 'ndma_data_for_mne']);
Route::post('mne/data/upload', [MNEController::class, 'mne_data_upload']);
Route::get('survey/form/mne', [MNEController::class, 'survey_form_mne']);


// gender safeguards logics

Route::post('gender/safeguard/data/upload', [GenderSafeguardController::class, 'data_upload']);
Route::post('ndma/data/for/gender_safeguard', [GenderSafeguardController::class, 'ndma_data_for_gender_safeguard']);
Route::post('ndma-data/gender-safeguard/after-case', [GenderSafeguardController::class, 'ndma_data_for_gender_safeguard_after_case']);
Route::post('gender/safeguard/reject_data', [GenderSafeguardController::class, 'reject_data']);
Route::post('gender/safeguard/approved_data', [GenderSafeguardController::class, 'approved_data']);
Route::post('gender/safeguard/submit_data', [GenderSafeguardController::class, 'submit_data']);
Route::post('gender/safeguard/reject_form_upload', [GenderSafeguardController::class, 'reject_form_upload']);
Route::post('gender/safeguard/rejected_list_mitigation', [GenderSafeguardController::class, 'rejected_list_mitigation']);
Route::post('gender/safeguard/case_close_list_mitigation', [GenderSafeguardController::class, 'case_close_list_mitigation']);
Route::post('gender/safeguard/submit_list_mitigation', [GenderSafeguardController::class, 'submit_list_mitigation']);




// social safeguards logics

Route::post('social/safeguard/data/upload', [SocialSafeGuardController::class, 'data_upload']);
Route::post('ndma/data/for/social_safeguard', [SocialSafeGuardController::class, 'ndma_data_for_social_safeguard']);
Route::post('ndma-data/social_safeguard/after-case', [SocialSafeGuardController::class, 'ndma_data_for_social_safeguard_after_case']);
Route::post('social/safeguard/reject_data', [SocialSafeGuardController::class, 'reject_data']);
Route::post('social/safeguard/approved_data', [SocialSafeGuardController::class, 'approved_data']);
Route::post('social/safeguard/submit_data', [SocialSafeGuardController::class, 'submit_data']);
Route::post('social/safeguard/reject_form_upload', [SocialSafeGuardController::class, 'reject_form_upload']);

Route::post('social/safeguard/rejected_list_mitigation', [SocialSafeGuardController::class, 'rejected_list_mitigation']);
Route::post('social/safeguard/case_close_list_mitigation', [SocialSafeGuardController::class, 'case_close_list_mitigation']);
Route::post('social/safeguard/submit_list_mitigation', [SocialSafeGuardController::class, 'submit_list_mitigation']);


//  environment logics

Route::post('/get_beneficiary_data', [EnvironmentController::class, 'get_beneficiary_data']);
Route::post('/environment_form_upload', [EnvironemntCaseController::class, 'environment_form_upload']);
Route::post('/environment/case/reject/list', [EnvironemntCaseController::class, 'rejected_list']);
Route::post('/environment/case/approved/list', [EnvironemntCaseController::class, 'approved_list']);
Route::post('/environment/case/submit/list', [EnvironemntCaseController::class, 'submit_list']);


