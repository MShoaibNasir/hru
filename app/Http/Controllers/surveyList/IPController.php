<?php

namespace App\Http\Controllers\surveyList;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\SurveyData;
use App\Models\Lot;
use App\Models\District;
use App\Models\Tehsil;
use App\Models\UC;
use App\Models\FormStatus;
use App\Models\QuestionTitle;
use App\Models\QuestionsAcceptReject;
use App\Models\CommentMissingDocument;
use App\Models\Answer;
use App\Models\NdmaVerification;
use App\Models\Option;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Validator;
use DB;
use Auth;
use Illuminate\Database\Eloquent\Builder;

class IPController extends Controller

{
    // for new damage assessment
    public function commonQueryForSurveyList($condition)
    {
        $query = SurveyData::join('users','survey_form.user_id','=','users.id')
                ->join('form_status','survey_form.id','form_status.form_id');
        $query = $this->addCommonJoins($query);
        $authenticate_user_uc=json_decode(Auth::user()->uc_id);
        $survey_data=$query->where('form_status.form_status','A')
                ->where('form_status.update_by',$condition)
                 ->whereIn('survey_form.uc_id', $authenticate_user_uc)
                ->select('users.name as user_name','form_status.is_m_and_e','survey_form.generated_id','form_status.m_and_e_comment',
                'uc.name as uc_name','lots.name as lot_name','tehsil.name as tehsil_name','districts.name as district_name',
                'form_status.id as form_status_id','form.name as form_name','survey_form.id as survey_form_id','survey_form.uc_id','form_status.team_member_status','form_status.certification as certification','form_status.user_status','form_status.id as form_status_id','survey_form.priority as priority','survey_form.beneficiary_details','survey_form.created_at as submission_date')
                ->orderBy('survey_form.priority','Desc')
                ->get();
            
              
        return $survey_data;
    }
    
     public function addCommonJoins(Builder $query): Builder
    {
        return $query->leftJoin('form', 'survey_form.form_id', '=', 'form.id')
                     ->leftJoin('lots', 'survey_form.lot_id', '=', 'lots.id')
                     ->leftJoin('tehsil', 'survey_form.tehsil_id', '=', 'tehsil.id')
                     ->leftJoin('districts', 'survey_form.district_id', '=', 'districts.id')
                     ->leftJoin('uc', 'survey_form.uc_id', '=', 'uc.id');
    }
    
    
    public function IPList(){
        $survey_data=$this->commonQueryForSurveyList('field supervisor');
        if(Auth::user()->role!=1){
        $final_data=[];
        foreach($survey_data as $item){
            $form_status=DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','IP')->first();
            if(($item->user_status=='30'  || $item->user_status=='51' || $item->team_member_status=='field_supervisor') && ($form_status==null || $form_status->form_status=='P')){
                $final_data[]=$item;
            }
        }
         if(count($final_data) > 0){
             $survey_data = $final_data;
        }
        else{
            $survey_data=[];
        }
        }
        return view('dashboard.NewDamageAssessment.ip',['survey_data'=>$survey_data]);
    }
    
    public function HRUList(){
        $survey_data=$this->commonQueryForSurveyList('IP');
   
    
        if(Auth::user()->role!=1){
        $final_data=[];
        foreach($survey_data as $item){
            $form_status=DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','HRU')->first();
            if(($item->user_status=='34' || $item->user_status=='51' || $item->team_member_status=='IP' || $item->user_status=='51') && ($form_status==null || $form_status->form_status=='P')){
                $final_data[]=$item;
            }
            
        }
        
         if(count($final_data) > 0){
             $survey_data = $final_data;
        }
        else{
            $survey_data=[];
        }
        }
        return view('dashboard.NewDamageAssessment.hru',['survey_data'=>$survey_data]);
    }
    
    public function FieldSuperVisorList(){
        $authenticate_user_uc=json_decode(Auth::user()->uc_id);
        $query = SurveyData::join('users', 'survey_form.user_id', '=', 'users.id')
         ->whereIn('survey_form.uc_id', $authenticate_user_uc);
        $query = $this->addCommonJoins($query);
            
        $survey_data = $query->select(
        'users.name as user_name',
        'survey_form.generated_id',
        'uc.name as uc_name',
        'lots.name as lot_name',
        'tehsil.name as tehsil_name',
        'districts.name as district_name',
        'form.name as form_name',
        'survey_form.id as survey_form_id',
        'survey_form.uc_id',
        'survey_form.priority as priority',
        'survey_form.beneficiary_details',
        'survey_form.created_at as submission_date'
        )
        ->orderBy('survey_form.priority', 'Desc')
        ->get();
        
        
       if(Auth::user()->role!=1){
        $final_data=[];
        foreach($survey_data as $item){
           $form_status=DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','field supervisor')->first();
            if(($form_status==null || $form_status->form_status=='P')){
                $final_data[]=$item;
            }
        }
         if(count($final_data) > 0){
             $survey_data = $final_data;
        }
        else{
            $survey_data=[];
        }
        }    
        
        
        
        
        return view('dashboard.NewDamageAssessment.fieldSuperVisor',['survey_data'=>$survey_data]);
    }
    
    
    public function dammagePendingList(){
        $query = SurveyData::join('users', 'survey_form.user_id', '=', 'users.id');
        $query = $this->addCommonJoins($query);
            
        $survey_data = $query->select(
        'users.name as user_name',
        'survey_form.generated_id',
        'uc.name as uc_name',
        'lots.name as lot_name',
        'tehsil.name as tehsil_name',
        'districts.name as district_name',
        'form.name as form_name',
        'survey_form.id as survey_form_id',
        'survey_form.uc_id',
        'survey_form.priority as priority',
        'survey_form.beneficiary_details'
        ,'survey_form.created_at as submission_date'
        )
        ->orderBy('survey_form.priority', 'Desc')
        ->paginate(10);
        return view('dashboard.NewDamageAssessment.admin',['survey_data'=>$survey_data]);
    }
    
    
    
     public function dammagePendingList2()
    {
		$districts = District::pluck('name','id')->all();
		return view('dashboard.NewDamageAssessment.damageAssessment', compact('districts'));
    }
    

	
	public function filter_new_damage_assessment(Request $request, NdmaVerification $pdmadata)
{
    // Initialize query with SurveyData model and join users table
    $query = SurveyData::join('users', 'survey_form.user_id', '=', 'users.id');
    $query = $this->addCommonJoins($query); // Assuming this adds necessary joins
    
    // Assign the request parameters to variables
    $district = $request->get('district');
    $tehsil = $request->get('tehsil_id');
    $uc = $request->get('uc_id');
    $b_reference_number = $request->get('b_reference_number');
    $beneficiary_name = $request->get('beneficiary_name');
    $cnic = $request->get('cnic');
    $sorting = $request->get('sorting');
    $order = $request->get('direction');
    $page = $request->get('ayis_page');
    $qty = $request->get('qty');
    $custom_pagination_path = '';

    // Add conditions based on request parameters
    if ($district && $district != null) {
        $query->where('survey_form.district_id', $district);
    }
    
    if ($tehsil && $tehsil != null) {
        $query->where('survey_form.tehsil_id', $tehsil);
    }

    if ($uc && $uc != null) {
        $query->where('survey_form.uc_id', $uc);
    }

    if ($b_reference_number && $b_reference_number != null) {
        $query->where('survey_form.ref_no', $b_reference_number);
    }

    if ($beneficiary_name && $beneficiary_name != null) {
        $query->where('survey_form.beneficiary_name', 'like', '%' . $beneficiary_name . '%');
    }

    // Optionally add CNIC condition
    if ($cnic && $cnic != null) {
        $query->where('survey_form.cnic', $cnic );
    }

    // Select the required columns from the query
    $pdmadata = $query->select(
        'users.name as user_name',
        'survey_form.generated_id',
        'uc.name as uc_name',
        'lots.name as lot_name',
        'tehsil.name as tehsil_name',
        'districts.name as district_name',
        'form.name as form_name',
        'survey_form.id as survey_form_id',
        'survey_form.uc_id',
        'survey_form.priority as priority',
        'survey_form.beneficiary_details',
        'survey_form.coordinates',
        'survey_form.created_at as submission_date',
          'survey_form.beneficiary_name',
               'survey_form.ref_no',
               'survey_form.cnic',
               'survey_form.father_name'
    );

    // Add sorting if provided
    // if ($sorting && $order) {
    //     $pdmadata->orderBy($sorting, $order);
    // }

    // Pagination logic
    $data = $pdmadata->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);

    // Modify data array to add district, tehsil, uc names
    $data_array = $data->toArray()['data'];
   
    // foreach ($data as $key => $dat) {
    //     $data_array[$key]['district'] = $dat->getdistrict->name;
    //     $data_array[$key]['tehsil'] = $dat->gettehsil->name;
    //     $data_array[$key]['uc'] = $dat->getuc->name;
    // }

    // Encode data to JSON and dump for debugging
    $jsondata = json_encode($data_array);
   

    // Return the view with the paginated data

    return view('dashboard.NewDamageAssessment.damageAssessmentData', compact('data', 'jsondata'))->render();
}

    
    
    
    
    public function certifyList(){
                 $certification_query = SurveyData::join('users', 'survey_form.user_id', '=', 'users.id');
                 $query = $this->addCommonJoins($certification_query);
                 $certification=$query->join('form_status', 'survey_form.id', '=', 'form_status.form_id')
                ->where('form_status.form_status', 'A')
                ->where('form_status.update_by', 'HRU')
                ->where('form_status.certification', '1')
                ->select('users.name as user_name','survey_form.generated_id','form.name as form_name', 'survey_form.id as survey_form_id', 
                'survey_form.uc_id', 'form_status.team_member_status', 'form_status.user_status', 
                'form_status.certification as certification', 'form_status.id as form_status_id', 
                'survey_form.priority as priority', 'survey_form.beneficiary_details','survey_form.created_at as submission_date','uc.name as uc_name',
                'tehsil.name as tehsil_name','districts.name as district_name','lots.name as lot_name')->paginate(10); 
               
            return view('dashboard.survey.certify_list',['certification'=>$certification]);
        
        
    }
    


        
    
    
    public function ceo_pending_list() {
    $query = SurveyData::join('users', 'survey_form.user_id', '=', 'users.id')
                ->join('form_status', 'survey_form.id', '=', 'form_status.form_id');
    
    $query = $this->addCommonJoins($query);
    
    $survey_data = $query->where(function($query) {
        $query->where('form_status.form_status', 'A')
              ->where('form_status.update_by', 'COO');
             
    })
    ->select(
        'users.name as user_name',
        'form_status.is_m_and_e',
        'survey_form.generated_id',
        'form_status.m_and_e_comment',
        'uc.name as uc_name',
        'lots.name as lot_name',
        'tehsil.name as tehsil_name',
        'districts.name as district_name',
        'form_status.id as form_status_id',
        'form.name as form_name',
        'survey_form.id as survey_form_id',
        'survey_form.uc_id',
        'survey_form.ref_no as ref_no',
        'form_status.team_member_status',
        'form_status.certification as certification',
        'form_status.user_status',
        'survey_form.priority as priority',
        'survey_form.beneficiary_details',
        'survey_form.created_at as submission_date'
    )
    ->distinct('survey_form.id')  // Ensure unique survey_form.id results
    ->orderBy('survey_form.priority', 'Desc')
    ->get();
    $coo_pending_list=$this->coo_pending_list();
    // dd($coo_pending_list);
    $final_data=[];
    $ids=[];
    foreach($coo_pending_list as $item){
       $ids[]=$item->ref_no;
    } 
    foreach($survey_data as $data){
        if(!in_array($data->ref_no,$ids)){
            $final_data[]=$data;
        }
    }
//   dd($final_data);
    $second_final_data=[];
    foreach ($coo_pending_list as $data){
        $second_final_data[]=$data;
    }
    // dd($second_final_data);
  
    $combinedArray = array_merge( $second_final_data,$final_data );             
    // Return the view with survey data
    return view('dashboard.NewDamageAssessment.pending_ceo', ['survey_data' => $combinedArray]);
}
    public function ceo_pending_list_two(Request $requets ,$routeCondition=null) {
    $query = SurveyData::join('users', 'survey_form.user_id', '=', 'users.id')
                ->join('form_status', 'survey_form.id', '=', 'form_status.form_id');
    
    $query = $this->addCommonJoins($query);
    $survey_data = $query->where(function($query) {
        
        $query->where('form_status.form_status', 'A')
              ->where('form_status.update_by', 'COO');
          
    })
    ->select(
        'users.name as user_name',
        'form_status.is_m_and_e',
        'survey_form.generated_id',
        'form_status.m_and_e_comment',
        'uc.name as uc_name',
        'lots.name as lot_name',
        'tehsil.name as tehsil_name',
        'districts.name as district_name',
        'form_status.id as form_status_id',
        'form.name as form_name',
        'survey_form.id as survey_form_id',
        'survey_form.uc_id',
        'survey_form.ref_no as ref_no',
        'form_status.team_member_status',
        'form_status.certification as certification',
        'form_status.user_status',
        'survey_form.priority as priority',
        'survey_form.beneficiary_details',
        'survey_form.created_at as submission_date'
    )
    ->distinct('survey_form.id')  
    ->orderBy('survey_form.priority', 'Desc')
    ->get();
    $coo_pending_list=$this->coo_pending_list();

    $final_data=[];
    $ids=[];
    foreach($coo_pending_list as $item){
       $ids[]=$item->ref_no;
    } 
    foreach($survey_data as $data){
        if(!in_array($data->ref_no,$ids)){
            $final_data[]=$data;
        }
    }

    $second_final_data=[];
    foreach ($coo_pending_list as $data){
        $second_final_data[]=$data;
    }
    $combinedArray = array_merge( $second_final_data,$final_data );
    if(isset($routeCondition)){

    return view('dashboard.survey.pending.m_and_e.ceo', ['survey_data' => $combinedArray]);
    }else{
    return view('dashboard.NewDamageAssessment.pending_ceo_two', ['survey_data' => $combinedArray]);
        
    }

}
    public function coo_pending_list() {
    // Start by constructing the base query with necessary joins
    $query = SurveyData::join('users', 'survey_form.user_id', '=', 'users.id')
                ->join('form_status', 'survey_form.id', '=', 'form_status.form_id');
    
    // Add any common joins from a method like addCommonJoins (if needed)
    $query = $this->addCommonJoins($query);
    
    // Apply the necessary filters
    $survey_data = $query->where(function($query) {
        // Group the where conditions related to 'form_status'
        $query->where('form_status.form_status', 'A')
              ->where('form_status.update_by', 'HRU_MAIN');
              // If you want to include 'HRU_MAIN', you can uncomment the next line
              // ->orWhere('form_status.update_by', 'HRU_MAIN');
    })
    ->select(
        'users.name as user_name',
        'form_status.is_m_and_e',
        'survey_form.generated_id',
        'form_status.m_and_e_comment',
        'uc.name as uc_name',
        'lots.name as lot_name',
        'tehsil.name as tehsil_name',
        'survey_form.ref_no as ref_no',
        'districts.name as district_name',
        'form_status.id as form_status_id',
        'form.name as form_name',
        'survey_form.id as survey_form_id',
        'survey_form.uc_id',
        'form_status.team_member_status',
        'form_status.certification as certification',
        'form_status.user_status',
        'survey_form.priority as priority',
        'survey_form.beneficiary_details',
        'survey_form.created_at as submission_date'
    )
    ->distinct('survey_form.id')  // Ensure unique survey_form.id results
    ->orderBy('survey_form.priority', 'Desc')
    // ->take(4)
    ->get();
    return $survey_data;
    // Return the view with survey data
    // return view('dashboard.NewDamageAssessment.pending_ceo', ['survey_data' => $survey_data]);
}


    
    















//Ayaz missing_document_list
public function missing_document_receive_list(){
   
    if(Auth::user()->role==1 || Auth::user()->role==38){
        $missing_documents = CommentMissingDocument::where('status','C')->latest()->paginate(10);
        return view('dashboard.NewDamageAssessment.missing_document', compact('missing_documents'));
    }else{
         return redirect()->route('admin.dashboard')->with([ 'error' => 'You are not authorized this page!']);
        }
    }


public function missing_document_list(Request $request){
    
    if(Auth::user()->role==1 || Auth::user()->role==30){
         $query = CommentMissingDocument::where('status','P');
         if($request->lot_id){
             $query->where('lot_id',$request->lot_id);
         }
        $missing_documents=$query->latest()->paginate(10);
        return view('dashboard.NewDamageAssessment.missing_document', compact('missing_documents'));
    }else{
         return redirect()->route('admin.dashboard')->with([ 'error' => 'You are not authorized this page!']);
        }
    }
    
    
    public function total_missing_document_datalist()
    {
		$lots = Lot::whereIn('id', json_decode(Auth::user()->lot_id))->pluck('name','id')->all();
        $gender = Option::where('question_id', 652)->pluck('name', 'name');
        $evidence_type = Option::where('question_id', 247)->pluck('name', 'name');
        
		return view('dashboard.NewDamageAssessment.total_missing_document_datalist', compact('lots', 'gender','evidence_type'));
    }
    
	public function total_missing_document_datalist_fetch_data(Request $request, SurveyData $surveydata)
	{
	    $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        
        $lot_id = $request->get('lot_id');
        $district_id = $request->get('district_id');
        $tehsil_id = $request->get('tehsil_id');
        $uc_id = $request->get('uc_id');

        $gender = $request->get('gender');
        $evidence_type = $request->get('evidence_type');
        $b_reference_number = $request->get('b_reference_number');

        $sorting = $request->get('sorting');
        $order = $request->get('direction');
        
		$surveydata = $surveydata->newQuery();
		
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
        
        
        
	    $surveydata->where('evidence_type', 'No Evidence Available');
        
        
        
		if($request->has('b_reference_number') && $request->get('b_reference_number') != null){
			$surveydata->where('ref_no', $b_reference_number);
        }

        
        $surveydata->orderBy($sorting, $order); 

        $data = $surveydata->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
        

        //$data_array = $data->toArray()['data'];
        //dump($data_array);


        return view('dashboard.NewDamageAssessment.pagination_total_missing_document_datalist', compact('data'))->render();
	}
    
    
    
    public function missing_document_data_set()
    {
		return view('dashboard.NewDamageAssessment.missing_document_data_set');
    }
    
	public function total_missing_datalist_fetch_data(Request $request, CommentMissingDocument $CommentMissingDocument)
	{
       
	    $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
		$CommentMissingDocument = $CommentMissingDocument->newQuery();
		$CommentMissingDocument = $CommentMissingDocument->where('status','P');

        if($request->has('lot_id') && $request->get('lot_id') != null){
            $lot = $request->get('lot_id');
			$CommentMissingDocument->where('lot_id', $lot);
        }
        else{
             $CommentMissingDocument->whereIn('lot_id', json_decode(Auth::user()->lot_id));

        }
        // dump(json_decode(Auth::user()->lot_id));
        
        $data = $CommentMissingDocument->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
        return view('dashboard.NewDamageAssessment.misisng_document_all_data', compact('data'))->render();
        
   
	}    
    
    
    
    


public function upload_missing_document_form(Request $request)
    {
		if($request->ajax()){

		    $comment_id = $request->comment_id;
		    $survey_id = $request->survey_id;
			$decision = $request->decision;
			$evidence_type = Option::where('question_id', 247)->whereNotIn('id',[388])->pluck('name', 'name');

			return view('dashboard.survey.formView.upload_missing_document_form_popup', compact('comment_id', 'survey_id','decision','evidence_type'))->render();  
		}
	}
	
public function upload_missing_document_form_submit(Request $request){

	if($request->ajax()){
	    
	    $request->validate([
	        'evidence_type' => 'required',
            'question_290' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'question_291' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

      $comment_id = $request->comment_id;
      $survey_id = $request->survey_id;
	  $decision = $request->decision;
	  $evidence_type = $request->evidence_type;
      $question_290 = $request->question_290;
      $question_291 = $request->question_291;
      
      $check_evidence_type = DB::table('answers')->where('section_id', 41)->where('question_id',247)->where('survey_form_id',$survey_id)->select('id','answer')->first();
      if($check_evidence_type){
       //dump($check_evidence_type);
       $update_evidence_type_answer = DB::table('answers')->where('section_id', 41)->where('question_id', 247)->where('survey_form_id', $survey_id)->update(['answer' => $evidence_type]);
      }
      

            if($request->hasFile('question_290')){ 
                $data = uploadfilesglobally('290', $question_290, $survey_id.'_47', 'surveyform_files'); 
                //dump($data->getData());

                $arraydata = [
                "question_id" => 290,
                "image" => [
                "cropRect" => [
                "width" =>$data->getData()->files->width,
                "y" =>0,
                "height" =>$data->getData()->files->height,
                "x" =>0,
                ],
                "modificationDate" => date('YmdHis'),
                "size" => $data->getData()->files->bytes,
                "mime" => $data->getData()->files->mime_type,
                "exif" => "",
                "height" => $data->getData()->files->height,
                "width" => $data->getData()->files->width,
                "path" => $data->getData()->files->file_name,
                "base64" => "",
                ],
                "fetchLocation" =>[]
                
                ];
                
                $jsondata = json_encode($arraydata);
                $answer=DB::table('answers')->where('section_id', 47)->where('question_id',290)->where('survey_form_id',$survey_id)->select('id')->first();
                if($answer){
                //dump($answer);
                $update_answer = DB::table('answers')->where('section_id', 47)->where('question_id', 290)->where('survey_form_id', $survey_id)->update(['answer' => $jsondata]);
                DB::table('missing_document_files')
                ->insert([
                    'survey_id' => $survey_id,
                    'evidence_type' => $evidence_type,
                    'question_id' => 290,
                    'answer_id' => $answer->id,
                    'filename' => $data->getData()->files->file_name,
                    'originalname' => $data->getData()->files->image_name,
                    'extension' => $data->getData()->files->extension,
                    'size' => $data->getData()->files->bytes,
                    'mime' => $data->getData()->files->mime_type,
                    'width' => $data->getData()->files->width,
                    'height' => $data->getData()->files->height,
                    'created_by' => auth()->user()->id
                ]);
                
                }
                
               
                

                
            }
            if($request->hasFile('question_291')){ 
                $data = uploadfilesglobally('291', $question_291, $survey_id.'_47', 'surveyform_files'); 
                //dump($data->getData());
                $arraydata = [
                "question_id" => 291,
                "image" => [
                "cropRect" => [
                "width" =>$data->getData()->files->width,
                "y" =>0,
                "height" =>$data->getData()->files->height,
                "x" =>0,
                ],
                "modificationDate" => date('YmdHis'),
                "size" => $data->getData()->files->bytes,
                "mime" => $data->getData()->files->mime_type,
                "exif" => "",
                "height" => $data->getData()->files->height,
                "width" => $data->getData()->files->width,
                "path" => $data->getData()->files->file_name,
                "base64" => "",
                ],
                "fetchLocation" =>[]
                
                ];
                $jsondata = json_encode($arraydata);
                $answer=DB::table('answers')->where('section_id', 47)->where('question_id',291)->where('survey_form_id',$survey_id)->select('id')->first();
                if($answer){
                //dump($answer);
                $update_answer = DB::table('answers')->where('section_id', 47)->where('question_id', 291)->where('survey_form_id', $survey_id)->update(['answer' => $jsondata]);
                DB::table('missing_document_files')
                ->insert([
                    'survey_id' => $survey_id,
                    'evidence_type' => $evidence_type,
                    'question_id' => 291,
                    'answer_id' => $answer->id,
                    'filename' => $data->getData()->files->file_name,
                    'originalname' => $data->getData()->files->image_name,
                    'extension' => $data->getData()->files->extension,
                    'size' => $data->getData()->files->bytes,
                    'mime' => $data->getData()->files->mime_type,
                    'width' => $data->getData()->files->width,
                    'height' => $data->getData()->files->height,
                    'created_by' => auth()->user()->id
                ]);
                }
                

            }
            if($comment_id > 0){
            $update_comment = CommentMissingDocument::where('id', $comment_id)->update(['status' => 'C']);
            }
            
            echo '<div class="col-md-12"><div class="alert alert-info"><strong>Success!</strong> Upload Missing Document submit is successfully</div></div>';
            

 
	  
	}
	}
	
	
public function update_review_survey(Request $request){
    $survey=DB::table('survey_form')->where('id',$request->survey_form_id)->select('review_by_mne')->first();
    if($survey->review_by_mne==0){
        $update_status=1;
        $result='checked';
    }else{
        $update_status=0;
        $result='un checked';
    }
    $survey=DB::table('survey_form')->where('id',$request->survey_form_id)->update(["review_by_mne"=>$update_status]);
    addLogs($result.' the  review status form the survey form  "'. $request->survey_form_id.'"', Auth::user()->id,'change review status','survey form management');
    return true;

}


    
}

 
    


   










