<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\NdmaVerification;
use App\Models\Form;
use App\Models\SurveyData;
use App\Models\MNE;
use App\Models\VRC;
use App\Models\GenderSafeguard;
use App\Models\GenderFile;
use App\Models\QuestionTitle; 
use App\Models\SignUpRetrictions;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Jobs\destructureForm;
class GenderSafeguardController extends BaseController
{
   
public function ndma_data_for_gender_safeguard(Request $request){
         
         $ucValues= $request->uc;
         $NdmaVerification = VRC::whereIn('uc', $ucValues)
         ->join('users','users.id','=','vrc_formation.user_id')
         ->join('districts','vrc_formation.district','=','districts.name')
         ->join('tehsil','vrc_formation.tehsil','=','tehsil.name')
         ->join('uc','vrc_formation.uc','=','uc.name')
         ->join('lots','vrc_formation.lot','=','lots.name')
         ->select('vrc_formation.name_of_vrc','vrc_formation.district as district_name'
         ,
         'districts.id as district_id',
         'tehsil.id as tehsil_id',
         'uc.id as uc_id',
         'lots.id as lot_id',
         'vrc_formation.lot as lot_name',
         'users.supervisor_name','vrc_formation.tehsil as tehsil_name','vrc_formation.uc as uc_name','users.id as user_id','vrc_formation.longitude','vrc_formation.latitude','vrc_formation.altitude','vrc_formation.accuracy')
         ->get();
        return $NdmaVerification;
    }
    
    
public function ndma_data_for_gender_safeguard_after_case(Request $request){
         
$ucValues= $request->uc;

$NdmaVerification = VRC::whereIn('vrc_formation.uc', $ucValues) 
    ->join('users', 'users.id', '=', 'vrc_formation.user_id')
    ->join('gender_safeguard_json', 'gender_safeguard_json.unique_name_of_vrc', '=', 'vrc_formation.name_of_vrc')
    ->join('districts','vrc_formation.district','=','districts.name')
    ->join('tehsil','vrc_formation.tehsil','=','tehsil.name')
    ->join('uc','vrc_formation.uc','=','uc.name')
    ->join('lots','vrc_formation.lot','=','lots.name')
     ->where('gender_safeguard_json.status', 'CR')
    ->select(
        'vrc_formation.name_of_vrc',
        'vrc_formation.district as district_name',
        'vrc_formation.lot as lot_name',
        'users.supervisor_name',
        'vrc_formation.tehsil as tehsil_name',
        'vrc_formation.uc as uc_name',
        'users.id as user_id',
        'vrc_formation.longitude',
        'vrc_formation.latitude',
        'vrc_formation.altitude',
        'vrc_formation.accuracy',
         'districts.id as district_id',
         'tehsil.id as tehsil_id',
         'uc.id as uc_id',
         'lots.id as lot_id'
    )
    ->get();

    return $NdmaVerification;
    }    

public function formate_cnic($number){
        
         
         $new_number='';
         for($i=0;$i<strlen($number);$i++){
           if($i==4){
            $new_number.=$number[$i].'-';
             
           }else if($i==11){
            $new_number.=$number[$i].'-';
           }
           else{
            $new_number.=$number[$i];
           }
         }
            return $new_number;
      }










public function data_upload(Request $request){
              
 
        if(!isset($request->form_id)  || !isset($request->user_id) || !isset($request->ref_no) || !isset($request->form_data)){
            return response()->json(['error'=>"validation error all fields are required!"],400);
        }  
        $unique_name_of_vrc = DB::table('gender_safeguard_json')
            ->where('form_id',43)
            ->distinct() 
            ->pluck('unique_name_of_vrc'); 
 
       
        
        
        $role_name=null;
        $role_id=null;
        
        if($request->form_id==46){
           
            $check_case=DB::table('gender_safeguard_json')
              ->where('form_id',46)
              ->where('unique_name_of_vrc',$request->ref_no)
              ->where('is_complete',1)
              ->first();
            
          $pending_case=DB::table('gender_safeguard_json')
              ->where('form_id',46)
              ->where('unique_name_of_vrc',$request->ref_no)
              ->where('is_complete',0)
              ->first();
          
               
          if($check_case){
                return response()->json(['error'=>"The case of this checklist is already resolve!"],400);
          }
          if($pending_case){
                return response()->json(['error'=>"The case for this checklist has already been submitted and it is currently under review.!"],400);
          }
          
         
            $role_name='Gender Specialist';
            $role_id=61;
        }else{
            
        if(in_array($request->ref_no, $unique_name_of_vrc->toArray())){
            return response()->json(['error'=>"This name of vrc already registered!"],400);
        }  
            $role_name='IP';
            $role_id=34;
        }
      
        
           
       
        $json = DB::table("gender_safeguard_json")->insertGetId([
        "form_id"=>$request->form_id,
        "mobile_version"=>$request->mobile_version ?? null,
        "user_id"=>$request->user_id,
        "unique_name_of_vrc"=>$request->ref_no,
        "json"=> $request->form_data,
        'role_id'=>$role_id,
        'role_name'=>$role_name,
        'district'=>$request->district_id,
        'tehsil'=>$request->tehsil_id,
        'uc'=>$request->uc_id,
        'lot'=>$request->lot_id,
       
        ]);
        

        
        
        if($request->hasFile('Images')){
           
            
          $images_data = uploadfilesconstruction($json, $request->Images, 's_', 'gender_safe_guard');  
          $data = $images_data->getData();
        
              foreach($data->files as $key=>$item){
               
              GenderFile::create([
		        'gender_id' => $json,
		        'question_id' => $item->only_image_name,
                'name' => $item->file_name,
				'extension' => $item->extension,
				'size' => getfilesize($item->bytes),
                'created_by' => auth()->user()->id ?? 0,
				'updated_by' => auth()->user()->id ?? 0
                
            ]);
        
            
              }

          }
          
          

      
        
   
        $answer=null;
        $form= json_decode($request->form_data,true);
     

        foreach($form['sections'] as $item){
           
        foreach($item['questions'] as $ques){
            

        
        $answer=DB::table("gender_safeguard_answer")->insert([
            "section_id"=>$item['section']['id'],
            "question_id"=>$ques['question']['id'],
            "answer" => is_array($ques['question']['answer']) ? json_encode($ques['question']['answer']) : $ques['question']['answer'],
            "user_id"=>$request->user_id,
            "ref_no"=>$request->ref_no,
            "type"=>$ques['question']['type'],
            "gender_safeguard_json_id"=>$json     
        ]);
    }
        }

            if($answer){
                 return response()->json(['success'=>"Gender Safeguards Screening Checklist Form Submit Successfully!"],200);
            }else{
                 return response()->json(['error'=>"Some Error Found Data Not Uploaded"],400);
            }     
                        
        
        
} 







public function reject_form_upload(Request $request){

        
        if(!isset($request->form_id)  || !isset($request->user_id) || !isset($request->ref_no) || !isset($request->form_data)){
            return response()->json(['error'=>"validation error all fields are required!"],400);
        }
        
        $json = DB::table("gender_safeguard_json")->where('unique_name_of_vrc',$request->ref_no)->update([
                "form_id"=>43,
                "mobile_version"=>$request->mobile_version ?? null,
                "user_id"=>$request->user_id,
                "unique_name_of_vrc"=>$request->ref_no,
                "json"=> $request->form_data,
                'role_id'=>34,
                'role_name'=>'IP',
                'district'=>$request->district_id,
                'tehsil'=>$request->tehsil_id,
                'uc'=>$request->uc_id,
                'lot'=>$request->lot_id,
            ]);
            
        $answer=null;
        $form= json_decode($request->form_data,true);
     
        DB::table("gender_safeguard_answer")->where('ref_no',$request->ref_no)->delete();
       
        foreach($form['sections'] as $item){
           
        foreach($item['questions'] as $ques){
            

        
        $answer=DB::table("gender_safeguard_answer")->insert([
            "section_id"=>$item['section']['id'],
            "question_id"=>$ques['question']['id'],
            "answer" => is_array($ques['question']['answer']) ? json_encode($ques['question']['answer']) : $ques['question']['answer'],
            "user_id"=>$request->user_id,
            "ref_no"=>$request->ref_no,
            "type"=>$ques['question']['type'],
            "gender_safeguard_json_id"=>$json     
        ]);
    }
        }
   
            
            if($answer){
                 return response()->json(['success'=>"Gender Safeguards Screening Checklist Form update Successfully!"],200);
            }else{
                 return response()->json(['error'=>"Some Error Found Data Not update"],400);
            }     
                        
        
        
}    
  

public function survey_form_mne(){
    $form = Form::with(['sections' => function($q){ 
        $q->with(['questions'=> function($q){
            $q->with(['options'=> function($q){
            $q->with(['subsection'=> function($q){ $q->with(['questions'=> function($q){ $q->with('options'); }])->where('sub_section','true')->where('form_id', 27); }]);
        }]); 
        }]);
    }])->where('id',31)->select('id','name')->first();

    if(!$form){
        return response()->json([
            'success' => false,
            'message' => 'Form not found'
        ]);
    }
    return response()->json($form, 200);
   
}

public function reject_data(Request $request)
{

    if (!isset($request->user_id)) {
        return response()->json(['error' => "Validation error: user ID is required"], 400);
    }

    $data = DB::table('gender_safeguard_json')
        ->join('districts','districts.id','=','gender_safeguard_json.district')
        ->join('tehsil','tehsil.id','=','gender_safeguard_json.tehsil')
        ->join('uc','uc.id','=','gender_safeguard_json.uc')
        ->join('lots','lots.id','=','gender_safeguard_json.lot')
        ->join('users','users.id','=','gender_safeguard_json.user_id')
        ->join('form','form.id','=','gender_safeguard_json.form_id')
        ->select('districts.name as district',
                    'gender_safeguard_json.json as form_data',
                    'tehsil.name as tehsil',
                    'uc.name as uc',
                    'lots.name as lot',
                    'gender_safeguard_json.unique_name_of_vrc',
                    'users.name as role_name',
                    'form.name as form_name',
                    'gender_safeguard_json.id as primaryId',
                    'gender_safeguard_json.updated_at as rejected_date',
                    'gender_safeguard_json.created_at as created_at',
                    'districts.id as district_id',
                    'tehsil.id as tehsil_id',
                    'uc.id as uc_id',
                    'lots.id as lot_id',
                    DB::raw("'rejected' as type")
            )
        ->where('gender_safeguard_json.user_id', $request->user_id)
        ->where('gender_safeguard_json.role_id', 27)
        ->where('gender_safeguard_json.form_id', 43)
        // ->where('gender_safeguard_json.status','!=', 'CR')
        ->get();
        

  


    $count = $data->count();


    $result = [
        'data' => $data,
        'count' => $count,
    ];

    return response()->json($result, 200);
}

public function approved_data(Request $request)
{
    if (!isset($request->user_id)) {
        return response()->json(['error' => "Validation error: user ID is required"], 400);
    }

    $data = GenderSafeguard::
        join('districts','districts.id','=','gender_safeguard_json.district')
        ->join('tehsil','tehsil.id','=','gender_safeguard_json.tehsil')
        ->join('uc','uc.id','=','gender_safeguard_json.uc')
        ->join('lots','lots.id','=','gender_safeguard_json.lot')
        ->join('users','users.id','=','gender_safeguard_json.user_id')
        ->join('form','form.id','=','gender_safeguard_json.form_id')
            ->select('districts.name as district',
                    'tehsil.name as tehsil',
                    'uc.name as uc',
                    'lots.name as lot',
                    'gender_safeguard_json.unique_name_of_vrc',
                    'users.name as role_name',
                    'form.name as form_name',
                    'gender_safeguard_json.id as primaryId',
                    'gender_safeguard_json.created_at as created_at'
                 
            )
        ->where('gender_safeguard_json.form_id', 43)    
        ->where('user_id', $request->user_id)
        ->where('gender_safeguard_json.role_id', 61)
        ->get();

  


    $count = $data->count();


    $result = [
        'data' => $data,
        'count' => $count,
    ];

    return response()->json($result, 200);
}
public function submit_data(Request $request)
{
    if (!isset($request->user_id)) {
        return response()->json(['error' => "Validation error: user ID is required"], 400);
    }

    $data = GenderSafeguard::join('districts','districts.id','=','gender_safeguard_json.district')
        ->join('tehsil','tehsil.id','=','gender_safeguard_json.tehsil')
        ->join('uc','uc.id','=','gender_safeguard_json.uc')
        ->join('lots','lots.id','=','gender_safeguard_json.lot')
        ->join('users','users.id','=','gender_safeguard_json.user_id')
        ->join('form','form.id','=','gender_safeguard_json.form_id')
            ->select('districts.name as district',
                   
                    'tehsil.name as tehsil',
                    'uc.name as uc',
                    'lots.name as lot',
                    'gender_safeguard_json.unique_name_of_vrc',
                    'users.name as role_name',
                    'form.name as form_name',
                    'gender_safeguard_json.id as primaryId',
                    'gender_safeguard_json.created_at as created_at'
                 
            )
        ->where('gender_safeguard_json.form_id', 43)    
        ->where('user_id', $request->user_id)
        ->get();

  


    $count = $data->count();


    $result = [
        'data' => $data,
        'count' => $count,
    ];

    return response()->json($result, 200);
}








   public function rejected_list_mitigation(Request $request){
       if(!isset($request->user_id)){
            return response()->json(['error'=>"validation error user id requierd to get data!"],400);
       }
       $user_id=$request->user_id;
       $data=DB::table('gender_safeguard_json')
       ->join('form','gender_safeguard_json.form_id','=','form.id')
       ->join('districts','gender_safeguard_json.district','=','districts.id')
       ->join('tehsil','gender_safeguard_json.tehsil','=','tehsil.id')
       ->join('uc','gender_safeguard_json.uc','=','uc.id')
       ->where('gender_safeguard_json.is_complete',2)
       ->where('gender_safeguard_json.status','R')
       ->where('gender_safeguard_json.past_reject',0)
       ->where('gender_safeguard_json.user_id', $user_id)
       ->where('gender_safeguard_json.form_id', 46)
       ->select(
           'form.name as form_name',
           'gender_safeguard_json.unique_name_of_vrc',
           'districts.name as district_name',
           'gender_safeguard_json.created_at as created_at',
           'gender_safeguard_json.updated_at as rejected_at',
           'tehsil.name as tehsil_name',
           'uc.name as uc_name'
       )
       ->get();
       return  $data;
   }
   public function case_close_list_mitigation(Request $request){
       if(!isset($request->user_id)){
            return response()->json(['error'=>"validation error user id requierd to get data!"],400);
       }
       $user_id=$request->user_id;
       $data=DB::table('gender_safeguard_json')
       ->join('form','gender_safeguard_json.form_id','=','form.id')
       ->join('districts','gender_safeguard_json.district','=','districts.id')
       ->join('tehsil','gender_safeguard_json.tehsil','=','tehsil.id')
       ->join('uc','gender_safeguard_json.uc','=','uc.id')
       ->join('users','gender_safeguard_json.user_id','=','users.id')
       ->where('gender_safeguard_json.status','C')
       ->where('gender_safeguard_json.user_id', $user_id)
        ->where('gender_safeguard_json.form_id', 46)
       ->select(
           'form.name as form_name',
           'gender_safeguard_json.unique_name_of_vrc',
           'districts.name as district',
           'tehsil.name as tehsil',
           'uc.name as uc',
           'gender_safeguard_json.created_at as created_at',
           'gender_safeguard_json.updated_at as approved_at',
           'users.name as role_name'
       )
       ->get();
       return  $data;
   }
   public function submit_list_mitigation(Request $request){
       if(!isset($request->user_id)){
            return response()->json(['error'=>"validation error user id requierd to get data!"],400);
       }
       $user_id=$request->user_id;
       $data=DB::table('gender_safeguard_json')
       ->join('form','gender_safeguard_json.form_id','=','form.id')
    //   ->join('survey_form','gender_safeguard_json.ref_no','=','survey_form.ref_no')
       ->join('districts','gender_safeguard_json.district','=','districts.id')
       ->join('tehsil','gender_safeguard_json.tehsil','=','tehsil.id')
       ->join('uc','gender_safeguard_json.uc','=','uc.id')
     
       ->where('gender_safeguard_json.user_id', $user_id)
       ->where('gender_safeguard_json.form_id', 46)
       ->select(
           'form.name as form_name',
           'gender_safeguard_json.unique_name_of_vrc',
           'gender_safeguard_json.created_at as created_at',
           'districts.name as district_name',
           'tehsil.name as tehsil_name',
           'uc.name as uc_name'
       )
       ->get();
       return  $data;
   }







}