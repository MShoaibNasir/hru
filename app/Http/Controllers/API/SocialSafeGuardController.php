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
use App\Models\SocialSafeguard;
use App\Models\SocialFile;
use App\Models\QuestionTitle; 
use App\Models\SignUpRetrictions;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Jobs\destructureForm;
class SocialSafeGuardController extends BaseController
{
   
public function ndma_data_for_social_safeguard(Request $request){
         
         $ucValues= $request->uc;
         $NdmaVerification = VRC::whereIn('uc', $ucValues)
         ->join('users','users.id','=','vrc_formation.user_id')
         ->join('districts','vrc_formation.district','=','districts.name')
         ->join('tehsil','vrc_formation.tehsil','=','tehsil.name')
         ->join('uc','vrc_formation.uc','=','uc.name')
         ->join('lots','vrc_formation.lot','=','lots.name')
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
public function ndma_data_for_social_safeguard_after_case(Request $request){
         
$ucValues= $request->uc;
$NdmaVerification = VRC::whereIn('vrc_formation.uc', $ucValues) 
    ->join('users', 'users.id', '=', 'vrc_formation.user_id')
    ->join('social_safeguard_json', 'social_safeguard_json.unique_name_of_vrc', '=', 'vrc_formation.name_of_vrc')
    ->join('districts','vrc_formation.district','=','districts.name')
    ->join('tehsil','vrc_formation.tehsil','=','tehsil.name')
    ->join('uc','vrc_formation.uc','=','uc.name')
    ->join('lots','vrc_formation.lot','=','lots.name')
    ->where('social_safeguard_json.status', 'CR')
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
        $unique_name_of_vrc = DB::table('social_safeguard_json')
            ->where('form_id',44)
            ->distinct() 
            ->pluck('unique_name_of_vrc'); 
 
      
        
      
        $role_name=null;
        $role_id=null;
        
        if($request->form_id==45){
           
            $check_case=DB::table('social_safeguard_json')
               ->where('form_id',45)
               ->where('is_complete',1)
               ->where('unique_name_of_vrc',$request->ref_no)
               ->first();
            
           $pending_case=DB::table('social_safeguard_json')
               ->where('form_id',45)
               ->where('is_complete',0)
               ->where('unique_name_of_vrc',$request->ref_no)
               ->first();
               
          if($check_case){
                return response()->json(['error'=>"The case of this checklist is already resolve!"],400);
          }
          if($pending_case){
                return response()->json(['error'=>"The case for this checklist has already been submitted and it is currently under review.!"],400);
          }
          
        
            $role_name='Social Specialist';
            $role_id=63;
        }else{
            
        if(in_array($request->ref_no, $unique_name_of_vrc->toArray())){
            return response()->json(['error'=>"This name of vrc already use in social module!"],400);
        }
          
            
        
            $role_name='IP';
            $role_id=34;
        }
      
        
        
       
        $json = DB::table("social_safeguard_json")->insertGetId([

        "form_id"=>$request->form_id,
        "mobile_version"=>$request->mobile_version ?? null,
        "user_id"=>$request->user_id,
        "unique_name_of_vrc"=>$request->ref_no,
        "json"=> $request->form_data,
        'role_id'=>$role_id,
        'role_name'=>$role_name,
        'parseImages'=>$request->parseImages,
        'district'=>$request->district_id,
        'tehsil'=>$request->tehsil_id,
        'uc'=>$request->uc_id,
        'lot'=>$request->lot_id,
       
        ]);
      
       
        if($request->hasFile('Images')){ 
            
         
           $images_data = uploadfilesconstruction($json, $request->Images, 's_', 'social_safe_guard'); 
           
           $data = $images_data->getData();
            //   return  
               foreach($data->files as $key=>$item){
               
               SocialFile::create([
		        'social_id' => $json,
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
      

        foreach($form['sections'] as $key=>$item){
          
     
           
        foreach($item['questions'] as $ques){
           
          
   
     
        
        $answer=DB::table("social_safeguard_answer")->insert([
            "section_id"=>$item['section']['id'],
            "question_id"=>$ques['question']['id'],
            "answer" => is_array($ques['question']['answer']) ? json_encode($ques['question']['answer']) : $ques['question']['answer'],
            "user_id"=>$request->user_id,
            "ref_no"=>$request->ref_no,
            "type"=>$ques['question']['type'],
            "social_safeguard_json_id"=>$json     
        ]);
          
        
    }
        }
      
            
            

          
     
           
            
          
    
            if($answer){
                 return response()->json(['success'=>"Social Safeguards Screening Checklist Form Submit Successfully!"],200);
            }else{
                 return response()->json(['error'=>"Some Error Found Data Not Uploaded"],400);
            }     
                        
        
        
}    
public function reject_form_upload(Request $request){

        
        if(!isset($request->form_id)  || !isset($request->user_id) || !isset($request->ref_no) || !isset($request->form_data)){
            return response()->json(['error'=>"validation error all fields are required!"],400);
        }  
            

        
        $json = DB::table("social_safeguard_json")->where('unique_name_of_vrc',$request->ref_no)->update([
                "form_id"=>$request->form_id,
                "mobile_version"=>$request->mobile_version ?? null,
                "user_id"=>$request->user_id,
                "unique_name_of_vrc"=>$request->ref_no,
                "json"=> $request->form_data,
                'role_id'=>34,
                'role_name'=>'IP',
                'parseImages'=>$request->parseImages,
                'district'=>$request->district_id,
                'tehsil'=>$request->tehsil_id,
                'uc'=>$request->uc_id,
                'lot'=>$request->lot_id
            ]);
            
            
        if($request->hasFile('Images')){     
           $images_data = uploadfilesconstruction($json, $request->Images, 's_', 'social_safe_guard');  
           $data = $images_data->getData();
        
               foreach($data->files as $key=>$item){
               
               SocialFile::create([
    	        'social_id' => $json,
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
     
        DB::table("social_safeguard_answer")->where('ref_no',$request->ref_no)->delete();
       
        foreach($form['sections'] as $item){
           
        foreach($item['questions'] as $ques){
            

        
        $answer=DB::table("social_safeguard_answer")->insert([
            "section_id"=>$item['section']['id'],
            "question_id"=>$ques['question']['id'],
            "answer" => is_array($ques['question']['answer']) ? json_encode($ques['question']['answer']) : $ques['question']['answer'],
            "user_id"=>$request->user_id,
            "ref_no"=>$request->ref_no,
            "type"=>$ques['question']['type'],
            "social_safeguard_json_id"=>$json     
        ]);
    }
        }
   
          
          
         
            
          
    
            if($answer){
                 return response()->json(['success'=>"Gender Safeguards Screening Checklist Form update Successfully!"],200);
            }else{
                 return response()->json(['error'=>"Some Error Found Data Not update"],400);
            }     
                        
        
        
}    
  



public function reject_data(Request $request)
{
    if (!isset($request->user_id)) {
        return response()->json(['error' => "Validation error: user ID is required"], 400);
    }
  

    $data = DB::table('social_safeguard_json')
        ->join('districts','districts.id','=','social_safeguard_json.district')
        ->join('tehsil','tehsil.id','=','social_safeguard_json.tehsil')
        ->join('uc','uc.id','=','social_safeguard_json.uc')
        ->join('lots','lots.id','=','social_safeguard_json.lot')
        ->join('users','users.id','=','social_safeguard_json.user_id')
        ->join('form','form.id','=','social_safeguard_json.form_id')
        ->select('districts.name as district',
                    'social_safeguard_json.json as form_data',
                    'tehsil.name as tehsil',
                    'uc.name as uc',
                    'lots.name as lot',
                    'social_safeguard_json.unique_name_of_vrc',
                    'users.name as role_name',
                    'form.name as form_name',
                    'social_safeguard_json.id as primaryId',
                    'social_safeguard_json.updated_at as rejected_at',
                    'social_safeguard_json.created_at as created_at',
                    'social_safeguard_json.parseImages',
                    'social_safeguard_json.form_id',
                    'districts.id as district_id',
                    'tehsil.id as tehsil_id',
                    'uc.id as uc_id',
                     'lots.id as lot_id',
                    DB::raw("'rejected' as type")
            )
        ->where('user_id', $request->user_id)
        ->where('role_id', 27)
        ->where('form_id',44)
        ->where('social_safeguard_json.status','!=', 'CR')
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
  

    $data = SocialSafeguard
        ::join('districts','districts.id','=','social_safeguard_json.district')
        ->join('tehsil','tehsil.id','=','social_safeguard_json.tehsil')
        ->join('uc','uc.id','=','social_safeguard_json.uc')
        ->join('lots','lots.id','=','social_safeguard_json.lot')
        ->join('users','users.id','=','social_safeguard_json.user_id')
        ->join('form','form.id','=','social_safeguard_json.form_id')
            ->select('districts.name as district',
                    'tehsil.name as tehsil',
                    'uc.name as uc',
                    'lots.name as lot',
                    'social_safeguard_json.unique_name_of_vrc',
                    'users.name as role_name',
                    'form.name as form_name',
                    'social_safeguard_json.id as primaryId',
                    'social_safeguard_json.created_at as created_at',
                    'social_safeguard_json.updated_at as approved_at'
                 
            )
        ->where('user_id', $request->user_id)
        ->where('form_id',44)
        ->where('role_id',63)
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

    $data = SocialSafeguard::join('districts','districts.id','=','social_safeguard_json.district')
        ->join('tehsil','tehsil.id','=','social_safeguard_json.tehsil')
        ->join('uc','uc.id','=','social_safeguard_json.uc')
        ->join('lots','lots.id','=','social_safeguard_json.lot')
        ->join('users','users.id','=','social_safeguard_json.user_id')
        ->join('form','form.id','=','social_safeguard_json.form_id')
            ->select('districts.name as district',
                   
                    'tehsil.name as tehsil',
                    'uc.name as uc',
                    'lots.name as lot',
                    'social_safeguard_json.unique_name_of_vrc',
                    'users.name as role_name',
                    'form.name as form_name',
                    'social_safeguard_json.id as primaryId',
                    'social_safeguard_json.created_at as created_at'
                 
            )
        ->where('user_id', $request->user_id)
        ->where('form_id',44)
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
       $data=DB::table('social_safeguard_json')
       ->join('form','social_safeguard_json.form_id','=','form.id')
       ->join('districts','social_safeguard_json.district','=','districts.id')
       ->join('tehsil','social_safeguard_json.tehsil','=','tehsil.id')
       ->join('uc','social_safeguard_json.uc','=','uc.id')
       ->join('lots','social_safeguard_json.lot','=','lots.id')
       ->join('users','users.id','=','social_safeguard_json.user_id')
       ->where('social_safeguard_json.is_complete',2)
       ->where('social_safeguard_json.status','R')
       ->where('social_safeguard_json.past_reject',0)
       ->where('social_safeguard_json.user_id', $user_id)
       ->where('social_safeguard_json.form_id', 45)
       ->select(
           'form.name as form_name',
            'users.name as role_name',
           'social_safeguard_json.unique_name_of_vrc',
           'districts.name as district',
           'social_safeguard_json.created_at as created_at',
           'social_safeguard_json.updated_at as rejected_at',
           'tehsil.name as tehsil',
           'uc.name as uc',
           'lots.name as lot'
       )
       ->get();
       return  $data;
   }
   public function case_close_list_mitigation(Request $request){
       if(!isset($request->user_id)){
            return response()->json(['error'=>"validation error user id requierd to get data!"],400);
       }
       $user_id=$request->user_id;
       $data=DB::table('social_safeguard_json')
       ->join('form','social_safeguard_json.form_id','=','form.id')
       ->join('districts','social_safeguard_json.district','=','districts.id')
       ->join('tehsil','social_safeguard_json.tehsil','=','tehsil.id')
       ->join('uc','social_safeguard_json.uc','=','uc.id')
       ->join('lots','social_safeguard_json.lot','=','lots.id')
       ->join('users','users.id','=','social_safeguard_json.user_id')
       ->where('social_safeguard_json.status','C')
       ->where('social_safeguard_json.user_id', $user_id)
        ->where('social_safeguard_json.form_id', 45)
       ->select(
           'form.name as form_name',
           'social_safeguard_json.unique_name_of_vrc',
           'districts.name as district',
           'tehsil.name as tehsil',
           'uc.name as uc',
           'lots.name as lot',
            'users.name as role_name',
           'social_safeguard_json.created_at as created_at',
           'social_safeguard_json.updated_at as approved_at'
       )
       ->get();
       return  $data;
   }
   public function submit_list_mitigation(Request $request){
       if(!isset($request->user_id)){
            return response()->json(['error'=>"validation error user id requierd to get data!"],400);
       }
       $user_id=$request->user_id;
       $data=DB::table('social_safeguard_json')
       ->join('form','social_safeguard_json.form_id','=','form.id')
        ->join('users','users.id','=','social_safeguard_json.user_id')
    
       ->join('districts','social_safeguard_json.district','=','districts.id')
       ->join('tehsil','social_safeguard_json.tehsil','=','tehsil.id')
       ->join('uc','social_safeguard_json.uc','=','uc.id')
       ->join('lots','social_safeguard_json.lot','=','lots.id')
       ->where('social_safeguard_json.user_id', $user_id)
       ->where('social_safeguard_json.form_id', 45)
       ->select(
           'form.name as form_name',
           'social_safeguard_json.unique_name_of_vrc',
           'social_safeguard_json.created_at as created_at',
           'districts.name as district',
            'users.name as role_name',
           'tehsil.name as tehsil',
           'uc.name as uc',
            'lots.name as lot'
       )
       ->get();
       return  $data;
   }





















}