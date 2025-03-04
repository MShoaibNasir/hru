<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\GenderSafeguard;
use App\Models\GenderStatusHistory;
use App\Models\SurveyData;
use App\Models\Lot;
use App\Models\District;
use App\Models\Tehsil;
use App\Models\UC;
use App\Models\FormStatus;
use App\Models\QuestionTitle;
use App\Models\Option;
use App\Models\Role;
use Auth;
use DB;
use Carbon\Carbon;

class GenderSafeguardController extends Controller

{
    
    
    public function index()
    {
	
		$lots = Lot::whereIn('id', json_decode(Auth::user()->lot_id))->pluck('name','id')->all();
		return view('dashboard.gender.alldatalist', compact('lots'));
    }
    
	public function total_gender_datalist_fetch_data(Request $request, GenderSafeguard $genderdata)
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
		$constructiondata = $genderdata->newQuery();
        if($request->has('department') && $request->get('department') != null){
       
			$constructiondata->where('role_id', $department);
        }
        if($request->has('vrc') && $request->get('vrc') != null){
            
			$constructiondata->where('unique_name_of_vrc', 'LIKE', '%' . $request->vrc . '%');

        }
        if($request->has('form_name') && $request->get('form_name') != null){
		
			$constructiondata->where('form_id', $form_name);
        }
        if($request->has('item_status') && $request->get('item_status') != null){
		
			$constructiondata->where('status', $item_status);
        }
		
	
		
		
		if($request->has('lot_id') && $request->get('lot_id') != null){
		    
			$constructiondata->where('lot', $lot_id);
		
        }
        else{
            $constructiondata->whereIn('lot', json_decode(Auth::user()->lot_id));
        }

        
        
		if($request->has('district_id') && $request->get('district_id') != null){
		   
			$constructiondata->where('district', $district_id);
		}
		else{
            $constructiondata->whereIn('district', json_decode(Auth::user()->district_id));
        }
        
        if($request->has('tehsil_id') && $request->get('tehsil_id') != null){
       
			$constructiondata->where('tehsil', $tehsil_id);
        }
        else{
            $constructiondata->whereIn('tehsil', json_decode(Auth::user()->tehsil_id));
        }
        
        if($request->has('uc_id') && $request->get('uc_id') != null){
			$constructiondata->where('uc', $uc_id);
        }
        else{
            $constructiondata->whereIn('uc', json_decode(Auth::user()->uc_id));
        }
        

        $constructiondata->orderBy($sorting, $order);
        $data = $constructiondata->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
   
        return view('dashboard.gender.pagination_alldatalist', compact('data'))->render();
   
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
		  //  case register here
		    if($construction->role_id == 61 && $status == 'CR'){
		     update_gender_status($construction_id,61,'CR');   
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
		     update_gender_status($construction_id,61,'R');  
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
	
	
	 public function add_comment_view(Request $request){
	    $question_id=$request->question_id;
	    $surveyid=$request->surveyid;
	    return view('dashboard.gender.addComment',['question_id'=>$question_id,'surveyid'=>$surveyid]);
	}

    
    
    
    
    
}
    
    










