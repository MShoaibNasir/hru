<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\NdmaVerification;
use App\Models\Form;
use App\Models\SurveyData;
use App\Models\QuestionTitle; 
use App\Models\VRC;

use App\Models\VrcAttendenceMain;
use Illuminate\Support\Facades\Http;

use DB;
use App\Jobs\destructureForm;
class VRCConrolller extends BaseController
{
    
    public function uploadVRC(Request $request){
     

         $validator = Validator::make($request->all(), [
            'form_data' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $name_of_vrc = null;
        $lot = null;
        $district = null;
        $coordinates = null;
        $tehsil = null;
        $uc = null;
        $no_of_village = null;
        $villiage_name = null;
        $total_beneficiaries = null;
        $vrc_members = null;
        $longitude = null;
        $images_data = null;
        
        $latitude = null;
        $altitude = null;
        $accuracy = null;
        $capture_image_top = null;
        $capture_image_notification = null;
        $vrc_committeee = []; 
        $vrc_attendence_members = [];
        $form_data=json_decode($request->form_data);
      
        $userId=$request->userId;

        foreach ($form_data->sections as $item) {
            
            
                // For the Village Reconstruction Management Committee Formation section
                if ($item->section->id == 139) {
                    
                    foreach ($item->questions as $question) {
                        // 2678
                        if ($question->question->id == 2508) {
                          
                            $name_of_vrc = $question->question->answer;
                            
                            // same name of vrc not allowed
                            $condition=DB::table('vrc_formation')->where("name_of_vrc",$name_of_vrc)->first();
                            // if($condition){
                            //     return response()->json([
                            //     'success' => true,
                            //     'message' => 'vrc name already registered!',
                                
                            //     ], 400);
                            // }
                            
                            
                         
                        }
                        
                        
                        if ($question->question->id == 2509) {
                            $lot = $question->question->answer;
                        }
                        if ($question->question->id == 2510) {
                            $district = $question->question->answer;
                        }
                        if ($question->question->id == 2511) {
                            $tehsil = $question->question->answer;
                        }
                        if ($question->question->id == 2512) {
                            $uc = $question->question->answer;
                        }
                        if ($question->question->id == 2513) {
                            $no_of_village = $question->question->answer;
                        }
                        if ($question->question->id == 277470) {
                            $villiage_name = $question->question->answer;
                        }
                        if ($question->question->id == 2514) {
                            $total_beneficiaries = $question->question->answer;
                        }
                        if ($question->question->id == 2515) {
                            $vrc_members = $question->question->answer;
                        }
                      
                        if ($question->question->id == 277294) {
                             
                            $coordinates = $question->question->answer;
                           
                            $coordinates= json_decode($coordinates); 
                            $longitude= $coordinates[0];
                            $latitude= $coordinates[1];
                            $altitude = $coordinates[2];
                            $accuracy= $coordinates[3];
                           
                        } 
                           
                        if($request->hasFile('Images')){
                            $images_data= uploadfilesconstruction(1, $request->Images, 'vrc_file', 'vrc_attendance'); 
                        }
                      
                        
                        else{
                            return response()->json(['message' => 'File Not Found!', 'response' => $request->Images]);
                        }
                   
                        
                       
                     
                      $data = $images_data->getData();
                       foreach ($data->files as $key=>$item_data) {
            
                            if($item_data->only_image_name==277471){

                                $capture_image_top=$data->files[$key]->file_name;
                            }
                            if($item_data->only_image_name==277472){
                                $capture_image_notification=$data->files[$key]->file_name;
                            }
                        
                       }
                        
                    }
                }
               
            
                // vrc committee
                 
                if ($item->section->id == 140) {
                   
                    $current_member = [];
                    foreach ($item->questions as $question) {
                       
                        if (isset($question->question->repeat_question)) {
                            if ($question->question->repeat_question == 'beneficiary_id') {
                             
                                if (!empty($current_member)) {
                                    $vrc_committeee[] = $current_member;
                                }
                                
                               
                                $current_member = [
                                    'beneficiary_id' => $question->question->answer,
                                    'name' => null,
                                    'father_name' => null,
                                    'gender' => null,
                                    'disability' => null,
                                    'cnic' => null,
                                    'mobile_no' => null,
                                    'vrc_designation' => null,
                                ];
                               
                               
                                
                            }

            
                            // Fill in the current member's data based on the repeat question type
                            if ($question->question->repeat_question == 'name') {
                                
                                $current_member['name'] = $question->question->answer;
                            }
                            if ($question->question->repeat_question == 'father_name') {
                                $current_member['father_name'] = $question->question->answer;
                            }
                            if ($question->question->repeat_question == 'gender') {
                                $current_member['gender'] = $question->question->answer;
                            }
                            if ($question->question->repeat_question == 'disability') {
                                $current_member['disability'] = $question->question->answer;
                            }
                            if ($question->question->repeat_question == 'cnic') {
                                $current_member['cnic'] = $question->question->answer;
                            }
                            if ($question->question->repeat_question == 'mobile_no') {
                                $current_member['mobile_no'] = $question->question->answer;
                            }
                            if ($question->question->repeat_question == 'vrc_designation') {
                                $current_member['vrc_designation'] = $question->question->answer;
                            }
                        }
                    }
            
                    // Push the last member if it exists
                    if (!empty($current_member)) {
                        $vrc_committeee[] = $current_member;
                    }
                }
            
       
        }
    

                                               
        // Return all committee member details
      

        
        
        $vrc=new VRC();
        $vrc->name_of_vrc=$name_of_vrc;
        $vrc->lot=$lot;
        $vrc->district=$district;
        $vrc->tehsil=$tehsil;
        $vrc->uc=$uc;
        $vrc->no_of_village=$no_of_village;
        $vrc->village_name=$villiage_name;
        $vrc->total_beneficiaries=$total_beneficiaries;
        $vrc->vrc_members=$vrc_members;
        $vrc->user_id=$userId;
        $vrc->longitude=$longitude->Longitude;
        $vrc->latitude=$latitude->Latitude;
        $vrc->altitude=$altitude->Altitude;
        $vrc->accuracy=$accuracy->Accuracy;
        $vrc->capture_image_top=$capture_image_top ?? null;
        $vrc->capture_image_notification=$capture_image_notification ?? null;
        $vrc->save();
        
      
        $vrc_josn= DB::table('vrc_json')->insert([
          'user_id'=>$userId,
          'vrc_formation_id'=>$vrc->id,
          'json'=>json_encode($form_data)
        ]);
                          


           
        
        $currentVrcId = $vrc->id;
        $check_duplications=DB::table('vrc_committee')->pluck('beneficiary_id')->toArray();
      
 
        $check_duplications_for_cnic=DB::table('vrc_committee')->pluck('cnic')->toArray();
        $duplicate_beneficiary_data=[];
        
        
        foreach($vrc_committeee as $item){

        if(in_array($item['beneficiary_id'],$check_duplications)  ||  in_array($item['cnic'],$check_duplications_for_cnic)){
              $duplicate_beneficiary_data[]=$item['beneficiary_id'];
        }
            if(count($duplicate_beneficiary_data)>0){
             
                $remove_vrc=VRC::find($currentVrcId)->delete();
                return response()->json([
                'success' => false,
                'message' => 'Duplicates Members',
                'duplicate_beneficiary' => $duplicate_beneficiary_data,
                'cnic'=>$item['cnic']
                ], 400);
            }
            else{
              
                 DB::table('vrc_committee')->insert([
                    'vrc_formation_id'=>$currentVrcId,
                    'beneficiary_id'=>$item['beneficiary_id'],
                    'name'=>$item['name'],
                    'father_name'=> $item['father_name'],
                    'gender'=> $item['gender'],
                    'disability'=> $item['disability'],
                    'cnic'=> $item['cnic'],
                    'mobile_no'=>$item['mobile_no'],
                    'vrc_designation'=> $item['vrc_designation']
                    ]); 
                    
            }    
        }
      
        


          return response()->json([
                    'success' => true,
                    'message' => 'committee is registered in the system successfully',
                ], 200);
      
        
    }
    public function uploadVrcAttendece(Request $request){
        
          $validator = Validator::make($request->all(), [
            'form_data' => 'required',
        ]);
      
       
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        
        //dd(json_decode(json_decode($request->form_data)));
        
          
        $name_of_vrc = null;
        $lot = null;
        $district = null;
        $tehsil = null;
        $uc = null;
        $no_of_village = null;
        $total_beneficiaries = null;
        $vrc_members = null;
      
        
        $name_of_vrc = null;
        $lot = null;
        $district = null;
        $tehsil = null;
        $uc = null;
        $no_of_village = null;
        $total_beneficiaries = null;
        $vrc_members = null;
        $vrc_committeee = []; 
        $vrc_attendence_members = [];
        
        
      
        
        $form_data =  json_decode(json_decode($request->form_data));
        
        $userId=$request->userId;
       
        $form_data=gettype($form_data)=='string' ? json_decode($form_data) : $form_data; 
       
       
        foreach ($form_data->sections as $item) {
          
            //   vrc attendence
                 if ($item->section->id == 162) {
                     $vrc_attendence_member = [];
                     
                    foreach ($item->vrc_attendence_sheet as $question) {
                        
                        if (isset($question->question->repeat_question)) {
                            if ($question->question->repeat_question == 'beneficiary_id') {
                             
                                if (!empty($current_member)) {
                                    $vrc_committeee[] = $current_member;
                                }
                                
                                $vrc_attendence_member = [
                                    'beneficiary_id' => $question->question->answer,
                                    'name' => null,
                                    'father_name' => null,
                                    'gender' => null,
                                    'disability' => null,
                                    'cnic' => null,
                                    'mobile_no' => null,
                                    'vrc_designation' => null,
                                    'attendance'=>null,
                                    'vrc_attendece_main_id'=>null
                                ];
                            }
                            
            
                            // Fill in the current member's data based on the repeat question type
                            if ($question->question->repeat_question == 'name') {
                                $vrc_attendence_member['name'] = $question->question->answer;
                            }
                            if ($question->question->repeat_question == 'father_name') {
                                $vrc_attendence_member['father_name'] = $question->question->answer;
                            }
                            if ($question->question->repeat_question == 'gender') {
                                $vrc_attendence_member['gender'] = $question->question->answer;
                            }
                            if ($question->question->repeat_question == 'disability') {
                                $vrc_attendence_member['disability'] = $question->question->answer;
                            }
                            if ($question->question->repeat_question == 'cnic') {
                                $vrc_attendence_member['cnic'] = $question->question->answer;
                            }
                            if ($question->question->repeat_question == 'mobile_no') {
                                $vrc_attendence_member['mobile_no'] = $question->question->answer;
                            }
                            if ($question->question->repeat_question == 'vrc_designation') {
                                $vrc_attendence_member['vrc_designation'] = $question->question->answer;
                            }
                            if ($question->question->repeat_question == 'attendance') {
                                $vrc_attendence_member['attendance'] = $question->question->answer;
                            }
                        }
                    }
                      
                   
            
                    // Push the last member if it exists
                    if (!empty($vrc_attendence_member)) {
                        $vrc_attendence_members[] = $vrc_attendence_member;
                    }
                
                    // vrc_attendence_main
                    
                    
                          
                    foreach ($item->questions as $question) {
                       
                    // 2538
                        if ($question->question->id == 2674) {
                            $name_of_event = $question->question->answer;
                           
                        
                        }
                       
                        // 2539
                        if ($question->question->id == 2675) {
                            $district = $question->question->answer;
                        }
                        // 2540
                        if ($question->question->id == 2676) {
                            $tehsil = $question->question->answer;
                        }
                        // 2541
                        if ($question->question->id == 2677) {
                            $uc = $question->question->answer;
                        }
                        // 2678
                        if ($question->question->id == 2678) {
                            $name_of_vrc = $question->question->answer;
                        
                        }
                        // 2543
                        if ($question->question->id == 2679) {
                            $venue = $question->question->answer;
                        }
                        // 2544
                        if ($question->question->id == 2680) {
                            $date = $question->question->answer;
                        }
                        // 2545
                        if ($question->question->id == 2681) {
                            $duration = $question->question->answer;
                        }
                        // 2546
                        if ($question->question->id == 2682) {
                            $responsibilities = $question->question->answer;
                        }
                    }
                       
                }
        }
     
    
        
        $vrc=DB::table('vrc_formation')->where('name_of_vrc',$name_of_vrc)->select('id')->first();
     
        

      
      if(!$vrc){
            return response()->json([
                    'success' => false,
                    'message' => 'kindly make vrc committee of this name '. $vrc_name.' then you will register attendence of this committee',
                ], 400);
      }

       
    
     
     
    
       
    
       
        $Vrc_attendence_main=new VrcAttendenceMain;
        $Vrc_attendence_main->name_of_event=$name_of_event;
        $Vrc_attendence_main->district=$district;
        $Vrc_attendence_main->tehsil=$tehsil;
        $Vrc_attendence_main->uc=$uc;
        $Vrc_attendence_main->vrc_name=$name_of_vrc;
        $Vrc_attendence_main->venue=$venue;
        $Vrc_attendence_main->durations=$duration;
        $Vrc_attendence_main->responsibilities=$responsibilities;
        $Vrc_attendence_main->vrc_formation_id=$vrc->id;
        
        $item = $request->Images;
        if($request->hasFile('Images')){
         $images_data= uploadfilesconstruction(1, $request->Images, 'vrc_file', 'vrc_attendance'); 
        }else{
            return response()->json(['message' => 'File Not Found!', 'response' => $request->Images]);
        }
        $data = $images_data->getData();
        foreach($data->images_name as $key=>$item){
            if($item==2691){
                $Vrc_attendence_main->capture_image_1=$data->files[$key]->file_name;
            }
            if($item==2692){
                $Vrc_attendence_main->capture_image_2=$data->files[$key]->file_name;
            }
            if($item==2693){
                $Vrc_attendence_main->capture_image_3=$data->files[$key]->file_name;
            }
            if($item==2694){
                $Vrc_attendence_main->capture_image_4=$data->files[$key]->file_name;
            }
            if($item==2695){
                $Vrc_attendence_main->capture_image_5=$data->files[$key]->file_name;
            }
        }
        $Vrc_attendence_main->save();
          
      foreach($vrc_attendence_members as $item){
         DB::table('vrc_attendence')->insert([
            'beneficiary_id'=>$item['beneficiary_id'],
            'name'=>$item['name'],
            'father_name'=> $item['father_name'],
            'gender'=> $item['gender'],
            'disability'=> $item['disability'],
            'cnic'=> $item['cnic'],
            'mobile_no'=>$item['mobile_no'],
            'vrc_designation'=> $item['vrc_designation'],
            'attendance'=> $item['attendance'],
            'vrc_attendece_main_id'=> $Vrc_attendence_main->id,
            ]);   
      
    }
   

          return response()->json([
                    'success' => true,
                    'message' => 'committee is registered in the system successfully',
                ], 200);
      
        
    }
    public function vrc_committee(Request $request)
{

    $validator = Validator::make($request->all(), [
        'userId' => 'required',
    ]);

    if ($validator->fails()) {
        return $this->sendError('Validation Error.', $validator->errors());       
    }

   
    $vrc_formation = DB::table('vrc_formation')
        ->where('user_id', $request->userId)
        ->get();
       
       
     
        

    $vrc_json_data = [];
    $attendence_json_data = [];
   

   
    foreach ($vrc_formation as $item) {
      
       
        $vrc_json = DB::table('vrc_json')
            ->where('vrc_formation_id', $item->id)
             ->where('user_id', $item->user_id)
            ->select('json')
            ->first();
           

        $attendence_json = DB::table('vrc_attendence_json')
            ->where('vrc_formation_id', $item->id)
            ->where('user_id', $item->user_id)
            ->select('json')
            ->latest()
            ->first();
          
           
            if(!$attendence_json){
              $attendence_json=DB::table("vrc_empty_json")->where('id',1)->first();
             
            } 
           
        if (isset($vrc_json) && isset($attendence_json)) {
            $vrc_json=json_decode($vrc_json->json);
            $attendence_json=json_decode($attendence_json->json);
            $attendence_json=  $attendence_json->sections;
            $attendence_data=null;
            foreach($attendence_json as $key=>$item){
             $attendence_data= $item;    
            }
            $required_variable='ATTENDENCE SHEET VRCs';
            $vrc_json->sections->$required_variable= $attendence_data;
            $vrc_json_data[] = ['form_data' =>$vrc_json];
            
        }
    }

 
return $vrc_json_data;

}


 public function storeImages( $request)
    {     
         
       
          $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg|max:2048', 
        ]);
        return 'ok done';
       if ($request->hasFile('file')) {
          $image = $request->file('file');
            $imageName = 'vrc_attendence'.Str::random(10). '.' .$image->getClientOriginalExtension();
            $path = $image->storeAs('VRCAttendecne', $imageName, 'public');
            return response()->json([
                'message' => 'Image uploaded successfully!',
                'path' => Storage::url($path),
            ]);
}

        // Handle case when no image is provided
        return response()->json([
            'error' => 'No image provided',
        ], 400);
    }

    
}