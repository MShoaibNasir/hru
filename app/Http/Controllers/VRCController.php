<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\GenderSafeguard;
use App\Models\GenderStatusHistory;
use App\Models\SurveyData;
use App\Models\VRCCommittee;
use App\Models\Lot;
use App\Exports\VRCFormation;
use App\Exports\VRCAttendenceList;
use App\Exports\VRCCommitteee;
use App\Exports\VRCEvent;
use App\Models\District;
use Excel;
use App\Models\Tehsil;
use App\Models\UC;
use App\Models\FormStatus;
use App\Models\QuestionTitle;
use App\Models\Option;
use App\Models\VRC;
use App\Models\VrcAttendenceMain;
use App\Models\VrcAttendence;
use App\Models\Role;
use Auth;
use DB;
use Carbon\Carbon;

class VRCController extends Controller

{
    
    
    public function index()
    {
	
		$lots = Lot::whereIn('id', json_decode(Auth::user()->lot_id))->pluck('name','name')->all();
		return view('dashboard.vrc.alldatalist', compact('lots'));
    }
    
	public function total_vrc_datalist_fetch_data(Request $request, VRC $vrc_data)
	{
	
	    $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        
       
        $action_condition = $request->get('action_condition');
        $department = $request->get('department');
        $form_name = $request->get('form_name');
      
        
        $lot_id = $request->get('lot_id');
        
        $district_id = $request->get('district_id');
        $tehsil_id = $request->get('tehsil_id');
        $uc_id = $request->get('uc_id');
        $item_status = $request->get('item_status');
 
        
   
        
        $b_reference_number = $request->get('b_reference_number');
        $beneficiary_name = $request->get('beneficiary_name');
		$cnic = $request->get('cnic');
		
        $sorting = $request->get('sorting');
        $order = $request->get('direction');

		$constructiondata = $vrc_data->newQuery();
	

        

        if($request->has('department') && $request->get('department') != null){
         
			$constructiondata->where('role_id', $department);
        }

        if($request->has('item_status') && $request->get('item_status') != null){
			$constructiondata->where('status', $item_status);
        }
		
	
		
		
		if($request->has('lot_id') && $request->get('lot_id') != null){
            
			$constructiondata->where('lot', $lot_id);
		
        }
        // else{
        //     $constructiondata->whereIn('lot', json_decode(Auth::user()->lot_id));
        // }

        
        
		if($request->has('district_id') && $request->get('district_id') != null){
		   
			$constructiondata->where('district', $district_id);
		}
// 		else{
//             $constructiondata->whereIn('district', json_decode(Auth::user()->district_id));
//         }
        
        if($request->has('tehsil_id') && $request->get('tehsil_id') != null){
       
			$constructiondata->where('tehsil', $tehsil_id);
        }
        // else{
        //     $constructiondata->whereIn('tehsil', json_decode(Auth::user()->tehsil_id));
        // }
        
        if($request->has('uc_id') && $request->get('uc_id') != null){
			$constructiondata->where('uc', $uc_id);
        }
        // else{
        //     $constructiondata->whereIn('uc', json_decode(Auth::user()->uc_id));
        // }
        

        $constructiondata->orderBy($sorting, $order);
        $selected_data = $constructiondata->get()->map(function ($item)  {
        
          
          
            return [
                'Name Of VRC' => $item->name_of_vrc ?? null,
                'lot' => $item->lot,
                'District' => $item->district ?? '',
                'Tehsil' => $item->tehsil ?? '',
                'UC' => $item->uc ?? '',
                'No Of Village' => $item->no_of_village ?? '',
                'Total Beneficiries' => $item->total_beneficiaries ?? '',
                'Vrc Members' => $item->vrc_members ?? '',
            ];
        });
        $jsondata = json_encode($selected_data);
        $data = $constructiondata->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
       

        return view('dashboard.vrc.pagination_alldatalist', compact('data','jsondata'))->render();
   
	}
	
	public function export_vrc_formation(Request $request){
	    
	   return Excel::download(new VRCFormation(json_decode($request->environment_data)), 'vrc_formation_'.date('YmdHis').'.xlsx');

     }
	
	public function gender_status_trail_history(Request $request)
    {
		if($request->ajax()){
		    $construction_id = $request->construction_id;
		    $construction = GenderSafeguard::where('id', $construction_id)->first();
			return view('dashboard.gender.render_report_trail_history', compact('construction'))->render(); 
		}
	}
	
	
	public function view($id){
	
    $construction = GenderSafeguard::findOrFail($id);
    $question_cat = QuestionTitle::with(['questions' => function ($q) use ($id) {
        
     
        

        $q->with(['gender_safeguard_answer' => function ($q) use ($id) {
            
            $q->where('gender_safeguard_json_id', $id); 
            
        }]);
        // $q->with(['consructionimage' => function ($q) use ($id) { $q->where('construction_id', $id)->where('stage', 1); }]);
    }])
    ->whereHas('questions.gender_safeguard_answer', function ($q) use ($id) {$q->where('gender_safeguard_json_id', $id); })
    ->select('id', 'name', 'section_order')
    ->where('visibility', 1)
    ->orderBy('section_order', 'ASC')
    ->get();


    
   return view('dashboard.gender.show', compact('id','question_cat','construction'));
    
    
    
    }
    
    
	public function gender_action_form(Request $request)
    {
		if($request->ajax()){
		    $construction_id = $request->construction_id;
			$decision = $request->decision;
			return view('dashboard.gender.construction_action_form', compact('construction_id','decision'))->render();  
		}
	}
	
	public function gender_action_form_submit(Request $request){

	if($request->ajax()){
      $role = Role::findORFail(Auth::user()->role);
      $construction_id = $request->construction_id;
	  $decision = $request->decision;
	  $comment = $request->comment;
	  $status = $decision === 'approve' ? 'A' : ($decision === 'reject' ? 'R' : ($decision === 'Case Registered' ? 'CR': ($decision === 'Case Close' ? 'C':'')));


		    $construction = GenderSafeguard::findORFail($construction_id);
		    
	

		    //30, FS
		    //34, IP
		    //36, HRU
		    //37, PSIA
		    //48  HRU Finance = R HRU = P
		    
		    if($construction->role_id == 30 && $status == 'A'){
		     update_gender_status($construction_id,34,'P');
		    }
		    if($construction->role_id == 34 && $status == 'A'){
		     update_gender_status($construction_id,61,'P');   
		    }
		    if($construction->role_id == 61 && $status == 'C'){
		     update_gender_status($construction_id,61,'C');   
		    }
		    if($construction->role_id == 61 && $status == 'CR'){
		     update_gender_status($construction_id,27,'CR');   
		    } 

		    if($construction->role_id == 48 && $status == 'A'){
		     update_gender_status($construction_id,48,'C');
		    }
		    	
		    if($construction->role_id == 48 && $status == 'R'){
		     update_gender_status($construction_id,36,'P');
		    }
		    	 
		    if($construction->role_id == 37 && $status == 'R'){
		     update_gender_status($construction_id,36,'P');  
		    }
		      
		    if($construction->role_id == 61 && $status == 'R'){
		     update_gender_status($construction_id,34,'P');  
		    }
		     
		    if($construction->role_id == 34 && $status == 'R')
		    {
		        
		       $result=update_gender_status(intval($construction_id),27,'P');
		     
		    }
		 
		
			$data['gender_id'] = $construction_id;
			$data['action_by'] = Auth::user()->id;
			$data['role_id'] = $role->id;
			$data['role_name'] = $role->name;
			$data['status'] = $status;
			$data['action'] = $decision;
			$data['comment'] = $comment;
            $result = GenderStatusHistory::create($data);
            echo '<div class="col-md-12"><div class="alert alert-success"><strong>Success!</strong>  Gender action submit is successfully</div></div>';
			
 
	  
	}
	
	}
	
	
	
	
	
	
	
	
	
	public function vrc_filter(Request $request,$id)
    {

		$vrc_committee=VRCCommittee::find($id);
		return view('dashboard.vrc.vrcCommittee.filter', compact('vrc_committee'));
    }
    
	public function vrc_committee_list(Request $request)
	{
	    
	    $vrc_formation=VRCCommittee::where('vrc_formation_id',$request->vrc_formation_id);
	    $vrc_formation_name=DB::table('vrc_formation')->where('id',$request->vrc_formation_id)->select('name_of_vrc')->first();
	    $vrc_formation_name=$vrc_formation_name->name_of_vrc;


	
	    $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        
       
        $action_condition = $request->get('action_condition');
        $department = $request->get('department');
        $form_name = $request->get('form_name');
      
        
        $lot_id = $request->get('lot_id');
        
        $district_id = $request->get('district_id');
        $tehsil_id = $request->get('tehsil_id');
        $uc_id = $request->get('uc_id');
        $item_status = $request->get('item_status');
 
        
   
        
        $b_reference_number = $request->get('b_reference_number');
        $beneficiary_name = $request->get('beneficiary_name');
		$cnic = $request->get('cnic');
		
        $sorting = $request->get('sorting');
        $order = $request->get('direction');

		$constructiondata = $vrc_formation->newQuery();
	

        

        if($request->has('department') && $request->get('department') != null){
         
			$constructiondata->where('role_id', $department);
        }

        if($request->has('item_status') && $request->get('item_status') != null){
			$constructiondata->where('status', $item_status);
        }
		
	
		
		
		if($request->has('lot_id') && $request->get('lot_id') != null){
            
			$constructiondata->where('lot', $lot_id);
		
        }
        // else{
        //     $constructiondata->whereIn('lot', json_decode(Auth::user()->lot_id));
        // }

        
        
		if($request->has('district_id') && $request->get('district_id') != null){
		   
			$constructiondata->where('district', $district_id);
		}
// 		else{
//             $constructiondata->whereIn('district', json_decode(Auth::user()->district_id));
//         }
        
        if($request->has('tehsil_id') && $request->get('tehsil_id') != null){
       
			$constructiondata->where('tehsil', $tehsil_id);
        }
        // else{
        //     $constructiondata->whereIn('tehsil', json_decode(Auth::user()->tehsil_id));
        // }
        
        if($request->has('uc_id') && $request->get('uc_id') != null){
			$constructiondata->where('uc', $uc_id);
        }
        // else{
        //     $constructiondata->whereIn('uc', json_decode(Auth::user()->uc_id));
        // }
        
          $constructiondata->orderBy($sorting, $order);
        
        $selected_data = $constructiondata->get()->map(function ($item)  use ($vrc_formation_name)   {
        
      
          
            return [
                'Name Of VRC' => $vrc_formation_name ?? null,
         
                'Beneficiary Name' => $item->father_name ?? '',
                'Gender' => $item->gender ?? '',
                'Disability' => $item->disability ?? '',
                'CNIC' => $item->cnic ?? '',
                'Mobile No' => $item->mobile_no ?? '',
                'Vrc Designation' => $item->vrc_designation ?? '',
            ];  
        });
        $jsondata = json_encode($selected_data);
        $data = $constructiondata->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
        return view('dashboard.vrc.vrcCommittee.list', compact('data','jsondata'))->render();
   
	}
	
	
	public function export_vrc_committee(Request $request){
	    return Excel::download(new VRCCommitteee(json_decode($request->environment_data)), 'vrc_committee_'.date('YmdHis').'.xlsx');
	}
	
	
	
	
	
	
	
	
	
	
	//VRC Events List
	public function vrc_event_list($vrc_formation_id)
    {
        $lots = Lot::whereIn('id', json_decode(Auth::user()->lot_id))->pluck('name','name')->all();
		return view('dashboard.vrc.events.filter', compact('vrc_formation_id','lots'));
    }
    
	public function events_datalist_fetch_data(Request $request)
	{
	 
	    $vrc_events = VrcAttendenceMain::where('vrc_formation_id',$request->vrc_formation_id);
	    $page = $request->get('ayis_page');
        $qty = $request->get('qty');
       
        
        
        
        $custom_pagination_path = '';

        $sorting = $request->get('sorting');
        $order = $request->get('direction');
		$vrc_events_data = $vrc_events->newQuery();
		
		 if(isset($request->district_id)){
            $vrc_events_data->where('district',$request->district_id);
        }
		 if(isset($request->tehsil_id)){
            $vrc_events_data->where('tehsil',$request->tehsil_id);
        }
		 if(isset($request->uc_id)){
            $vrc_events_data->where('uc',$request->uc_id);
        }
		
		
        $vrc_events_data->orderBy($sorting, $order);
        
        
        $selected_data = $vrc_events_data->get()->map(function ($item)  {
        
          
          
            return [
                'Name Of Event' => $item->name_of_event ?? null,
                'District' => $item->district ?? '',
                'Tehsil' => $item->tehsil ?? '',
                'UC' => $item->uc ?? '',
                'Name Of VRC' => $item->vrc_name ?? '',
                'Venue' => $item->venue ?? '',
                'Date' => Carbon::parse($item->date)->format('d-m-Y') ?? '',
                'Duration' => $item->durations ?? '',
                'Responsibilities' => $item->responsibilities ?? '',
                'First Image' =>  $item->capture_image_1 ? "https://mis.hru.org.pk/storage/vrc_attendance/".$item->capture_image_1 : '',
                'Second Image' =>  $item->capture_image_2 ? "https://mis.hru.org.pk/storage/vrc_attendance/".$item->capture_image_2 : '',
                'Third Image' =>  $item->capture_image_3 ? "https://mis.hru.org.pk/storage/vrc_attendance/".$item->capture_image_3 : '',
                'Fourth Image' =>  $item->capture_image_4 ? "https://mis.hru.org.pk/storage/vrc_attendance/".$item->capture_image_4 : '',
                'Fifth Image' =>  $item->capture_image_5 ? "https://mis.hru.org.pk/storage/vrc_attendance/".$item->capture_image_5 : '',
            ];
        });
        $jsondata = json_encode($selected_data);
        
        
        
        $data = $vrc_events_data->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
	    return view('dashboard.vrc.events.datalist', compact('data','jsondata'))->render();
	}
	
	public function export_vrc_event_list(Request $request){
	    
	   return Excel::download(new VRCEvent(json_decode($request->environment_data)), 'vrc_event'.date('YmdHis').'.xlsx');

     }
	
	
	//VRC Attendance List
	public function vrc_attendance_list($vrc_attendece_main_id)
    {
		return view('dashboard.vrc.attendance.filter', compact('vrc_attendece_main_id'));
    }
    
	public function attendance_datalist_fetch_data(Request $request)
	{
	    $vrc_attendence = VrcAttendence::where('vrc_attendece_main_id',$request->vrc_attendece_main_id);
	    $main_vrc=DB::table('vrc_attendence_main')->where('id',$request->vrc_attendece_main_id)->select('name_of_event','vrc_name')->first();
	    $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        //$b_reference_number = $request->get('b_reference_number');
        $sorting = $request->get('sorting');
        $order = $request->get('direction');
		$vrc_attendence_data = $vrc_attendence->newQuery();
        $vrc_attendence_data->orderBy($sorting, $order);
        
        
        $selected_data = $vrc_attendence_data->get()->map(function ($item) use($main_vrc)  {
       
          
            return [
                'Created At' => Carbon::parse($item->created_at)->format('d-m-Y') ?? null,
                'VRC Name' => $main_vrc->vrc_name ?? '',
                'Name Of Event' => $main_vrc->name_of_event ?? '',
                'Beneficiary Name' => $item->name ?? '',
                'Father Name' => $item->father_name ?? '',
                'Gender' => $item->gender ?? '',
                'Disability' => $item->disability ?? '',
                'CNIC' => $item->cnic ?? '',
                'Mobile No' => $item->mobile_no ?? '',
                'VRC Designation' => $item->vrc_designation ?? '',
                'Attendence' =>  $item->attendance ? : '',
            ];
        });
        $jsondata = json_encode($selected_data);
        
        
        
        $data = $vrc_attendence_data->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
	    return view('dashboard.vrc.attendance.datalist', compact('data','jsondata'))->render();
	}
	
	
	public function export_vrc_attendence_list(Request $request){
	    
	   return Excel::download(new VRCAttendenceList(json_decode($request->environment_data)), 'vrc_attendence_list'.date('YmdHis').'.xlsx');

     }
    
    
    
    
    
}
    
    










