<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NdmaVerification;
use App\Imports\NdmaImport;

use App\Models\SurveyData;
use App\Models\SurveyQuestionAnswer;
use App\Models\SurveyReportSection35;
use App\Models\Lot;
use App\Models\District;
use App\Models\Tehsil;
use App\Models\UC;
use App\Models\FormStatus;
use App\Models\MasterReport;
use App\Models\MasterReportDetail;
use App\Models\QuestionTitle;
use App\Models\Option;
use App\Models\QuestionsAcceptReject;
use App\Models\Answer;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Facades\Log;
use Auth;
use DB;
use Carbon\Carbon;
use App\Exports\SurveyDataExport;
use App\Exports\SurveyCustomDataExport;
use App\Exports\ReportSectionwiseDatalistExport;
use Excel;
class MasterReportController extends Controller
{
    
    
    public function report_survey_datalist_optimize()
    {
		if(Auth::user()->role==1 || Auth::user()->role==30 || Auth::user()->role==34 || Auth::user()->role==36 || Auth::user()->role==37 || Auth::user()->role==38 || Auth::user()->role==40 || Auth::user()->role==48 || Auth::user()->role==51){
		  $lots = Lot::whereIn('id', json_decode(Auth::user()->lot_id))->pluck('name','id')->all();

        $gender = Option::where('question_id', 652)->pluck('name', 'name');
        $landownership = Option::where('question_id', 240)->pluck('name', 'name');
        $construction_type = Option::where('question_id', 760)->pluck('name', 'name');
        
        $socio_legal_status = Option::where('question_id', 246)->pluck('name', 'name');
        $evidence_type = Option::where('question_id', 247)->pluck('name', 'name');
        $status_of_land = Option::where('question_id', 243)->pluck('name', 'name');
        
        $vulnerabilities = Option::where('question_id', 2243)->pluck('name', 'id');

		return view('dashboard.masterReport.report_survey_datalist', compact('lots', 'gender','landownership','construction_type','socio_legal_status','evidence_type','status_of_land','vulnerabilities'));
		
		}else{
         return redirect()->route('admin.dashboard')->with([ 'error' => 'You are not authorized this page!']);
        }
    }
    
    
    
    
    public function report_survey_datalist_fetch_data_optimize(Request $request, SurveyData $surveydata)
{
    
    //dump($request->all());
    
    $page = $request->get('ayis_page');
    $qty = $request->get('qty');
    $custom_pagination_path = '';

    $filters = $request->only([
        'lot_id', 'district_id', 'tehsil_id', 'uc_id', 'gender', 'disability', 'vulnerable', 'vulnerabilities',
        'landownership', 'bank_ac_wise', 'reconstruction_wise', 'construction_type', 'damage_type',
        'department', 'form_status', 'status', 'not_action', 'proposed_beneficiary', 'socio_legal_status',
        'evidence_type', 'status_of_land', 'tranche', 'b_reference_number', 'beneficiary_name', 'cnic', 'pending_days'
    ]);

    $sorting = $request->get('sorting', 'created_at');
    $order = $request->get('direction', 'desc');

    $surveydata = $surveydata->newQuery();

    // Apply Date Filter
    if ($request->has(['start_date', 'end_date'])) {
        $start_date = Carbon::parse($request->get('start_date'))->startOfDay();
        $end_date = Carbon::parse($request->get('end_date'))->endOfDay();
        //$surveydata->whereBetween('created_at', [$start_date, $end_date]);
    }

    // Dynamic Filters
    foreach ($filters as $column => $value) {
        if ($value !== null) {
            if ($column === 'vulnerabilities') {
                $surveydata->whereRaw("JSON_CONTAINS(q_2243, JSON_OBJECT('option_id', ?), '$')", [(int) $value]);
            } elseif ($column === 'beneficiary_name') {
                $surveydata->where('beneficiary_details->beneficiary_name', 'like', "%$value%");
            } elseif ($column === 'pending_days') {
                $this->applyPendingDaysFilter($surveydata, $value);
            } elseif ($column === 'tranche') {
                $surveydata->whereHas('getverifybeneficairytranche', fn ($q) => $q->where('trench_no', $value));
            } elseif ($column === 'department') {
                dump($value);
            } elseif ($column === 'status') {
                dump($value);
            } else {
                $surveydata->where($column, $value);
            }
        }
    }

    // Auth-based conditions
    //$this->applyAuthFilters($surveydata);

    // Status Filters
    //$this->applyStatusFilters($surveydata, $filters['status'] ?? null, $filters['department'] ?? null);

    // Bulk Actions
    $bulk_survey_id = $this->getBulkSurveyId($request, $surveydata);

    // Sorting & Pagination
    $data = $surveydata->orderBy($sorting, $order)->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);

    // Transform Data
    $selected_data = []; //$data->map(fn ($item) => $this->transformSurveyData($item, $filters['status'] ?? null, $filters['department'] ?? null));
    $jsondata = json_encode($selected_data);

    return view('dashboard.masterReport.pagination_report_survey_datalist', compact('data', 'jsondata', 'bulk_survey_id'))->render();
}

/**
 * Applies pending days filter to query.
 */
private function applyPendingDaysFilter($query, $pending_days)
{
    $dateRanges = [
        4  => [Carbon::now()->subDays(6), null],
        5  => [Carbon::now()->subDays(11), Carbon::now()->subDays(6)],
        10 => [Carbon::now()->subDays(16), Carbon::now()->subDays(11)],
        15 => [null, Carbon::now()->subDays(16)],
    ];

    if (!isset($dateRanges[$pending_days])) {
        return;
    }

    [$startDate, $endDate] = $dateRanges[$pending_days];

    $query->where('m_status', 'P')->where(function ($q) use ($startDate, $endDate) {
        $q->whereNotNull('m_last_action_date');
        if ($startDate && $endDate) {
            $q->whereBetween('m_last_action_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $q->where('m_last_action_date', '>=', $startDate);
        } else {
            $q->where('m_last_action_date', '<=', $endDate);
        }

        $q->orWhere(function ($subQuery) use ($startDate, $endDate) {
            $subQuery->whereNull('m_last_action_date');
            if ($startDate && $endDate) {
                $subQuery->whereBetween('created_at', [$startDate, $endDate]);
            } elseif ($startDate) {
                $subQuery->where('created_at', '>=', $startDate);
            } else {
                $subQuery->where('created_at', '<=', $endDate);
            }
        });
    });
}

/**
 * Applies user role-based filters to the query.
 */
private function applyAuthFilters($query)
{
    $user = Auth::user();
    
    $query->whereIn('lot_id', json_decode($user->lot_id))
          ->whereIn('district_id', json_decode($user->district_id));
          //->whereIn('tehsil_id', json_decode($user->tehsil_id))
          //->whereIn('uc_id', json_decode($user->uc_id));

    if ($user->role == 51 && request()->has('review_by_mne')) {
        $review_by_mne = request()->get('review_by_mne') === 'Yes' ? 1 : 0;
        $query->where('review_by_mne', $review_by_mne);
    }
}

/**
 * Applies status-based filters.
 */
private function applyStatusFilters($query, $status, $department)
{
    if (!$status) {
        return;
    }

    $userRole = Auth::user()->role;

    if ($status == 'P' && $userRole != 51) {
        $upper_role = upper_role_id_master_report();
        $upper_rejected_ids = SurveyData::where('m_last_action_role_id', $upper_role->id)->where('m_last_action', 'R')->pluck('id');
        $query->where('m_status', $status)->whereNotIn('id', $upper_rejected_ids);
    } elseif ($status == 'A') {
        $updateByMapping = [30 => 'field supervisor', 34 => 'IP', 36 => 'HRU', 37 => 'PSIA', 38 => 'HRU_MAIN', 40 => 'CEO', 48 => 'Finance'];

        $query->whereHas('getformstatusold', function ($q) use ($department, $updateByMapping) {
            if ($department && isset($updateByMapping[$department])) {
                $q->where('update_by', $updateByMapping[$department]);
            }
            $q->where('form_status', 'A');
        });
    } elseif ($status == 'R') {
        $query->where('m_last_action_role_id', $department)->where('m_last_action', $status);
    }
}

/**
 * Retrieves bulk survey IDs.
 */
private function getBulkSurveyId($request, $query)
{
    if (!$request->has('bulkaction')) {
        return $request->get('bulk_survey_id');
    }

    return $request->get('bulkaction') == 1 ? $query->pluck('id') : '';
}

/**
 * Transforms survey data for output.
 */
private function transformSurveyData($item, $status = null, $department = null)
{
    $beneficiaryDetails = json_decode($item->beneficiary_details);
    $formstatus = $item->getformstatusold()
        ->where('form_status', $status)
        ->where('update_by', $department)
        ->first();

    return [
        'sid' => $item->id,
        'date' => Carbon::parse($item->created_at)->format('d-m-Y'),
        'ref_no' => $item->ref_no ?? '',
        //'department' => get_role_name($item->m_role_id) ?? '',
        'formstatus' => $item->m_status,
        'beneficiary_name' => $item->beneficiary_name ?? '',
        'cnic' => $item->cnic ?? '',
        'father_name' => $beneficiaryDetails->father_name ?? '',
        'user_name' => $item->getuser->name ?? '',
        'form_name' => $item->getform->name ?? '',
        'lot' => $item->getlot->name ?? '',
        'district' => $item->getdistrict->name ?? '',
        'tehsil' => $item->gettehsil->name ?? '',
        'uc' => $item->getuc->name ?? '',
        'generated_id' => $item->generated_id ?? ''
    ];
}


    
    

}