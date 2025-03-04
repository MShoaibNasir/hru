<?php

namespace App\Http\Controllers\surveyList;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\NdmaVerification;
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
use Excel;

class AyisDamageController extends Controller

{
    public function getdamage_datalist()
    {
       
		$lots = Lot::whereIn('id', json_decode(Auth::user()->lot_id))->pluck('name','id')->all();
        $gender = Option::where('question_id', 652)->pluck('name', 'name');
        $landownership = Option::where('question_id', 240)->pluck('name', 'name');
        $construction_type = Option::where('question_id', 760)->pluck('name', 'name');
        $socio_legal_status = Option::where('question_id', 246)->pluck('name', 'name');
        $evidence_type = Option::where('question_id', 247)->pluck('name', 'name');
        $status_of_land = Option::where('question_id', 243)->pluck('name', 'name');
        $vulnerabilities = Option::where('question_id', 2243)->pluck('name', 'id');
		return view('dashboard.ayisdamage.damage_datalist', compact('lots', 'gender','landownership','construction_type','socio_legal_status','evidence_type','status_of_land','vulnerabilities'));
    }
    
	public function getdamage_datalist_fetch_data(Request $request, SurveyData $surveydata)
	{
	    
	    //if(Auth::user()->id==570 && Auth::user()->role==30){
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
            $surveydata->whereIn('tehsil_id', json_decode(Auth::user()->tehsil_id));
        }
        
        if($request->has('uc_id') && $request->get('uc_id') != null){
			$surveydata->where('uc_id', $uc_id);
        }else{
            $surveydata->whereIn('uc_id', json_decode(Auth::user()->uc_id));
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
        
        
        if($request->has('pending_days') && $request->get('pending_days') != null){
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
        }
        
        
        
        if($request->has('department') && $request->get('department') != null){
			if($status == 'R' || $status == 'A'){
			    
			    if(Auth::user()->role == 1){
			     //$surveydata->where('m_role_id', $department_new);   
			    }
			    
			}else{
			$surveydata->where('m_role_id', $department_new);
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
                }
                
                
            }
            
            if($request->has('status') && $request->get('status') != null){ $q->where('form_status', 'A'); }
            
        });
            
            
            
            }elseif($status == 'R'){
                
             $surveydata->where('m_last_action_role_id', $department_new)->where('m_last_action', $status);   
            }
            
            
        }else{ 
          
        if($status == 'P'){
            
            $upper_role = upper_role_id_master_report(); 
            $upper_rejected_ids = SurveyData::where('m_last_action_role_id', $upper_role->id)->where('m_last_action', 'R')->pluck('id');
            $surveydata->where('m_status', $status)->whereNotIn('id', $upper_rejected_ids);
            
            //$surveydata->where('m_status', $status)->whereNot('m_last_action_role_id', $upper_role->id)->whereNot('m_last_action', 'R'); 
            //$surveydata->where('m_status', $status);
             
  
        }elseif($status == 'H'){
        $surveydata->where('m_status', $status);
        }elseif($status == 'A'){
            
            
        //$surveydata->where('m_last_action_role_id', Auth::user()->role)->where('m_last_action_user_id', Auth::user()->id)->where('m_last_action', $status); 
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
                }
                
                
            }
            
            if($request->has('status') && $request->get('status') != null){ $q->where('form_status', $request->get('status')); }
            
        });
        
        
        
        
        
        
        
            
        }elseif($status == 'R'){
            
            if($department_new == Auth::user()->role){  
            //$surveydata->where('m_last_action_role_id', Auth::user()->role)->where('m_last_action_user_id', Auth::user()->id)->where('m_last_action', $status); 
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
                }
                
                
            }
            
            if($request->has('status') && $request->get('status') != null){ $q->where('form_status', $request->get('status')); }
            
        });
            
            
            
            
                
            }else{
            $surveydata->where('m_last_action_role_id', $department_new)->where('m_last_action', $status);    
            }
        
            
        }else{
        //$surveydata->where('m_last_action_role_id', $status)->where('m_last_action', 'R');
        }
        
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
        
        
        
        $surveydata->orderBy($sorting, $order)->take(100); 

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


        return view('dashboard.ayisdamage.pagination_damage_datalist', compact('data','jsondata','department','status','not_action','bulk_survey_id'))->render();

	}
}