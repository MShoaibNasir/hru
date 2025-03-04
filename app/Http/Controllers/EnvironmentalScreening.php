<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Exports\EnvironmentExport;
use App\Models\GenderSafeguard;
use Excel;
use App\Models\GenderStatusHistory;
use App\Models\SurveyData;
use App\Models\Environment;
use App\Models\EnvironmentTrail;
use App\Models\Lot;
use App\Models\District;
use App\Models\Tehsil;
use App\Models\Answer;
use App\Models\UC;
use App\Models\FormStatus;
use App\Models\QuestionTitle;
use App\Models\Option;
use App\Models\Role;
use Auth;
use DB;
use Carbon\Carbon;

class EnvironmentalScreening extends Controller

{
    
    public function index()
    {
		$lots = Lot::whereIn('id', json_decode(Auth::user()->lot_id))->pluck('name','id')->all();
		return view('dashboard.environment.alldatalist', compact('lots'));
    }
    
	public function total_environment_datalist_fetch_data(Request $request)
	{
	
	    $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        $data = DB::table('survey_report_section_102')
            ->join('survey_form', 'survey_form.id', '=', 'survey_report_section_102.survey_id')
            ->join('lots', 'survey_form.lot_id', '=', 'lots.id')
            ->join('districts', 'survey_form.district_id', '=', 'districts.id')
            ->join('tehsil', 'survey_form.tehsil_id', '=', 'tehsil.id')
            ->join('uc', 'survey_form.uc_id', '=', 'uc.id')
          
            ->select(
                'lots.name as lot_name',
                'survey_form.id as survey_form_id',
                'survey_form.ref_no as survey_form_ref_no',
                'districts.name as district_name',
                'tehsil.name as tehsil_name',
                'uc.name as uc_name',
                'survey_report_section_102.status',
                'survey_report_section_102.role_name',
                'survey_report_section_102.action_by',
                'survey_report_section_102.id as primary_id'
            )
            ->where(function ($query) {
                $query->where('q_826', 'Yes')
                      ->orWhere('q_829', 'Yes')
                      ->orWhere('q_832', 'Yes')
                      ->orWhere('q_836', 'Yes')
                      ->orWhere('q_840', 'Yes')
                      ->orWhere('q_846', 'Yes')
                      ->orWhere('q_850', 'Yes')
                      ->orWhere('q_854', 'Yes')
                      ->orWhere('q_858', 'Yes')
                      ->orWhere('q_861', 'Yes');
        });

        $lot_id = $request->get('lot_id');
        $action_condition = $request->get('action_condition');
        $department = $request->get('department');
        $case_status = $request->get('case_status');
     
      
        $district_id = $request->get('district_id');
        $tehsil_id = $request->get('tehsil_id');
        $uc_id = $request->get('uc_id');
        $b_reference_number = $request->get('b_reference_number');
        $beneficiary_name = $request->get('beneficiary_name');
		$cnic = $request->get('cnic');
        $sorting = $request->get('sorting');
        $order = $request->get('direction');
        
        
        
        if($request->has('lot_id') && $request->get('lot_id') != null){
		 
			$data->where('survey_form.lot_id', intval($lot_id));
		
        }else{
         
            $data->whereIn('survey_form.lot_id', json_decode(Auth::user()->lot_id));
        }
        
        
        if($request->has('department') && $request->get('department') != null){
		 
			$data->where('survey_report_section_102.role_id', intval($department));
		
        }
        if($request->has('b_reference_number') && $request->get('b_reference_number') != null){
		   
			$data->where('survey_report_section_102.ref_no', intval($b_reference_number));
		
        }
        
        if($request->has('case_status') && $request->get('case_status') != null){
		 
			$data->where('survey_report_section_102.status', $case_status);
		
        }
        
        
        
		if($request->has('district_id') && $request->get('district_id') != null){
		   
			$data->where('survey_form.district_id', $district_id);
		}else{
            $data->whereIn('survey_form.district_id', json_decode(Auth::user()->district_id));
        }
        
        
        if($request->has('tehsil_id') && $request->get('tehsil_id') != null){
       
			$data->where('survey_form.tehsil_id', $tehsil_id);
        }else{
            $data->whereIn('survey_form.tehsil_id', json_decode(Auth::user()->tehsil_id));
        }
        
        if($request->has('uc_id') && $request->get('uc_id') != null){
			$data->where('survey_form.uc_id', $uc_id);
        }else{
              
            $data->whereIn('survey_form.uc_id', json_decode(Auth::user()->uc_id));
        }
        
        
        $selected_data = $data->get()->map(function ($item)  {
        
           
            $status='';
            if($item->status=='P'){
            $status='Pending';
            }
            if($item->status=='C'){
            $status='Case Close';
            }
            if($item->status=='CR'){
            $status='Case Register';
            }
          
            return [
                'Refrence No' => $item->survey_form_ref_no,
                'Department' => $item->role_name,
                'Status' => $status,
                'Lot' => $item->lot_name ?? '',
                'District' => $item->district_name ?? '',
                'Tehsil' => $item->tehsil_name ?? '',
                'UC' => $item->uc_name ?? '',
            ];
        });
        $jsondata = json_encode($selected_data);
        $data = $data->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
        return view('dashboard.environment.pagination_alldatalist', compact('data','jsondata'))->render();
   
	}
	
	
		public function environment_datalist_export(Request $request) 
    {
        $environment = $request->environment_data;
        $environment = json_decode($environment, true);
        return Excel::download(new EnvironmentExport($environment), 'environment_export_'.date('YmdHis').'.xlsx');
    }
	
	public function environment_status_trail_history(Request $request)
    {
		if($request->ajax()){
		    $construction_id = $request->construction_id;
		    $construction = Environment::where('id', $construction_id)->first();
			return view('dashboard.environment.render_report_trail_history', compact('construction'))->render(); 
		}
	}
	
	
	public function view($id){
	
    $construction = GenderSafeguard::findOrFail($id);
    $question_cat = QuestionTitle::with(['questions' => function ($q) use ($id) {
        
     
        

        $q->with(['gender_safeguard_answer' => function ($q) use ($id) {
            
            $q->where('social_safeguard_json_id', $id); 
            
        }]);
        // $q->with(['consructionimage' => function ($q) use ($id) { $q->where('construction_id', $id)->where('stage', 1); }]);
    }])
    ->whereHas('questions.gender_safeguard_answer', function ($q) use ($id) {$q->where('social_safeguard_json_id', $id); })
    ->select('id', 'name', 'section_order')
    ->where('visibility', 1)
    ->orderBy('section_order', 'ASC')
    ->get();


    
   return view('dashboard.gender.show', compact('id','question_cat','construction'));
    
    
    
    }
    
    
	public function environment_action_form(Request $request)
    {
       
		if($request->ajax()){
		    $construction_id = $request->construction_id;
			$decision = $request->decision;
			return view('dashboard.environment.construction_action_form', compact('construction_id','decision'))->render();  
		}
	}
	
	public function environment_action_form_submit(Request $request){
  
	if($request->ajax()){
      $role = Role::findORFail(Auth::user()->role);
   
      $construction_id = $request->construction_id;
   
	  $decision = $request->decision;
	  $comment = $request->comment;
	  $status = $decision === 'approve' ? 'A' : ($decision === 'reject' ? 'R' : ($decision === 'Case Registered' ? 'CR': ($decision === 'Case Close' ? 'C':'')));
	
	  $construction = Environment::findORFail($construction_id);
	 

		    //30, FS
		    //34, IP
		    //36, HRU
		    //37, PSIA
		    //48  HRU Finance = R HRU = P
		    
		    
		    if($construction->role_id == 34 && $status == 'A'){
		   
		     $result=update_environment_status($construction_id,62,'P');
		   
		    }
		    if($construction->role_id == 62 && $status == 'C'){
		     update_environment_status($construction_id,62,'C');   
		    }
		    if($construction->role_id == 62 && $status == 'CR'){
		     update_environment_status($construction_id,27,'CR');   
		    } 

		    if($construction->role_id == 48 && $status == 'A'){
		     update_environment_status($construction_id,48,'C');
		    }
		    	
		    if($construction->role_id == 48 && $status == 'R'){
		     update_environment_status($construction_id,36,'P');
		    }
		    	 
		    if($construction->role_id == 37 && $status == 'R'){
		     update_environment_status($construction_id,36,'P');  
		    }
		      
		    if($construction->role_id == 61 && $status == 'R'){
		     update_environment_status($construction_id,34,'P');  
		    }
		     
		    if($construction->role_id == 34 && $status == 'R')
		    {
		        
		       $result=update_environment_status(intval($construction_id),27,'P');
		     
		    }
		 
		
			$data['environment_id'] = $construction_id;
			$data['action_by'] = Auth::user()->id;
			$data['role_id'] = $role->id;
			$data['role_name'] = $role->name;
			$data['status'] = $status;
			$data['action'] = $decision;
			$data['comment'] = $comment;
            $result = EnvironmentTrail::create($data);
            echo '<div class="col-md-12"><div class="alert alert-success"><strong>Success!</strong>  Environment Screening action submit is successfully</div></div>';
			
 
	  
	}
	
	}
	
	public function data_check(){
	     $data=DB::table('survey_report_section_102')
        ->orWhere('q_826','Yes')
        ->orWhere('q_829','Yes')
        ->orWhere('q_832','Yes')
        ->orWhere('q_836','Yes')
        ->orWhere('q_846','Yes')
        ->orWhere('q_850','Yes')
        ->orWhere('q_854','Yes')
        ->orWhere('q_858','Yes')
        ->orWhere('q_861','Yes')
        ->orWhere('q_871','Yes')
        ->orWhere('q_881','Yes')
        ->pluck('ref_no');
	    
	}
	
	
	
	public function EnvironmentProfile($id) {
    $question_cat = QuestionTitle::with(['questions' => function ($q) use ($id) {
        $q->with(['useranswer' => function ($q) use ($id) {$q->where('survey_form_id', $id); }]);
        $q->with(['decision' => function ($q) use ($id) { $q->where('survey_id', $id); }]);
    }])
    ->whereHas('questions.useranswer', function ($q) use ($id) {$q->where('survey_form_id', $id); })
    ->select('id', 'name', 'section_order')
    ->where('visibility', 1)
    ->where('question_title.id', 102)
    ->orderBy('section_order', 'ASC')
    ->get();
    

    
    $form_status=DB::table('survey_report_section_102')
    ->join('survey_form','survey_report_section_102.survey_id','=','survey_form.id')
    ->where('survey_report_section_102.survey_id',$id)
    ->select(
    'survey_report_section_102.status',
    'survey_report_section_102.role_id'
    ,'survey_form.lot_id',
    'survey_form.district_id','survey_form.tehsil_id','survey_form.uc_id','survey_report_section_102.id as id'
    )
    ->first();
   
    return view('dashboard.environment.environmentProfile', compact('id','question_cat','form_status'));
    }
    
    
    public function environment_option_edit(Request $request){

        $options=Option::where('question_id',$request->question_id)->select('id','name')->get();
        $question_id=$request->question_id;
        $survey_id=$request->survey_id;
        return view('dashboard.environment.editQuestion',['options'=>$options,'survey_id'=>$survey_id,'question_id'=>$question_id]);
    }
    
    public function update_environment_option(Request $request){
      
        
        
        if($request->question_id==826){
            $column='q_826';
        }
        if($request->question_id==829){
            $column='q_829';
        }
        if($request->question_id==832){
            $column='q_832';
        }
        if($request->question_id==836){
            $column='q_836';
        }
        if($request->question_id==840){
            $column='q_840';
        }
        if($request->question_id==846){
            $column='q_846';
        }
        if($request->question_id==850){
            $column='q_850';
        }
        if($request->question_id==854){
            $column='q_854';
        }
        if($request->question_id==858){
            $column='q_858';
        }
        if($request->question_id==861){
            $column='q_861';
        }
        if($request->question_id==864){
            $column='q_864';
        }
        if($request->question_id==868){
            $column='q_868';
        }
        if($request->question_id==871){
            $column='q_871';
        }
        if($request->question_id==872){
            $column='q_872';
        }
        if($request->question_id==873){
            $column='q_873';
        }
        if($request->question_id==881){
            $column='q_881';
        }
        if($request->question_id==886){
            $column='q_886';
        }
        DB::table('survey_report_section_102')->where('survey_id',$request->survey_id)->update([$column=>$request->option]);
        DB::table('answers')->where('survey_form_id',$request->survey_id)->where('question_id',$request->question_id)->update(['answer'=>$request->option]);
        DB::table('environment_answer_changes_logs')->insert([
            'user_id'=>Auth::user()->id,
            'question_id'=>$request->question_id,
            'option'=>$request->option,
            'survey_id'=>$request->survey_id,
        ]);
        
        
        return redirect()->back()->with('success','Data update Successfully!');
        
    }
    
    
    
    
    
}
    
    










