<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\Construction;
use App\Models\ConstructionStatusHistory;
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

class ConstructionController extends Controller

{
    
    
    public function index()
    {
		$stage = Construction::distinct()->pluck('stage', 'stage')->filter(function ($value) { return $value !== null; });
		$lots = Lot::whereIn('id', json_decode(Auth::user()->lot_id))->pluck('name','id')->all();
		return view('dashboard.construction.alldatalist', compact('stage','lots'));
    }
    
	public function total_construction_datalist_fetch_data(Request $request, Construction $constructiondata)
	{
	 
	    $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        
        $stage = $request->get('stage');
        $action_condition = $request->get('action_condition');
        $department = $request->get('department');
        $status = $request->get('status');
        
        $lot_id = $request->get('lot_id');
        $district_id = $request->get('district_id');
        $tehsil_id = $request->get('tehsil_id');
        $uc_id = $request->get('uc_id');
        
        $b_reference_number = $request->get('b_reference_number');
        $beneficiary_name = $request->get('beneficiary_name');
		$cnic = $request->get('cnic');
		
        $sorting = $request->get('sorting');
        $order = $request->get('direction');

		$constructiondata = $constructiondata->newQuery();

		if($request->has('stage') && $request->get('stage') != null){
			$constructiondata->where('stage', $stage);
        }
        
        if($request->has('action_condition') && $request->get('action_condition') != null){
            if($action_condition == 'yes'){
			$constructiondata->where('action_condition', 3);
            }else{
            $constructiondata->whereIn('action_condition', [0,1,2]);    
            }
        }
        
        if($request->has('department') && $request->get('department') != null){
			if($status != 'A' && $status != 'R'){
			$constructiondata->where('role_id', $department);
			}
        }
        
        if($request->has('status') && $request->get('status') != null){
            
        if($status == 'A' || $status == 'R'){
        $constructiondata->whereHas('getdepartmentstatus', function ($q) use ($request, $department, $status){
            if($request->has('department') && $request->get('department') != null){
                 $q->where('role_id', $department);    
            }
            $q->where('status', $status);
            
        });
        }else{
         $constructiondata->where('status', $status);   
        }
            
            
            
			
        }
		
		if($request->has('lot_id') && $request->get('lot_id') != null){
			$constructiondata->where('lot_id', $lot_id);
        }else{
            $constructiondata->whereIn('lot_id', json_decode(Auth::user()->lot_id));
        }

        
        
		if($request->has('district_id') && $request->get('district_id') != null){ 
			$constructiondata->where('district_id', $district_id);
		}else{
            $constructiondata->whereIn('district_id', json_decode(Auth::user()->district_id));
        }
        
        if($request->has('tehsil_id') && $request->get('tehsil_id') != null){
			$constructiondata->where('tehsil_id', $tehsil_id);
        }else{
            $constructiondata->whereIn('tehsil_id', json_decode(Auth::user()->tehsil_id));
        }
        
        if($request->has('uc_id') && $request->get('uc_id') != null){
			$constructiondata->where('uc_id', $uc_id);
        }else{
            $constructiondata->whereIn('uc_id', json_decode(Auth::user()->uc_id));
        }
        
		if($request->has('b_reference_number') && $request->get('b_reference_number') != null){
			$constructiondata->where('ref_no', $b_reference_number);
        }
        
        if($request->has('beneficiary_name') && $request->get('beneficiary_name') != null){
			$constructiondata->where('beneficiary_details->beneficiary_name','like','%'.$beneficiary_name.'%');
        }
		
		if($request->has('cnic') && $request->get('cnic') != null){
			$constructiondata->where('cnic','like','%'.$cnic.'%');
        }
		
		

        $constructiondata->orderBy($sorting, $order);
        $data = $constructiondata->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);

        return view('dashboard.construction.pagination_alldatalist', compact('data'))->render();
        
   
	}
	
	public function construction_status_trail_history(Request $request)
    {
		if($request->ajax()){

		    $construction_id = $request->construction_id;
		    $construction = Construction::where('id', $construction_id)->first();
            //dump($construction->getstatustrail->toArray());
            
			return view('dashboard.construction.render_report_trail_history', compact('construction'))->render(); 
			
		    
		
		}
	}
	
	
	public function view($id){
	    
	    
	    
	
	
   $construction = Construction::findOrFail($id);



    $question_cat = QuestionTitle::with(['questions' => function ($q) use ($id, $construction) {
        
        /*
        $q->with(['options'=> function($q) use ($id){
            $q->with(['subsection'=> function($q) use ($id){ 
                $q->with(['questions'=> function($q) use ($id){
                    
                    //sub section answer image
                    $q->with(['consructionanswer' => function ($q) use ($id) {
                    $q->where('construction_json_id', $id); 
                    }]);
                    $q->with(['consructionimage' => function ($q) use ($id) { $q->where('construction_id', $id)->where('stage', 1); }]);
                    
                    
                    $q->with('options'); }])->where('sub_section','true')->where('form_id', 27); 
                
            }]);
        }]);
        */
        

        $q->with(['consructionanswer' => function ($q) use ($id, $construction) {
            
            $q->where('construction_json_id', $id); 
            
        }]);
        
        if($construction->stage == 'Stage 1'){
        $q->with(['consructionimage' => function ($q) use ($id) { $q->where('construction_id', $id)->where('stage', 1); }]);
        }elseif($construction->stage == 'Stage 2'){
        $q->with(['consructionimage' => function ($q) use ($id) { $q->where('construction_id', $id)->where('stage', 2); }]);
        }elseif($construction->stage == 'Stage 3'){    
        $q->with(['consructionimage' => function ($q) use ($id) { $q->where('construction_id', $id)->where('stage', 3); }]);   
        }elseif($construction->stage == 'Stage 4'){    
        $q->with(['consructionimage' => function ($q) use ($id) { $q->where('construction_id', $id)->where('stage', 4); }]);   
        }
        
    }])
    ->whereHas('questions.consructionanswer', function ($q) use ($id) {$q->where('construction_json_id', $id); })
    ->select('id', 'name', 'section_order')
    //->where('visibility', 1)
    ->orderBy('section_order', 'ASC')
    ->get();

//if($construction->stage == 'Stage 2'){
//dd($question_cat->toArray());
//}    
   return view('dashboard.construction.show', compact('id','question_cat','construction'));
    
    
    
    }
    
    /*
    public function viewnew_stage_2($id){
      $construction = Construction::findOrFail($id);  
      $constructionformanswer = Form::with(['sections' => function($q) use ($id, $construction){ 
        $q->with(['questions'=> function($q) use ($id, $construction){
            
            $q->with(['consructionanswer' => function ($q) use ($id, $construction) { $q->where('construction_json_id', $id); }]);
            $q->with(['consructionimage' => function ($q) use ($id) { $q->where('construction_id', $id); }]);
            
            $q->with(['options'=> function($q) use ($id, $construction){
            $q->with(['subsection'=> function($q) use ($id, $construction){ $q->with(['questions'=> function($q) use ($id, $construction){ 
                
                $q->with(['consructionanswer' => function ($q) use ($id, $construction) { $q->where('construction_json_id', $id); }]);
                $q->with(['consructionimage' => function ($q) use ($id) { $q->where('construction_id', $id)->where('stage', 2); }]);
                $q->with('options'); }])->where('sub_section','true')->where('form_id', 28); }]);
        }]); 
        }]);
    }])->where('id',28)->select('id','name')->first();
    
//dd($constructionformanswer->toArray()); 
//return view('dashboard.construction.show', compact('id','construction','constructionformanswer'));
    }
    */
    
    
	public function construction_action_form(Request $request)
    {
		if($request->ajax()){
		    $construction_id = $request->construction_id;
			$decision = $request->decision;
			return view('dashboard.construction.construction_action_form', compact('construction_id','decision'))->render();  
		}
	}
	
	public function construction_action_form_submit(Request $request){

	if($request->ajax()){

      $role = Role::findORFail(Auth::user()->role);
      $construction_id = $request->construction_id;
	  $decision = $request->decision;
	  $comment = $request->comment;
	  $status = $decision === 'approve' ? 'A' : ($decision === 'reject' ? 'R' : ($decision === 'hold' ? 'H' : ''));
	   $construction = Construction::findORFail($construction_id);
		    //30, FS
		    //34, IP
		    //36, HRU
		    //37, PSIA
		    //48  HRU Finance = R HRU = P
		    
		    if($construction->role_id == 30 && $status == 'A'){
		     update_construction_status($construction_id,34,'P',$status);
		       update_construction_departmentwise_status($construction_id,30,'A', $comment);
               update_construction_departmentwise_status($construction_id,34,'P', $comment);
		    }elseif($construction->role_id == 34 && $status == 'A'){
		     update_construction_status($construction_id,36,'P',$status);
		       update_construction_departmentwise_status($construction_id,34,'A', $comment);
               update_construction_departmentwise_status($construction_id,36,'P', $comment);
		    }elseif($construction->role_id == 36 && $status == 'A'){
		     update_construction_status($construction_id,37,'P',$status);
		       update_construction_departmentwise_status($construction_id,36,'A', $comment);
               update_construction_departmentwise_status($construction_id,37,'P', $comment);
		    }elseif($construction->role_id == 37 && $status == 'A'){
		     update_construction_status($construction_id,48,'P',$status);
		       update_construction_departmentwise_status($construction_id,37,'A', $comment);
               update_construction_departmentwise_status($construction_id,48,'P', $comment);
		    }elseif($construction->role_id == 48 && $status == 'A'){
		     update_construction_status($construction_id,48,'C',$status);
		       update_construction_departmentwise_status($construction_id,48,'A', $comment);
            
		     
		     
		    }elseif($construction->role_id == 48 && $status == 'R'){
		     update_construction_status($construction_id,37,'P',$status);
		       update_construction_departmentwise_status($construction_id,48,'R', $comment);
               update_construction_departmentwise_status($construction_id,37,'P', $comment);
		    }elseif($construction->role_id == 37 && $status == 'R'){
		     update_construction_status($construction_id,36,'P',$status);
		       update_construction_departmentwise_status($construction_id,37,'R', $comment);
               update_construction_departmentwise_status($construction_id,36,'P', $comment);
		    }elseif($construction->role_id == 36 && $status == 'R'){
		     update_construction_status($construction_id,34,'P',$status); 
		       update_construction_departmentwise_status($construction_id,36,'R', $comment);
               update_construction_departmentwise_status($construction_id,34,'P', $comment);
		    }elseif($construction->role_id == 34 && $status == 'R'){
		     update_construction_status($construction_id,30,'P',$status);
		       update_construction_departmentwise_status($construction_id,34,'R', $comment);
               update_construction_departmentwise_status($construction_id,30,'P', $comment);
		    }elseif($construction->role_id == 30 && $status == 'R'){
		     update_construction_status($construction_id,27,'R',$status);
		       update_construction_departmentwise_status($construction_id,30,'R', $comment);
		       
		       
		       
		    }elseif($construction->role_id == 30 && $status == 'H'){
		       update_construction_departmentwise_status($construction_id,30,'H', $comment);
		    }elseif($construction->role_id == 34 && $status == 'H'){
		       update_construction_departmentwise_status($construction_id,34,'H', $comment);
		    }elseif($construction->role_id == 36 && $status == 'H'){
		       update_construction_departmentwise_status($construction_id,36,'H', $comment);
		    }elseif($construction->role_id == 37 && $status == 'H'){
		       update_construction_departmentwise_status($construction_id,37,'H', $comment);
		    }elseif($construction->role_id == 48 && $status == 'H'){
		       update_construction_departmentwise_status($construction_id,48,'H', $comment);
		    }
		    


			$data = $request->all();
			$data['construction_id'] = $construction_id;
			$data['stage'] = $construction->stage;
			$data['ref_no'] = $construction->ref_no;
			$data['action_by'] = Auth::user()->id;
			
			$data['role_id'] = $role->id;
			$data['role_name'] = $role->name;
			$data['status'] = $status;
			
			//$data['lot_id'] = $construction->lot_id;
			$data['action'] = $decision;
			$data['comment'] = $comment;

			//dump($data);
			
            $result = ConstructionStatusHistory::create($data);
            echo '<div class="col-md-12"><div class="alert alert-success"><strong>Success!</strong>  Construction action submit is successfully</div></div>';
			
 
	  
	}
	}


    public function construction_action_bulk(Request $request){
        
    $construction_ids = isset($request->construction_ids) ? explode(',', $request->construction_ids) : [];
    if(count($construction_ids)<=0){
        return redirect()->back()->with('error', 'Kindly select at least one construction form to proceed further!');
    }
    foreach ($construction_ids as $id) {
       
        $role = Role::findORFail(Auth::user()->role);
        $construction_id = $id;
        $decision = 'approve';
        $comment = 'Bulk Approve';
        $status = $decision === 'approve' ? 'A' : ($decision === 'reject' ? 'R' : ($decision === 'hold' ? 'H' : ''));
        $construction = Construction::findORFail($construction_id);
           
        if($construction->role_id == 30 && $status == 'A'){
         update_construction_status($construction_id,34,'P',$status);
           update_construction_departmentwise_status($construction_id,30,'A', $comment);
           update_construction_departmentwise_status($construction_id,34,'P', $comment);
        }elseif($construction->role_id == 34 && $status == 'A'){
         update_construction_status($construction_id,36,'P',$status);
           update_construction_departmentwise_status($construction_id,34,'A', $comment);
           update_construction_departmentwise_status($construction_id,36,'P', $comment);
        }elseif($construction->role_id == 36 && $status == 'A'){
         update_construction_status($construction_id,37,'P',$status);
           update_construction_departmentwise_status($construction_id,36,'A', $comment);
           update_construction_departmentwise_status($construction_id,37,'P', $comment);
        }elseif($construction->role_id == 37 && $status == 'A'){
         update_construction_status($construction_id,48,'P',$status);
           update_construction_departmentwise_status($construction_id,37,'A', $comment);
           update_construction_departmentwise_status($construction_id,48,'P', $comment);
        }elseif($construction->role_id == 48 && $status == 'A'){
         update_construction_status($construction_id,48,'C',$status);
           update_construction_departmentwise_status($construction_id,48,'A', $comment);
        
         
         
        }elseif($construction->role_id == 48 && $status == 'R'){
         update_construction_status($construction_id,37,'P',$status);
           update_construction_departmentwise_status($construction_id,48,'R', $comment);
           update_construction_departmentwise_status($construction_id,37,'P', $comment);
        }elseif($construction->role_id == 37 && $status == 'R'){
         update_construction_status($construction_id,36,'P',$status);
           update_construction_departmentwise_status($construction_id,37,'R', $comment);
           update_construction_departmentwise_status($construction_id,36,'P', $comment);
        }elseif($construction->role_id == 36 && $status == 'R'){
         update_construction_status($construction_id,34,'P',$status); 
           update_construction_departmentwise_status($construction_id,36,'R', $comment);
           update_construction_departmentwise_status($construction_id,34,'P', $comment);
        }elseif($construction->role_id == 34 && $status == 'R'){
         update_construction_status($construction_id,30,'P',$status);
           update_construction_departmentwise_status($construction_id,34,'R', $comment);
           update_construction_departmentwise_status($construction_id,30,'P', $comment);
        }elseif($construction->role_id == 30 && $status == 'R'){
         update_construction_status($construction_id,27,'R',$status);
           update_construction_departmentwise_status($construction_id,30,'R', $comment);
           
           
           
        }elseif($construction->role_id == 30 && $status == 'H'){
           update_construction_departmentwise_status($construction_id,30,'H', $comment);
        }elseif($construction->role_id == 34 && $status == 'H'){
           update_construction_departmentwise_status($construction_id,34,'H', $comment);
        }elseif($construction->role_id == 36 && $status == 'H'){
           update_construction_departmentwise_status($construction_id,36,'H', $comment);
        }elseif($construction->role_id == 37 && $status == 'H'){
           update_construction_departmentwise_status($construction_id,37,'H', $comment);
        }elseif($construction->role_id == 48 && $status == 'H'){
           update_construction_departmentwise_status($construction_id,48,'H', $comment);
        }


        $data = $request->all();
        $data['construction_id'] = $construction_id;
        $data['stage'] = $construction->stage;
        $data['ref_no'] = $construction->ref_no;
        $data['action_by'] = Auth::user()->id;
        
        $data['role_id'] = $role->id;
        $data['role_name'] = $role->name;
        $data['status'] = $status;
        
        //$data['lot_id'] = $construction->lot_id;
        $data['action'] = $decision;
        $data['comment'] = $comment;

        //dump($data);
        
        $result = ConstructionStatusHistory::create($data);
        
    }  
    return redirect()->back()->with('success', 'Form approved in bulk successfully!');
			
    }
	
    
    
}









