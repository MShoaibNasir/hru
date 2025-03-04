<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;

use App\Models\EnvironmentCaseJson;
use App\Models\EnvironmentCaseStatusHistories;
use App\Models\SurveyData;
use App\Models\Lot;
use Excel;
use App\Exports\EnvironmentMitigationExport;
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

class EnvironmentCaseController extends Controller

{
    
    
    public function index()
    {
		$lots = Lot::whereIn('id', json_decode(Auth::user()->lot_id))->pluck('name','id')->all();
		return view('dashboard.environmentCase.alldatalist', compact('lots'));
    }
    
	public function fetchTotalEnvironmentDatalist(Request $request, EnvironmentCaseJson $constructiondata)
	{
	    //dump($request->all());
	    $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        $form_name=$request->form_name;
        
        $stage = $request->get('stage');
        $action_condition = $request->get('action_condition');
       
        $department = $request->get('department');
        
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

	    $constructiondata->where('status', $action_condition);
       
        
        if($request->has('department') && $request->get('department') != null){
			$constructiondata->where('role_id', $department);
        }
        if(isset($form_name)){
			$constructiondata->where('form_id', intval($form_name));
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
        
        $selected_data = $constructiondata->get()->map(function ($item)  {
        
            $beneficairy_details=json_decode($item->beneficiary_details);
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
                'Checklist Name' => $item->getFormName->name ?? null,
                'Date' => Carbon::parse($item->created_at)->format('d-m-Y'),
                'Created By' => $item->getuser->name ?? '',
                'Ref No' => $item->ref_no ?? '',
                'Department' => $item->role_name ?? '',
                'Status' => $item->tehsil_name ?? '',
                'Lot' => $item->getlot->name ?? '',
                'District' => $item->getdistrict->name ?? '',
                'Tehsil' => $item->gettehsil->name ?? '',
                'UC' => $item->getuc->name ?? '',
            ];
        });
        $jsondata = json_encode($selected_data);
        
        
        
        $data = $constructiondata->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);

        return view('dashboard.environmentCase.pagination_alldatalist', compact('data','jsondata'))->render();
        
   
	}
	
	public function environment_datalist_mitigation_export(Request $request) 
    {
        $environment = $request->environment_data;
        $environment = json_decode($environment, true);
        return Excel::download(new EnvironmentMitigationExport($environment), 'environment_export_mitigation_'.date('YmdHis').'.xlsx');
    }
	
	public function statusTrailHistory(Request $request)
    {
		if($request->ajax()){

		    $construction_id = $request->construction_id;
		    $construction = EnvironmentCaseJson::where('id', $construction_id)->first();
			return view('dashboard.environmentCase.render_report_trail_history', compact('construction'))->render(); 
			
		    
		
		}
	}
	
	
	public function view($id){
	    
	    
	    

	
    $construction = EnvironmentCaseJson::findOrFail($id);
    $question_cat = QuestionTitle::with(['questions' => function ($q) use ($id) {
        
      
        

        $q->with(['environment_answer' => function ($q) use ($id) {
            
            $q->where('environment_case_json_id', $id); 
            
        }]);
        
    }])
    ->whereHas('questions.environment_answer', function ($q) use ($id) {$q->where('environment_case_json_id', $id); })
    ->select('id', 'name', 'section_order')
    ->where('visibility', 1)
    ->orderBy('section_order', 'ASC')
    ->get();
    

//dump($question_cat->toArray());

    
   return view('dashboard.environmentCase.show', compact('id','question_cat','construction'));
    
    
    
    }
    
    
	public function showActionForm(Request $request)
    {
		if($request->ajax()){
		    $construction_id = $request->construction_id;
			$decision = $request->decision;
			return view('dashboard.environmentCase.construction_action_form', compact('construction_id','decision'))->render();  
		}
	}
	
	public function submitActionForm(Request $request){

	if($request->ajax()){

      $role = Role::findORFail(Auth::user()->role);
      $construction_id = $request->construction_id;
	  $decision = $request->decision;
	  $comment = $request->comment;
	  $status = $decision === 'approve' ? 'A' : ($decision === 'reject' ? 'R' : ($decision === 'hold' ? 'H' : ''));


    $construction = EnvironmentCaseJson::findORFail($construction_id);

    
    if($construction->role_id == 62 && $status == 'A'){
     update_environment_case_status($construction_id,62,'A',$status);
    }
    
    if($construction->role_id == 62 && $status == 'R'){
      $result= update_environment_case_status($construction_id,62,'R',$status);
    
    }

			$data = $request->all();
			$data['environment_case_id'] = $construction_id;
			$data['ref_no'] = $construction->ref_no;
			$data['action_by'] = Auth::user()->id;
			$data['role_id'] = $role->id;
			$data['role_name'] = $role->name;
			$data['status'] = $status;
			$data['action'] = $decision;
			$data['comment'] = $comment;

			//dump($data);
            $result = EnvironmentCaseStatusHistories::create($data);
            echo '<div class="col-md-12"><div class="alert alert-success"><strong>Success!</strong>  Environment Case action submit  successfully</div></div>';
			
 
	  
	}
	}
	
	
	public function add_comment_view(Request $request){
	    $question_id=$request->question_id;
	    $surveyid=$request->surveyid;
	    return view('dashboard.environmentCase.addComment',['question_id'=>$question_id,'surveyid'=>$surveyid]);
	}
	public function upload_comment(Request $request){
       
        DB::table('comment_soc_gen_envi')->insert(
            [
            'comment'=>$request->comment,
            'model_name'=>$request->modal_name,
            'primary_id'=>$request->primary_id,
            'question_id'=>$request->question_id,
            'user_id'=>Auth::user()->id,
            ]);
	    return redirect()->back()->with('success','Add Comment To This Question Successfully!');
	}
	public function delete_comment(Request $request,$question_id,$survey_id){
	     $delete_comment= DB::table('comment_soc_gen_envi')->where('question_id',$question_id)->where('primary_id',$survey_id)->delete();
	     if($delete_comment){
	         return redirect()->back()->with('success','Revert Comment Successfully!');
	     }
	}
    
    
}









