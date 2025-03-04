<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\MNE;
use App\Models\MNEStatusHistory;
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

class MNEController extends Controller

{
    
    
    public function index()
    {
	  if(Auth::user()->role == 34 || Auth::user()->role == 37 || Auth::user()->role == 51 || Auth::user()->role ==38 || Auth::user()->role ==40 || Auth::user()->role ==1){
		$lots = Lot::whereIn('id', json_decode(Auth::user()->lot_id))->pluck('name','id')->all();
		return view('dashboard.mne.alldatalist', compact('lots'));
	  }else{
        return redirect()->route('admin.dashboard')->with([ 'error' => 'You are not authorized this page!']);
      }
    }
    
	public function total_mne_datalist_fetch_data(Request $request, MNE $mnedata)
	{
	    //dump($request->all());
	    $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        
        
        
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

		$mnedata = $mnedata->newQuery();

		
        
        
        
        if($request->has('department') && $request->get('department') != null){
			$mnedata->where('role_id', $department);
        }
		
		
		
		if($request->has('lot_id') && $request->get('lot_id') != null){
			$mnedata->where('lot_id', $lot_id);
        }else{
            $mnedata->whereIn('lot_id', json_decode(Auth::user()->lot_id));
        }

        
        
		if($request->has('district_id') && $request->get('district_id') != null){ 
			$mnedata->where('district_id', $district_id);
		}else{
            $mnedata->whereIn('district_id', json_decode(Auth::user()->district_id));
        }
        
        if($request->has('tehsil_id') && $request->get('tehsil_id') != null){
			$mnedata->where('tehsil_id', $tehsil_id);
        }else{
            $mnedata->whereIn('tehsil_id', json_decode(Auth::user()->tehsil_id));
        }
        
        if($request->has('uc_id') && $request->get('uc_id') != null){
			$mnedata->where('uc_id', $uc_id);
        }else{
            $mnedata->whereIn('uc_id', json_decode(Auth::user()->uc_id));
        }
        
		if($request->has('b_reference_number') && $request->get('b_reference_number') != null){
			$mnedata->where('ref_no', $b_reference_number);
        }
        
        /*
        if($request->has('beneficiary_name') && $request->get('beneficiary_name') != null){
			$mnedata->where('beneficiary_details->beneficiary_name','like','%'.$beneficiary_name.'%');
        }
		
		if($request->has('cnic') && $request->get('cnic') != null){
			$mnedata->where('cnic','like','%'.$cnic.'%');
        }
		*/
		

        $mnedata->orderBy($sorting, $order);
        $data = $mnedata->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);

        return view('dashboard.mne.pagination_alldatalist', compact('data'))->render();
        
   
	}
	
	public function mne_status_trail_history(Request $request)
    {
		if($request->ajax()){

		    $mne_id = $request->mne_id;
		    $mne = MNE::where('id', $mne_id)->first();
            //dump($mne->getstatustrail->toArray());
            
			return view('dashboard.mne.render_report_trail_history', compact('mne'))->render(); 
			
		    
		
		}
	}
	
	
	public function view($id){
     if(Auth::user()->role == 34 || Auth::user()->role == 37 || Auth::user()->role == 51 || Auth::user()->role ==38 || Auth::user()->role ==40 || Auth::user()->role ==1){
    
	    
	    
	if($id == 1){ 
	    //mne_destructure($id);
	    
	    //$mnes = MNE::pluck('id');
	    //foreach($mnes as $mne_id){
	        //echo $mne_id;
	    //mne_destructure($mne_id);
	    //}
	}
	
   $mne = MNE::findOrFail($id);



    $question_cat = QuestionTitle::with(['questions' => function ($q) use ($id, $mne) {
        
        /*
        $q->with(['options'=> function($q) use ($id){
            $q->with(['subsection'=> function($q) use ($id){ 
                $q->with(['questions'=> function($q) use ($id){
                    
                    //sub section answer image
                    $q->with(['mneanswer' => function ($q) use ($id) {
                    $q->where('mne_json_id', $id); 
                    }]);
                    $q->with(['mneimage' => function ($q) use ($id) { $q->where('mne_id', $id)->where('stage', 1); }]);
                    
                    
                    $q->with('options'); }])->where('sub_section','true')->where('form_id', 27); 
                
            }]);
        }]);
        */
        

        $q->with(['mneanswer' => function ($q) use ($id, $mne) {
            $q->where('mne_json_id', $id); 
        }]);
        
        $q->with(['mneimage' => function ($q) use ($id) { $q->where('mne_id', $id); }]);
        
        
    }])
    ->whereHas('questions.mneanswer', function ($q) use ($id) {$q->where('mne_json_id', $id); })
    ->select('id', 'name', 'section_order')
    ->where('visibility', 1)
    ->orderBy('section_order', 'ASC')
    ->get();


//dd($question_cat->toArray());
  
    return view('dashboard.mne.show', compact('id','question_cat','mne'));
	}else{
        return redirect()->route('admin.dashboard')->with([ 'error' => 'You are not authorized this page!']);
    } 
    
    
    }
    
    /*
    public function viewnew_stage_2($id){
      $mne = MNE::findOrFail($id);  
      $mneformanswer = Form::with(['sections' => function($q) use ($id, $mne){ 
        $q->with(['questions'=> function($q) use ($id, $mne){
            
            $q->with(['mneanswer' => function ($q) use ($id, $mne) { $q->where('mne_json_id', $id); }]);
            $q->with(['mneimage' => function ($q) use ($id) { $q->where('mne_id', $id); }]);
            
            $q->with(['options'=> function($q) use ($id, $mne){
            $q->with(['subsection'=> function($q) use ($id, $mne){ $q->with(['questions'=> function($q) use ($id, $mne){ 
                
                $q->with(['mneanswer' => function ($q) use ($id, $mne) { $q->where('mne_json_id', $id); }]);
                $q->with(['mneimage' => function ($q) use ($id) { $q->where('mne_id', $id)->where('stage', 2); }]);
                $q->with('options'); }])->where('sub_section','true')->where('form_id', 28); }]);
        }]); 
        }]);
    }])->where('id',28)->select('id','name')->first();
    
//dd($mneformanswer->toArray()); 
//return view('dashboard.mne.show', compact('id','mne','mneformanswer'));
    }
    */
    
    
	public function mne_action_form(Request $request)
    {
		if($request->ajax()){
		    $mne_id = $request->mne_id;
			$decision = $request->decision;
			return view('dashboard.mne.mne_action_form', compact('mne_id','decision'))->render();  
		}
	}
	
	public function mne_action_form_submit(Request $request){

	if($request->ajax()){

      $role = Role::findORFail(Auth::user()->role);
      $mne_id = $request->mne_id;
	  $decision = $request->decision;
	  $comment = $request->comment;
	  $status = $decision === 'approve' ? 'A' : ($decision === 'reject' ? 'R' : ($decision === 'hold' ? 'H' : ''));
	  //$status = $decision === 'approve' ? 'A' : ($decision === 'reject' ? 'R' : ($decision === 'hold' ? 'H' : ($decision === 'pending' ? 'P' : '')));


		    $mne = MNE::findORFail($mne_id);
		    //34, IP
		    //37, PSIA
		    //51  MNE
		    
		    if($mne->role_id == 34 && $status == 'A'){
		     update_mne_status($mne_id,37,'P',$status);
		    }elseif($mne->role_id == 37 && $status == 'A'){
		     update_mne_status($mne_id,51,'P',$status);   
		    }elseif($mne->role_id == 51 && $status == 'A'){
		     update_mne_status($mne_id,51,'C',$status);
		     
		     
		    }elseif($mne->role_id == 51 && $status == 'R'){
		     update_mne_status($mne_id,37,'P',$status);
		    }elseif($mne->role_id == 37 && $status == 'R'){
		     update_mne_status($mne_id,34,'P',$status);  
		    }elseif($mne->role_id == 34 && $status == 'R'){
		     update_mne_status($mne_id,27,'P',$status);   
		    }
		    
		    
		    
		    
			
			
			
			$data = $request->all();
			$data['mne_id'] = $mne_id;
			$data['stage'] = $mne->stage;
			$data['ref_no'] = $mne->ref_no;
			$data['action_by'] = Auth::user()->id;
			
			$data['role_id'] = $role->id;
			$data['role_name'] = $role->name;
			$data['status'] = $status;
			
			//$data['lot_id'] = $mne->lot_id;
			$data['action'] = $decision;
			$data['comment'] = $comment;

			//dump($data);
            $result = MNEStatusHistory::create($data);
            echo '<div class="col-md-12"><div class="alert alert-success"><strong>Success!</strong>  MNE action submit is successfully</div></div>';
			
 
	  
	}
	}
	
    
    
}









