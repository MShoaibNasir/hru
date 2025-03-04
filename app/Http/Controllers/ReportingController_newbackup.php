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
class ReportingController extends Controller
{
    public function beneficiaryReport()
    {
       
        $result = DB::select(DB::raw("
         SELECT 
            lots.name AS lotName,
            lots.id AS lotId,
            districts.id as districtId,
            districts.name AS district,
            total_beneficiaries.total_beneficiary,
            validated_beneficiaries.validated_beneficiary
        FROM 
            lots
        LEFT JOIN 
            districts ON lots.id = districts.lot_id
        LEFT JOIN (
            SELECT 
                district, 
                COUNT(DISTINCT id) AS total_beneficiary
            FROM 
                ndma_verifications
            GROUP BY 
                district
        ) AS total_beneficiaries ON districts.id = total_beneficiaries.district
        LEFT JOIN (
            SELECT 
                district_id, 
                COUNT(DISTINCT id) AS validated_beneficiary
            FROM 
                vu_survey_formReport
            GROUP BY 
                district_id
        ) AS validated_beneficiaries ON districts.id = validated_beneficiaries.district_id
        GROUP BY lots.id,districts.id,lots.name,
            districts.name,
            total_beneficiaries.total_beneficiary,
            validated_beneficiaries.validated_beneficiary
            
    "));
    
        return view('dashboard.report.beneficiaryReport',['result'=>$result]);
    }
    
/*
   public function filterBeneficiaryReport(Request $request) {
   
    $query = District::query();
 


    if ($request->filled('lot')) {
        $query->where('districts.lot_id', $request->lot);
    }

    
    $query->leftJoin('ndma_verifications', 'districts.id', '=', 'ndma_verifications.district')
          ->leftJoin('survey_form', 'districts.id', '=', 'survey_form.district_id')
          ->select(
              'districts.name as district',
              DB::raw('count(DISTINCT ndma_verifications.id) as total_beneficiary'),
              DB::raw('count(DISTINCT survey_form.id) as validated_beneficiary')
          )
          ->groupBy('districts.id', 'districts.name');

   
    if ($request->filled('from') && $request->filled('to')) {
     
        $fromDate = $request->from;
        $toDate = $request->to;

        if ($fromDate === $toDate) {

            $toDate = Carbon::parse($toDate)->endOfDay()->toDateTimeString();
        }

     
        $query->whereBetween('survey_form.created_at', [$fromDate, $toDate]);
    }
   
  
    try {
        $results = $query->get();
    } catch (\Exception $e) {
     
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching the data: ' . $e->getMessage()
        ], 500); 
    }

 
    return response()->json([
        'success' => true,
        'data' => $results

}

*/




public function filterBeneficiaryReport(Request $request) {
    $query = DB::table('lots')
        ->select(
            'lots.name AS lotName',
            'lots.id AS lotId',
            'districts.id AS districtId',
            'districts.name AS district',
            'total_beneficiaries.total_beneficiary',
            'validated_beneficiaries.validated_beneficiary'
        )
        ->leftJoin('districts', 'lots.id', '=', 'districts.lot_id')
        ->leftJoin(
            DB::raw('(SELECT district, COUNT(DISTINCT id) AS total_beneficiary FROM ndma_verifications GROUP BY district) AS total_beneficiaries'),
            'districts.id',
            '=',
            'total_beneficiaries.district'
        )
        ->leftJoin(
            DB::raw('(SELECT district_id, COUNT(DISTINCT id) AS validated_beneficiary FROM vu_survey_formReport GROUP BY district_id) AS validated_beneficiaries'),
            'districts.id',
            '=',
            'validated_beneficiaries.district_id'
        )
       
        ->leftJoin('vu_survey_formReport', 'districts.id', '=', 'vu_survey_formReport.district_id');

  
    if ($request->filled('lot')) {
        $query->where('lots.id', $request->lot);
    }


    if ($request->filled('from') && $request->filled('to')) {
        $fromDate = Carbon::parse($request->from)->startOfDay()->toDateTimeString(); // Ensure start of the day
        $toDate = Carbon::parse($request->to)->endOfDay()->toDateTimeString(); // Ensure end of the day

        $query->whereBetween('vu_survey_formReport.created_at', [$fromDate, $toDate]);
    }

    // Apply grouping to match SQL behavior
    $query->groupBy(
        'lots.id',
        'districts.id',
        'lots.name',
        'districts.name',
        'total_beneficiaries.total_beneficiary',
        'validated_beneficiaries.validated_beneficiary'
    );
    
   

    try {
        // Get the results
        $results = $query->get();

        return response()->json([
            'success' => true,
            'data' => $results
        ]);

    } catch (\Exception $e) {
        // Return error message in case of exception
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching the data: ' . $e->getMessage()
        ], 500);
    }
}
public function filterBeneficiaryReportDetail(Request $request) {
    try {
        // Start building the query
        $query = DB::table('lots')
            ->select(
                'lots.name AS lotName',
                'lots.id AS lotId',
                'districts.id AS districtId',
                'districts.name AS district',
                'total_beneficiaries.total_beneficiary',
                'validated_beneficiaries.validated_beneficiary',

                DB::raw("COUNT(DISTINCT CASE WHEN form_status.update_by = 'IP' AND form_status.form_status = 'P' THEN vu_survey_formReport.id END) AS IPFormCount"),
                DB::raw("COUNT(DISTINCT CASE WHEN form_status.update_by = 'field supervisor' AND form_status.form_status = 'P' THEN vu_survey_formReport.id END) AS field_super_visor_count")
            )
            ->leftJoin('districts', 'lots.id', '=', 'districts.lot_id')
            
            // Join to count total beneficiaries
            ->leftJoin(
                DB::raw('(SELECT district, COUNT(DISTINCT id) AS total_beneficiary FROM ndma_verifications GROUP BY district) AS total_beneficiaries'),
                'districts.id', '=', 'total_beneficiaries.district'
            )
            
            // Join to count validated beneficiaries
            ->leftJoin(
                DB::raw('(SELECT district_id, COUNT(DISTINCT id) AS validated_beneficiary FROM vu_survey_formReport GROUP BY district_id) AS validated_beneficiaries'),
                'districts.id', '=', 'validated_beneficiaries.district_id'
            )
            
            // Join to the vu_survey_formReport table
            ->leftJoin('vu_survey_formReport', 'districts.id', '=', 'vu_survey_formReport.district_id')

            // Join to the form_status table to get role information
            ->leftJoin('form_status', 'vu_survey_formReport.id', '=', 'form_status.form_id');

        // Apply filters from the request
        if ($request->filled('lot')) {
            $query->where('lots.id', $request->lot);
        }

        if ($request->filled('from') && $request->filled('to')) {
            $fromDate = Carbon::parse($request->from)->startOfDay()->toDateTimeString();
            $toDate = Carbon::parse($request->to)->endOfDay()->toDateTimeString();
            $query->whereBetween('vu_survey_formReport.created_at', [$fromDate, $toDate]);
        }

        // Group by all the relevant columns
        $query->groupBy(
            'lots.id',
            'districts.id',
            'lots.name',
            'districts.name',
            'total_beneficiaries.total_beneficiary',
            'validated_beneficiaries.validated_beneficiary'
        );

        // Execute the query
        $results = $query->get();

        return response()->json([
            'success' => true,
            'data' => $results
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching the data: ' . $e->getMessage()
        ], 500);
    }
}



public function beneficiaryFormReport(Request $request){
   
   return view("dashboard.report.beneficiaryFormReport");
}
public function Form_status_tracking(Request $request){
   
   return view("dashboard.report.form_status_tracking");
}

public function get_form_status_tracking(Request $request){
    
    $data=DB::table('survey_form')->where('ref_no',$request->number)
    ->join('form_status','survey_form.id','=','form_status.form_id')
    ->join('users','users.id','=','form_status.user_id')
    ->select('form_status.form_status','form_status.update_by','form_status.comment','users.name as user_name','form_status.created_at AS created_date')->get();
    return $data;
}







public function validationFormStatus()
{
       
        $result=$this->commonQueryForReport();
        return view("dashboard.report.validationFormStatus",['result'=>$result]);
    }
public function validationFormStatusForField()
{
       
        $result=$this->commonQueryForReport();
        return view("dashboard.report.validationFormStatusField",['result'=>$result]);
}
public function validationFormStatusIP()
{
       
        $result=$this->commonQueryForReport();
        return view("dashboard.report.validationFormStatusIP",['result'=>$result]);
}
public function validationFormStatusHRU()
{
       
        $result=$this->commonQueryForReport();
        return view("dashboard.report.validationFormStatusHRU",['result'=>$result]);
}
public function validationFormStatusPSIA()
{
       
        $result=$this->commonQueryForReport();
        return view("dashboard.report.validationFormStatusPSIA",['result'=>$result]);
}
public function validationFormStatusHruMain()
{
       
        $result=$this->commonQueryForReport();
        return view("dashboard.report.validationFormStatusHruMain",['result'=>$result]);
}

public function validationFormStatusCOO()
{
    $result=$this->commonQueryForReport();
    return view("dashboard.report.validationFormStatusCOO",['result'=>$result]);
}
public function validationFormStatusCEO()
{
    $result=$this->commonQueryForReport();
    return view("dashboard.report.validationFormStatusCEO",['result'=>$result]);
}
    
    
public function commonQueryForReport(){
    
      $result = DB::select(DB::raw("
        SELECT 
            lots.name AS lotName,
            lots.id AS lotId,
            districts.id as districtId,
            districts.name AS district,
            total_beneficiaries.total_beneficiary,
            validated_beneficiaries.validated_beneficiary
        FROM 
            lots
        LEFT JOIN 
            districts ON lots.id = districts.lot_id
        LEFT JOIN (
            SELECT 
                district, 
                COUNT(DISTINCT id) AS total_beneficiary
            FROM 
                ndma_verifications
            GROUP BY 
                district
        ) AS total_beneficiaries ON districts.id = total_beneficiaries.district
        LEFT JOIN (
            SELECT 
                district_id, 
                COUNT(DISTINCT id) AS validated_beneficiary
            FROM 
                vu_survey_formReport
            GROUP BY 
                district_id
        ) AS validated_beneficiaries ON districts.id = validated_beneficiaries.district_id
        GROUP BY lots.id,districts.id,lots.name,
            districts.name,
            total_beneficiaries.total_beneficiary,
            validated_beneficiaries.validated_beneficiary
            
    "));
    return $result;
}    






    //Ayaz Survey Report with filteration data
    public function fetch_district_list(Request $request)
    {
		$lot_id = $request->lot_id;
		$fetch_district_list = District::whereIn('id', json_decode(Auth::user()->district_id))->where('status',1)->where('lot_id', $lot_id)->pluck('name','id')->all();
		return view('frontend.grm.render.fetch_district_list',compact('fetch_district_list'))->render();
    }
    
    
    public function fetch_tehsil_list(Request $request)
    {
		$district_id = $request->district_id;
		$fetch_tehsil_list = Tehsil::whereIn('id', json_decode(Auth::user()->tehsil_id))->where('status',1)->where('district_id', $district_id)->pluck('name','id')->all();
		//$fetch_tehsil_list = DB::table('tehsil')->where('district_id', $district_id)->pluck('name','id')->all();
		return view('frontend.grm.render.fetch_tehsil_list',compact('fetch_tehsil_list'))->render();
    }
    
    public function fetch_uc_list(Request $request)
    {
		$tehsil_id = $request->tehsil_id;
		$fetch_uc_list = UC::whereIn('id', json_decode(Auth::user()->uc_id))->where('status',1)->where('tehsil_id', $tehsil_id)->pluck('name','id')->all();
		//$fetch_uc_list = DB::table('uc')->where('tehsil_id', $tehsil_id)->pluck('name','id')->all();
		return view('frontend.grm.render.fetch_uc_list',compact('fetch_uc_list'))->render();
    }
    
    
    
    
    
    
    
    
    
        // Survey Report with filteration data according to name
    public function fetch_district_list_ac_name(Request $request)
    {
		$lot_id = $request->lot_id;
	    $lot=DB::table('lots')->where('name',$lot_id)->first();
		$lot_id=$lot->id;
		$fetch_district_list = District::whereIn('id', json_decode(Auth::user()->district_id))->where('status',1)->where('lot_id', $lot_id)->pluck('name','name')->all();
		return view('frontend.grm.render2.fetch_district_list',compact('fetch_district_list'))->render();
    }
    
    
    public function fetch_tehsil_list_ac_name(Request $request)
    {
		$district_id = $request->district_id;

		$district=DB::table('districts')->where('name',$district_id)->first();
		$district_id=$district->id;
		$fetch_tehsil_list = Tehsil::whereIn('id', json_decode(Auth::user()->tehsil_id))->where('status',1)->where('district_id', $district_id)->pluck('name','name')->all();
		//$fetch_tehsil_list = DB::table('tehsil')->where('district_id', $district_id)->pluck('name','id')->all();
		return view('frontend.grm.render2.fetch_tehsil_list',compact('fetch_tehsil_list'))->render();
    }
    
    public function fetch_uc_list_ac_name(Request $request)
    {
		$tehsil_id = $request->tehsil_id;
		$tehsil=DB::table('tehsil')->where('name',$tehsil_id)->first();
		$tehsil_id=$tehsil->id;
		$fetch_uc_list = UC::whereIn('id', json_decode(Auth::user()->uc_id))->where('status',1)->where('tehsil_id', $tehsil_id)->pluck('name','name')->all();
		//$fetch_uc_list = DB::table('uc')->where('tehsil_id', $tehsil_id)->pluck('name','id')->all();
		return view('frontend.grm.render2.fetch_uc_list',compact('fetch_uc_list'))->render();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function report_survey_datalist()
    {
		if(Auth::user()->role==1 || Auth::user()->role==30 || Auth::user()->role==34 || Auth::user()->role==36 || Auth::user()->role==37 || Auth::user()->role==38 || Auth::user()->role==40 || Auth::user()->role==48 || Auth::user()->role==51){
		//$lots = Lot::pluck('name','id')->all();
		  $lots = Lot::whereIn('id', json_decode(Auth::user()->lot_id))->pluck('name','id')->all();
		//$gender = SurveyData::distinct()->pluck('gender', 'gender')->filter(function ($value) { return $value !== null; });
		//$landownership = SurveyData::distinct()->pluck('landownership', 'landownership')->filter(function ($value) { return $value !== null; });
		//$construction_type = SurveyData::distinct()->pluck('construction_type', 'construction_type')->filter(function ($value) { return $value !== null; });

        //$socio_legal_status = SurveyData::distinct()->pluck('socio_legal_status', 'socio_legal_status')->filter(function ($value) { return $value !== null; });
        //$evidence_type = SurveyData::distinct()->pluck('evidence_type', 'evidence_type')->filter(function ($value) { return $value !== null; });
        //$status_of_land = SurveyData::distinct()->pluck('status_of_land', 'status_of_land')->filter(function ($value) { return $value !== null; });
        
        $gender = Option::where('question_id', 652)->pluck('name', 'name');
        $landownership = Option::where('question_id', 240)->pluck('name', 'name');
        $construction_type = Option::where('question_id', 760)->pluck('name', 'name');
        
        $socio_legal_status = Option::where('question_id', 246)->pluck('name', 'name');
        $evidence_type = Option::where('question_id', 247)->pluck('name', 'name');
        $status_of_land = Option::where('question_id', 243)->pluck('name', 'name');
        
        $vulnerabilities = Option::where('question_id', 2243)->pluck('name', 'id');

		return view('dashboard.report.report_survey_datalist', compact('lots', 'gender','landownership','construction_type','socio_legal_status','evidence_type','status_of_land','vulnerabilities'));
		
		}else{
         return redirect()->route('admin.dashboard')->with([ 'error' => 'You are not authorized this page!']);
        }
    }
    
	
	public function report_survey_datalist_fetch_data(Request $request, SurveyData $surveydata)
	{
	    //if(Auth::user()->id==888 && Auth::user()->role==51){
	    //dump($request->all());
	    //}
	    
	    //dump(report_department_pending_count('P', '30',23)->count());
	    //dump(report_department_rejected_count('R', '30',23)->count());
	    
	    $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        
        $lot_id = $request->get('lot_id');
        $district_id = $request->get('district_id');
        $tehsil_id = $request->get('tehsil_id');
        $uc_id = $request->get('uc_id');
        
        $gender = $request->get('gender');
        $disability = $request->get('disability');
        $vulnerable = $request->get('vulnerable');
        $vulnerabilities = $request->get('vulnerabilities');
        $landownership = $request->get('landownership');
        $bank_ac_wise = $request->get('bank_ac_wise');
        $reconstruction_wise = $request->get('reconstruction_wise');
        $construction_type = $request->get('construction_type');
        $damage_type = $request->get('damage_type');

        $department_new = $request->get('department');
        $department = $request->get('form_status');
        $status = $request->get('status');
        $not_action = $request->get('not_action');
        
      

        $proposed_beneficiary = $request->get('proposed_beneficiary');
        $socio_legal_status = $request->get('socio_legal_status');
        $evidence_type = $request->get('evidence_type');
        $status_of_land = $request->get('status_of_land');
        $tranche = $request->get('tranche');
        
        

        $b_reference_number = $request->get('b_reference_number');
        $beneficiary_name = $request->get('beneficiary_name');
		$cnic = $request->get('cnic');
		
        $sorting = $request->get('sorting');
        $order = $request->get('direction');
        
        
        
        //dump($vulnerabilities);
        
        
        
		$surveydata = $surveydata->newQuery();


        if($request->has('start_date') && $request->get('start_date') != null && $request->has('end_date') && $request->get('end_date') != null){
            $start_date = Carbon::createFromFormat('Y-m-d', $request->get('start_date'))->startOfDay()->toDateTimeString();
            $end_date = Carbon::createFromFormat('Y-m-d', $request->get('end_date'))->endOfDay()->toDateTimeString();
			$surveydata->whereBetween('created_at', [$start_date, $end_date]);
        }
        
        
        if($request->has('lot_id') && $request->get('lot_id') != null){
			$surveydata->where('lot_id', $lot_id);
        }else{
            $surveydata->whereIn('lot_id', json_decode(Auth::user()->lot_id));
        }

		if($request->has('district_id') && $request->get('district_id') != null){ 
			$surveydata->where('district_id', $district_id);
		}else{
            $surveydata->whereIn('district_id', json_decode(Auth::user()->district_id));
        }
        
        if($request->has('tehsil_id') && $request->get('tehsil_id') != null){
			$surveydata->where('tehsil_id', $tehsil_id);
        }else{
            //$surveydata->whereIn('tehsil_id', json_decode(Auth::user()->tehsil_id));
        }
        
        if($request->has('uc_id') && $request->get('uc_id') != null){
			$surveydata->where('uc_id', $uc_id);
        }else{
            //$surveydata->whereIn('uc_id', json_decode(Auth::user()->uc_id));
        }

        
        if($request->has('gender') && $request->get('gender') != null){
			$surveydata->where('gender', $gender);
        }
        
        if($request->has('disability') && $request->get('disability') != null){
			$surveydata->where('disability', $disability);
        }
        
        if($request->has('vulnerable') && $request->get('vulnerable') != null){
			$surveydata->where('q_2242', $vulnerable);
        }
        
        if($request->has('vulnerabilities') && $request->get('vulnerabilities') != null){

			//$surveydata->whereRaw("JSON_CONTAINS(q_2243, JSON_OBJECT('option_id', ?), '$')", 2930)
          //->orWhereRaw("JSON_CONTAINS(q_2243, JSON_OBJECT('option_id', ?), '$')", 2937);

			$surveydata->whereRaw("JSON_CONTAINS(q_2243, JSON_OBJECT('option_id', ?), '$')", intval($vulnerabilities));
        }
        
        if($request->has('landownership') && $request->get('landownership') != null){
			$surveydata->where('landownership', $landownership);
        }
        
        if($request->has('bank_ac_wise') && $request->get('bank_ac_wise') != null){
			$surveydata->where('bank_ac_wise', $bank_ac_wise);
        }
        
        if($request->has('reconstruction_wise') && $request->get('reconstruction_wise') != null){
			$surveydata->where('reconstruction_wise', $reconstruction_wise);
        }
        
        if($request->has('construction_type') && $request->get('construction_type') != null){
			$surveydata->where('construction_type', $construction_type);
        }
        
        if($request->has('damage_type') && $request->get('damage_type') != null){
			$surveydata->where('damage_type', $damage_type);
        }
        

        if($request->has('socio_legal_status') && $request->get('socio_legal_status') != null){
			$surveydata->where('socio_legal_status', $socio_legal_status);
        }
        if(Auth::user()->role==51){
            
        $review_by_mne = $request->get('review_by_mne');
     
  
        if($request->has('review_by_mne') && $request->get('review_by_mne') != null){
            if($review_by_mne=='Yes'){
                $review_by_mne=1;
            }else{
                $review_by_mne=0;
            }
			$surveydata->where('review_by_mne', $review_by_mne);
        }
        }
        
        if($request->has('evidence_type') && $request->get('evidence_type') != null){
			$surveydata->where('evidence_type', $evidence_type);
        }
        
        if($request->has('status_of_land') && $request->get('status_of_land') != null){
			$surveydata->where('status_of_land', $status_of_land);
        }
        
        if($request->has('proposed_beneficiary') && $request->get('proposed_beneficiary') != null){
			//$surveydata->where('proposed_beneficiary','like','%'.$proposed_beneficiary.'%');
			$surveydata->where('proposed_beneficiary', $proposed_beneficiary);
        }
        
        
		if($request->has('b_reference_number') && $request->get('b_reference_number') != null){
			$surveydata->where('ref_no', $b_reference_number);
        }
        
        if($request->has('beneficiary_name') && $request->get('beneficiary_name') != null){
			$surveydata->where('beneficiary_details->beneficiary_name','like','%'.$beneficiary_name.'%');
        }
		
		if($request->has('cnic') && $request->get('cnic') != null){
			//$surveydata->where('cnic','like','%'.$cnic.'%');
			$surveydata->where('cnic', $cnic);
        }
        
        
        if($request->has('tranche') && $request->get('tranche') != null){
    		 $surveydata->whereHas('getverifybeneficairytranche', function ($q) use ($tranche) {$q->where('trench_no', $tranche); });
        }
        
        
        /*
        if(Auth::user()->role==30 && Auth::user()->id==161){
        $surveydata->whereHas('getformstatusold', function ($q) {$q->where('form_status', 'A'); });    
        }else{
            
            if($request->has('status') && $request->get('status') != null){
             $status = $request->get('status');
    		 $surveydata->whereHas('getformstatusold', function ($q) use ($status) {$q->where('form_status', $status); });
            }
            
        }
        
        
        if(Auth::user()->role==30 && Auth::user()->id==161){
        $form_status = 'field supervisor';    
        $surveydata->whereHas('getformstatusold', function ($q) use ($form_status) {$q->where('update_by', $form_status); });
        }else{
            
            if($request->has('form_status') && $request->get('form_status') != null){
             $form_status = $request->get('form_status');
    		 $surveydata->whereHas('getformstatusold', function ($q) use ($form_status) {$q->where('update_by', $form_status); });
            }
            
        }
        */
        
        /*
        //if(Auth::user()->role==30 && Auth::user()->id==161){
        if(Auth::user()->role==30){
        $surveydata->whereHas('getformstatusold', function ($q) use ($request){

            if($request->has('form_status') && $request->get('form_status') != null){
            $q->where('update_by', 'field supervisor');
            }
            
            if($request->has('status') && $request->get('status') != null){
            $q->where('form_status', 'A'); 
            }
            
        });
        }elseif(Auth::user()->role==34){
        $surveydata->whereHas('getformstatusold', function ($q) use ($request){

            if($request->has('form_status') && $request->get('form_status') != null){
            $q->where('update_by', 'IP');
            }
            
            if($request->has('status') && $request->get('status') != null){
            $q->where('form_status', 'A'); 
            }
            
        });    
           
        }else{
        }
        */
        
        
        
        
if($request->has('pending_days') && $request->get('pending_days') != null){
            
if($request->get('pending_days') == 4){
//$surveydata->where('m_status', 'P')->orderBy('id','DESC')->whereNotNull('m_last_action_date')->where('m_last_action_date', '>=', Carbon::now()->subDays(6));
$surveydata->where('m_status', 'P')->where(function ($query) {
        $query->whereNotNull('m_last_action_date')
              ->where('m_last_action_date', '>=', Carbon::now()->subDays(6))
              ->orWhere(function ($subQuery) {$subQuery->whereNull('m_last_action_date')->where('created_at', '>=', Carbon::now()->subDays(6)); });
    });

}elseif($request->get('pending_days') == 5){
$surveydata->where('m_status', 'P')->where(function ($query) {
        $query->whereNotNull('m_last_action_date')
              ->whereBetween('m_last_action_date', [Carbon::now()->subDays(11), Carbon::now()->subDays(6)])
              ->orWhere(function ($subQuery) {$subQuery->whereNull('m_last_action_date')->whereBetween('created_at', [Carbon::now()->subDays(11), Carbon::now()->subDays(6)]); });
    });
}elseif($request->get('pending_days') == 10){
$surveydata->where('m_status', 'P')->where(function ($query) {
        $query->whereNotNull('m_last_action_date')
              ->whereBetween('m_last_action_date', [Carbon::now()->subDays(16), Carbon::now()->subDays(11)])
              ->orWhere(function ($subQuery) {$subQuery->whereNull('m_last_action_date')->whereBetween('created_at', [Carbon::now()->subDays(16), Carbon::now()->subDays(11)]); });
    });
}elseif($request->get('pending_days') == 15){
$surveydata->where('m_status', 'P')->where(function ($query) {
        $query->whereNotNull('m_last_action_date')
              ->where('m_last_action_date', '<=', Carbon::now()->subDays(16))
              ->orWhere(function ($subQuery) {$subQuery->whereNull('m_last_action_date')->where('created_at', '<=', Carbon::now()->subDays(16)); });
    });
}            
          
            
        /*   
        $surveydata->whereHas('getformstatusold', function ($q) use ($request){
              
              if($request->get('pending_days') == 4){
              $q->where('form_status', 'P')->orderBy('id','DESC')->where('created_at', '>=', Carbon::now()->subDays(6)); //0-5   
              }elseif($request->get('pending_days') == 5){
              $q->where('form_status', 'P')->orderBy('id','DESC')->whereBetween('created_at', [Carbon::now()->subDays(11), Carbon::now()->subDays(6)]); //5-10
              }elseif($request->get('pending_days') == 10){
              $q->where('form_status', 'P')->orderBy('id','DESC')->whereBetween('created_at', [Carbon::now()->subDays(16), Carbon::now()->subDays(11)]); //11-15
              }elseif($request->get('pending_days') == 15){
              $q->where('form_status', 'P')->orderBy('id','DESC')->where('created_at', '<=', Carbon::now()->subDays(16)); //15UP
              }
              
        }); //->where('created_at', '<=', Carbon::now()->subDays(16));
        */
}
        
        
        
        if($request->has('department') && $request->get('department') != null){
			if($status == 'R' || $status == 'A'){
			    
			    if(Auth::user()->role == 1){
			     //$surveydata->where('m_role_id', $department_new);   
			    }
			    
			}else{
			    
    			if (filter_var($status, FILTER_VALIDATE_INT) === false) {
    			$surveydata->where('m_role_id', $department_new);
    			}
			}
        }
        
        
        
        
        if($request->has('status') && $request->get('status') != null){
         
         
        if(Auth::user()->role == 1){
            
            if($status == 'P' || $status == 'H'){
            $surveydata->where('m_status', $status);
            }elseif($status == 'A'){

            //$surveydata->where('m_last_action_role_id', $department_new)->where('m_last_action', $status);
            $surveydata->whereHas('getformstatusold', function ($q) use ($request, $department_new){
            
            if($request->has('department') && $request->get('department') != null){
                
                
                if($department_new == 30){
                 $q->where('update_by', 'field supervisor'); 
                }elseif($department_new == 34){
                 $q->where('update_by', 'IP');
                }elseif($department_new == 36){
                 $q->where('update_by', 'HRU');
                }elseif($department_new == 37){
                 $q->where('update_by', 'PSIA');
                }elseif($department_new == 38){
                 $q->where('update_by', 'HRU_MAIN');
                }elseif($department_new == 40){
                 $q->where('update_by', 'CEO');
                }elseif($department_new == 48){
                 $q->where('update_by', 'Finance');
                }
                
                
            }
            
            if($request->has('status') && $request->get('status') != null){ $q->where('form_status', 'A'); }
            
        });
            
            
            
            }elseif($status == 'R'){
                
             $surveydata->where('m_last_action_role_id', $department_new)->where('m_last_action', $status);   
            }
            
            
        }else{ 
          
        if($status == 'P'){

            if(Auth::user()->role == 51){
                $surveydata->where('m_status', $status);  
            }else{
                $upper_role = upper_role_id_master_report(); 
                $upper_rejected_ids = SurveyData::where('m_last_action_role_id', $upper_role->id)->where('m_last_action', 'R')->pluck('id');
                $surveydata->where('m_status', $status)->whereNotIn('id', $upper_rejected_ids);
                //$surveydata->where('m_status', $status)->whereNot('m_last_action_role_id', $upper_role->id)->whereNot('m_last_action', 'R'); 
                //$surveydata->where('m_status', $status);
            }
            
            
             
  
        }elseif($status == 'H'){    
        $surveydata->where('m_status', $status);
        }elseif($status == 'A'){
            
            
        //$surveydata->where('m_last_action_role_id', Auth::user()->role)->where('m_last_action_user_id', Auth::user()->id)->where('m_last_action', $status); 
        $surveydata->whereHas('getformstatusold', function ($q) use ($request, $department_new){
            
            if($request->has('department') && $request->get('department') != null){
                
                
                if($department_new == 27){
                 $q->where('update_by', 'Validator');    
                }elseif($department_new == 30){    
                 $q->where('update_by', 'field supervisor'); 
                }elseif($department_new == 34){
                 $q->where('update_by', 'IP');
                }elseif($department_new == 36){
                 $q->where('update_by', 'HRU');
                }elseif($department_new == 37){
                 $q->where('update_by', 'PSIA');
                }elseif($department_new == 38){
                 $q->where('update_by', 'HRU_MAIN');
                }elseif($department_new == 40){
                 $q->where('update_by', 'CEO');
                }elseif($department_new == 48){
                 $q->where('update_by', 'Finance');
                }
                
                
            }
            
            if($request->has('status') && $request->get('status') != null){ $q->where('form_status', $request->get('status')); }
            
        });
        
        
        
        
        
        
        
            
        }elseif($status == 'R'){
        
            if(Auth::user()->role == $department_new){     
            $surveydata->where('m_last_action_role_id', Auth::user()->role)->where('m_last_action_user_id', Auth::user()->id)->where('m_last_action', $status); 
            }else{
            $surveydata->where('m_last_action_role_id', $department_new)->where('m_last_action', $status);
            }
            
        }else{
        $surveydata->where('m_last_action_role_id', $status)->where('m_last_action', 'R');
        }
        
        }
        
        
        
        // OLD METHOD
        /*
        $surveydata->whereHas('getformstatusold', function ($q) use ($request){

            if($request->has('form_status') && $request->get('form_status') != null){
            $q->where('update_by', $request->get('form_status'));
            }
            
            if($request->has('status') && $request->get('status') != null){
            $q->where('form_status', $request->get('status')); 
            }
            
        }); 
        */
        
        
        
        
        }
        
        
        
        
        if($request->has('not_action') && $request->get('not_action') != null){
         //$surveydata->whereDoesntHave('getformstatusold'); // 
         //$surveydata->whereIn('id', no_action_perform('field supervisor', 'IP'));
         
         if($request->get('not_action') == 'not_action_fs'){
          $surveydata->whereDoesntHave('getformstatusold');    
         }elseif($request->get('not_action') == 'not_action_ip'){
          $surveydata->whereIn('id', no_action_perform('field supervisor', 'IP'));
         }elseif($request->get('not_action') == 'not_action_hru'){
          $surveydata->whereIn('id', no_action_perform('IP', 'HRU'));
         }elseif($request->get('not_action') == 'not_action_psia'){
          $surveydata->whereIn('id', no_action_perform('HRU', 'PSIA'));
         }elseif($request->get('not_action') == 'not_action_hru_main'){
          $surveydata->whereIn('id', no_action_perform('PSIA', 'HRU_MAIN'));
         }elseif($request->get('not_action') == 'not_action_coo'){
          $surveydata->whereIn('id', no_action_perform('HRU_MAIN', 'COO'));
         }elseif($request->get('not_action') == 'not_action_ceo'){
          $surveydata->whereIn('id', no_action_perform('HRU_MAIN', 'CEO'));
         
             
         }elseif($request->get('not_action') == 'reject_by_ceo'){
          $surveydata->whereIn('id', rejected_by_department('CEO'));
         }elseif($request->get('not_action') == 'reject_by_hru_main'){
          $surveydata->whereIn('id', rejected_by_department('HRU_MAIN'));
         }elseif($request->get('not_action') == 'reject_by_psia'){
          $surveydata->whereIn('id', rejected_by_department('PSIA'));
         }elseif($request->get('not_action') == 'reject_by_hru'){
          $surveydata->whereIn('id', rejected_by_department('HRU'));
         }elseif($request->get('not_action') == 'reject_by_ip'){
          $surveydata->whereIn('id', rejected_by_department('IP'));
         }elseif($request->get('not_action') == 'reject_by_fs'){
          $surveydata->whereIn('id', rejected_by_department('field supervisor'));
         }
         
         
         
        }

        if($request->has('bulkaction') && $request->get('bulkaction') != null){
        
        if($request->get('bulkaction') == 1){
         $bulk_survey_id = $surveydata->pluck('id');   
        }elseif($request->get('bulkaction') == 0){
         $bulk_survey_id = '';   
        }

        }else{
        $bulk_survey_id = $request->get('bulk_survey_id');    
        }
        
        
        
        $surveydata->orderBy($sorting, $order); 

        $data = $surveydata->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
        

        //$data_array = $data->toArray()['data'];
        //dump($surveydata->count());
        
        
        
        $selected_data = $data->map(function ($item) use($status, $department) {
            $beneficairy_details = json_decode($item->beneficiary_details);
            $formstatus = $item->getformstatusold()->where('form_status', $status)->where('update_by', $department)->first();
            if($formstatus){ 
                $department = $formstatus->update_by.' ( '.$formstatus->created_by->name.' )'; 
                $form_status = $formstatus->form_status;
            }else{ 
                $department = 'Null';
                $form_status = 'Null';
                
            }
            return [
                'sid' => $item->id,
                'date' => Carbon::parse($item->created_at)->format('d-m-Y'),
                'ref_no' => $item->ref_no ?? '',
                //'formstatus' => $item->getformstatus->role ?? '',
                'department' => get_role_name($item->m_role_id) ?? '', //$department,
                'formstatus' => $item->m_status, //$form_status,
                'beneficiary_name' => $item->beneficiary_name ?? '',
                'cnic' => $item->cnic ?? '',
                'father_name' => $beneficairy_details->father_name ?? '',
                'user_name' => $item->getuser->name ?? '',
                'form_name' => $item->getform->name ?? '',
                'lot' => $item->getlot->name ?? '',
                'district' => $item->getdistrict->name ?? '',
                'tehsil' => $item->gettehsil->name ?? '',
                'uc' => $item->getuc->name ?? '',
                'generated_id' => $item->generated_id ?? ''
            ];
        });
        $jsondata = json_encode($selected_data);


        return view('dashboard.report.pagination_report_survey_datalist', compact('data','jsondata','department','status','not_action','bulk_survey_id'))->render();
        
        
        

   
	}
	
	
	public function report_survey_datalist_export(Request $request) 
    {
        $report_survey_datalist = $request->survey_datalist_export;
        $survey_datalist_export = json_decode($report_survey_datalist, true);
        //dd($request);
        
        return Excel::download(new SurveyDataExport($survey_datalist_export), 'surveydata_report_export_'.date('YmdHis').'.xlsx');
    }
    

    public function report_trail_history(Request $request)
    {
		if($request->ajax()){

		    $survey_id = $request->survey_id;
		    $surveydata = SurveyData::where('id', $survey_id)->first();
            //dump($surveydata->getformstatus->report_history);
            
			return view('dashboard.report.rende_report_trail_history', compact('surveydata'))->render(); 
			
		    
		
		}
	}
	 
	
	
//Manual Reporting Setting
public function managesurveyreport(){	
    return view ('dashboard.report.managereports.surveyreport');
}

public function managesurveyreportsubmit(Request $request){
	if ($request->ajax()){	
		request()->validate([
          "reference_number" => 'required',
        ]);
	$reference_no = $request->reference_number;
	$reportdata = SurveyData::where('ref_no', $reference_no)->first();
        $log_data = array('description' => 'Search By Reference No', 'referance_tbl' => 'survey_form', 'referance_val' => $reference_no);
        logg($log_data);
return view('dashboard.report.managereports.rendersurveyreport', compact('reportdata'))->render();

    }
	}	

public function masterreport_trail_form(Request $request){
		if($request->ajax()){
		    $report_id = $request->report_id;
		    $survey_id = $request->survey_id;
                $data = MasterReport::where('id', $report_id)->where('survey_id', $survey_id)->first();
                //dump($data);
                return view('dashboard.report.managereports.masteredit', compact('data'))->render();
		}
}

public function masterreportupdate(Request $request){
		if($request->ajax()){
		    
		    $request->validate([ 
                "role" => 'required',
                "user_id" => 'required',
                "last_status" => 'required',
                "new_status" => 'required',
                "last_action_user_id" => 'required',
        ]);
		$masterreport = MasterReport::find($request->report_id);    
		   //dump($request->all()); 
		   $input = $request->all();
        
        //dump($input);
        //dump($masterreport);
        
		//$input['last_action_user_id'] = Auth::user()->id;
		
	    $masterreport->fill($input)->save();
        $log_data = array('description' => 'Master Report Parent Update', 'referance_tbl' => 'master_report', 'referance_val' => $masterreport->id);
        logg($log_data);
		echo "Master Report Updated Succefully Done";  
		}
}








public function report_trail_form(Request $request){
		if($request->ajax()){

		    $report_type = $request->report_type;
		    $action = $request->action;
		    $report_id = $request->report_id;
		    $survey_id = $request->survey_id;
		    
		    if($report_type == 'master'){
		        
		        if($action == 'add'){
		        return view('dashboard.report.managereports.create', compact('survey_id','report_id'))->render();
		        }elseif($action == 'edit'){
                $data = MasterReportDetail::where('id', $report_id)->first();
                return view('dashboard.report.managereports.edit', compact('data'))->render();
		        }
		        
		    }elseif($report_type == 'formstatus'){
		        
		        if($action == 'add'){
		        return view('dashboard.report.managereports.formstatuscreate', compact('survey_id','report_id'))->render();
		        }elseif($action == 'edit'){
		        $data = FormStatus::where('id', $report_id)->first();
		        return view('dashboard.report.managereports.formstatusedit', compact('data'))->render();
		        }
		      
		    }
   
		
		}
}

public function reportdetailstore(Request $request){
		if($request->ajax()){
		    
		    $request->validate([ 
            	"survey_id" => 'required',
                "role" => 'required',
                "user_id" => 'required',
                "last_status" => 'required',
                "new_status" => 'required',
                "last_action_user_id" => 'required',
            ]);
            
		    $surveydata = SurveyData::where('id', $request->survey_id)->first();
    		if($surveydata){
        		$input = $request->all();
                //$input['user_id'] = Auth::user()->id ?? 0;
        		$input['lot_id'] =  $surveydata->lot_id;
        		$input['district_id'] =  $surveydata->district_id;
        		$input['tehsil_id'] =  $surveydata->tehsil_id;
        		$input['uc_id'] =  $surveydata->uc_id;
        		$input['maaster_report_id'] =  $surveydata->getformstatus->id;
        		$input['form_type'] =  'dammage assessment test';
        		$input['form_id'] =  88;
        	    //dd($input);
        	    
             //dump($input);  
            $master_report_detail = MasterReportDetail::create($input);
           $log_data = array('description' => 'Master Report Detail Added', 'referance_tbl' => 'master_report_detail', 'referance_val' => $master_report_detail->id);
           logg($log_data);
    		 echo "Master Report Detail Added Succefully Done";    
    		}else{
    		 echo "Survey Id Not Exist";   
    		}    
	}
}
public function reportdetailupdate(Request $request){
		if($request->ajax()){
		    
		    $request->validate([ 
                "role" => 'required',
                "user_id" => 'required',
                "last_status" => 'required',
                "new_status" => 'required',
                "last_action_user_id" => 'required',
        ]);
		$master_report_detail = MasterReportDetail::find($request->report_id);    
		   //dump($request->all()); 
		   $input = $request->all();
        
        //dump($master_report_detail);
        
		//$input['last_action_user_id'] = Auth::user()->id;
		
	    $master_report_detail->fill($input)->save();
        $log_data = array('description' => 'Master Report Detail Updated', 'referance_tbl' => 'master_report_detail', 'referance_val' => $master_report_detail->id);
        logg($log_data);
		echo "Master Report Detail Updated Succefully Done";  
		   
		   
		}
}


public function formstatusstore(Request $request){
		if($request->ajax()){
		    $request->validate([ 
                "update_by" => 'required',
                "user_id" => 'required',
                "form_status" => 'required',
        ]);
        
        
        $surveydata = SurveyData::where('id',$request->form_id)->first();
		
    		if($surveydata){
        		$input = $request->all();
                //$input['user_id'] = Auth::user()->id ?? 0;
        	      $input['user_status'] =  123;
        	    
             //dump($input);  
             $form_status = FormStatus::create($input);
             $log_data = array('description' => 'Report Form Status Added', 'referance_tbl' => 'form_status', 'referance_val' => $form_status->id);
             logg($log_data);
    		 echo "Form Status Added Succefully Done";    
    		}else{
    		 echo "Survey Id Not Exist";   
    		}
    		
        
		}
    
}

public function formstatusupdate(Request $request){
		if($request->ajax()){
		    $request->validate([ 
            	"update_by" => 'required',
                "user_id" => 'required',
                "form_status" => 'required',
        ]);
        $formstatus = FormStatus::find($request->report_id);
        $input = $request->all();
        $input['user_status'] =  123;
        //dump($input);
        
		//$input['last_action_user_id'] = Auth::user()->id;
		//dump($formstatus);
	    $formstatus->fill($input)->save();
        $log_data = array('description' => 'Report Form Status Updated', 'referance_tbl' => 'form_status', 'referance_val' => $formstatus->id);
        logg($log_data);
        echo "Form Status Updated Succefully Done";  
		}
    
}




//Delete Report Trail
public function report_trail_delete($id){
        $master_report_detail = MasterReportDetail::find($id);
        $log_data = array('description' => 'Master Report Detail Delete', 'referance_tbl' => 'master_report_detail', 'referance_val' => $master_report_detail->id);
        logg($log_data);
        $master_report_detail->delete();
        return redirect()->back()->with('success', 'You Delete Master Report Trail Successfully');

	}
public function formstatus_trail_delete($id){
        $formstatus = FormStatus::find($id);
        $log_data = array('description' => 'Report Form Status Delete', 'referance_tbl' => 'form_status', 'referance_val' => $formstatus->id);
        logg($log_data);
        $formstatus->delete();
        return redirect()->back()->with('success', 'You Delete Form Status Trail Successfully');

	}	
	
//Manual Reporting Setting END
    
    public function master_report_summary()
    {
		///if(Auth::user()->role==1){
		//$lots = Lot::select('name','id')->get();
		$districts = District::select('name','id','lot_id')->whereNot('id',37)->orderBy('lot_id','asc')->get();
		//$gender = SurveyData::distinct()->pluck('gender', 'gender')->filter(function ($value) { return $value !== null; });
        //$gender = Option::where('question_id', 652)->pluck('name', 'name');


		
		 return view('dashboard.report.master_report_summary', compact('districts'));
		///}else{
         ///return redirect()->route('admin.dashboard')->with([ 'error' => 'You are not authorized this page!']);
        ///}
        
		}
	public function WeeklySummaryReport()
    {
		$districts = District::select('name','id','lot_id')->whereNot('id',37)->orderBy('lot_id','asc')->get();
		return view('dashboard.report.weekly_summary_report', compact('districts'));
		}	
		
		
		
	public function report_survey_customdatalist()
    {
		//if(Auth::user()->role==1){
		
		$lots = Lot::pluck('name','id')->all();
		$gender = Option::where('question_id', 652)->pluck('name', 'name');
        //$landownership = Option::where('question_id', 240)->pluck('name', 'name');
        //$construction_type = Option::where('question_id', 760)->pluck('name', 'name');
        
        //$socio_legal_status = Option::where('question_id', 246)->pluck('name', 'name');
        //$evidence_type = Option::where('question_id', 247)->pluck('name', 'name');
        //$status_of_land = Option::where('question_id', 243)->pluck('name', 'name');
        $vulnerabilities = Option::where('question_id', 2243)->pluck('name', 'id');
		return view('dashboard.report.report_survey_customdatalist', compact('lots', 'gender', 'vulnerabilities'));
		
		//}else{
         //return redirect()->route('admin.dashboard')->with([ 'error' => 'You are not authorized this page!']);
        //}
    }
    
	
	public function report_survey_customdatalist_fetch_data(Request $request, SurveyQuestionAnswer $surveydata)
	{
	    //dump($request->all());
	    
	    $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        
        $b_reference_number = $request->get('b_reference_number');
        $gender = $request->get('gender');
        $disability = $request->get('disability');
        $vulnerable = $request->get('vulnerable');
        $vulnerabilities = $request->get('vulnerabilities');
        
        $lot_id = $request->get('lot_id');
        $district_id = $request->get('district_id');
        $tehsil_id = $request->get('tehsil_id');
        $uc_id = $request->get('uc_id');
        
        $department = $request->get('form_status');
        $status = $request->get('status');

        $sorting = $request->get('sorting');
        $order = $request->get('direction');
        $bulk_survey_id = $request->get('bulk_survey_id');
        
		$surveydata = $surveydata->newQuery();


        if($request->has('start_date') && $request->get('start_date') != null && $request->has('end_date') && $request->get('end_date') != null){
            $start_date = Carbon::createFromFormat('Y-m-d', $request->get('start_date'))->startOfDay()->toDateTimeString();
            $end_date = Carbon::createFromFormat('Y-m-d', $request->get('end_date'))->endOfDay()->toDateTimeString();
			$surveydata->whereBetween('created_at', [$start_date, $end_date]);
        }
        
        if($request->has('b_reference_number') && $request->get('b_reference_number') != null){
			$surveydata->where('ref_no', $b_reference_number);
        }
        
        if($request->has('gender') && $request->get('gender') != null){
			$surveydata->where('q_652', $gender);
        }
        
        if($request->has('disability') && $request->get('disability') != null){
			$surveydata->where('q_968', $disability);
        }
        
        if($request->has('vulnerable') && $request->get('vulnerable') != null){
			$surveydata->where('q_2242', $vulnerable);
        }
        
        if($request->has('vulnerabilities') && $request->get('vulnerabilities') != null){

			//$surveydata->whereRaw("JSON_CONTAINS(q_2243, JSON_OBJECT('option_id', ?), '$')", 2930)
          //->orWhereRaw("JSON_CONTAINS(q_2243, JSON_OBJECT('option_id', ?), '$')", 2937);

			$surveydata->whereRaw("JSON_CONTAINS(q_2243, JSON_OBJECT('option_id', ?), '$')", intval($vulnerabilities));
        }
        
        if($request->has('lot_id') && $request->get('lot_id') != null){
			$surveydata->where('lot_id', $lot_id);
        }

		if($request->has('district_id') && $request->get('district_id') != null){ 
			$surveydata->where('district_id', $district_id);
		}
        
        if($request->has('tehsil_id') && $request->get('tehsil_id') != null){
			$surveydata->where('tehsil_id', $tehsil_id);
        }
        
        if($request->has('uc_id') && $request->get('uc_id') != null){
			$surveydata->where('uc_id', $uc_id);
        }
        
        if($request->has('status') && $request->get('status') != null){
        $surveydata->whereHas('getformstatus', function ($q) use ($request){

            if($request->has('form_status') && $request->get('form_status') != null){
            $q->where('update_by', $request->get('form_status'));
            }
            
            if($request->has('status') && $request->get('status') != null){
            $q->where('form_status', $request->get('status')); 
            }
            
        }); 
        }


        $surveydata->orderBy($sorting, $order); 

        $data = $surveydata->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
        

        //$data_array = $data->toArray()['data'];
        //dump($data_array);
        
        
       
        $selected_data = $data->map(function ($item) {
            
            /*
            $checkbox_q_2243 = json_decode($item->q_2243);
            if($checkbox_q_2243){
                $array_2243 = [];
                foreach($checkbox_q_2243 as $itemm){
                    $array_2243[] = getoptionlabel($itemm->option_id);
                }
                 $q_2243 = json_encode($array_2243);
            }
            */
            $checkbox_q_2243 = json_decode($item->q_2243);
            $data_q_2243 = collect($checkbox_q_2243);
            
            
            
            return [
                'sid' => $item->survey_id,
                'date' => Carbon::parse($item->created_at)->format('d-m-Y'),
                'ref_no' => $item->ref_no ?? '',
                'beneficiary_name' => $item->q_482 ?? '',
                'proposed_beneficiary_name' => $item->q_643 ?? '',
                'beneficiary_gender' => $item->q_652 ?? '',
                'functional_gender' => $item->q_971 ?? '',
                'cnic' => $item->cnic ?? '',
                'father_name' => $item->q_483 ?? '',
                'lot' => $item->getlot->name ?? '',
                'district' => $item->getdistrict->name ?? '',
                'tehsil' => $item->gettehsil->name ?? '',
                'uc' => $item->getuc->name ?? '',
                'is_registered_bisp' => $item->q_704 ?? '',
                
                'is_vulnerable' => $item->q_2242 ?? '',
                //'vulnerability' => $q_2243 ?? '',
                
                'visually_challanged' => $item->q_2001 ?? '',
                'amputation_case' => $item->q_2007 ?? '',
                'physical_issues' => $item->q_2009 ?? '',
                
                'other_physical_limitations_1' => $item->q_2014 ?? '',
                'other_physical_limitations_2' => $item->q_2015 ?? '',
                'other_physical_limitations_3' => $item->q_2016 ?? '',
                'other_physical_limitations_4' => $item->q_2017 ?? '',
                'other_physical_limitations_5' => $item->q_2018 ?? '',
                'other_physical_limitations_6' => $item->q_2021 ?? '',
                'households_vulnerable_women_2927' => $data_q_2243->firstWhere('option_id', 2927) ? 'Yes' : '',
                'households_vulnerable_women_2936' => $data_q_2243->firstWhere('option_id', 2936) ? 'Yes' : '',
                'households_vulnerable_women_2928' => $data_q_2243->firstWhere('option_id', 2928) ? 'Yes' : '',
                'households_vulnerable_women_2929' => $data_q_2243->firstWhere('option_id', 2929) ? 'Yes' : '',
                

            ];
        });
        $jsondata = json_encode($selected_data);
        

        return view('dashboard.report.pagination_report_survey_customdatalist', compact('data','jsondata','bulk_survey_id'))->render();

	}
	
	
	public function report_survey_customdatalist_export(Request $request) 
    {
        $report_survey_datalist = $request->survey_datalist_export;
        $survey_datalist_export = json_decode($report_survey_datalist, true);
        //dd($request);
        
        return Excel::download(new SurveyCustomDataExport($survey_datalist_export), 'surveycustomdata_report_export_'.date('YmdHis').'.xlsx');
    }
    
    
    public function report_sectionwise_datalist()
    {
		//if(Auth::user()->role==1){
		
		$lots = Lot::pluck('name','id')->all();
		$gender = Option::where('question_id', 652)->pluck('name', 'name');
        //$landownership = Option::where('question_id', 240)->pluck('name', 'name');
        //$construction_type = Option::where('question_id', 760)->pluck('name', 'name');
        
        //$socio_legal_status = Option::where('question_id', 246)->pluck('name', 'name');
        //$evidence_type = Option::where('question_id', 247)->pluck('name', 'name');
        //$status_of_land = Option::where('question_id', 243)->pluck('name', 'name');
        $vulnerabilities = Option::where('question_id', 2243)->pluck('name', 'id');
		return view('dashboard.report.report_sectionwise_datalist', compact('lots', 'gender', 'vulnerabilities'));
		
		//}else{
         //return redirect()->route('admin.dashboard')->with([ 'error' => 'You are not authorized this page!']);
        //}
    }
    
	
	public function report_sectionwise_datalist_fetch_data(Request $request, SurveyData $surveydata)
	{
	    //dump($request->all());
	    
	    $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        
        $b_reference_number = $request->get('b_reference_number');
        $gender = $request->get('gender');
        $disability = $request->get('disability');
        $vulnerable = $request->get('vulnerable');
        $vulnerabilities = $request->get('vulnerabilities');
        
        $lot_id = $request->get('lot_id');
        $district_id = $request->get('district_id');
        $tehsil_id = $request->get('tehsil_id');
        $uc_id = $request->get('uc_id');
        
        $department = $request->get('form_status');
        $status = $request->get('status');

        $sorting = $request->get('sorting');
        $order = $request->get('direction');
        $bulk_survey_id = $request->get('bulk_survey_id');
        
		$surveydata = $surveydata->newQuery();


        if($request->has('start_date') && $request->get('start_date') != null && $request->has('end_date') && $request->get('end_date') != null){
            $start_date = Carbon::createFromFormat('Y-m-d', $request->get('start_date'))->startOfDay()->toDateTimeString();
            $end_date = Carbon::createFromFormat('Y-m-d', $request->get('end_date'))->endOfDay()->toDateTimeString();
			$surveydata->whereBetween('created_at', [$start_date, $end_date]);
        }
        
        if($request->has('b_reference_number') && $request->get('b_reference_number') != null){
			$surveydata->where('ref_no', $b_reference_number);
        }
        
        if($request->has('gender') && $request->get('gender') != null){
    		$surveydata->where('gender', $gender);
            //$surveydata->whereHas('getsection86', function ($q) use ($request){
            //$q->where('q_652', $request->get('gender')); 
            //}); 
        }
        
        if($request->has('disability') && $request->get('disability') != null){
			$surveydata->where('disability', $request->get('disability'));
			//$surveydata->whereHas('getsection117', function ($q) use ($request){
            //$q->where('q_968', $request->get('disability')); 
            //});
        }
        
        if($request->has('vulnerable') && $request->get('vulnerable') != null){
			$surveydata->whereHas('getsection117', function ($q) use ($request){
            $q->where('q_2242', $request->get('vulnerable')); 
            });
        }
        
        if($request->has('vulnerabilities') && $request->get('vulnerabilities') != null){
			$surveydata->whereHas('getsection117', function ($q) use ($request){
		    //$q->whereRaw("JSON_CONTAINS(q_2243, JSON_OBJECT('option_id', ?), '$')", 2930)->orWhereRaw("JSON_CONTAINS(q_2243, JSON_OBJECT('option_id', ?), '$')", 2937);
            $q->whereRaw("JSON_CONTAINS(q_2243, JSON_OBJECT('option_id', ?), '$')", intval($request->get('vulnerabilities')));
            });
        }
        
        
        
        if($request->has('lot_id') && $request->get('lot_id') != null){
			$surveydata->where('lot_id', $lot_id);
        }

		if($request->has('district_id') && $request->get('district_id') != null){ 
			$surveydata->where('district_id', $district_id);
		}
        
        if($request->has('tehsil_id') && $request->get('tehsil_id') != null){
			$surveydata->where('tehsil_id', $tehsil_id);
        }
        
        if($request->has('uc_id') && $request->get('uc_id') != null){
			$surveydata->where('uc_id', $uc_id);
        }
        
        if($request->has('status') && $request->get('status') != null){
        $surveydata->whereHas('getformstatus', function ($q) use ($request){

            if($request->has('form_status') && $request->get('form_status') != null){
            $q->where('update_by', $request->get('form_status'));
            }
            
            if($request->has('status') && $request->get('status') != null){
            $q->where('form_status', $request->get('status')); 
            }
            
        }); 
        }


        $surveydata->orderBy($sorting, $order); 

        $data = $surveydata->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
        //$data_array = $data->toArray()['data'];
        //dump($data_array);

        $selected_data = $data->map(function ($item) {
            $beneficairy_details = json_decode($item->beneficiary_details);
            
            if(isset($item->getsection117->q_2243)){
            $checkbox_q_2243 = json_decode($item->getsection117->q_2243);
            $data_q_2243 = collect($checkbox_q_2243);
            }
            
            
            
            return [
                'sid' => $item->id,
                'date' => Carbon::parse($item->created_at)->format('d-m-Y'),
                'ref_no' => $item->ref_no ?? '',
                
                'beneficiary_name' => $beneficairy_details->beneficiary_name ?? '',
                'proposed_beneficiary_name' => $item->beneficiary_name ?? '',
                'beneficiary_gender' => $item->gender ?? '',
                'functional_gender' => $item->getsection117->q_971 ?? '',
                'cnic' => $item->cnic ?? '',
                'father_name' => $item->getsection59->q_483 ?? '',
                'lot' => $item->getlot->name ?? '',
                'district' => $item->getdistrict->name ?? '',
                'tehsil' => $item->gettehsil->name ?? '',
                'uc' => $item->getuc->name ?? '',
                'is_registered_bisp' => $item->getsection86->q_704 ?? '',
                
                'is_vulnerable' => $item->q_2242 ?? '',
                //'vulnerability' => $q_2243 ?? '',
                
                'visually_challanged' => $item->getsection117->q_2001 ?? '',
                'amputation_case' => $item->getsection117->q_2007 ?? '',
                'physical_issues' => $item->getsection117->q_2009 ?? '',
                
                'other_physical_limitations_1' => $item->getsection117->q_2014 ?? '',
                'other_physical_limitations_2' => $item->getsection117->q_2015 ?? '',
                'other_physical_limitations_3' => $item->getsection117->q_2016 ?? '',
                'other_physical_limitations_4' => $item->getsection117->q_2017 ?? '',
                'other_physical_limitations_5' => $item->getsection117->q_2018 ?? '',
                'other_physical_limitations_6' => $item->getsection117->q_2021 ?? '',
                
                'households_vulnerable_women_2927' => isset($data_q_2243) && $data_q_2243->isNotEmpty() && $data_q_2243->firstWhere('option_id', 2927) ? 'Yes' : '',
                'households_vulnerable_women_2936' => isset($data_q_2243) && $data_q_2243->isNotEmpty() && $data_q_2243->firstWhere('option_id', 2936) ? 'Yes' : '',
                'households_vulnerable_women_2928' => isset($data_q_2243) && $data_q_2243->isNotEmpty() && $data_q_2243->firstWhere('option_id', 2928) ? 'Yes' : '',
                'households_vulnerable_women_2929' => isset($data_q_2243) && $data_q_2243->isNotEmpty() && $data_q_2243->firstWhere('option_id', 2929) ? 'Yes' : '',
                
                

            ];
        });
        $jsondata = json_encode($selected_data);
        

        return view('dashboard.report.pagination_report_sectionwise_datalist', compact('data','jsondata','bulk_survey_id'))->render();

	}
	
	public function report_sectionwise_datalist_export(Request $request) 
    {
        $report_sectionwise_datalist = $request->survey_datalist_export;
        $survey_datalist_export = json_decode($report_sectionwise_datalist, true);
        //dd($request);
        
        return Excel::download(new ReportSectionwiseDatalistExport($survey_datalist_export), 'report_sectionwise_datalist_export_'.date('YmdHis').'.xlsx');
    }
    
    
    
    public function report_ceo_export(){
        $ceo_approved_ids = DB::table('form_status')->where('update_by','CEO')->where('form_status', 'A')->pluck('form_id')->all();
        $ceo_approved_list = DB::table('survey_question_answer')->whereIn('survey_id',$ceo_approved_ids)->get();
        dump($ceo_approved_list->toArray());
  
    }
    
    
    //Efficient CSV Export Using Laravel Streaming
    public function testexportlargeCsv() 
    {
        $fileName = 'large_export.csv';
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Write CSV Header
            fputcsv($file, ['id', 'user_id', 'ref_no', 'm_status', 'm_role_id', 'created_at', 'beneficiary_name', 'cnic', 'lot','district','tehsil','uc', 'generated_id']);

            // Stream data in chunks to prevent memory issues
            SurveyData::chunk(10000, function ($records) use ($file) {
                foreach ($records as $item) {
                    fputcsv($file, [$item->id, $item->user_id, $item->ref_no, $item->m_status, $item->m_role_id, Carbon::parse($item->created_at)->format('d-m-Y'), $item->beneficiary_name ?? '', $item->cnic ?? '', $item->getlot->name ?? '', $item->getdistrict->name ?? '', $item->gettehsil->name ?? '', $item->getuc->name ?? '', $item->generated_id ?? '']);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);

        
    }
    

    public function exportlargeCsv(Request $request, SurveyData $surveydata) 
    {
        // Start Query
        $surveydata = $surveydata->newQuery();
        
        // Apply Date Filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start_date = Carbon::createFromFormat('Y-m-d', $request->get('start_date'))->startOfDay()->toDateTimeString();
            $end_date = Carbon::createFromFormat('Y-m-d', $request->get('end_date'))->endOfDay()->toDateTimeString();
            $surveydata->whereBetween('created_at', [$start_date, $end_date]);
        }
        
        // Apply Lot Filter
        if ($request->filled('lot_id')) {
            $surveydata->where('lot_id', $request->get('lot_id'));
        } else {
            $lotIds = json_decode(Auth::user()->lot_id, true);
            if (!is_array($lotIds)) {
                $lotIds = [];
            }
            $surveydata->whereIn('lot_id', $lotIds);
        }

        // File Details
        $fileName = 'large_export.csv';
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        // Callback for Streaming Response
        $callback = function () use ($surveydata) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM to Fix Encoding Issues in Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Write CSV Header
            fputcsv($file, ['id', 'user_id', 'ref_no', 'm_status', 'm_role_id', 'created_at', 'beneficiary_name', 'cnic', 'lot', 'district', 'tehsil', 'uc', 'generated_id']);

            // Fetch Data in Chunks
            $surveydata
                ->with(['getlot', 'getdistrict', 'gettehsil', 'getuc'])
                ->select(['id', 'user_id', 'ref_no', 'm_status', 'm_role_id', 'created_at', 'beneficiary_name', 'cnic', 'lot_id', 'district_id', 'tehsil_id', 'uc_id', 'generated_id'])
                ->chunk(10000, function ($records) use ($file) {
                    foreach ($records as $item) {
                        fputcsv($file, [
                            $item->id, 
                            $item->user_id, 
                            $item->ref_no, 
                            $item->m_status, 
                            $item->m_role_id, 
                            Carbon::parse($item->created_at)->format('d-m-Y'), 
                            $item->beneficiary_name ?? '', 
                            $item->cnic ?? '', 
                            $item->getlot->name ?? '', 
                            $item->getdistrict->name ?? '', 
                            $item->gettehsil->name ?? '', 
                            $item->getuc->name ?? '', 
                            $item->generated_id ?? ''
                        ]);
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    


}
