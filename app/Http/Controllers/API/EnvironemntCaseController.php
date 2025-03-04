<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\NdmaVerification;
use App\Models\Form;
use App\Models\EnvironmentFile;
use App\Models\SurveyData;
use App\Models\Construction;
use App\Models\ConstructionFile;
use App\Models\QuestionTitle; 
use App\Models\SignUpRetrictions;

use Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use DB;

class EnvironemntCaseController extends BaseController
{

public function environment_form_upload(Request $request){  
    
           $check_case=DB::table('environment_case_json')
           ->where('form_id',$request->form_id)
           ->where('ref_no',$request->ref_no)
           ->where('is_complete',1)
           ->first();
           $pending_case=DB::table('environment_case_json')
           ->where('form_id',$request->form_id)
           ->where('ref_no',$request->ref_no)
           ->where('is_complete',0)
           ->first();
          if($check_case){
                return response()->json(['error'=>"The case of this checklist is already resolve!"],400);
          }
          if($pending_case){
                return response()->json(['error'=>"The case for this checklist has already been submitted and it is currently under review.!"],400);
          }
           
        
           $construction_json=DB::table("environment_case_json")->insertGetId([
                "user_id"=>$request->user_id,
                "ref_no"=>$request->ref_no,
                "json"=> $request->form_data,
                "lot_id"=> $request->lot_id,
                "district_id"=> $request->district_id,
                "tehsil_id"=> $request->tehsil_id,
                "uc_id"=> $request->uc_id,
                "form_id"=> $request->form_id,
                ]);
              
                
           if($request->hasFile('Images')){     
               $images_data = uploadfilesenvironment($construction_json, $request->Images, 'env_', 'environemnt');  
               $data = $images_data->getData();
               foreach($data->files as $key=>$item){
               EnvironmentFile::create([
		        'environment_case_id' => $construction_json,
		        'question_id' => $item->only_image_name,
		        'form_id' => $request->form_id,
                'name' => $item->file_name,
				'extension' => $item->extension,
				'size' => getfilesize($item->bytes),
                'created_by' => auth()->user()->id ?? 0,
				'updated_by' => auth()->user()->id ?? 0
                
            ]);
               }
         
           }
            $contructions_answer=null;
            $form= json_decode($request->form_data,true);
            $action_condition = 0;
            foreach($form['sections'] as $item){
            foreach($item['questions'] as $ques){


                $contructions_answer = DB::table("environment_answer")->insert([
                    "question_id"=>$ques['question']['id'],
                    "answer" => is_array($ques['question']['answer']) ? json_encode($ques['question']['answer']) : $ques['question']['answer'],
                    "user_id"=>$request->user_id,
                    "ref_no"=>$request->ref_no,
                    "type"=>$ques['question']['type'],
                    "environment_case_json_id" => $construction_json     
                ]);
            }
            } 
            if($contructions_answer){
                 return response()->json(['success'=>"Environment Checklist Data Uploaded Successfully"],200);
            }else{
                 return response()->json(['error'=>"some error found data not uploaded"],400);
            }     
                        
        
        
}


public function storeImages(Request $request)
    {
       /*
        $validator = Validator::make($request->all(), [ 
            //'evidence_files.*' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            "evidence_files.*" => 'file|max:2048',
        ]);
        
        if($validator->fails()){ 
			$response = [
				'success' => false,
				'message' => $validator->errors()
			];
            return response()->json($response, 401); //400, 401, 403           
        }
        */
        
        
        $input = $request->all();
        
        if($request->hasFile('Images')){ 
            
            //return uploadfilesconstruction(3, $input['Images'], 'evidence_file', 'construction_first_stage'); 
            return uploadfilesconstruction('vrc', $input['Images'], 'vrc_file', 'vrc_attendance');

        }else{
            return response()->json([
            'message' => 'Error',
            'request' => $request->all(),
            ]);
        }
        
        
        
        
    }


    public function storeImagesworking(Request $request){
        $request->validate([
            'evidence_files' => 'required|file|mimes:jpeg,png,jpg|max:2048', // Adjust file types and size as needed
        ]);
        // Access the file from the request
        $file = $request->file('evidence_files');
        
            $filefullname = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
			$filerename = "evidence_file_2_".date('YmdHis').'.'. $extension;
			$filepath = $file->storeAs('construction_first_stage', $filerename, 'public');

        $url = Storage::url($filepath);
        return response()->json([
            'message' => 'File uploaded successfully!',
            'path' => $filepath,
            'url' => $url,
        ]);
    }
   
   
   public function rejected_list(Request $request){
       if(!isset($request->user_id)){
            return response()->json(['error'=>"validation error user id requierd to get data!"],400);
       }
       $user_id=$request->user_id;
       $data=DB::table('environment_case_json')
       ->join('form','environment_case_json.form_id','=','form.id')
       ->join('survey_form','environment_case_json.ref_no','=','survey_form.ref_no')
       ->join('districts','environment_case_json.district_id','=','districts.id')
       ->join('tehsil','environment_case_json.tehsil_id','=','tehsil.id')
       ->join('uc','environment_case_json.uc_id','=','uc.id')
       ->where('environment_case_json.is_complete',2)
       ->where('environment_case_json.status','R')
       ->where('environment_case_json.past_reject',0)
       ->where('environment_case_json.user_id', $user_id)
       ->select(
           'form.name as form_name',
           'survey_form.cnic2 as cnic',
           'survey_form.beneficiary_name as beneficiary_name',
           'survey_form.village_name',
           'districts.name as district_name',
           'environment_case_json.created_at as created_at',
           'environment_case_json.updated_at as rejected_at',
           'tehsil.name as tehsil_name',
           'uc.name as uc_name',
           'environment_case_json.ref_no as ref_no'
       )
       ->get();
       return  $data;
   }
   public function approved_list(Request $request){
       if(!isset($request->user_id)){
            return response()->json(['error'=>"validation error user id requierd to get data!"],400);
       }
       $user_id=$request->user_id;
       $data=DB::table('environment_case_json')
       ->join('form','environment_case_json.form_id','=','form.id')
       ->join('survey_form','environment_case_json.ref_no','=','survey_form.ref_no')
       ->join('districts','environment_case_json.district_id','=','districts.id')
       ->join('tehsil','environment_case_json.tehsil_id','=','tehsil.id')
       ->join('uc','environment_case_json.uc_id','=','uc.id')
       ->where('environment_case_json.status','A')
       ->where('environment_case_json.user_id', $user_id)
       ->select(
           'form.name as form_name',
           'survey_form.cnic2 as cnic',
           'survey_form.beneficiary_name as beneficiary_name',
           'survey_form.village_name',
           'districts.name as district_name',
           'tehsil.name as tehsil_name',
           'uc.name as uc_name',
           'environment_case_json.ref_no as ref_no',
           'environment_case_json.created_at as created_at',
           'environment_case_json.updated_at as approved_at'
       )
       ->get();
       return  $data;
   }
   public function submit_list(Request $request){
       if(!isset($request->user_id)){
            return response()->json(['error'=>"validation error user id requierd to get data!"],400);
       }
       $user_id=$request->user_id;
       $data=DB::table('environment_case_json')
       ->join('form','environment_case_json.form_id','=','form.id')
       ->join('survey_form','environment_case_json.ref_no','=','survey_form.ref_no')
       ->join('districts','environment_case_json.district_id','=','districts.id')
       ->join('tehsil','environment_case_json.tehsil_id','=','tehsil.id')
       ->join('uc','environment_case_json.uc_id','=','uc.id')
     
       ->where('environment_case_json.user_id', $user_id)
       ->select(
           'form.name as form_name',
           'survey_form.cnic2 as cnic',
           'survey_form.beneficiary_name as beneficiary_name',
           'survey_form.village_name',
           'environment_case_json.created_at as created_at',
           'districts.name as district_name',
           'tehsil.name as tehsil_name',
           'uc.name as uc_name',
           'environment_case_json.ref_no as ref_no'
       )
       ->get();
       return  $data;
   }

    
}