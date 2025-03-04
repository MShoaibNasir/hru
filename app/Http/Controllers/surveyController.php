<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\SurveyData;
use App\Models\FormStatus;
use Session;
use App\Models\QuestionTitle;
use App\Models\QuestionsAcceptReject;
use App\Models\CommentMissingDocument;
use App\Models\Answer;
use App\Models\Role;
use Cache;
use DB;
use Auth;
use Illuminate\Database\Eloquent\Builder;


class surveyController extends Controller
{
    public function create(Request $requets)
    {
        return view('dashboard.Area.Create');
    }
    
    

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'uc_id' => 'required'
            ]);
            $data = $request->all();
            $area = Area::create($data);
            
            addLogs('added a new settlement titled "'. $request->name.'"', Auth::user()->id,'create','settlement management');
            return redirect()->route('area.list')->with(['success' => 'You create settlement  successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
    public function delete(Request $request, $id)
    {
        $area = Area::find($id);
        addLogs('delete the settlement titled "'. $area->name.'"', Auth::user()->id,'delete','settlement management');
        $area->delete();
        return redirect()->back()->with('success', 'You Delete Settlement Successfully');
    }


  
    public function view(Request $request, $id)
    {

         $survey_data = SurveyData::join('users','survey_form.user_id','=','users.id')
        ->join('form','survey_form.form_id','form.id')
        ->select('users.name as user_name','form.name as form_name','survey_form.form_data as survey_form_data','survey_form.beneficiary_details')
        ->where('survey_form.id',$id)
        ->first();     
        return view('dashboard.survey.view', ['survey_data' => $survey_data]);
    }
    public function final_view(Request $request, $id)
    {

         $survey_data = SurveyData::join('users','survey_form.user_id','=','users.id')
        ->join('form','survey_form.form_id','form.id')
        ->select('users.name as user_name','form.name as form_name','survey_form.form_data as survey_form_data','survey_form.id as survey_form_id','survey_form.beneficiary_details')
        ->where('survey_form.id',$id)
        ->first();
        $hru_data=[];
        $survey_comparison_data=json_decode($survey_data->survey_form_data);
        $beneficiary_info=$survey_comparison_data->sections;
        foreach($beneficiary_info as $key=>$item){
            if($key=='Beneficiary Information'){
                foreach($item->questions as $data){
                    if($data->question->name=='If Yes CNIC #'){
                        $cnic=$data->question->answer; 
                        $hru_data['cnic']=$cnic;
                    }
                    if($data->question->name=='Beneficiary Name'){
                        $benefeciary_name=$data->question->answer; 
                        $hru_data['beneficairyName']=$benefeciary_name;
                    }
                    if($data->question->name=='Father Name'){
                        $father_name=$data->question->answer; 
                        $hru_data['fatherName']=$father_name;
                    }
                    if($data->question->name=='Gender'){
                        $gender=$data->question->answer; 
                        $hru_data['gender']=$gender;
                    }
                    if($data->question->name=='Mobile/Cell No'){
                        $contact=$data->question->answer; 
                        $hru_data['contact']=$contact;
                    }
                }
            }
            if($key=='Location Information'){
                foreach($item->questions as $data){
                    if($data->question->name=='District'){
                        $district=$data->question->answer; 
                        $hru_data['district']=$district;
                    }
                    if($data->question->name=='Tehsil'){
                        $tehsil=$data->question->answer; 
                        $hru_data['tehsil']=$tehsil;
                    }
                    if($data->question->name=='Village/Settlement Name'){
                        $address=$data->question->answer; 
                        $hru_data['address']=$address;
                    }
                }
            }
        }
        

        
$beneficiary_details = json_decode($survey_data->beneficiary_details);

// Ensure the beneficiary details were successfully decoded
$beneficiary_details_data=[];
if ($beneficiary_details) {
    // Fetch the district, tehsil, and uc names
    $district = DB::table('districts')->where('id', $beneficiary_details->district)->select('name')->first();
    $tehsil = DB::table('tehsil')->where('id', $beneficiary_details->tehsil)->select('name')->first();
    $uc = DB::table('uc')->where('id', $beneficiary_details->uc)->select('name')->first();

    // Use null coalescing operator to avoid errors if the records do not exist
    $district_name = $district ? $district->name : null;
    $tehsil_name = $tehsil ? $tehsil->name : null;
    $uc_name = $uc ? $uc->name : null;

    // Prepare the beneficiary details array
    $beneficiary_details_data = [
        'beneficairyName' => $beneficiary_details->beneficiary_name,
        'cnic' => $beneficiary_details->cnic,
        'gender' => $beneficiary_details->gender,
        'fatherName' => $beneficiary_details->father_name,
        'contact' => $beneficiary_details->contact_number,
        'district' => $district_name,
        'tehsil' => $tehsil_name,
        'address' => $beneficiary_details->address,
    ];
    $nameOfField=['Beneficairy Name','CNIC','Gender','Father Name','Contact','District','Tehsil','Address'];

} else {
    // Handle error in case of unsuccessful decoding
    return ['error' => 'Invalid beneficiary details.'];
}
        return view('dashboard.survey.final_view', ['survey_data' => $survey_data,'hru_data'=>$hru_data,'survey_form_id'=>$id,'beneficiary_details_data'=>$beneficiary_details_data,'nameOfField'=>$nameOfField]);
    }
    
    public function beneficiaryProfile(Request $request, $id) {
    // Fetch survey form data
    $survey_form = DB::table('survey_form')->where('id', $id)
        ->select('form_data', 'beneficiary_details')
        ->first();

    if (!$survey_form) {
        return ['error' => 'Survey form not found.'];
    }

    // Decode JSON data
    $beneficiary_details = json_decode($survey_form->beneficiary_details);
    $form = json_decode($survey_form->form_data);

    $sections = $form->sections ?? [];
    $required_data = [];
    $hru_data = [];
    $date_of_insurance = null;
    $banking_info = [];
    $third_person_allow=[];
  
    foreach ($sections as $key => $item) {
        switch ($key) {
            case 'Upload Photos & Documents':
                $required_data['upload_docs'] = $item; 
                foreach ($item->questions as $question) {
                    switch ($question->question->id) {
                        case 285:
                            $required_data['beneficiaryProfileImage'] = $question->question->answer;
                            break;
                        case 286:
                            $required_data['cnicFrontImage'] = $question->question->answer;
                            break;
                        case 287:
                            $required_data['cnicBackImage'] = $question->question->answer;
                            break;
                    }
                }
                break;
            case 'Location Information':
              $required_data['location_information'] = $item; 
              break;
            case 'Land Ownership':
              $required_data['land_ownership'] = $item;    
              break;
            case 'Reconstruction Status':
              $required_data['reconstruction_status'] = $item;
              break;
            case 'House Description (Pre-floods)':
              $required_data['house_description'] = $item;    
              break;
            case 'Hazardous Location':
              $required_data['hazaradous_location'] = $item;    
              break;
            case 'Environmental Screening':
              $required_data['environmental_screening'] = $item;
              break;
            case 'Upload Photos & Documents':
              $required_data['document_photos'] = $item;    
           break;
            case 'Other Questions':
              $required_data['other_questions'] = $item; 
              break;
            case 'Functional Limitation For 2 Persons':
              $required_data['functional_limitation_two_persons'] = $item; 
              
              
            foreach ($item->questions as $data) {
                    switch ($data->question->id) {
                        case 2243:
                            $required_data['Vulnerability'] = $data;
                            break;
                    }
                }
                break;
              
              
              
            case 'Functional Limitation For 3rd Person':
              $required_data['functional_limitation_third_persons'] = $item;  
           
              foreach ($item->questions as $data) {
                    switch ($data->question->id) {
                        case 2071:
                            $required_data['third_person_allow'] = $data->question->answer;
                            break;
                    }
                }
                break;
        
              
              
              
            case 'Functional Limitation For 4th Person':
              $required_data['functional_limitation_fourth_person'] = $item;
              foreach ($item->questions as $data) {
                    switch ($data->question->id) {
                        case 2081:
                            $required_data['fourth_person_allow'] = $data->question->answer;
                            break;
                    }
                }
                break;
              
              
              
            case 'Functional Limitation For 5th Person':
              $required_data['functional_limitation_fifth_person'] = $item;  
              
              foreach ($item->questions as $data) {
                    switch ($data->question->id) {
                        case 2185:
                            $required_data['fifth_person_allow'] = $data->question->answer;
                            break;
                    }
                }
                break;
              
              
            case 'Beneficiary Information':
                $required_data['beneficiaryInformation'] = $item; // Store the entire item here
                foreach ($item->questions as $data) {
                    switch ($data->question->name) {
                        case 'If Yes CNIC #':
                            $hru_data['cnic'] = $data->question->answer;
                            break;
                        case 'Beneficiary Name':
                            $hru_data['beneficairyName'] = $data->question->answer;
                            break;
                        case 'Father Name':
                            $hru_data['fatherName'] = $data->question->answer;
                            break;
                        case 'Gender':
                            $hru_data['gender'] = $data->question->answer;
                            break;
                        case 'Mobile/Cell No':
                            $hru_data['contact'] = $data->question->answer;
                            break;
                    }
                }
                break;

            case 'Banking Information':
                
               
                foreach ($item->questions as $data) {
                  
                    switch ($data->question->id) {
                        case 248:
                            $banking_info['beneficiaryExist'] = $data->question;  
                            break;
                        case 250:
                            $banking_info['AccountNo'] = $data->question;  
                            break;

                        case 251:
                            $banking_info['bankName'] = $data->question;
                            break;
                        case 252:
                            $banking_info['branchName'] = $data->question;  
                            break;
                        case 253:
                            $banking_info['bankAddress'] = $data->question;  

                            break;
                        case 618:
                            $banking_info['date_of_insurance']=$data->question;
                            break;
                        case 616:
                            $banking_info['mothers_maiden_name'] = $data->question;  
                            break;
                        case 617:
                            $banking_info['city_birth'] = $data->question;  
                            break;
                        case 350:
                            $banking_info['is_cnic_expiry_date_life'] = $data->question;
                            break;
                        case 352:
                            $banking_info['Preferred'] = $data->question;
                            break;
                        case 351:
                            $banking_info['date_of_birth'] = $data->question;
                            break;
                     
                    }
                }
                
                break;

            case 'Location Information':
                foreach ($item->questions as $data) {
                    switch ($data->question->name) {
                        case 'District':
                            $hru_data['district'] = $data->question->answer;
                            break;
                        case 'Tehsil':
                            $hru_data['tehsil'] = $data->question->answer;
                            break;
                        case 'Village/Settlement Name':
                            $hru_data['address'] = $data->question->answer;
                            break;
                    }
                }
                break;
        }
    }
    
    $bank_info_heading=null;
    $requiredBankingInfo=[];
    foreach($banking_info as $key=>$item){
        if($item->id==248){
            if($item->answer=='Yes'){
               $bank_info_heading='Bank Account Details';    
               $requiredBankingInfo['AccountNo']=$banking_info['AccountNo'] ?? null;
               $requiredBankingInfo['bankName']=$banking_info['bankName'] ?? null;
               $requiredBankingInfo['branchName']=$banking_info['branchName'] ?? null;
               $requiredBankingInfo['bankAddress']=$banking_info['bankAddress'] ?? null;
            }
            else{
               $bank_info_heading='Account Opening Details';
               $requiredBankingInfo['date_of_insurance']=$banking_info['date_of_insurance'] ?? null;
               $requiredBankingInfo['mothers_maiden_name']=$banking_info['mothers_maiden_name'] ?? null;
               $requiredBankingInfo['city_birth']=$banking_info['city_birth'] ?? null;
               $requiredBankingInfo['is_cnic_expiry_date_life']=$banking_info['is_cnic_expiry_date_life'] ?? null;
               $requiredBankingInfo['Preferred']=$banking_info['Preferred'] ?? null;
               $requiredBankingInfo['date_of_birth']=$banking_info['date_of_birth'] ?? null;
            }
        }
    }
    
    $required_data['bankingInfo']=$requiredBankingInfo;
    $beneficiary_details_data = [];
    if ($beneficiary_details) {
        // Fetch related names for district, tehsil, and uc
        $district_name = DB::table('districts')->where('id', $beneficiary_details->district)->value('name');
        $tehsil_name = DB::table('tehsil')->where('id', $beneficiary_details->tehsil)->value('name');
        $uc_name = DB::table('uc')->where('id', $beneficiary_details->uc)->value('name');

        // Prepare beneficiary details array
        $beneficiary_details_data = [
            'beneficairyName' => $beneficiary_details->beneficiary_name,
            'cnic' => $beneficiary_details->cnic,
            'gender' => $beneficiary_details->gender,
            'fatherName' => $beneficiary_details->father_name,
            'contact' => $beneficiary_details->contact_number,
            'district' => $district_name,
            'tehsil' => $tehsil_name,
            'address' => $beneficiary_details->address,
        ];
        $nameOfField = ['Beneficairy Name', 'CNIC', 'Gender', 'Father Name', 'Contact', 'District', 'Tehsil', 'Address'];
    } else {
        return ['error' => 'Invalid beneficiary details.'];
    }


    return view('dashboard.survey.formView.beneficiaryProfile', [
        'required_data' => $required_data,
        'beneficiary_details' => $beneficiary_details,
        'beneficiary_details_data' => $beneficiary_details_data,
        'nameOfField' => $nameOfField,
        'bank_info_heading' => $bank_info_heading
    ]);
}
    public function beneficiaryProfileNew($id,$session=0,$role=null) {

       $answer_check = Cache::remember("answer_check_{$id}", 600, function () use ($id) {
            return Answer::where('survey_form_id', $id)->pluck('id')->first();
        });

   
     
    if(!$answer_check){
    destructure_form_new($id);  
    }


    
    
    $question_cat = Cache::remember("question_cat_{$id}", 600, function () use ($id) {
    
    return QuestionTitle::with(['questions' => function ($q) use ($id) {
        $q->with(['useranswer' => function ($q) use ($id) {$q->where('survey_form_id', $id); }]);
        $q->with(['decision' => function ($q) use ($id) { $q->where('survey_id', $id); }]);
    }])
    ->whereHas('questions.useranswer', function ($q) use ($id) {$q->where('survey_form_id', $id); })
    ->select('id', 'name', 'section_order')
    ->where('visibility', 1)
    //->whereIn('id', [35,39])
    ->orderBy('section_order', 'ASC')
    ->get();
    
        });
    
    if(Auth::user()->role == 1){
    //dd($question_cat->toArray());
    }
    
    
    $comment_missing_document = Cache::remember("comment_missing_doc_{$id}", 600, function () use ($id) {
            return CommentMissingDocument::where('survey_id', $id)->select('id', 'created_role', 'comment')->first();
        });
    
   
    Session::put('role', $role);
    

    
   return view('dashboard.survey.formView.beneficiaryProfileNew', compact('id','question_cat','session','comment_missing_document'));
    }
    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'uc_id' => 'required'
            ]);
            $data = $request->all();
            $area = Area::find($id);
            addLogs('updated the settlement titled "'. $area->name.'"', Auth::user()->id,'update','settlement management');
            $area->fill($data)->save();
   
            return redirect()->route('area.list')->with(['success' => 'You update  settlement successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
    public function area_status(Request $request, $id)
    {
        $area = Area::find($id);
        if ($area->status == '0') {
            $area->status = '1';
            addLogs('activate settlement titled "' . $area->name . '"', Auth::user()->id,'change status','settlement management');
            $area->save();
            return redirect()->back()->with('success','You active settlement  Successfully!');
        } else {
            $area->status = '0';
            $area->save();
            addLogs('deactivate settlement titled "' . $area->name . '"', Auth::user()->id,'change status','settlement management');
            return redirect()->back()->with('success','You deactivate settlement Successfully!');
        }
    }
    public function update_certified(Request $request,$id){
        $form_status=FormStatus::where('id',$id)->first();
        if($form_status->certification==0){
        $form_status->certification=1;    
        $success='You Certified the form Successfully';
        
        }else{
        $form_status->certification=0;
        $success='You UnCertified the form Successfully';
        }
        $form_status->save();
        return redirect()->back()->with('success',$success);
 
    }
    public function beneficiary_details(Request $request){
        $survey_form=DB::table('survey_form')->where('id',$request->id)->select('beneficiary_details')->first();
        return json_decode($survey_form->beneficiary_details);
        
    }
    
    public function survey_rejected_data(Request $request)
    {
    $allow_to_update_form = DB::table('roles')
        ->join('users', 'users.role', '=', 'roles.id')
        ->where('users.id', Auth::user()->id)
        ->select('allow_to_update_form')
        ->first() ?? null;

    if (Auth::user()->id == 1) {
        $survey_data = DB::table('form_status')
            ->join('users', 'form_status.user_id', '=', 'users.id')
            ->join('survey_form', 'form_status.form_id', '=', 'survey_form.id')
            ->join('form', 'survey_form.form_id', '=', 'form.id')
            ->join('roles', 'users.role', '=', 'roles.id')
            ->select(
                'form_status.id as form_status_id',
                'form_status.is_m_and_e',
                'users.name as validator_name',
                'form_status.form_status',
                'form.name as form_name',
                'survey_form.id as survey_form_id',
                'survey_form.generated_id',
                'roles.name as role_name',
                'form_status.comment',
                'survey_form.priority as priority',
                'survey_form.beneficiary_details',
                'survey_form.created_at as submission_date',
                'survey_form.beneficiary_name',
                'survey_form.ref_no',
                'survey_form.cnic',
                'survey_form.father_name'
            )
            ->where('form_status.form_status', 'R')
            ->orderBy('survey_form.priority', 'Desc')
            ->get()
            ->toArray();
    } else {
       
        $survey_data = DB::table('form_status')
            ->join('users', 'form_status.user_id', '=', 'users.id')
            ->join('survey_form', 'form_status.form_id', '=', 'survey_form.id')
            ->join('form', 'survey_form.form_id', '=', 'form.id')
            ->join('roles', 'users.role', '=', 'roles.id')
            ->select(
                'form_status.id as form_status_id',
                'users.name as validator_name',
                'form_status.form_status',
                'form.name as form_name',
                'survey_form.id as survey_form_id',
                'form_status.is_m_and_e',
                'survey_form.generated_id',
                'roles.name as role_name',
                'users.email as email',
                'form_status.comment',
                'survey_form.priority as priority',
                'survey_form.beneficiary_details',
                'survey_form.created_at as submission_date',
                'survey_form.ref_no',
                'survey_form.beneficiary_name',
                'survey_form.ref_no',
                'survey_form.cnic',
                'survey_form.father_name'
            )
            ->where('form_status.form_status', 'R')
            ->where('form_status.user_id', Auth::user()->id)
            ->orderBy('survey_form.priority', 'Desc')
            ->get()
            ->toArray();
           

        // Remove duplicates based on `ref_no` using an array filter
        $final_data = [];
        $ref_ids = [];

        foreach ($survey_data as $item) {
            if (!in_array($item->ref_no, $ref_ids)) {
                $final_data[] = $item;
                $ref_ids[] = $item->ref_no;  
            }
        }

        $survey_data = $final_data;
    }
    return view('dashboard.survey.current_rejected', ['survey_data' => $survey_data]);
}

    
    
    
    //Added By Ayaz
    public function survey_everyuserrejected_data(){  
       
       
         $role = Role::find(Auth::user()->role) ?? null;
         $authenticate_user_uc=json_decode(Auth::user()->uc_id);
         $updated_by_name = upper_role_name_form_status();

       
         $updated_by_same_name = same_role_name_form_status();
   
         
         $survey_data =DB::table('form_status')
         ->join('users','form_status.user_id','=','users.id')
         ->join('survey_form','form_status.form_id','=','survey_form.id')
         ->join('form','survey_form.form_id','=','form.id')
         ->join('roles','users.role','=','roles.id')
         ->select('form_status.id as form_status_id','users.name as validator_name','form_status.form_status',
         'form.name as form_name','survey_form.id as survey_form_id','form_status.is_m_and_e','survey_form.generated_id','roles.name as role_name','users.email as email','form_status.comment','survey_form.priority as priority','survey_form.beneficiary_details','survey_form.created_at as submission_date')
     
         ->where('form_status.update_by', $updated_by_name)
         ->where('form_status.form_status','R')
         ->whereIn('survey_form.uc_id', $authenticate_user_uc)
         ->orderBy('survey_form.priority','Desc')
         ->get()->toArray();
         
         //dd($survey_data);
        return view('dashboard.survey.reject_form',['survey_data'=>$survey_data]);
    }//end userrejected
    
    
    public function survey_approved_data(Request $request){
         $allow_to_update_form=DB::table('roles')
        ->join('users','users.role','=','roles.id')
        ->where('users.id',Auth::user()->id)
        ->select('allow_to_update_form')->first() ?? null ;
        
         if(Auth::user()->id==1 ){             
         $survey_data =DB::table('form_status')
         ->join('users','form_status.user_id','=','users.id')
         ->join('survey_form','form_status.form_id','=','survey_form.id')
         ->join('form','survey_form.form_id','=','form.id')
         ->join('roles','users.role','=','roles.id')
         ->select('form_status.id as form_status_id','form_status.update_by','users.name as validator_name','form_status.form_status',
         'form.name as form_name','survey_form.id as survey_form_id','survey_form.generated_id','survey_form.coordinates','roles.name as role_name','form_status.comment','form_status.is_m_and_e','survey_form.priority as priority','survey_form.beneficiary_details','survey_form.father_name','survey_form.beneficiary_name','survey_form.cnic','survey_form.ref_no','survey_form.created_at as submission_date')
         ->where('form_status.form_status','A')
         ->orderBy('survey_form.priority','Desc')
         ->get()->toArray();
         }
         else{   
         $survey_data =DB::table('form_status')
         ->join('users','form_status.user_id','=','users.id')
         ->join('survey_form','form_status.form_id','=','survey_form.id')
         ->join('form','survey_form.form_id','=','form.id')
         ->join('roles','users.role','=','roles.id')
         ->select('form_status.id as form_status_id','users.name as validator_name','form_status.form_status',
         'form.name as form_name','survey_form.id as survey_form_id','survey_form.generated_id','survey_form.coordinates','form_status.is_m_and_e','roles.name as role_name','users.email as email','form_status.is_m_and_e','survey_form.priority as priority','survey_form.beneficiary_details','survey_form.created_at as submission_date','survey_form.ref_no','survey_form.father_name','survey_form.beneficiary_name','survey_form.cnic')
         ->where('form_status.form_status','A')->where('form_status.user_id',Auth::user()->id)
         ->orderBy('survey_form.priority','Desc')
         ->get()->toArray();
        }
        return view('dashboard.survey.approved_form',['survey_data'=>$survey_data]);
        
    }
    public function survey_approved_data_testing(Request $request){
         $allow_to_update_form=DB::table('roles')
        ->join('users','users.role','=','roles.id')
        ->where('users.id',Auth::user()->id)
        ->select('allow_to_update_form')->first() ?? null ;
        
         if(Auth::user()->id==1 ){             
         $survey_data =DB::table('form_status')
         ->join('users','form_status.user_id','=','users.id')
         ->join('survey_form','form_status.form_id','=','survey_form.id')
         ->join('form','survey_form.form_id','=','form.id')
         ->join('roles','users.role','=','roles.id')
         ->select('form_status.id as form_status_id','form_status.update_by','users.name as validator_name','form_status.form_status',
         'form.name as form_name','survey_form.id as survey_form_id','survey_form.generated_id','roles.name as role_name','form_status.comment','form_status.is_m_and_e','survey_form.priority as priority','survey_form.beneficiary_details','survey_form.created_at as submission_date')
         ->where('form_status.form_status','A')
         ->orderBy('survey_form.priority','Desc')
         ->get()->toArray();
         }
         else{   
         $survey_data =DB::table('form_status')
         ->join('users','form_status.user_id','=','users.id')
         ->join('survey_form','form_status.form_id','=','survey_form.id')
         ->join('form','survey_form.form_id','=','form.id')
         ->join('roles','users.role','=','roles.id')
         ->select('form_status.id as form_status_id','users.name as validator_name','form_status.form_status',
         'form.name as form_name','survey_form.id as survey_form_id','survey_form.generated_id','form_status.is_m_and_e','roles.name as role_name','users.email as email','form_status.is_m_and_e','survey_form.priority as priority','survey_form.beneficiary_details','survey_form.created_at as submission_date')
         ->where('form_status.form_status','A')->where('form_status.user_id',Auth::user()->id)
         ->orderBy('survey_form.priority','Desc')
         ->get()->toArray();
     
        
        
         
         $role=null;
       
        }
        return view('dashboard.survey.approved_testing',['survey_data'=>$survey_data]);
        
    }
    public function survey_hold_data(Request $request){
        
        
        // find user roles
         $allow_to_update_form=DB::table('roles')
        ->join('users','users.role','=','roles.id')
        ->where('users.id',Auth::user()->id)
        ->select('allow_to_update_form')->first() ?? null ;
         if(Auth::user()->id==1 || Auth::user()->role==51){             
         $survey_data =DB::table('form_status')
         ->join('users','form_status.user_id','=','users.id')
         ->join('survey_form','form_status.form_id','=','survey_form.id')
         ->join('form','survey_form.form_id','=','form.id')
         ->join('roles','users.role','=','roles.id')
         ->select('form_status.id as form_status_id','users.name as validator_name','form_status.form_status',
         'form.name as form_name','survey_form.id as survey_form_id','survey_form.generated_id','roles.name as role_name','form_status.comment','survey_form.priority as priority','survey_form.beneficiary_details','survey_form.created_at as submission_date','survey_form.generated_id')
         ->where('form_status.form_status','H')
         ->orderBy('survey_form.priority','Desc')
         ->get()->toArray();
         }
         else{   
         $survey_data =DB::table('form_status')
         ->join('users','form_status.user_id','=','users.id')
         ->join('survey_form','form_status.form_id','=','survey_form.id')
         ->join('form','survey_form.form_id','=','form.id')
         ->join('roles','users.role','=','roles.id')
         ->select('form_status.id as form_status_id','users.name as validator_name','form_status.form_status',
         'form.name as form_name','survey_form.id as survey_form_id','roles.name as role_name','users.email as email','survey_form.priority as priority','survey_form.beneficiary_details','survey_form.created_at as submission_date','survey_form.generated_id')
         ->where('form_status.form_status','H')->where('form_status.user_id',Auth::user()->id)
         ->orderBy('survey_form.priority','Desc')
         ->get()
         ->toArray();
          $role=null;
       
         if($role != null){
         $survey_data_of_junior_position =DB::table('form_status')
         ->join('users','form_status.user_id','=','users.id')
         ->join('survey_form','form_status.form_id','=','survey_form.id')
         ->join('form','survey_form.form_id','=','form.id')
         ->join('roles','users.role','=','roles.id')
         ->select('form_status.id as form_status_id','users.name as validator_name','form_status.form_status',
         'form.name as form_name','survey_form.id as survey_form_id','roles.name as role_name','users.email as email','survey_form.priority as priority','survey_form.beneficiary_details','survey_form.uc_id as uc_id','survey_form.created_at as submission_date')
         ->where('form_status.form_status','H')->where('form_status.update_by',$role)
         ->orderBy('survey_form.priority','Desc')
         ->get()
         ->toArray();
         $junior_survey_data=[];
         $authenticate_user_uc=json_decode(Auth::user()->uc_id);
         foreach($survey_data_of_junior_position as $item){
         if(in_array($item->uc_id,$authenticate_user_uc)){
          $junior_survey_data[]=$item;   
         }
             
         }
          $survey_data=count($survey_data)==0 ?[]:  $survey_data; 
          $final_junior_survey_data= $junior_survey_data;
          $survey_data=array_merge($final_junior_survey_data,$survey_data);
         }
        
        }
       
        return view('dashboard.survey.hold_form',['survey_data'=>$survey_data,'allow_to_update_form'=>$allow_to_update_form]);
        
    }
    protected function survey_pending_list($condition){
      
        
        $query = SurveyData::join('users','survey_form.user_id','=','users.id')
                ->join('form_status','survey_form.id','form_status.form_id');
        $query = $this->addCommonJoins($query);
        $authenticate_user_uc=json_decode(Auth::user()->uc_id);
        if(Auth::user()->role==1 ||  Auth::user()->role==39){
         $survey_data=$query->where('form_status.form_status','A')
                ->where('form_status.update_by',$condition)
                ->select(
                'users.name as user_name','form_status.is_m_and_e',
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
                'form_status.team_member_status',
                'form_status.certification as certification','form_status.user_status',
                'form_status.id as form_status_id','survey_form.priority as priority',
                'survey_form.beneficiary_details',
                'survey_form.created_at as submission_date',
                      
               'survey_form.beneficiary_name',
               'survey_form.ref_no',
               'survey_form.cnic',
               'survey_form.father_name'
               
                )
                ->orderBy('survey_form.priority','Desc')
                ->get();  
                
            
        }
        else if($condition=='COO'){
              
              $survey_data=$query->where('form_status.form_status','A')
                // ->where('form_status.update_by','COO')
                ->orWhere('form_status.update_by','HRU_MAIN')
                ->select('users.name as user_name','survey_form.beneficiary_name','survey_form.ref_no','survey_form.cnic','survey_form.father_name','form_status.is_m_and_e','survey_form.generated_id','form_status.m_and_e_comment',
                'uc.name as uc_name','lots.name as lot_name','tehsil.name as tehsil_name','districts.name as district_name',
                'form_status.id as form_status_id','form.name as form_name','survey_form.id as survey_form_id','survey_form.uc_id','form_status.team_member_status','form_status.certification as certification','form_status.user_status','form_status.id as form_status_id','survey_form.priority as priority','survey_form.beneficiary_details','survey_form.created_at as submission_date')
                ->orderBy('survey_form.priority','Desc')
                ->get(); 
                
            
              
                
        }
      
        
        else{   
   
        $survey_data=$query->where('form_status.form_status','A')
                ->where('form_status.update_by',$condition)
                ->whereIn('survey_form.uc_id', $authenticate_user_uc)
                ->where('survey_form.is_ineligible', 1)
                ->select('survey_form.father_name','survey_form.ref_no','survey_form.beneficiary_name','users.name as user_name','survey_form.beneficiary_name','survey_form.ref_no','survey_form.cnic','form_status.is_m_and_e','survey_form.generated_id','form_status.m_and_e_comment',
                'uc.name as uc_name','lots.name as lot_name','tehsil.name as tehsil_name','districts.name as district_name',
                'form_status.id as form_status_id','form.name as form_name','survey_form.id as survey_form_id','survey_form.uc_id','form_status.team_member_status','form_status.certification as certification','form_status.user_status','form_status.id as form_status_id','survey_form.priority as priority','survey_form.beneficiary_details','survey_form.created_at as submission_date')
                ->orderBy('survey_form.priority','Desc')
                ->get();

        }
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
    
    
    public function survey_pending_data_test(Request $request)
    { 
        

        //  initalize some varibales
         $certification=null;
         $non_certification=null;
         $field_super_visor_survey_final=null;
         $survey_data=null;
         $final_data=[];
    
        
        //  some roles are not directly define they are just team members  so access them through their team roles
         $allow_to_update_form=DB::table('roles')
        ->join('users','users.role','=','roles.id')
        ->where('users.id',Auth::user()->id)
        ->select('allow_to_update_form')->first() ?? null ;
        
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
        // 209429
        )->whereBetween('survey_form.id', [180000 , 220000])
        ->orderBy('survey_form.priority', 'Desc')
        ->get();
        //dd($survey_data);
  
        
        
        $uc_restrictions=false;
        if( (Auth::user()->role==37 || $allow_to_update_form->allow_to_update_form=='PSIA') || (Auth::user()->role==38 || $allow_to_update_form->allow_to_update_form=='HRU_Main') || Auth::user()->role==39 || $allow_to_update_form->allow_to_update_form=='COO' || Auth::user()->role==40 || $allow_to_update_form->allow_to_update_form=='CEO' || Auth::user()->role==1){
            $uc_restrictions=true;
        }else{
            $uc_restrictions=true;
        }
        
         
        
        if($uc_restrictions==true){
        $authenticate_user_uc=json_decode(Auth::user()->uc_id);
        $final_data=[];
        foreach($survey_data as $item){
            if(in_array($item->uc_id,$authenticate_user_uc)){
                $final_data[]=$item;
            }
        }
      
            
        }
        if(count($final_data) > 0){
            $survey_data=$final_data;
        }
       
       
   
         
        return view('dashboard.survey.pending_form',['survey_data'=>$survey_data,
        'allow_to_update_form'=>$allow_to_update_form,
        'certification'=>$certification,
        'non_certification'=>$non_certification,
        "field_super_visor_survey_final"=>$field_super_visor_survey_final
        ]);
    }
    
    public function survey_pending_data(Request $request)
    { 
                
        //  initalize some varibales
         $certification=null;
         $non_certification=null;
         $field_super_visor_survey_final=null;
         $survey_data=null;
         $final_data=[];
    
        
        //  some roles are not directly define they are just team members  so access them through their team roles
         $allow_to_update_form=DB::table('roles')
        ->join('users','users.role','=','roles.id')
        ->where('users.id',Auth::user()->id)
        ->select('allow_to_update_form')->first() ?? null ;
      
          
         if(Auth::user()->role==51){
            // this is special query for m&e   
        // ip survey data  
       
      
        $survey_data_ip=$this->survey_pending_list('field supervisor');  
       
        
        // because the fields which is updated by fields super so exclude that data through ids
        $excluded_ids = $survey_data_ip->pluck('survey_form_id')->toArray();
        
        $field_super_visor_survey = SurveyData::join('users','survey_form.user_id','=','users.id')
            ->join('form','survey_form.form_id','form.id')
            ->select('users.name as user_name',
            'form.name as form_name',
            'survey_form.generated_id',
            'survey_form.id as survey_form_id',
            'survey_form.uc_id',
            'survey_form.priority as priority',
            'survey_form.beneficiary_details',
            'survey_form.id as  survey_form_id',
            'survey_form.created_at as submission_date',
            // from here
            'survey_form.beneficiary_name',
            'survey_form.ref_no',
            'survey_form.cnic',
            'survey_form.father_name'
            )
            ->orderBy('survey_form.priority','Desc')
            ->get();
            
        $field_super_visor_survey_final=[];    
        foreach($field_super_visor_survey as $item){
            if(!in_array($item->survey_form_id,$excluded_ids)){
                 $field_super_visor_survey_final[]=$item;
                 
            }
        }    
      
        $survey_data_ip=array ($survey_data_ip);
     
        $survey_data=$field_super_visor_survey_final;

        }
        else if(Auth::user()->role==34 || $allow_to_update_form->allow_to_update_form=='IP'){
        $survey_data=$this->survey_pending_list('field supervisor');
    
         
        
        }
        else if(Auth::user()->role==26 || $allow_to_update_form->allow_to_update_form=='HRU'){
                $survey_data=$this->survey_pending_list('IP');
        }
        else if(Auth::user()->role==37 || $allow_to_update_form->allow_to_update_form=='PSIA'){
        $survey_data=$this->survey_pending_list('HRU');
       
        }
        else if(Auth::user()->role==38 || $allow_to_update_form->allow_to_update_form=='HRU_Main'){
            //   i need data according to the approvel of PSIA
                $survey_data=$this->survey_pending_list('PSIA');
        }
        else if(Auth::user()->role==39 || $allow_to_update_form->allow_to_update_form=='COO'){
                $survey_data=$this->survey_pending_list('HRU_Main');
        }
        else if(Auth::user()->role==40 || $allow_to_update_form->allow_to_update_form=='CEO'){
                $survey_data=$this->survey_pending_list('COO');
            
            
        }
        else{
          
        
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
        ,'survey_form.created_at as submission_date',
        'survey_form.beneficiary_name',
        'survey_form.ref_no',
        'survey_form.cnic',
        'survey_form.father_name'
        )
        ->orderBy('survey_form.priority', 'Desc')
        ->get();
      
  
        }
        
      
        
        
        $authenticate_user_uc=json_decode(Auth::user()->uc_id);
        $final_data=[];
        foreach($survey_data as $item){
            if(in_array($item->uc_id,$authenticate_user_uc)){
                $final_data[]=$item;
            }
        }
      
            
        
        if(count($final_data) > 0){
            $survey_data=$final_data;
        }
         
          
      
        return view('dashboard.survey.pending_form',['survey_data'=>$survey_data,
        'allow_to_update_form'=>$allow_to_update_form,
        'certification'=>$certification,
        'non_certification'=>$non_certification,
        "field_super_visor_survey_final"=>$field_super_visor_survey_final
        ]);
    }
    public function survey_pending_data_for_ids(Request $request)
    { 
                
        //  initalize some varibales
         $certification=null;
         $non_certification=null;
         $field_super_visor_survey_final=null;
         $survey_data=null;
         $final_data=[];
    
        
        //  some roles are not directly define they are just team members  so access them through their team roles
         $allow_to_update_form=DB::table('roles')
        ->join('users','users.role','=','roles.id')
        ->where('users.id',Auth::user()->id)
        ->select('allow_to_update_form')->first() ?? null ;
      
          
         if(Auth::user()->role==51){
            // this is special query for m&e   
        // ip survey data  
       
      
        $survey_data_ip=$this->survey_pending_list('field supervisor');  
       
        
        // because the fields which is updated by fields super so exclude that data through ids
        $excluded_ids = $survey_data_ip->pluck('survey_form_id')->toArray();
        
        $field_super_visor_survey = SurveyData::join('users','survey_form.user_id','=','users.id')
            ->join('form','survey_form.form_id','form.id')
            ->select('users.name as user_name',
            'form.name as form_name',
            'survey_form.generated_id',
            'survey_form.id as survey_form_id',
            'survey_form.uc_id',
            'survey_form.priority as priority',
            'survey_form.beneficiary_details',
            'survey_form.id as  survey_form_id',
            'survey_form.created_at as submission_date',
            // from here
            'survey_form.beneficiary_name',
            'survey_form.ref_no',
            'survey_form.cnic',
            'survey_form.father_name'
            )
            ->orderBy('survey_form.priority','Desc')
            ->get();
            
        $field_super_visor_survey_final=[];    
        foreach($field_super_visor_survey as $item){
            if(!in_array($item->survey_form_id,$excluded_ids)){
                 $field_super_visor_survey_final[]=$item;
                 
            }
        }    
      
        $survey_data_ip=array ($survey_data_ip);
     
        $survey_data=$field_super_visor_survey_final;

        }
        else if(Auth::user()->role==34 || $allow_to_update_form->allow_to_update_form=='IP'){
        // $survey_data=$this->survey_pending_list('field supervisor');
                
            $query = SurveyData::join('users','survey_form.user_id','=','users.id')
                    ->join('form_status','survey_form.id','form_status.form_id');
            $query = $this->addCommonJoins($query);
           $survey_data=$query->where('form_status.form_status','A')
                ->where('form_status.update_by','field supervisor')
                // ->whereIn('survey_form.uc_id', $authenticate_user_uc)
                ->where('survey_form.is_ineligible', 1)
                
                ->select('survey_form.father_name','survey_form.ref_no','survey_form.beneficiary_name','users.name as user_name','survey_form.beneficiary_name','survey_form.ref_no','survey_form.cnic','form_status.is_m_and_e','survey_form.generated_id','form_status.m_and_e_comment',
                'uc.name as uc_name','lots.name as lot_name','tehsil.name as tehsil_name','districts.name as district_name',
                'form_status.id as form_status_id','form.name as form_name','survey_form.id as survey_form_id','survey_form.uc_id','form_status.team_member_status','form_status.certification as certification','form_status.user_status','form_status.id as form_status_id','survey_form.priority as priority','survey_form.beneficiary_details','survey_form.created_at as submission_date')
                ->orderBy('survey_form.priority','Desc')
                ->get();
         
        
        }
        else if(Auth::user()->role==26 || $allow_to_update_form->allow_to_update_form=='HRU'){
                    $query = SurveyData::join('users','survey_form.user_id','=','users.id')
                    ->join('form_status','survey_form.id','form_status.form_id');
            $query = $this->addCommonJoins($query);
           $survey_data=$query->where('form_status.form_status','A')
                ->where('form_status.update_by','IP')
                // ->whereIn('survey_form.uc_id', $authenticate_user_uc)
                ->where('survey_form.is_ineligible', 1)
                ->select('survey_form.father_name','survey_form.ref_no','survey_form.beneficiary_name','users.name as user_name','survey_form.beneficiary_name','survey_form.ref_no','survey_form.cnic','form_status.is_m_and_e','survey_form.generated_id','form_status.m_and_e_comment',
                'uc.name as uc_name','lots.name as lot_name','tehsil.name as tehsil_name','districts.name as district_name',
                'form_status.id as form_status_id','form.name as form_name','survey_form.id as survey_form_id','survey_form.uc_id','form_status.team_member_status','form_status.certification as certification','form_status.user_status','form_status.id as form_status_id','survey_form.priority as priority','survey_form.beneficiary_details','survey_form.created_at as submission_date')
                ->orderBy('survey_form.priority','Desc')
                ->get();
        }
        else if(Auth::user()->role==37 || $allow_to_update_form->allow_to_update_form=='PSIA'){
           $query = SurveyData::join('users','survey_form.user_id','=','users.id')
                    ->join('form_status','survey_form.id','form_status.form_id');
            $query = $this->addCommonJoins($query);
           $survey_data=$query->where('form_status.form_status','A')
                ->where('form_status.update_by','HRU')
                // ->whereIn('survey_form.uc_id', $authenticate_user_uc)
                ->where('survey_form.is_ineligible', 1)
                ->select('survey_form.father_name','survey_form.ref_no','survey_form.beneficiary_name','users.name as user_name','survey_form.beneficiary_name','survey_form.ref_no','survey_form.cnic','form_status.is_m_and_e','survey_form.generated_id','form_status.m_and_e_comment',
                'uc.name as uc_name','lots.name as lot_name','tehsil.name as tehsil_name','districts.name as district_name',
                'form_status.id as form_status_id','form.name as form_name','survey_form.id as survey_form_id','survey_form.uc_id','form_status.team_member_status','form_status.certification as certification','form_status.user_status','form_status.id as form_status_id','survey_form.priority as priority','survey_form.beneficiary_details','survey_form.created_at as submission_date')
                ->orderBy('survey_form.priority','Desc')
                ->get();
       
        }
        else if(Auth::user()->role==38 || $allow_to_update_form->allow_to_update_form=='HRU_Main'){
            //   i need data according to the approvel of PSIA
               
            $query = SurveyData::join('users','survey_form.user_id','=','users.id')
                    ->join('form_status','survey_form.id','form_status.form_id');
            $query = $this->addCommonJoins($query);
           $survey_data=$query->where('form_status.form_status','A')
                ->where('form_status.update_by','PSIA')
                // ->whereIn('survey_form.uc_id', $authenticate_user_uc)
                ->where('survey_form.is_ineligible', 1)
                ->select('survey_form.father_name','survey_form.ref_no','survey_form.beneficiary_name','users.name as user_name','survey_form.beneficiary_name','survey_form.ref_no','survey_form.cnic','form_status.is_m_and_e','survey_form.generated_id','form_status.m_and_e_comment',
                'uc.name as uc_name','lots.name as lot_name','tehsil.name as tehsil_name','districts.name as district_name',
                'form_status.id as form_status_id','form.name as form_name','survey_form.id as survey_form_id','survey_form.uc_id','form_status.team_member_status','form_status.certification as certification','form_status.user_status','form_status.id as form_status_id','survey_form.priority as priority','survey_form.beneficiary_details','survey_form.created_at as submission_date')
                ->orderBy('survey_form.priority','Desc')
                ->get();
        }
        else if(Auth::user()->role==39 || $allow_to_update_form->allow_to_update_form=='COO'){
                $survey_data=$this->survey_pending_list('HRU_Main');
        }
        else if(Auth::user()->role==40 || $allow_to_update_form->allow_to_update_form=='CEO'){
$query = SurveyData::join('users', 'survey_form.user_id', '=', 'users.id')
    ->join('form_status', 'survey_form.id', '=', 'form_status.form_id');

// Apply additional joins
$query = $this->addCommonJoins($query);

// Build the query
$survey_data = $query->whereBetween('survey_form.id', [1, 100000])
    ->where(function ($query) {
        $query->where('form_status.form_status', 'A')
              ->orWhere('form_status.update_by', 'HRU_MAIN');
    })
    ->select(
        'users.name as user_name',
        'survey_form.beneficiary_name',
        'survey_form.ref_no',
        'survey_form.cnic',
        'survey_form.father_name',
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
        'form_status.team_member_status',
        'form_status.certification',
        'form_status.user_status',
        'survey_form.priority',
        'survey_form.beneficiary_details',
        'survey_form.created_at as submission_date'
    )
    ->orderBy('survey_form.priority', 'desc')
    ->get();

            
            
        }
        else{
          
        
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
        ,'survey_form.created_at as submission_date',
        'survey_form.beneficiary_name',
        'survey_form.ref_no',
        'survey_form.cnic',
        'survey_form.father_name'
        )
        ->orderBy('survey_form.priority', 'Desc')
        ->get();
      
  
        }
        
      
        
        
     
      
            
        
       
         
          
         
        return view('dashboard.survey.pending_form_one',['survey_data'=>$survey_data,
        
        'allow_to_update_form'=>$allow_to_update_form,
        'certification'=>$certification,
        'non_certification'=>$non_certification,
        "field_super_visor_survey_final"=>$field_super_visor_survey_final
        ]);
    }
    
    
    
    public function certificationBaseData($status){
         $certification_query = SurveyData::join('users','survey_form.user_id','=','users.id') 
        ->join('form_status', 'survey_form.id', '=', 'form_status.form_id');       
        $certification_query = $this->addCommonJoins($certification_query);
        
        $non_certification=$certification_query
                ->where('form_status.form_status', 'A')
                ->where('form_status.update_by', 'HRU')
                ->where('form_status.certification', $status)
                ->select('users.name as user_name','form_status.is_m_and_e','survey_form.generated_id','form_status.m_and_e_comment',
                'uc.name as uc_name','lots.name as lot_name','tehsil.name as tehsil_name','districts.name as district_name',
                'form_status.id as form_status_id','form.name as form_name','survey_form.id as survey_form_id','survey_form.uc_id','form_status.team_member_status','form_status.certification as certification','form_status.user_status','form_status.id as form_status_id','survey_form.priority as priority','survey_form.beneficiary_details','survey_form.created_at as submission_date')
                ->orderBy('survey_form.priority','Desc')
                ->get();
        return $non_certification;        
    }
    
    
    
    
    
    
    public function suvery_pending_data_ip_by_m_and_e(){
        
        $survey_data=$this->survey_pending_list('field supervisor');
        return view('dashboard.survey.pending.m_and_e.ip',['survey_data'=>$survey_data]);
    }
    public function suvery_pending_data_hru_by_m_and_e(){
        
        $survey_data=$this->survey_pending_list('IP');
        return view('dashboard.survey.pending.m_and_e.hru',['survey_data'=>$survey_data]);
    }
    public function suvery_pending_data_psia_by_m_and_e(){
        
        $survey_data=$this->survey_pending_list('HRU');
        return view('dashboard.survey.pending.m_and_e.psia',['survey_data'=>$survey_data]);
    }
    public function suvery_pending_data_hru_main_by_m_and_e(){
        
        $survey_data=$this->survey_pending_list('PSIA');
        return view('dashboard.survey.pending.m_and_e.hru_main',['survey_data'=>$survey_data]);
    }
    
    public function suvery_pending_data_coo_by_m_and_e(){
        $survey_data=$this->survey_pending_list('HRU_Main');
        return view('dashboard.survey.pending.m_and_e.coo',['survey_data'=>$survey_data]);
    }
    public function suvery_pending_data_ceo_by_m_and_e(){
        
        $survey_data=$this->survey_pending_list('COO');
    
        return view('dashboard.survey.pending.m_and_e.ceo',['survey_data'=>$survey_data]);
    }
    

//     public function rejection_revert(Request $request) 
//     {
// 		if($request->ajax()){
// 			$survey_id = $request->survey_id;
			
// 			$ques_id = $request->ques_id;
// 			$decision = $request->decision;
			
//             //dump($decision); 
            
// 			if($decision == 'revert'){
// 			$exist = QuestionsAcceptReject::where('survey_id', $survey_id)->where('ques_id', $ques_id)->first();
//     			if($exist){
//         			$exist->delete();    
//         			echo "Rejection revert successfully";    
//     			}else{
//                     echo "Rejection Not Found";
//     			}
// 			}
// 		}
// 	}

    public function rejection_revert(Request $request) 
    {
		if($request->ajax()){
			$survey_id = $request->survey_id;
			
			$ques_id = $request->ques_id;
			$decision = $request->decision;
			
            //dump($decision); 
            
			if($decision == 'revert'){
			 if ($ques_id == '247' || $ques_id =='642'  || $ques_id =='646' ||  $ques_id =='756') {
                if($ques_id == '247'){
                $ques_ids = [247, 290, 291];
                }
                else if($ques_id == '642'){
                $ques_ids = [642, 289, 288];
                }
                else if($ques_id == '646'){
                $ques_ids = [646, 2305, 2537];
                }
                else if($ques_id == '756'){
                $ques_ids = [756, 293, 294];
                } 
                foreach($ques_ids as $id){
                    	$exist = QuestionsAcceptReject::where('survey_id', $survey_id)->where('ques_id', $id)->delete();
                    	
                    	
                }
                echo "Rejection revert successfully"; 
			 }
			 else{
			$exist = QuestionsAcceptReject::where('survey_id', $survey_id)->where('ques_id', $ques_id)->first();
    			if($exist){
        			$exist->delete();    
        			echo "Rejection revert successfully";    
    			}else{
                    echo "Rejection Not Found";
    			}
			     
			 }   
			    
			}
		}
	}
	
	
	public function surveyquestion_rejectforms(Request $request)
    {
		if($request->ajax()){

		    $survey_id = $request->survey_id;
			$ques_id = $request->ques_id;
			$decision = $request->decision;

			return view('dashboard.survey.formView.surveyquestion_rejectform_popup', compact('survey_id','ques_id','decision'))->render(); 
			
		    
		
		}
	}
	
	

public function surveyquestion_rejectformsubmit(Request $request){
     
	if($request->ajax()){
	  
      $survey_id = $request->survey_id;
	  $ques_id = $request->ques_id;
	  $decision = $request->decision;
	  $comment = $request->comment;
	  $surveydata = SurveyData::where('id', $survey_id)->first();
	  if($decision == 'reject'){
			$exist = QuestionsAcceptReject::where('survey_id', $survey_id)->where('ques_id', $ques_id);
			$answer = Answer::where('survey_form_id', $survey_id)->where('question_id', $ques_id)->first();
			$role = Role::find(Auth::user()->role) ?? '';
			
			if($exist->count() > 0){
			echo '<div class="col-md-12"><div class="alert alert-danger"><strong>Error!</strong> Rejection already submit</div></div>';
			}else{
            if ($ques_id == '247' || $ques_id =='642'  || $ques_id =='646' ||  $ques_id =='756') {
                if($ques_id == '247'){
                $ques_ids = [247, 290, 291];
                }
                else if($ques_id == '642'){
                $ques_ids = [642, 289, 288];
                }
                else if($ques_id == '646'){
                $ques_ids = [646, 2305, 2537];
                }
                else if($ques_id == '756'){
                $ques_ids = [756, 293, 294];
                
                }
                $userId = Auth::user()->id;
                $insertData = [];
                foreach ($ques_ids as $ques_id) {
                    $insertData[] = [
                        'answer_id' => null,
                        'ques_id' => $ques_id,
                        'created_role' => $role->name,
                        'created_role_id' => $role->id,
                        'current_role_id' => $surveydata->m_role_id,
                        'survey_id'=>$survey_id,
                        'created_by' => $userId,
                        'decision'=>$decision,
                        'comment'=>$comment,
                        
                    ];
                }
                QuestionsAcceptReject::insert($insertData);
			}
			else{
			$data = $request->all();
			$data['answer_id'] = $answer->id;
			$data['created_by'] = Auth::user()->id;
			$data['created_role'] = $role->name;
			$data['created_role_id'] = $role->id;
			$data['current_role_id'] = $surveydata->m_role_id;
			
            $result = QuestionsAcceptReject::create($data);
           
			}    
            
            echo '<div class="col-md-12"><div class="alert alert-success"><strong>Success!</strong> Rejection submit is successfully</div></div>';
			}
	  }elseif($decision == 'comment'){

		    $exist = QuestionsAcceptReject::where('survey_id', $survey_id)->where('ques_id', $ques_id)->where('decision','comment');
			$answer = Answer::where('survey_form_id', $survey_id)->where('question_id', $ques_id)->first();
			$role = Role::find(Auth::user()->role) ?? '';
			
			if($exist->count() > 0){
			echo '<div class="col-md-12"><div class="alert alert-danger"><strong>Error!</strong> Comment already submit</div></div>';
			}else{
			$data = $request->all();
			$data['answer_id'] = $answer->id;
			$data['created_by'] = Auth::user()->id;
			$data['created_role'] = $role->name;
			$data['created_role_id'] = $role->id;
			$data['current_role_id'] = $surveydata->m_role_id;
			
			//dump($data);
            $result = QuestionsAcceptReject::create($data);
            echo '<div class="col-md-12"><div class="alert alert-success"><strong>Success!</strong> Comment submit is successfully</div></div>';
			}
		    
		    
		}

	  
	  
	  
	}
	}
	
	public function comment_revert(Request $request) 
    {
		if($request->ajax()){
			$comment_id = $request->comment_id;
			$exist = QuestionsAcceptReject::where('id', $comment_id)->first();
    			if($exist){
        			$exist->delete();    
        			echo "Comment remove successfully";    
    			}else{
                    echo "Comment Not Found";
    			}
		}
	}
	
    public function edit_question_answer(Request $request){
    $update_answer=DB::table('answers')->where('survey_form_id',$request->surveyId)->where('question_id',$request->quesId)->update(["answer"=>$request->new_value]);
    if($update_answer){
    return response()->json(['message'=>'update answer successfully',200]);
    }else{
    return response()->json(['message'=>'answer not updated',400]);
    }
    
    }
	
	
	
public function missing_documentcomment_form(Request $request)
    {
		if($request->ajax()){

		    $survey_id = $request->survey_id;
			$ques_id = $request->ques_id;
			$decision = $request->decision;

			return view('dashboard.survey.formView.missing_documentcomment_form_popup', compact('survey_id','ques_id','decision'))->render();  
			
		    
		
		}
	}
public function add_to_ineligible(Request $request)
    {
		

		    $survey_id = $request->survey_id;
		    if($survey_id){
		    DB::table('survey_form')->where('id',$survey_id)->update([   
		    'is_ineligible'=>0    
		    ]);
		    }
		     addLogs('added this form into Ineligible list "'. $request->name.'"', Auth::user()->id,'change status','survey form management');

		    return response()->json(['success'=>'You enter this form into Ineligible list successfully!']);
		


		    
		
		
	}
public function remove_to_ineligible(Request $request)
    {
		

		    $survey_id = $request->survey_id;
		    if($survey_id){
		    DB::table('survey_form')->where('id',$survey_id)->update([   
		    'is_ineligible'=>1   
		    ]);
		    }
		     addLogs('remove this form into Ineligible list "'. $request->survey_id.'"', Auth::user()->id,'change status','survey form management');

		    return response()->json(['success'=>'You remove this form from Ineligible list successfully!']);
		


		    
		
		
	}
	
public function missing_documentcomment_form_submit(Request $request){

	if($request->ajax()){

      $survey_id = $request->survey_id;
	  $ques_id = $request->ques_id;
	  $decision = $request->decision;
	  $comment = $request->comment;

		    $exist = CommentMissingDocument::where('survey_id', $survey_id)->where('ques_id', $ques_id)->where('decision','comment');
			$answer = Answer::where('survey_form_id', $survey_id)->where('question_id', $ques_id)->first();
			$surveydata = SurveyData::where('id', $survey_id)->first();
			$role = Role::find(Auth::user()->role) ?? '';
			
			if($exist->count() > 0){
			echo '<div class="col-md-12"><div class="alert alert-danger"><strong>Error!</strong> Missing Document Comment already submit</div></div>';
			}else{
			$data = $request->all();
			$data['answer_id'] = $answer->id;
			$data['created_by'] = Auth::user()->id;
			$data['created_role'] = $role->name;
			$data['lot_id'] = $surveydata->lot_id;
			
			//dump($data);
            $result = CommentMissingDocument::create($data);
            echo '<div class="col-md-12"><div class="alert alert-success"><strong>Success!</strong> Missing Document Comment submit is successfully</div></div>';
			}
 
	  
	}
	}
	
	public function missing_document_comment_remove(Request $request) 
    {
		if($request->ajax()){
			$comment_id = $request->comment_id;
			$exist = CommentMissingDocument::where('id', $comment_id)->first();
    			if($exist){
        			$exist->delete();    
        			echo "Missing Document Comment remove successfully";    
    			}else{
                    echo "Missing Document Comment Not Found";
    			}
		}
	}
	
	public function ineligible_list(){
	    $query = SurveyData::join('users', 'survey_form.user_id', '=', 'users.id');
	    
        $query = $this->addCommonJoins($query);
        $query=$query->where('survey_form.is_ineligible',0); 
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
        ->get();

        return view('dashboard.survey.ineligible',['survey_data'=>$survey_data]);
	}
	public function approved_by_ceo(){
	    $survey_data =DB::table('form_status')
         ->join('users','form_status.user_id','=','users.id')
         ->join('survey_form','form_status.form_id','=','survey_form.id')
         ->join('form','survey_form.form_id','=','form.id')
         ->join('roles','users.role','=','roles.id')
         ->select('form_status.id as form_status_id','users.name as validator_name','form_status.form_status',
         'form.name as form_name','survey_form.id as survey_form_id','survey_form.generated_id',
         'form_status.is_m_and_e','roles.name as role_name','users.email as email'
         ,'form_status.is_m_and_e','survey_form.priority as priority','survey_form.beneficiary_details','survey_form.created_at as submission_date',
         'survey_form.ref_no as ref_no'
         ,'survey_form.beneficiary_name as beneficiary_name'
         ,'survey_form.cnic2 as cnic'
         ,'survey_form.father_name as father_name'
         ,'survey_form.coordinates as coordinates'
         
         )
         ->where('form_status.form_status','A')
         ->where('form_status.update_by','CEO')
         ->orderBy('survey_form.priority','Desc')
         ->get()->toArray();
     
           return view('dashboard.survey.approved_form',['survey_data'=>$survey_data]);
           
	}
	
	public function remove_from_hold_list(Request $request){
	    DB::table('form_status')->where('form_id',$request->id)->where('update_by','Finance')->delete();
	    return response()->json(['success'=>'Remove From Hold List Successfully']);
	    
	}
    
    
}