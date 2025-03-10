<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\EnvironmentalScreening;
use App\Http\Controllers\LotController;
use App\Http\Controllers\GenderSafeguardController;
use App\Http\Controllers\VRCController;
use App\Http\Controllers\SocialSafeguardController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\TehsilController;
use App\Http\Controllers\UcController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\QuestionTitleController;
use App\Http\Controllers\EnvironmentCaseController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\OptionsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\surveyController;
use App\Http\Controllers\NdmaVerificationController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\DesignationContoller;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\MasterReportController;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\BankController;

//Contruction
use App\Http\Controllers\ConstructionController;
use App\Http\Controllers\ChangeBeneficiaryController;
use App\Http\Controllers\MNEController;

//GRM
use App\Http\Controllers\GrievanceTypeController;
use App\Http\Controllers\SourceChannelController;
use App\Http\Controllers\PIUController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\Frontend\ComplaintController as FComplaintController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// for admin dashboard
Route::get('/test', function () {
    return 'This is a test route.';
});



Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard')->middleware('auth.redirect');
Route::get('/destructure/test', [AdminController::class, 'destructure_test']);
Route::get('/revertStatus', [AdminController::class, 'revertStatus']);

Route::get('/updateAnswer', [AdminController::class, 'updateAnswer']);
Route::get('/deleteDuplicate', [AdminController::class, 'deleteDuplicate']);
Route::get('/revetToCEO', [AdminController::class, 'revetToCEO']);
Route::get('/updateAnswerForName', [AdminController::class, 'updateAnswerForName']);
Route::get('/updateAnswerForContactNumber', [AdminController::class, 'updateAnswerForContactNumber']);
Route::get('/making_images/{id}', [AdminController::class, 'making_images']);
Route::get('/admin/dashboard/newimg', [AdminController::class, 'dashboardnewimg'])->name('admin.dashboardnewimg')->middleware('auth.redirect');
Route::post('/login/user', [ProfileController::class, 'customLogin'])->name('customLogin');
Route::get('/user/logout', [ProfileController::class, 'logout'])->name('user.logout');
Route::middleware(['auth', 'user-access:user'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

Route::get('/edit/profile', [HomeController::class, 'edit_profile'])->name('edit_profile');
Route::post('/update/profile', [HomeController::class, 'update_profile'])->name('update_profile');

Route::get('/fieldSuperVisor', [HomeController::class, 'fieldSuperVisor']);



Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('admin.dashboard')
        : view('dashboard.signin');
})->name('admin.sign');





Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('admin/user')->middleware('auth.redirect')->group(function () {
    Route::get('/create', [UserController::class, 'create'])->name('ip.create');
    Route::get('/list', [UserController::class, 'index'])->name('ip.list');
    Route::post('/signup', [UserController::class, 'ip_signup'])->name('ip_signup');
    Route::get('/delete/{id}', [UserController::class, 'delete'])->name('ip.delete');
    Route::get('/block/{id}', [UserController::class, 'block'])->name('ip.block');
    Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/update/{id}', [UserController::class, 'update'])->name('user.update');
});
Route::prefix('admin/section')->middleware('auth.redirect')->group(function () {
    Route::get('/create', [SectionController::class, 'create'])->name('section.create');
    Route::get('/list', [SectionController::class, 'index'])->name('section.list');
    Route::post('/store', [SectionController::class, 'store'])->name('section.store');
    Route::get('/edit/{id}', [SectionController::class, 'edit'])->name('section.edit');
    Route::post('/update/{id}', [SectionController::class, 'update'])->name('section.update');
    Route::get('/status/{id}', [SectionController::class, 'status'])->name('section.status');
});
Route::prefix('admin/designation')->middleware('auth.redirect')->group(function () {
    Route::get('/create', [DesignationContoller::class, 'create'])->name('designation.create');
    Route::get('/list', [DesignationContoller::class, 'index'])->name('designation.list');
    Route::post('/store', [DesignationContoller::class, 'store'])->name('designation.store');
    Route::get('/edit/{id}', [DesignationContoller::class, 'edit'])->name('designation.edit');
    Route::post('/update/{id}', [DesignationContoller::class, 'update'])->name('designation.update');
    Route::get('/status/{id}', [DesignationContoller::class, 'status'])->name('designation.status');
});

Route::prefix('admin/bank')->middleware('auth.redirect')->group(function () {
    Route::get('/create', [BankController::class, 'create'])->name('bank.create');
    Route::get('/list', [BankController::class, 'index'])->name('bank.list');
    Route::post('/store', [BankController::class, 'store'])->name('bank.store');
    Route::get('/edit/{id}', [BankController::class, 'edit'])->name('bank.edit');
    Route::post('/update/{id}', [BankController::class, 'update'])->name('bank.update');
    Route::get('/status/{id}', [BankController::class, 'status'])->name('bank.status');
    
});


// Zone managemnt
Route::prefix('admin/zone')->middleware('auth.redirect')->group(function () {
    Route::get('/create', [ZoneController::class, 'create'])->name('zone.create');
    Route::post('/store', [ZoneController::class, 'store'])->name('zone.store');
    Route::get('/list', [ZoneController::class, 'index'])->name('zone.list');
    Route::get('/delete/{id}', [ZoneController::class, 'delete'])->name('zone.delete');
    Route::get('/edit/{id}', [ZoneController::class, 'edit'])->name('zone.edit');
    Route::post('/update/{id}', [ZoneController::class, 'update'])->name('zone.update');
    Route::get('/status/{id}', [ZoneController::class, 'zone_status'])->name('zone.status');

});



// area managemnt
Route::prefix('admin/area')->middleware('auth.redirect')->group(function () {
    Route::get('/create', [AreaController::class, 'create'])->name('area.create');
    Route::post('/store', [AreaController::class, 'store'])->name('area.store');
    Route::get('/list', [AreaController::class, 'index'])->name('area.list');
    Route::get('/delete/{id}', [AreaController::class, 'delete'])->name('area.delete');
    Route::get('/edit/{id}', [AreaController::class, 'edit'])->name('area.edit');
    Route::post('/update/{id}', [AreaController::class, 'update'])->name('area.update');
    Route::get('/status/{id}', [AreaController::class, 'area_status'])->name('area.status');
    Route::get('/removeLotFromDataBase/{id}', [AreaController::class, 'removeLotFromDataBase'])->name('removeLotFromDataBase');
});


// survey managemnt
Route::get('/beneficiary/details', [surveyController::class, 'beneficiary_details']);
Route::prefix('admin/survey')->middleware('auth.redirect')->group(function () {
    
    Route::get('/create', [AreaController::class, 'create'])->name('survey.create');
    Route::post('/store', [AreaController::class, 'store'])->name('survey.store');
    Route::get('/list', [surveyController::class, 'index'])->name('survey.list');
    Route::get('/remove/from_hold_list', [surveyController::class, 'remove_from_hold_list'])->name('remove_from_hold_list');
    Route::get('/beneficiaryProfileNew/{id}/{session?}/{role?}', [surveyController::class, 'beneficiaryProfileNew'])->name('beneficiaryProfile');
    
    Route::post('/questions_accept_reject', [surveyController::class, 'questions_accept_reject'])->name('questions_accept_reject');

    

    Route::get('/pending/data/ip/by_m_and_e', [surveyController::class, 'suvery_pending_data_ip_by_m_and_e'])->name('suvery_pending_data_ip_by_m_and_e');
    Route::get('/pending/data/hru/by_m_and_e', [surveyController::class, 'suvery_pending_data_hru_by_m_and_e'])->name('suvery_pending_data_hru_by_m_and_e');
    Route::get('/pending/data/psia/by_m_and_e', [surveyController::class, 'suvery_pending_data_psia_by_m_and_e'])->name('suvery_pending_data_psia_by_m_and_e');
    Route::get('/pending/data/hru_main/by_m_and_e', [surveyController::class, 'suvery_pending_data_hru_main_by_m_and_e'])->name('suvery_pending_data_hru_main_by_m_and_e');
    Route::get('/pending/data/coo/by_m_and_e', [surveyController::class, 'suvery_pending_data_coo_by_m_and_e'])->name('suvery_pending_data_coo_by_m_and_e');
    Route::get('/pending/data/ceo/by_m_and_e', [surveyController::class, 'suvery_pending_data_ceo_by_m_and_e'])->name('suvery_pending_data_ceo_by_m_and_e');

    
    Route::get('/update_certified/{id}', [surveyController::class, 'update_certified'])->name('update_certified');
    Route::get('/view/{id}', [surveyController::class, 'final_view'])->name('survey.view');
    Route::post('/rejection_revert', [surveyController::class, 'rejection_revert'])->name('rejection_revert');
    Route::post('/comment_revert', [surveyController::class, 'comment_revert'])->name('comment_revert');
    Route::post('/surveyquestion_rejectforms', [surveyController::class, 'surveyquestion_rejectforms'])->name('surveyquestion_rejectforms');
    Route::post('/surveyquestion_rejectformsubmit', [surveyController::class, 'surveyquestion_rejectformsubmit'])->name('surveyquestion_rejectformsubmit');
    
    Route::post('/update/{id}', [AreaController::class, 'update'])->name('area.update');
    Route::get('/status/{id}', [AreaController::class, 'area_status'])->name('area.status');
    Route::post('/edit_question_answer', [surveyController::class, 'edit_question_answer'])->name('edit_question_answer');
    
    Route::post('/missing_documentcomment_form', [surveyController::class, 'missing_documentcomment_form'])->name('missing_documentcomment_form');
    Route::post('/add_to_ineligible', [surveyController::class, 'add_to_ineligible'])->name('add_to_ineligible');
    Route::post('/remove_to_ineligible', [surveyController::class, 'remove_to_ineligible'])->name('remove_to_ineligible');
    Route::post('/missing_documentcomment_form_submit', [surveyController::class, 'missing_documentcomment_form_submit'])->name('missing_documentcomment_form_submit');
    Route::post('/missing_document_comment_remove', [surveyController::class, 'missing_document_comment_remove'])->name('missing_document_comment_remove');


    
    Route::get('pending', [surveyController::class, 'survey_pending_data'])->name('survey.pending.form');
    Route::get('pending1', [surveyController::class, 'survey_pending_data_for_ids'])->name('survey.pending.form1');
    Route::get('ineligible_list', [surveyController::class, 'ineligible_list'])->name('ineligible_list');
    Route::get('survey_pending_data_test', [surveyController::class, 'survey_pending_data_test'])->name('survey_pending_data_test');
    Route::get('rejected', [surveyController::class, 'survey_rejected_data'])->name('survey.rejected.form');
    Route::get('everyuserrejected', [surveyController::class, 'survey_everyuserrejected_data'])->name('survey.everyuserrejected.form');
    Route::get('approved', [surveyController::class, 'survey_approved_data'])->name('survey.approved.form');
    Route::get('approved_by_ceo', [surveyController::class, 'approved_by_ceo'])->name('approved_by_ceo');
    Route::get('survey_approved_data_testing', [surveyController::class, 'survey_approved_data_testing'])->name('survey_approved_data_testing');
    Route::get('hold', [surveyController::class, 'survey_hold_data'])->name('survey.hold.form');
});

Route::prefix('admin/construction')->middleware('auth.redirect')->group(function () {
    Route::get('/list', [constructionController::class, 'index'])->name('construction.list');
    Route::post('/total_construction_datalist_fetch_data', [constructionController::class, 'total_construction_datalist_fetch_data'])->name('total_construction_datalist_fetch_data');
    Route::get('/{id?}/view', [constructionController::class, 'view'])->name('construction.view');
    Route::get('/construction_status_trail_history', [constructionController::class, 'construction_status_trail_history'])->name('construction_status_trail_history');
    Route::post('/construction_action_form', [constructionController::class, 'construction_action_form'])->name('construction_action_form');
    Route::post('/construction_action_form_submit', [constructionController::class, 'construction_action_form_submit'])->name('construction_action_form_submit');
    Route::post('/construction_action_bulk', [constructionController::class, 'construction_action_bulk'])->name('construction_action_bulk');
    
});

Route::prefix('admin/mne')->middleware('auth.redirect')->group(function () {
    Route::get('/list', [MNEController::class, 'index'])->name('mne.list');
    Route::post('/total_mne_datalist_fetch_data', [MNEController::class, 'total_mne_datalist_fetch_data'])->name('total_mne_datalist_fetch_data');
    Route::get('/{id?}/view', [MNEController::class, 'view'])->name('mne.view');
    Route::get('/mne_status_trail_history', [MNEController::class, 'mne_status_trail_history'])->name('mne_status_trail_history');
    Route::post('/mne_action_form', [MNEController::class, 'mne_action_form'])->name('mne_action_form');
    Route::post('/mne_action_form_submit', [MNEController::class, 'mne_action_form_submit'])->name('mne_action_form_submit');
    
});


Route::prefix('admin/gender')->middleware('auth.redirect')->group(function () {
    Route::get('/list', [GenderSafeguardController::class, 'index'])->name('gender.list');
    Route::post('/total_gender_datalist_fetch_data', [GenderSafeguardController::class, 'total_gender_datalist_fetch_data'])->name('total_gender_datalist_fetch_data');
    Route::get('/{id?}/view', [GenderSafeguardController::class, 'view'])->name('gender.view');
    Route::get('/gender_status_trail_history', [GenderSafeguardController::class, 'gender_status_trail_history'])->name('gender_status_trail_history');
    Route::post('/gender_action_form', [GenderSafeguardController::class, 'gender_action_form'])->name('gender_action_form');
    Route::post('/gender_action_form_submit', [GenderSafeguardController::class, 'gender_action_form_submit'])->name('gender_action_form_submit');
    Route::post('/add/comment/view', [GenderSafeguardController::class, 'add_comment_view'])->name('gender_case.add_comment.view');

});
Route::prefix('admin/vrc')->middleware('auth.redirect')->group(function () {
    Route::get('/list', [VRCController::class, 'index'])->name('vrc.list');
    Route::post('/total_vrc_datalist_fetch_data', [VRCController::class, 'total_vrc_datalist_fetch_data'])->name('total_vrc_datalist_fetch_data');
    Route::get('/vrc_filter/{id}', [VRCController::class, 'vrc_filter'])->name('vrc_filter');
    Route::post('/vrc_committee_list', [VRCController::class, 'vrc_committee_list'])->name('vrc_committee_list');
    Route::post('/export_vrc_committee', [VRCController::class, 'export_vrc_committee'])->name('export_vrc_committee');
    
    //VRC Events List & Attendance List
    Route::get('/{id?}/events', [VRCController::class, 'vrc_event_list'])->name('vrc_event_list');
    Route::post('/export_vrc_event_list', [VRCController::class, 'export_vrc_event_list'])->name('export_vrc_event_list');
    Route::post('/events_datalist_fetch_data', [VRCController::class, 'events_datalist_fetch_data'])->name('events_datalist_fetch_data');
    Route::get('/{id?}/attendance', [VRCController::class, 'vrc_attendance_list'])->name('vrc_attendance_list');
    Route::post('/attendance_datalist_fetch_data', [VRCController::class, 'attendance_datalist_fetch_data'])->name('attendance_datalist_fetch_data');
    Route::post('/export_vrc_attendence', [VRCController::class, 'export_vrc_attendence'])->name('export_vrc_attendence');
    Route::post('/export_vrc_formation', [VRCController::class, 'export_vrc_formation'])->name('export_vrc_formation');
    Route::post('/export_vrc_attendence_list', [VRCController::class, 'export_vrc_attendence_list'])->name('export_vrc_attendence_list');
});

Route::prefix('admin/social')->middleware('auth.redirect')->group(function () {
    Route::get('/list', [SocialSafeguardController::class, 'index'])->name('social.list');
    Route::post('/total_social_datalist_fetch_data', [SocialSafeguardController::class, 'total_social_datalist_fetch_data'])->name('total_social_datalist_fetch_data');
    Route::get('/{id?}/view', [SocialSafeguardController::class, 'view'])->name('social.view');
    Route::get('/social_status_trail_history', [SocialSafeguardController::class, 'social_status_trail_history'])->name('social_status_trail_history');
    Route::post('/social_action_form', [SocialSafeguardController::class, 'social_action_form'])->name('social_action_form');
    Route::post('/social_action_form_submit', [SocialSafeguardController::class, 'social_action_form_submit'])->name('social_action_form_submit');
    Route::post('/add/comment/view', [SocialSafeguardController::class, 'add_comment_view'])->name('social_case.add_comment.view');
});


Route::prefix('admin/environment')->middleware('auth.redirect')->group(function () {
    Route::get('/list', [EnvironmentalScreening::class, 'index'])->name('environment.list');
    Route::post('/total_environment_datalist_fetch_data', [EnvironmentalScreening::class, 'total_environment_datalist_fetch_data'])->name('total_environment_datalist_fetch_data');
    Route::get('/{id?}/view', [EnvironmentalScreening::class, 'view'])->name('environment.view');
    Route::get('/environment_status_trail_history', [EnvironmentalScreening::class, 'environment_status_trail_history'])->name('environment_status_trail_history');
    Route::get('/environment_option_edit', [EnvironmentalScreening::class, 'environment_option_edit'])->name('environment_option_edit');
    Route::post('/update_environment_option', [EnvironmentalScreening::class, 'update_environment_option'])->name('update_environment_option');
    
    Route::post('/environment_action_form', [EnvironmentalScreening::class, 'environment_action_form'])->name('environment_action_form');
    Route::post('/environment_action_form_submit', [EnvironmentalScreening::class, 'environment_action_form_submit'])->name('environment_action_form_submit');
    Route::get('/data_check', [EnvironmentalScreening::class, 'data_check'])->name('data_check');
    Route::get('/profile/{id}', [EnvironmentalScreening::class, 'EnvironmentProfile'])->name('environment.profile');
    Route::post('/environment/datalist/export', [App\Http\Controllers\EnvironmentalScreening::class, 'environment_datalist_export'])->name('environment_datalist_export');

    
});





// lot management
Route::prefix('admin/lot')->middleware('auth.redirect')->group(function () {
    Route::get('/create', [LotController::class, 'create'])->name('lot.create');
    Route::post('/store', [LotController::class, 'store'])->name('lot.store');
    Route::get('/list', [LotController::class, 'index'])->name('lot.list');
    Route::get('/delete/{id}', [LotController::class, 'delete'])->name('lot.delete');
    Route::get('/edit/{id}', [LotController::class, 'edit'])->name('lot.edit');
    Route::post('/update/{id}', [LotController::class, 'update'])->name('lot.update');
    Route::get('/status/{id}', [LotController::class, 'lot_status'])->name('lot.status');
    Route::get('/lot_testing', [LotController::class, 'lot_testing'])->name('lot_testing');

});
// district management
Route::prefix('admin/district')->middleware('auth.redirect')->group(function () {
    Route::get('/create', [DistrictController::class, 'create'])->name('district.create');
    Route::post('/store', [DistrictController::class, 'store'])->name('district.store');
    Route::get('/list', [DistrictController::class, 'index'])->name('district.list');
    Route::get('/delete/{id}', [DistrictController::class, 'delete'])->name('district.delete');
    Route::get('/edit/{id}', [DistrictController::class, 'edit'])->name('district.edit');
    Route::post('/update/{id}', [DistrictController::class, 'update'])->name('district.update');
    Route::get('/status/{id}', [DistrictController::class, 'district_status'])->name('district.status');
});



// tehsil  management

Route::prefix('admin/tehsil')->middleware('auth.redirect')->group(function () {
    Route::get('/create', [TehsilController::class, 'create'])->name('tehsil.create');
    Route::post('/store', [TehsilController::class, 'store'])->name('tehsil.store');
    Route::get('/list', [TehsilController::class, 'index'])->name('tehsil.list');
    Route::get('/delete/{id}', [TehsilController::class, 'delete'])->name('tehsil.delete');
    Route::get('/edit/{id}', [TehsilController::class, 'edit'])->name('tehsil.edit');
    Route::post('/update/{id}', [TehsilController::class, 'update'])->name('tehsil.update');
    Route::get('/status/{id}', [TehsilController::class, 'tehsil_status'])->name('tehsil.status');
});



// UC  management

Route::prefix('admin/uc')->middleware('auth.redirect')->group(function () {
    Route::get('/create', [UcController::class, 'create'])->name('uc.create');
    Route::post('/store', [UcController::class, 'store'])->name('uc.store');
    Route::get('/list', [UcController::class, 'index'])->name('uc.list');
    Route::get('/delete/{id}', [UcController::class, 'delete'])->name('uc.delete');
    Route::get('/edit/{id}', [UcController::class, 'edit'])->name('uc.edit');
    Route::post('/update/{id}', [UcController::class, 'update'])->name('uc.update');
    Route::get('/status/{id}', [UcController::class, 'uc_status'])->name('uc.status');
});


// form management
Route::prefix('admin/form')->middleware('auth.redirect')->group(function () {
    Route::get('/create', [FormController::class, 'create'])->name('form.create');
    Route::post('/store', [FormController::class, 'store'])->name('form.store');
    Route::get('/list', [FormController::class, 'index'])->name('form.list');
    Route::get('/delete/{id}', [FormController::class, 'delete'])->name('form.delete');
    Route::get('/edit/{id}', [FormController::class, 'edit'])->name('form.edit');
    Route::post('/update/{id}', [FormController::class, 'update'])->name('form.update');
    Route::get('/view/{id}', [FormController::class, 'view'])->name('form.view');
    Route::get('/status/{id}', [FormController::class, 'form_status'])->name('form.status');
    Route::get('/up/{id}', [FormController::class, 'up'])->name('form_up');
    Route::get('/down/{id}', [FormController::class, 'down'])->name('form_down');
    
    
});

// question title management
Route::prefix('admin/question/title')->middleware('auth.redirect')->group(function () {
    Route::get('/create/{id}', [QuestionTitleController::class, 'create'])->name('question.title.create');
    Route::post('/store/{id}', [QuestionTitleController::class, 'store'])->name('question.title.store');
    Route::get('/list', [QuestionTitleController::class, 'index'])->name('question.title.list');
    Route::get('/delete/{id}', [QuestionTitleController::class, 'delete'])->name('question.title.delete');
    Route::get('/edit/{id}', [QuestionTitleController::class, 'edit'])->name('question.title.edit');
    Route::get('/show/{id}', [QuestionTitleController::class, 'show'])->name('question.title.show');
    Route::post('/update/{id}', [QuestionTitleController::class, 'update'])->name('question.title.update');
    Route::get('/view/{id}', [QuestionTitleController::class, 'view'])->name('question.title.view');
    Route::get('/filter_question', [QuestionTitleController::class, 'filter_question'])->name('question.title.view');
    Route::get('/section/up/{id}', [QuestionTitleController::class, 'section_up'])->name('section_up');
    Route::get('/section/down/{id}', [QuestionTitleController::class, 'section_down'])->name('section_down');
});

// Question  management
Route::prefix('admin/question')->middleware('auth.redirect')->group(function () {
    Route::get('/create/{id}', [QuestionController::class, 'create'])->name('question.create');
    Route::post('/store/{id}', [QuestionController::class, 'store'])->name('question.store');
    Route::get('/list/{id}', [QuestionController::class, 'index'])->name('question.list');
    Route::get('/delete/{id}', [QuestionController::class, 'delete'])->name('question.delete');
    Route::get('/edit/{id}/{section_id}', [QuestionController::class, 'edit'])->name('question.edit');
    Route::get('/show/{id}', [QuestionController::class, 'show'])->name('question.show');
    Route::post('/update/{id}', [QuestionController::class, 'update'])->name('question.update');
    Route::get('/view/{id}', [QuestionController::class, 'view'])->name('question.view');
    Route::get('filter', [QuestionController::class, 'question_filter'])->name('question.filter');
    Route::get('related', [QuestionController::class, 'related_question'])->name('related.question');
    Route::get('question/up/{id}', [QuestionController::class, 'question_up'])->name('question_up');
    Route::get('question/down/{id}', [QuestionController::class, 'question_down'])->name('question_down');

});
// Options  management
Route::prefix('admin/options')->middleware('auth.redirect')->group(function () {
    Route::get('/create/{id}', [OptionsController::class, 'create'])->name('options.create');
    Route::post('/store/{id}', [OptionsController::class, 'store'])->name('options.store');
    Route::get('/list/{id}', [OptionsController::class, 'index'])->name('options.list');
    Route::get('/delete/{id}', [OptionsController::class, 'delete'])->name('options.delete');
    Route::get('/edit/{id}/{title_id}', [OptionsController::class, 'edit'])->name('options.edit');
    Route::get('/show/{id}', [OptionsController::class, 'show'])->name('options.show');
    Route::post('/update/{id}/{title_id}', [OptionsController::class, 'update'])->name('options.update');
    Route::get('/view/{id}', [OptionsController::class, 'view'])->name('options.view');
    Route::get('filter', [OptionsController::class, 'option_filter'])->name('options.filter');
});
// logs  management
Route::prefix('admin/logs')->middleware('auth.redirect')->group(function () {
    Route::get('/list', [LogsController::class, 'index'])->name('logs.list');
    Route::get('/logsdata', [LogsController::class, 'logs_data'])->name('logs.data');
    Route::get('/delete/{id}', [LogsController::class, 'delete'])->name('logs.delete');


    Route::get('/logdatalist', [LogsController::class, 'logdatalist'])->name('logdatalist');
    Route::post('/logdatalist_fetch_data', [LogsController::class, 'logdatalist_fetch_data'])->name('logdatalist_fetch_data');
});


// Role  management

Route::prefix('admin/role')->middleware('auth.redirect')->group(function () {
    Route::get('/create', [RoleController::class, 'create'])->name('role.create');
    Route::post('/store', [RoleController::class, 'store'])->name('role.store');
    Route::get('/list', [RoleController::class, 'index'])->name('role.list');
    Route::get('/delete/{id}', [RoleController::class, 'delete'])->name('role.delete');
    Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('role.edit');
    Route::post('/update/{id}', [RoleController::class, 'update'])->name('role.update');
    Route::get('/status/{id}', [RoleController::class, 'role_status'])->name('role.status');
});


Route::prefix('admin/dashboard')->middleware('auth.redirect')->group(function () {
    Route::get('/show-graph', [\App\Http\Controllers\AdminController::class, 'show_graph'])->name('show_graph');
    Route::get('/correctGender', [\App\Http\Controllers\AdminController::class, 'correctGender'])->name('correctGender');
});
// sub_role  management

// Route::prefix('admin/sub/role')->middleware('auth.redirect')->group(function () {
//     Route::get('/create', [RoleController::class, 'create'])->name('role.create');
//     Route::post('/store', [RoleController::class, 'store'])->name('role.store');
//     Route::get('/list', [RoleController::class, 'index'])->name('role.list');
//     Route::get('/delete/{id}', [RoleController::class, 'delete'])->name('role.delete');
//     Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('role.edit');
//     Route::post('/update/{id}', [RoleController::class, 'update'])->name('role.update');
// });



Route::prefix('admin/ndma')->middleware('auth.redirect')->group(function () {
    Route::get('/upload', [App\Http\Controllers\NdmaVerificationController::class, 'view_upload'])->name('upload_ndma_page');
    Route::get('/data', [App\Http\Controllers\NdmaVerificationController::class, 'ndma_data'])->name('ndma_data');
    Route::get('/download/sample', [App\Http\Controllers\NdmaVerificationController::class, 'downloadAction'])->name('downloadAction');
    Route::get('/downloadActionToUplaodAccount', [App\Http\Controllers\NdmaVerificationController::class, 'downloadActionToUplaodAccount'])->name('downloadActionToUplaodAccount');
    Route::get('/download/ndma/edit', [App\Http\Controllers\NdmaVerificationController::class, 'downloadNdmaEdit'])->name('downloadNdmaEdit');
    Route::post('/upload/data', [App\Http\Controllers\NdmaVerificationController::class, 'upload_ndma_data'])->name('upload_ndma_data');
    Route::post('filter', [App\Http\Controllers\NdmaVerificationController::class, 'filter_pdma'])->name('filter_pdma');
    Route::get('edit', [App\Http\Controllers\NdmaVerificationController::class, 'editPDMA'])->name('editPDMA');
    Route::post('upload/updated', [App\Http\Controllers\NdmaVerificationController::class, 'uploadEditPDMA'])->name('uploadEditPDMA');
    Route::get('edit/pdma/{id}', [App\Http\Controllers\NdmaVerificationController::class, 'editPDMASingle'])->name('edit.pdma');
    Route::post('update/pdma/{id}', [App\Http\Controllers\NdmaVerificationController::class, 'updatePDMA'])->name('update.pdma');
    
    
    
    Route::get('/pdmadatalist', [App\Http\Controllers\NdmaVerificationController::class, 'pdmadatalist'])->name('pdmadatalist');
    Route::post('/pdmadatalist_fetch_data', [App\Http\Controllers\NdmaVerificationController::class, 'pdmadatalist_fetch_data'])->name('pdmadatalist_fetch_data');
    Route::post('/pdmadatalist_export', [App\Http\Controllers\NdmaVerificationController::class, 'pdmadatalist_export'])->name('pdmadatalist_export');

});

Route::prefix('admin/finance')->middleware('auth.redirect')->group(function () {
    Route::controller(FinanceController::class)->group(function () {
        Route::get('/already/account',  'financeList')->name('financeList');
        Route::get('/wholeWithAccountData',  'wholeWithAccountData')->name('wholeWithAccountData');
        Route::get('/beneficiary/account',  'beneficiaryAccounntVerification')->name('beneficiaryAccounntVerification');
        Route::get('/beneficiary/withoutAccount',  'beneficiaryWithoutAccount')->name('beneficiaryWithoutAccount');
        Route::get('/beneficiary/bioMetric',  'beneficiaryBioMetric')->name('beneficiaryBioMetric');
        Route::get('/beneficiary/ready',  'beneficiaryReady')->name('beneficiaryReady');
        Route::get('/download',  'downloadFinance')->name('download.finance');
        Route::get('/verify/account/{id}',  'verifyAccount')->name('verify.account');
        Route::get('/verify/biometric/account/{id}',  'bioMetricAccount')->name('verify.biometric.account');
        Route::get('/edit/account/{id}',  'editAccount')->name('editAccount');
        Route::post('/update/account/{id}',  'updateAccount')->name('updateAccount');
        Route::post('/update/bio/metric',  'updateBioMetricList')->name('updateBioMetricList');
        Route::post('/wholeWithAccountFetchData',  'wholeWithAccountFetchData')->name('wholeWithAccountFetchData');
        Route::post('/change_name','change_name')->name('change_name');
        Route::get('/upload/bank/account',  'upload_bank_account')->name('upload.bank.account');
        Route::post('/upload/account',  'uploadAccount')->name('uploadAccount');
        Route::get('/verify/account/{id}',  'verifyAccount')->name('verify.account');
        Route::get('/move/trench/{ref_no}/{id}',  'moveToFirstTrench')->name('moveToFirstTrench');
        Route::post('/move/bulk/trench',  'moveToFirstTrenchBulk')->name('moveToFirstTrenchBulk');
        Route::get('/firstTrenchList',  'firstTrenchList')->name('firstTrenchList');
        Route::get('/save/first/trench',  'save_first_trench_value')->name('save_first_trench_value');
        Route::post('/save/first/trench/data',  'save_first_trench_value_data')->name('save_first_trench_value_data');
        Route::post('/get_export_already_account_data',  'get_export_already_account_data')->name('get_export_already_account_data');
        Route::post('/get_export_without_account_data',  'get_export_without_account_data')->name('get_export_without_account_data');
        Route::post('/get_export_beneficiary_account',  'get_export_beneficiary_account')->name('get_export_beneficiary_account');
        Route::post('/get_export_ready_for_disbursment',  'get_export_ready_for_disbursment')->name('get_export_ready_for_disbursment');
        Route::post('/get_export_bio_metric_status',  'get_export_bio_metric_status')->name('get_export_bio_metric_status');
        Route::get('/withOutAccountDataFilter',  'withOutAccountDataFilter')->name('withOutAccountDataFilter');
        Route::post('/WithOutAccountFetchData',  'WithOutAccountFetchData')->name('WithOutAccountFetchData');
        Route::get('/withAccountDataFilter',  'withAccountDataFilter')->name('withAccountDataFilter');
        Route::post('/WithAccountFetchData',  'WithAccountFetchData')->name('WithAccountFetchData');
        Route::get('/beneficiaryAccountVerificationFilter',  'beneficiaryAccountVerificationFilter')->name('beneficiaryAccountVerificationFilter');
        Route::post('/beneficiaryAccountVerificationFetchData',  'beneficiaryAccountVerificationFetchData')->name('beneficiaryAccountVerificationFetchData');
        Route::get('/beneficiaryBioMetricFilter',  'beneficiaryBioMetricFilter')->name('beneficiaryBioMetricFilter');
        Route::post('/beneficiaryBioMetricFetchData',  'beneficiaryBioMetricFetchData')->name('beneficiaryBioMetricFetchData');
        Route::get('/beneficiaryDisbursmentFilter',  'beneficiaryDisbursmentFilter')->name('beneficiaryDisbursmentFilter');
        Route::post('/beneficiaryDisbursmentFetchData',  'beneficiaryDisbursmentFetchData')->name('beneficiaryDisbursmentFetchData');
        Route::get('/beneficiaryFirstTrenchFilter',  'beneficiaryFirstTrenchFilter')->name('beneficiaryFirstTrenchFilter');
        Route::post('/beneficiaryFirstTrenchFetchData',  'beneficiaryFirstTrenchFetchData')->name('beneficiaryFirstTrenchFetchData');
        Route::post('/withoutAccount_export',  'withoutAccount_export')->name('withoutAccount_export');
        Route::post('/withAccount_export',  'withAccount_export')->name('withAccount_export');
    });
});
Route::prefix('report')->middleware('auth.redirect')->group(function () {
    Route::get('/beneficiary', [App\Http\Controllers\ReportingController::class, 'beneficiaryReport'])->name('beneficiaryReport');
    Route::post('filter/beneficiary', [App\Http\Controllers\ReportingController::class, 'filterBeneficiaryReport'])->name('filterBeneficiaryReport');
    Route::post('filter/beneficiary/detail', [App\Http\Controllers\ReportingController::class, 'filterBeneficiaryReportDetail'])->name('filterBeneficiaryReportDetail');
    Route::get('filter/filterBeneficiaryReportTest', [App\Http\Controllers\ReportingController::class, 'filterBeneficiaryReportTest'])->name('filterBeneficiaryReportTest');
    Route::get('detail/pdna', [App\Http\Controllers\ReportingController::class, 'pdnaReportDetail'])->name('pdnaReportDetail');
    Route::get('form/beneficairy', [App\Http\Controllers\ReportingController::class, 'beneficiaryFormReport'])->name('beneficiaryFormReport');
    Route::get('form/tracking/report', [App\Http\Controllers\ReportingController::class, 'form_status_tracking'])->name('form_status_tracking');
    Route::post('get/form/tracking/report', [App\Http\Controllers\ReportingController::class, 'get_form_status_tracking'])->name('get_form_status_tracking');
    Route::get('validation/form/status', [App\Http\Controllers\ReportingController::class, 'validationFormStatus'])->name('validationFormStatus');
    Route::get('validation/form/status/field', [App\Http\Controllers\ReportingController::class, 'validationFormStatusForField'])->name('validationFormStatusForField');
    Route::get('validation/form/status/IP', [App\Http\Controllers\ReportingController::class, 'validationFormStatusIP'])->name('validationFormStatusIP');
    Route::get('validation/form/status/HRU', [App\Http\Controllers\ReportingController::class, 'validationFormStatusHRU'])->name('validationFormStatusHRU');
    Route::get('validation/form/status/psia', [App\Http\Controllers\ReportingController::class, 'validationFormStatusPSIA'])->name('validationFormStatusPSIA');
    Route::get('validation/form/status/hru_main', [App\Http\Controllers\ReportingController::class, 'validationFormStatusHruMain'])->name('validationFormStatusHruMain');
    Route::get('validation/form/status/coo', [App\Http\Controllers\ReportingController::class, 'validationFormStatusCOO'])->name('validationFormStatusCOO');
    Route::get('validation/form/status/ceo', [App\Http\Controllers\ReportingController::class, 'validationFormStatusCEO'])->name('validationFormStatusCEO');
    
     
    Route::post('fetch_district_list', [App\Http\Controllers\ReportingController::class, 'fetch_district_list'])->name('report.fetch_district_list');
    Route::post('fetch_tehsil_list', [App\Http\Controllers\ReportingController::class, 'fetch_tehsil_list'])->name('report.fetch_tehsil_list');
    Route::post('fetch_uc_list', [App\Http\Controllers\ReportingController::class, 'fetch_uc_list'])->name('report.fetch_uc_list');



    Route::post('fetch_district_list_ac_name', [App\Http\Controllers\ReportingController::class, 'fetch_district_list_ac_name'])->name('report.fetch_district_list_ac_name');
    Route::post('fetch_tehsil_list_ac_name', [App\Http\Controllers\ReportingController::class, 'fetch_tehsil_list_ac_name'])->name('report.fetch_tehsil_list_ac_name');
    Route::post('fetch_uc_list_ac_name', [App\Http\Controllers\ReportingController::class, 'fetch_uc_list_ac_name'])->name('report.fetch_uc_list_ac_name');





    Route::get('survey/datalist/optimize', [App\Http\Controllers\MasterReportController::class, 'report_survey_datalist_optimize'])->name('report_survey_datalist_optimize');
    Route::post('report_survey_datalist_fetch_data_optimize', [App\Http\Controllers\MasterReportController::class, 'report_survey_datalist_fetch_data_optimize'])->name('report_survey_datalist_fetch_data_optimize');
    
    Route::get('survey/datalist', [App\Http\Controllers\ReportingController::class, 'report_survey_datalist'])->name('report_survey_datalist');
    Route::post('report_survey_datalist_fetch_data', [App\Http\Controllers\ReportingController::class, 'report_survey_datalist_fetch_data'])->name('report_survey_datalist_fetch_data');
    Route::post('report_survey_datalist_export', [App\Http\Controllers\ReportingController::class, 'report_survey_datalist_export'])->name('report_survey_datalist_export');
    Route::get('report_trail_history', [App\Http\Controllers\ReportingController::class, 'report_trail_history'])->name('report_trail_history');
    
    Route::get('finance_trail_history', [App\Http\Controllers\ReportingController::class, 'finance_trail_history'])->name('finance_trail_history');
    
    
    
    //Data Report Limitation
    Route::get('survey/customdatalist', [App\Http\Controllers\ReportingController::class, 'report_survey_customdatalist'])->name('report_survey_customdatalist');
    Route::post('report_survey_customdatalist_fetch_data', [App\Http\Controllers\ReportingController::class, 'report_survey_customdatalist_fetch_data'])->name('report_survey_customdatalist_fetch_data');
    Route::post('report_survey_customdatalist_export', [App\Http\Controllers\ReportingController::class, 'report_survey_customdatalist_export'])->name('report_survey_customdatalist_export');
    
    
    //Data Report Sectionwise
    Route::get('survey/sectionwise_datalist', [App\Http\Controllers\ReportingController::class, 'report_sectionwise_datalist'])->name('report_sectionwise_datalist');
    Route::post('report_sectionwise_datalist_fetch_data', [App\Http\Controllers\ReportingController::class, 'report_sectionwise_datalist_fetch_data'])->name('report_sectionwise_datalist_fetch_data');
    Route::post('report_sectionwise_datalist_export', [App\Http\Controllers\ReportingController::class, 'report_sectionwise_datalist_export'])->name('report_sectionwise_datalist_export');
    
    
    
    
    Route::get('report_ceo_export', [App\Http\Controllers\ReportingController::class, 'report_ceo_export'])->name('report_ceo_export');
    
    Route::get('managesurveyreport', [App\Http\Controllers\ReportingController::class, 'managesurveyreport'])->name('managesurveyreport');
    Route::post('managesurveyreportsubmit', [App\Http\Controllers\ReportingController::class, 'managesurveyreportsubmit'])->name('managesurveyreportsubmit');
    Route::get('report_trail_form', [App\Http\Controllers\ReportingController::class, 'report_trail_form'])->name('report_trail_form');
    
    Route::get('masterreport_trail_form', [App\Http\Controllers\ReportingController::class, 'masterreport_trail_form'])->name('masterreport_trail_form');
    Route::patch('masterreportupdate', [App\Http\Controllers\ReportingController::class, 'masterreportupdate'])->name('masterreportupdate');
    
    
    Route::post('formstatusstore', [App\Http\Controllers\ReportingController::class, 'formstatusstore'])->name('formstatusstore');
    Route::patch('formstatusupdate', [App\Http\Controllers\ReportingController::class, 'formstatusupdate'])->name('formstatusupdate');
    
    Route::post('reportdetailstore', [App\Http\Controllers\ReportingController::class, 'reportdetailstore'])->name('reportdetailstore');
    Route::patch('reportdetailupdate', [App\Http\Controllers\ReportingController::class, 'reportdetailupdate'])->name('reportdetailupdate');
    
    Route::get('report_trail_delete/{id}', [App\Http\Controllers\ReportingController::class, 'report_trail_delete'])->name('report_trail_delete');
    Route::get('formstatus_trail_delete/{id}', [App\Http\Controllers\ReportingController::class, 'formstatus_trail_delete'])->name('formstatus_trail_delete'); 

    Route::get('summary', [App\Http\Controllers\ReportingController::class, 'master_report_summary'])->name('master_report_summary');
    Route::get('weekly/summary/report', [App\Http\Controllers\ReportingController::class, 'WeeklySummaryReport'])->name('weekly_summary_report');
    Route::get('export/large/csv', [App\Http\Controllers\ReportingController::class, 'exportlargeCsv'])->name('export_large_csv');
    
    
});




 


Route::get('/filter/lot', [App\Http\Controllers\HomeController::class, 'filter_lot'])->name('filter_lot');
Route::get('/filter/districts', [App\Http\Controllers\HomeController::class, 'filter_districts'])->name('filter_districts');
Route::get('/filter/tehsil', [App\Http\Controllers\HomeController::class, 'filter_tehsil'])->name('filter_tehsil');
Route::get('/filter/uc', [App\Http\Controllers\HomeController::class, 'filter_uc'])->name('filter_uc');
Route::get('update/form/status', [FormController::class, 'update_form_status']);
Route::post('bulk/approved', [FormController::class, 'bulkApprove'])->name('bulkApprove');
Route::get('destructureForm/{id}', [FormController::class, 'destructureForm']);
Route::post('add/comment', [FormController::class, 'add_comment'])->name('add_comment');
Route::get('prority/data', [FormController::class, 'priority_form']);


Route::get('testing/jobs', function(){
    dispatch(new App\Jobs\DestructureForm(24));
    return "job testing";
    

});


Route::get('/hash_password',function(){
   dd(Hash::make('12345678')); 
});





//Route::get('/saveImage', [App\Http\Controllers\AdminController::class, 'saveImage'])->name('saveImage');
Route::get('/saveReport', [App\Http\Controllers\AdminController::class, 'saveReport'])->name('saveReport');
Route::get('/beneficiaryProfileNewTest/{id}', [App\Http\Controllers\AdminController::class, 'beneficiaryProfileNewTest'])->name('beneficiaryProfileNewTest');
Route::get('/userrejected', [App\Http\Controllers\AdminController::class, 'userrejected'])->name('userrejected');
//Route::get('/save/survey/json/{start}/{end}', [App\Http\Controllers\AdminController::class, 'saveSurveyJson'])->name('saveSurveyJson');




Route::prefix('admin/environment/case')
    ->middleware('auth.redirect')
    ->group(function () {
        Route::get('/list', [EnvironmentCaseController::class, 'index'])->name('environment_case.list');
        Route::post('/total-environment-datalist', [EnvironmentCaseController::class, 'fetchTotalEnvironmentDatalist'])->name('environment_case.fetch_datalist');
        Route::get('/{id}/view', [EnvironmentCaseController::class, 'view'])->name('environment_case.view');
        Route::get('/status-trail-history', [EnvironmentCaseController::class, 'statusTrailHistory'])->name('environment_case.status_trail_history');
        Route::post('/action-form', [EnvironmentCaseController::class, 'showActionForm'])->name('environment_case.action_form');
        Route::post('/take-action', [EnvironmentCaseController::class, 'submitActionForm'])->name('environment_case.action_form.submit');
        Route::post('/datalist/export/', [App\Http\Controllers\EnvironmentCaseController::class, 'environment_datalist_mitigation_export'])->name('environment_datalist_mitigation_export');
        Route::post('/add/comment/view', [App\Http\Controllers\EnvironmentCaseController::class, 'add_comment_view'])->name('environment_case.add_comment.view');
        Route::post('/upload/comment', [App\Http\Controllers\EnvironmentCaseController::class, 'upload_comment'])->name('environment_case.upload_comment');
      
        Route::get('/delete/comment/{question_id}/{survey_id}', [App\Http\Controllers\EnvironmentCaseController::class, 'delete_comment'])->name('environment_case.delete_comment');
    
        
    });









/*Added By Ayaz Ahmed*/
//Frontend Route
Route::prefix('grm')->controller(FComplaintController::class)->group(function(){
	//Route::get('/refresh_captcha', 'refresh_captcha')->name('refresh_captcha');
	Route::get('/complaint', 'complaintform')->name('complaintform');
    Route::post('/complaint', 'complaintsubmit')->name('complaintsubmit');
    Route::post('/fetch_district_list', 'fetch_district_list')->name('complaints.fetch_district_list');
    Route::post('/fetch_tehsil_list', 'fetch_tehsil_list')->name('complaints.fetch_tehsil_list');
    Route::post('/fetch_uc_list', 'fetch_uc_list')->name('complaints.fetch_uc_list');
    
    Route::get('/trackcomplaint', 'trackcomplaint')->name('trackcomplaint');
	Route::post('/trackcomplaintsubmit', 'trackcomplaintsubmit')->name('trackcomplaintsubmit');
	
	//Route::post('feedbackform', 'feedbackform')->name('feedbackform');
    //Route::post('feedbackformsubmit', 'feedbackformsubmit')->name('feedbackformsubmit');
    
    Route::post('/getcomplaintdetail', 'getcomplaintdetail')->name('getcomplaintdetail');
    
	//Route::get('/test', 'test')->name('complaints.test');
});

//Backend Route
Route::prefix('admin/changebeneficiary')->middleware('auth.redirect')->controller(ChangeBeneficiaryController::class)->group(function () {
        Route::get('/', 'index')->name('changebeneficiary.index');
        Route::get('create', 'create')->name('changebeneficiary.create');
        Route::post('/', 'store')->name('changebeneficiary.store');
        Route::get('{id?}/show', 'show')->name('changebeneficiary.show');
        Route::get('{id?}/edit', 'edit')->name('changebeneficiary.edit');
        Route::patch('{id?}', 'update')->name('changebeneficiary.update');
        Route::delete('{id?}', 'delete')->name('changebeneficiary.delete');
        Route::patch('{id?}/status', 'status')->name('changebeneficiary.status');
        
    
    Route::post('/total_changebeneficiary_datalist_fetch_data', 'fetch_datalist')->name('fetch_datalist');
    //Route::post('/datalist_export', 'datalist_export')->name('datalist_export');
    Route::get('/changebeneficiary_status_trail_history', 'changebeneficiary_status_trail_history')->name('changebeneficiary_status_trail_history');
    Route::post('/changebeneficiary_action_form', 'changebeneficiary_action_form')->name('changebeneficiary_action_form');
    Route::post('/changebeneficiary_action_form_submit', 'changebeneficiary_action_form_submit')->name('changebeneficiary_action_form_submit');
    
        
        
});



Route::prefix('admin/grm/complaints')->middleware('auth.isgrm')->controller(ComplaintController::class)->group(function(){
       Route::post('/followupstore/{complaint}', 'followupstore')->name('complaints.followupstore');
	   Route::post('/fetch_grm_users', 'fetch_grm_users')->name('complaints.fetch_grm_users');
	   Route::post('/assigncomplaint', 'assigncomplaint')->name('complaints.assigncomplaint'); 
	   Route::get('/pending', 'pending')->name('complaints.pending');
	   Route::get('/closed', 'closed')->name('complaints.closed');
	   Route::get('/inprocess', 'inprocess')->name('complaints.inprocess');
	   Route::get('/returned', 'returned')->name('complaints.returned');
	   Route::get('/forward', 'forward')->name('complaints.forward');

	   Route::get('/today_total', 'today_total_complaint')->name('complaints.today_total');
	   Route::get('/closed_today_total', 'closed_today_total_complaint')->name('complaints.closed_today_total');
	   Route::get('/inprocess_today_total', 'inprocess_today_total_complaint')->name('complaints.inprocess_today_total');
	   Route::get('/returned_today_total', 'returned_today_total_complaint')->name('complaints.returned_today_total');
	   Route::get('/exclusioncases_complaint', 'exclusioncases_complaint')->name('complaints.exclusioncases_complaint');
	   Route::get('/today_exclusioncases_complaint', 'today_exclusioncases_complaint')->name('complaints.exclusioncases_today');
	   
	   
});
Route::resource('admin/grm/complaints', ComplaintController::class)->middleware('auth.isgrm');


Route::prefix('admin/grm/grievance_type')->middleware('auth.isgrm')->controller(GrievanceTypeController::class)->group(function(){
       Route::get('/{id}/delete', 'delete')->name('grievance_type.delete');
	   Route::get('/{id}/status', 'status')->name('grievance_type.status');
});
Route::resource('admin/grm/grievance_type', GrievanceTypeController::class)->middleware('auth.isgrm');

Route::prefix('admin/grm/piu')->middleware('auth.isgrm')->controller(PIUController::class)->group(function(){
       Route::get('/{id}/delete', 'delete')->name('piu.delete');
	   Route::get('/{id}/status', 'status')->name('piu.status');
});
Route::resource('admin/grm/piu', PIUController::class)->middleware('auth.isgrm');

Route::prefix('admin/grm/source_channel')->middleware('auth.isgrm')->controller(SourceChannelController::class)->group(function(){
       Route::get('/{id}/delete', 'delete')->name('source_channel.delete');
	   Route::get('/{id}/status', 'status')->name('source_channel.status');
});
Route::resource('admin/grm/source_channel', SourceChannelController::class)->middleware('auth.isgrm');
Route::get('/set/status', [AdminController::class, 'setStatus']);
Route::get('/found_data', [AdminController::class, 'found_data']);
Route::get('/update_answer_for_name', [AdminController::class, 'update_answer_for_name_data']);
Route::get('/update_answer_for_cnic', [AdminController::class, 'update_answer_for_cnic']);
Route::get('/correctDataProcess', [AdminController::class, 'correctDataProcess']);
Route::get('/updateAllData', [AdminController::class, 'updateAllData']);
Route::get('/destructure_testt123', [AdminController::class, 'destructure_testt123']);
Route::get('/correct/update/status', [AdminController::class, 'CorrectupdateStatus']);
/*ENDED ROUTES By Ayaz Ahmed*/

