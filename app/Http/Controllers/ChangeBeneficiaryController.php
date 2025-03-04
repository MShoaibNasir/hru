<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Models\SurveyData;
use App\Models\ChangeBeneficiary;
use App\Models\ChangeBeneficiaryStatusHistory;
use App\Models\Lot;
use App\Models\District;
use App\Models\Tehsil;
use App\Models\UC;
use App\Models\User;
use App\Models\Role;
use DB;
use Auth;
use Validator;
use Carbon\Carbon;
use Hash;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class ChangeBeneficiaryController extends Controller
{
    
    
    public function index()
    {
		$lots = Lot::whereIn('id', json_decode(Auth::user()->lot_id))->pluck('name','id')->all();
		return view('dashboard.changebeneficiary.index', compact('lots'));
    }
    
	public function fetch_datalist(Request $request, ChangeBeneficiary $changebeneficiary)
	{
	    //dump($request->all());
	    $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        
        $department = $request->get('department');
        $datatype = $request->get('datatype');
        
        
        $lot_id = $request->get('lot_id');
        $district_id = $request->get('district_id');
        $tehsil_id = $request->get('tehsil_id');
        $uc_id = $request->get('uc_id');
        
        $b_reference_number = $request->get('b_reference_number');
        $beneficiary_name = $request->get('beneficiary_name');
		$cnic = $request->get('cnic');
		
        $sorting = $request->get('sorting');
        $order = $request->get('direction');

		$changebeneficiary = $changebeneficiary->newQuery();

		
        
        
        
        if($request->has('department') && $request->get('department') != null){
			$changebeneficiary->where('role_id', $department);
        }
        
        if($request->has('datatype') && $request->get('datatype') != null){
			if($datatype != 'Both'){
			$changebeneficiary->where('type', $datatype);
			}
        }
		
		if($request->has('lot_id') && $request->get('lot_id') != null){
			$changebeneficiary->where('lot_id', $lot_id);
        }else{
            $changebeneficiary->whereIn('lot_id', json_decode(Auth::user()->lot_id));
        }

        
        
		if($request->has('district_id') && $request->get('district_id') != null){ 
			$changebeneficiary->where('district_id', $district_id);
		}else{
            $changebeneficiary->whereIn('district_id', json_decode(Auth::user()->district_id));
        }
        
        if($request->has('tehsil_id') && $request->get('tehsil_id') != null){
			$changebeneficiary->where('tehsil_id', $tehsil_id);
        }else{
            $changebeneficiary->whereIn('tehsil_id', json_decode(Auth::user()->tehsil_id));
        }
        
        if($request->has('uc_id') && $request->get('uc_id') != null){
			$changebeneficiary->where('uc_id', $uc_id);
        }else{
            $changebeneficiary->whereIn('uc_id', json_decode(Auth::user()->uc_id));
        }
        
		if($request->has('b_reference_number') && $request->get('b_reference_number') != null){
			$changebeneficiary->where('ref_no', $b_reference_number);
        }
        
        if($request->has('beneficiary_name') && $request->get('beneficiary_name') != null){
			$changebeneficiary->where('beneficiary_details->beneficiary_name','like','%'.$beneficiary_name.'%');
        }
		
		if($request->has('cnic') && $request->get('cnic') != null){
			$changebeneficiary->where('cnic','like','%'.$cnic.'%');
        }
		
		

        $changebeneficiary->orderBy($sorting, $order);
        $data = $changebeneficiary->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);

        return view('dashboard.changebeneficiary.pagination_datalist', compact('data'))->render();
   
	}
	
	public function changebeneficiary_status_trail_history(Request $request)
    {
		if($request->ajax()){
		    $cb_id = $request->cb_id;
		    $changebeneficiary = ChangeBeneficiary::where('id', $cb_id)->first();
            //dump($changebeneficiary->getstatustrail->toArray());
			return view('dashboard.changebeneficiary.render_report_trail_history', compact('changebeneficiary'))->render(); 
		}
	}
	
	
	public function show($id){
	    
	   try {
		$decrypted = Crypt::decryptString($id);
		$row_id = decrypt($id);
		
		$changebeneficiary = ChangeBeneficiary::findOrFail($row_id);
        return view('dashboard.changebeneficiary.show', compact('changebeneficiary'));

		
		} catch (DecryptException $e) {
		      abort(404);
		} 
	    
	    
       
    }
    
    
	public function changebeneficiary_action_form(Request $request)
    {
		if($request->ajax()){
		    $cb_id = $request->cb_id;
			$decision = $request->decision;
			return view('dashboard.changebeneficiary.changebeneficiary_action_form', compact('cb_id','decision'))->render();  
		}
	}
	
	public function changebeneficiary_action_form_submit(Request $request){

	if($request->ajax()){

      $role = Role::findORFail(Auth::user()->role);
      $cb_id = $request->cb_id;
	  $decision = $request->decision;
	  $comment = $request->comment;
	  $status = $decision === 'approve' ? 'A' : ($decision === 'reject' ? 'R' : ($decision === 'hold' ? 'H' : ''));
	  //$status = $decision === 'approve' ? 'A' : ($decision === 'reject' ? 'R' : ($decision === 'hold' ? 'H' : ($decision === 'pending' ? 'P' : '')));


		    $changebeneficiary = ChangeBeneficiary::findORFail($cb_id);
		    //30, FS
		    //34, IP
		    //36, HRU
		    //37, PSIA
		    //48  HRU Finance = R HRU = P
		    
		    if($changebeneficiary->role_id == 30 && $status == 'A'){
		     update_changebeneficiary_status($cb_id,34,'P',$status);
		    }elseif($changebeneficiary->role_id == 34 && $status == 'A'){
		     update_changebeneficiary_status($cb_id,38,'P',$status);   
		    }elseif($changebeneficiary->role_id == 38 && $status == 'A'){
		     update_changebeneficiary_status($cb_id,40,'P',$status);  
		    }elseif($changebeneficiary->role_id == 40 && $status == 'A'){
		     update_changebeneficiary_status($cb_id,48,'P',$status);
		    
		        
		    }elseif($changebeneficiary->role_id == 48 && $status == 'A'){
		     update_changebeneficiary_status($cb_id,48,'C',$status);
		     
		     
		    }elseif($changebeneficiary->role_id == 48 && $status == 'R'){
		     update_changebeneficiary_status($cb_id,40,'P',$status);
		    }elseif($changebeneficiary->role_id == 40 && $status == 'R'){
		     update_changebeneficiary_status($cb_id,38,'P',$status);  
		    }elseif($changebeneficiary->role_id == 38 && $status == 'R'){
		     update_changebeneficiary_status($cb_id,34,'P',$status);  
		    }elseif($changebeneficiary->role_id == 34 && $status == 'R'){
		     update_changebeneficiary_status($cb_id,30,'P',$status);   
		    //}elseif($changebeneficiary->role_id == 30 && $status == 'R'){
		     //update_changebeneficiary_status($cb_id,27,'P',$status);   
		    }
		    

			$data = $request->all();
			$data['cb_id'] = $cb_id;
			$data['ref_no'] = $changebeneficiary->ref_no;
			$data['survey_id'] = $changebeneficiary->survey_id;
			$data['action_by'] = Auth::user()->id;
			
			$data['role_id'] = $role->id;
			$data['role_name'] = $role->name;
			$data['status'] = $status;
			
			//$data['lot_id'] = $changebeneficiary->lot_id;
			$data['action'] = $decision;
			$data['comment'] = $comment;

			//dump($data);
            $result = ChangeBeneficiaryStatusHistory::create($data);
            echo '<div class="col-md-12"><div class="alert alert-success"><strong>Success!</strong>  ChangeBeneficiary action submit is successfully</div></div>';
			
 
	  
	}
	}
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function create() //: View
    {
        if(Auth::user()->role == 30){
		$districts = District::query()->pluck('name','id')->all();
		
		
		$surveydata = SurveyData::query();
		$surveydata->whereIn('lot_id', json_decode(Auth::user()->lot_id));
		$surveydata->whereIn('district_id', json_decode(Auth::user()->district_id));
		$surveydata->whereIn('tehsil_id', json_decode(Auth::user()->tehsil_id));
		$surveydata->whereIn('uc_id', json_decode(Auth::user()->uc_id));
		$references = $surveydata->pluck('ref_no', 'ref_no');
		
		//$references = SurveyData::query()->distinct()->pluck('ref_no', 'ref_no')->filter(function ($value) { return $value !== null; });
		//dd($references);
		
		return view('dashboard.changebeneficiary.create', compact('districts','references')); 
        }else{
         return redirect()->route('changebeneficiary.index')->with([ 'error' => 'You are not authorized user!']);
        }
    }
    
    public function store(Request $request) : RedirectResponse
    {
		
		request()->validate([
		  "name_beneficiary" => 'required',
		  "name_father_husband" => 'required',
		  "cnic" => 'required',
          "ref_no" => 'required',
          "next_kin_name" => 'required',
          "cnic_of_kin" => 'required',
          
          "cnic_issue_date" => 'required',
          //"cnic_expiry_date" => 'required',
          
          'cnic_expiry_date' => ['nullable', 'date', 
                                    function ($attribute, $value, $fail) {
                                        if (request('lifetime_cnic') !== 'on') {
                                            $expiryDate = Carbon::parse($value);
                                            $threeMonthsFromNow = now()->addMonths(3);
                                            
                                            if ($expiryDate->isPast()) {
                                                $fail('CNIC is expired and cannot be accepted.');
                                            } elseif ($expiryDate->lessThan($threeMonthsFromNow)) {
                                                $fail('CNIC expiry date must be at least 3 months from today.');
                                            }
                                        }
                                    }
                                ],
          
          "mother_maiden_name" => 'required',
          "date_of_birth" => 'required',
          "city_of_birth" => 'required',
          
          "reason_change_beneficiary" => 'required',
          "change_beneficiary_image" => 'required|image|mimes:jpeg,png,jpg,gif|max:1024',
          "cnic_front" => 'required|image|mimes:jpeg,png,jpg,gif|max:1024',
          "cnic_back" => 'required|image|mimes:jpeg,png,jpg,gif|max:1024',
          "evidence_uploads" => 'required|file|max:5120',  // Each file is limited to 5MB
        ]);

//dd($request->all());


		if($request->ref_no){
		$changebeneficiary_exist = ChangeBeneficiary::query()->where('ref_no', $request->ref_no)->get(); 
		if($changebeneficiary_exist->count() == 0){
		$surveydata = SurveyData::query()->where('ref_no', $request->ref_no)->first();
		$role = Role::findORFail(Auth::user()->role);
		//dump($surveydata);
		//dd(Auth::user());
		

		$old_changebeneficiary = new ChangeBeneficiary;
		$old_changebeneficiary->type = 'Old';
		$old_changebeneficiary->survey_id = $surveydata->id;
		$old_changebeneficiary->ref_no = $surveydata->ref_no;
		$old_changebeneficiary->lot_id = $surveydata->lot_id;
		$old_changebeneficiary->district_id = $surveydata->district_id;
		$old_changebeneficiary->tehsil_id = $surveydata->tehsil_id;
		$old_changebeneficiary->uc_id = $surveydata->uc_id;
		
		//$old_changebeneficiary->cnic_issue_date = '';
		//$old_changebeneficiary->cnic_expiry_date = '';
		//$old_changebeneficiary->mother_maiden_name = '';
		//$old_changebeneficiary->date_of_birth = '';
		//$old_changebeneficiary->city_of_birth = '';
		
		$old_changebeneficiary->name_beneficiary = get_answer(645, $surveydata->id)->answer ?? '';
        $old_changebeneficiary->name_father_husband = get_answer(654, $surveydata->id)->answer ?? '';
        $old_changebeneficiary->cnic = get_answer(650, $surveydata->id)->answer ?? '';
        $old_changebeneficiary->next_kin_name = get_answer(657, $surveydata->id)->answer ?? '';
        $old_changebeneficiary->cnic_of_kin = get_answer(658, $surveydata->id)->answer ?? '';
        $old_changebeneficiary->change_beneficiary_image = get_answer(285, $surveydata->id)->answer ?? '';
        $old_changebeneficiary->cnic_front = get_answer(286, $surveydata->id)->answer ?? '';
        $old_changebeneficiary->cnic_back = get_answer(287, $surveydata->id)->answer ?? '';
        $old_changebeneficiary->reason_change_beneficiary = $request->input('reason_change_beneficiary');
        
        
        
        
        if($request->otherspecify){
         $old_changebeneficiary->otherspecify = $request->input('otherspecify');   
        }
        
        $old_changebeneficiary->user_id = Auth::user()->id;
		$old_changebeneficiary->save();
		
		
		
		
		
		
		
		
		$changebeneficiary = new ChangeBeneficiary;
		$changebeneficiary->type = 'New';
		$changebeneficiary->backup_id = $old_changebeneficiary->id;
		$changebeneficiary->survey_id = $surveydata->id;
		$changebeneficiary->ref_no = $surveydata->ref_no;
		$changebeneficiary->lot_id = $surveydata->lot_id;
		$changebeneficiary->district_id = $surveydata->district_id;
		$changebeneficiary->tehsil_id = $surveydata->tehsil_id;
		$changebeneficiary->uc_id = $surveydata->uc_id;
		
		$changebeneficiary->cnic_issue_date = $request->input('cnic_issue_date');

		if($request->lifetime_cnic){
         $changebeneficiary->lifetime_cnic = $request->has('lifetime_cnic') ? 1 : 0; 
         $changebeneficiary->cnic_expiry_date = NULL; 
		}else{
		 $changebeneficiary->lifetime_cnic = NULL; 
		}
		
		$changebeneficiary->cnic_expiry_date = $request->input('cnic_expiry_date');
		$changebeneficiary->mother_maiden_name = $request->input('mother_maiden_name');
		$changebeneficiary->date_of_birth = $request->input('date_of_birth');
		$changebeneficiary->city_of_birth = $request->input('city_of_birth');
		
		$changebeneficiary->name_beneficiary = $request->input('name_beneficiary');
        $changebeneficiary->name_father_husband = $request->input('name_father_husband');
        $changebeneficiary->cnic = $request->input('cnic');
        $changebeneficiary->next_kin_name = $request->input('next_kin_name');
        $changebeneficiary->cnic_of_kin = $request->input('cnic_of_kin');
        $changebeneficiary->reason_change_beneficiary = $request->input('reason_change_beneficiary');
        
        if($request->otherspecify){
         $changebeneficiary->otherspecify = $request->input('otherspecify');   
        }
        
        //$changebeneficiary->change_beneficiary_image = $request->input('change_beneficiary_image');
        //$changebeneficiary->cnic_front = $request->input('cnic_front');
        //$changebeneficiary->cnic_back = $request->input('cnic_back');
        //$changebeneficiary->evidence_uploads = $request->input('evidence_uploads');
        
        $changebeneficiary->user_id = Auth::user()->id;
        
    	//$changebeneficiary->role_id = 30;
    	//$changebeneficiary->role_name = 'Field SuperVisor';
    	//$changebeneficiary->status	= 'P';
    	//$changebeneficiary->action_date	= Carbon::now()->toDateTimeString();
    	//$changebeneficiary->action_by = Auth::user()->id;
    	
		$changebeneficiary->save();
		

		if($request->hasFile('change_beneficiary_image')){ 
		        $question_285 = $request->change_beneficiary_image;
                $data = uploadfilechangebeneficiary('285', $question_285, $surveydata->id.'_47', 'surveyform_files', $changebeneficiary->id); 
            }
        
		if($request->hasFile('cnic_front')){ 
		        $question_286 = $request->cnic_front;
                $data = uploadfilechangebeneficiary('286', $question_286, $surveydata->id.'_47', 'surveyform_files', $changebeneficiary->id); 
                //dump($data->getData());

                /*
                $answer=DB::table('answers')->where('section_id', 47)->where('question_id',286)->where('survey_form_id',$survey_id)->select('id')->first();
                if($answer){
                //dump($answer);
                $update_answer = DB::table('answers')->where('section_id', 47)->where('question_id', 286)->where('survey_form_id', $survey_id)->update(['answer' => $jsondata]);
                }
                */
            }
            
            if($request->hasFile('cnic_back')){ 
		        $question_287 = $request->cnic_back;
                $data = uploadfilechangebeneficiary('287', $question_287, $surveydata->id.'_47', 'surveyform_files', $changebeneficiary->id); 
            }
            
		if($request->hasFile('evidence_uploads')){
		    $evidence_uploads = $request->evidence_uploads;
		    $data = uploadfilesglobally($changebeneficiary->id, $evidence_uploads, 'evidence_upload', 'surveyform_files');
		    
		    DB::table('change_beneficiary_files')->insert([
                    'survey_id' => $changebeneficiary->survey_id,
                    'ref_no' => $changebeneficiary->ref_no,
                    'cb_id' => $changebeneficiary->id,
                    'evidence_type' => 'evidence_upload',
                    'question_id' => '',
                    'filename' => $data->getData()->files->file_name,
                    'originalname' => $data->getData()->files->image_name,
                    'extension' => $data->getData()->files->extension,
                    'size' => getfilesize($data->getData()->files->bytes),
                    'mime' => $data->getData()->files->mime_type,
                    'width' => $data->getData()->files->width,
                    'height' => $data->getData()->files->height,
                    'created_by' => auth()->user()->id
                ]);
		}
		
		
		        
		 //addLogs('added a new change beneficiary added ref_no#  "'. $changebeneficiary->ref_no.'"', Auth::user()->id);
         return redirect()->route('changebeneficiary.index')->with([ 'success' => 'Ref No '.$changebeneficiary->ref_no.' Change Beneficiary has been added successfully!']);		
		}else{
		    return redirect()->back()->with([ 'error' => 'Reference number is already exist in changebeneficiary']);
		} 
		    
		}else{
		 return redirect()->back()->with([ 'error' => 'Reference number is incorrect']);   
		 }
		    
		}
		
		
		
		
		public function edit($id){

        if(Auth::user()->role == 30){
            try {
    		$decrypted = Crypt::decryptString($id);
    		$row_id = decrypt($id);
    		$changebeneficiary = ChangeBeneficiary::findOrFail($row_id);

    		return view('dashboard.changebeneficiary.edit', compact('changebeneficiary'));
    		} catch (DecryptException $e) {
    		      abort(404);
    		}
        }else{
         return redirect()->route('changebeneficiary.index')->with([ 'error' => 'You are not authorized user!']);
        }

    }
    
    
    public function update(Request $request, $id) : RedirectResponse
    {
         
         request()->validate([
          "name_beneficiary" => 'required',
		  "name_father_husband" => 'required',
		  "cnic" => 'required',
          //"ref_no" => 'required',
          "next_kin_name" => 'required',
          "cnic_of_kin" => 'required',
          
          "cnic_issue_date" => 'required',
          //"cnic_expiry_date" => 'required',
          
          'cnic_expiry_date' => ['nullable', 'date', 
                                    function ($attribute, $value, $fail) {
                                        if (request('lifetime_cnic') !== 'on') {
                                            $expiryDate = Carbon::parse($value);
                                            $threeMonthsFromNow = now()->addMonths(3);
                                            
                                            if ($expiryDate->isPast()) {
                                                $fail('CNIC is expired and cannot be accepted.');
                                            } elseif ($expiryDate->lessThan($threeMonthsFromNow)) {
                                                $fail('CNIC expiry date must be at least 3 months from today.');
                                            }
                                        }
                                    }
                                ],
          "mother_maiden_name" => 'required',
          "date_of_birth" => 'required',
          "city_of_birth" => 'required',

          "reason_change_beneficiary" => 'required',
          "change_beneficiary_image" => 'image|mimes:jpeg,png,jpg,gif|max:1024',
          "cnic_front" => 'image|mimes:jpeg,png,jpg,gif|max:1024',  // Each file is limited to 5MB
          "cnic_back" => 'image|mimes:jpeg,png,jpg,gif|max:1024',
          "evidence_uploads" => 'file|max:5120',
        ]);
    

        
        $input = $request->all();
		$input['update_by'] = Auth::user()->id;
		
		if($request->reason_change_beneficiary == 'Other'){
		if($request->otherspecify){
         $input['otherspecify'] = $request->input('otherspecify'); 
		}
        }else{
           $input['otherspecify'] = NULL; 
        }
        
        
        if($request->lifetime_cnic){
         $input['lifetime_cnic'] = $request->has('lifetime_cnic') ? 1 : 0;
         $input['cnic_expiry_date'] = NULL; 
		}else{
		 $input['lifetime_cnic'] = NULL; 
		}
		
		//dd($input);
		$changebeneficiary = ChangeBeneficiary::findOrFail($id);
	    //$changebeneficiary->update($input);
	    $changebeneficiary->fill($input)->save();
	    
	    
	    if($request->hasFile('change_beneficiary_image')){
		        $question_285 = $request->change_beneficiary_image;
                $data = uploadfilechangebeneficiary('285', $question_285, $changebeneficiary->survey_id.'_47', 'surveyform_files', $changebeneficiary->id); 
        }
        
		if($request->hasFile('cnic_front')){ 
		        $question_286 = $request->cnic_front;
                $data = uploadfilechangebeneficiary('286', $question_286, $changebeneficiary->survey_id.'_47', 'surveyform_files', $changebeneficiary->id); 
        }
            
        if($request->hasFile('cnic_back')){ 
		        $question_287 = $request->cnic_back;
                $data = uploadfilechangebeneficiary('287', $question_287, $changebeneficiary->survey_id.'_47', 'surveyform_files', $changebeneficiary->id); 
        }
            
		if($request->hasFile('evidence_uploads')){
		    $evidence_uploads = $request->evidence_uploads;
		    $data = uploadfilesglobally($changebeneficiary->id, $evidence_uploads, 'evidence_upload', 'surveyform_files');
		    
		    DB::table('change_beneficiary_files')->insert([
                    'survey_id' => $changebeneficiary->survey_id,
                    'ref_no' => $changebeneficiary->ref_no,
                    'cb_id' => $changebeneficiary->id,
                    'evidence_type' => 'evidence_upload',
                    'question_id' => '',
                    'filename' => $data->getData()->files->file_name,
                    'originalname' => $data->getData()->files->image_name,
                    'extension' => $data->getData()->files->extension,
                    'size' => getfilesize($data->getData()->files->bytes),
                    'mime' => $data->getData()->files->mime_type,
                    'width' => $data->getData()->files->width,
                    'height' => $data->getData()->files->height,
                    'created_by' => auth()->user()->id
                ]);
		}
		

        return redirect()->route('changebeneficiary.index')->with('success','Change Beneficiary updated successfully');
        
    }
		
		
		
		
		
		
}