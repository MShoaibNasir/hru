<?php

use Illuminate\Support\Facades\DB; // Ensure you're using the correct namespace
use App\Models\ChangeBeneficiary;
use App\Models\FormStatus;
use App\Models\EnvironmentCaseJson;
use App\Models\Environment;
use App\Models\SurveyData;
use App\Models\Construction;
use App\Models\ConstructionDepartmentStatus;
use App\Models\MNE;
use App\Models\GenderSafeguard;
use App\Models\SocialSafeguard;
use App\Models\Question;
use App\Models\Option;
use App\Models\QuestionTitle;
use App\Models\Answer;
use App\Models\QuestionsAcceptReject;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use App\Models\User;
use App\Models\Role;
use App\Models\Form;
use App\Models\Complaint;
use App\Models\ComplaintFile;
use App\Models\ComplaintRemark;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


if(!function_exists('pending_route')){
function pending_route()
{
    $route=null;
    if(Auth::user()->role==1){
        $route='dammagePendingList2';
    }else if(Auth::user()->role==40){
     $route='ceo.pending.list';   
    }else{
        $route='survey.pending.form';
    }
    return $route;
}
}
if(!function_exists('form_name')){
function form_name($id)
{
    
    $form=Form::where('id',$id)->select('name')->first();
    return $form->name;

}
}



if(!function_exists('addLogs')){
function addLogs($activity, $userId,$action=null,$section=null)
{
    try {
        $result = DB::table('logs')->insert([
            'activity' => $activity,
            'user_id' => $userId,
            'action'=>$action,
            'section'=>$section
        ]);

        if (!$result) {
            throw new Exception("Failed to insert log entry.");
        }

        return $result; // true on success
    } catch (Exception $e) {
        // Handle exceptions or log the error message
        error_log($e->getMessage());
        return false; // false on failure
    }
}
}


if(!function_exists('get_trench_amount')){
function get_trench_amount($trench_no)
{
   $trech_amount=DB::table('trench_amount')->where('trenche_no',$trench_no)->select('amount')->first();
   return $trech_amount;
}
}





if(!function_exists('update_answer_finance_activities')){
function update_answer_finance_activities($id, $question_id,$answer)
{
    DB::table('finance_activities')->insert([
          "survey_id"=>$id,
          "action"=>"update_answer",
          "user_id"=>Auth::user()->id,
          "question_id"=>$question_id,
          "answer"=>$answer
        ]);
}
}
if (!function_exists("uploadfilesconstruction")) {
    function uploadfilesconstruction($id, $files, $filetitle, $path)
    {
        if($files){
        $images_name=[];    
        if(is_array($files)){
            $uploadedFiles = [];
            foreach($files as $key => $evidence_file){
			$file = $evidence_file;
			$filefullname = $file->getClientOriginalName();
		    
		    $onlyfilename = pathinfo($filefullname, PATHINFO_FILENAME);
		    $getfilename = Str::slug($onlyfilename);
		    //$getfilename = Str::before($filefullname, '.' );
			
            $extension = $file->getClientOriginalExtension();
            
            //$filerename = $image->survey_form_id.'_'.$image->section_id.'_'.$image->question_id.'_'.date('YmdHis');
            
			$filerename = $filetitle.$key."_".$id."_".$getfilename."_".date('YmdHis').'.'.$extension;
			$images_name[]=$filefullname;
			//$filepath = $file->move(public_path('uploads/'.$path), $filerename);
			//$filesize = $filepath->getSize();
			
            $filepath = $file->storeAs($path, $filerename, 'public');
			
			$uploadedFiles[] = [
                    'file_name' => $filerename,
                    'path' => $filepath,
                    'url' => Storage::url($filepath),
                    //'url2' => Storage::disk('public')->url($filepath),
                    'image_name' => $filefullname,
                    'only_image_name' => $getfilename,
                    'extension' => $extension,
                    'bytes' => Storage::disk('public')->size($filepath),
                ];

				}//loop end
				
			return response()->json([
            'message' => 'Files processed successfully!',
            'files' => $uploadedFiles,
            'images_name'=>$images_name
            ]);
				
				
        }else{
            
            $file = $files;
			$filefullname = $file->getClientOriginalName();
			$onlyfilename = pathinfo($filefullname, PATHINFO_FILENAME);
		    $getfilename = Str::slug($onlyfilename);
		    //$getfilename = Str::before($filefullname, '.' );
            $extension = $file->getClientOriginalExtension();
			$filerename = $filetitle."_".$id."_".date('YmdHis').'.'. $extension;
			//$filepath = $file->move(public_path('uploads/'.$path), $filerename);
			$filepath = $file->storeAs($path, $filerename, 'public');
            
            $uploadedFile = [
                    'file_name' => $filerename,
                    'path' => $filepath,
                    'url' => Storage::url($filepath),
                    //'url2' => Storage::disk('public')->url($filepath),
                    'image_name' => $filefullname,
                    'only_image_name' => $getfilename,
                    'extension' => $extension,
                    'bytes' => Storage::disk('public')->size($filepath),
                ];

        return response()->json([
            'message' => 'File processed successfully!',
            'files' => $uploadedFile,
            ]);
            
        }		
        }//END IF File EXIST
    }
    }
if (!function_exists("uploadfilesenvironment")) {
    function uploadfilesenvironment($id, $files, $filetitle, $path)
    {
        if($files){
        $images_name=[];    
        if(is_array($files)){
            $uploadedFiles = [];
            foreach($files as $key => $evidence_file){
			$file = $evidence_file;
			$filefullname = $file->getClientOriginalName();
		    
		    $onlyfilename = pathinfo($filefullname, PATHINFO_FILENAME);
		    $getfilename = Str::slug($onlyfilename);

            $extension = $file->getClientOriginalExtension();
            

			$filerename = $filetitle.$key."_".$id."_".$getfilename."_".date('YmdHis').'.'.$extension;
			$images_name[]=$filefullname;
	
			
            $filepath = $file->storeAs($path, $filerename, 'public');
			
			$uploadedFiles[] = [
                    'file_name' => $filerename,
                    'path' => $filepath,
                    'url' => Storage::url($filepath),
                    'image_name' => $filefullname,
                    'only_image_name' => $getfilename,
                    'extension' => $extension,
                    'bytes' => Storage::disk('public')->size($filepath),
                ];

				}//loop end
				
			return response()->json([
            'message' => 'Files processed successfully!',
            'files' => $uploadedFiles,
            'images_name'=>$images_name
            ]);
				
				
        }else{
            
            $file = $files;
			$filefullname = $file->getClientOriginalName();
			$onlyfilename = pathinfo($filefullname, PATHINFO_FILENAME);
		    $getfilename = Str::slug($onlyfilename);
		    //$getfilename = Str::before($filefullname, '.' );
            $extension = $file->getClientOriginalExtension();
			$filerename = $filetitle."_".$id."_".date('YmdHis').'.'. $extension;
			//$filepath = $file->move(public_path('uploads/'.$path), $filerename);
			$filepath = $file->storeAs($path, $filerename, 'public');
            
            $uploadedFile = [
                    'file_name' => $filerename,
                    'path' => $filepath,
                    'url' => Storage::url($filepath),
                    //'url2' => Storage::disk('public')->url($filepath),
                    'image_name' => $filefullname,
                    'only_image_name' => $getfilename,
                    'extension' => $extension,
                    'bytes' => Storage::disk('public')->size($filepath),
                ];

        return response()->json([
            'message' => 'File processed successfully!',
            'files' => $uploadedFile,
            ]);
            
        }		
        }//END IF File EXIST
    }
    }
    
    
if(!function_exists("mne_destructure")){
function mne_destructure($id){
	        $mne_answer=null;
            $mne = MNE::findOrFail($id);
            $mne_answer_exist = DB::table("mne_answer")->where('mne_json_id', $mne->id)->get();
            if($mne_answer_exist->count() > 0){
            DB::table("mne_answer")->where('mne_json_id',$mne->id)->delete();
            }

            $form = json_decode($mne->json,true);
            //dump($form);
            if(isset($form['sections'])){
            foreach($form['sections'] as $item){
            foreach($item['questions'] as $ques){

                $mne_answer = DB::table("mne_answer")->insert([
                    "section_id"=>$item['section']['id'],
                    "question_id"=>$ques['id'],
                    "answer" => is_array($ques['answer']) ? json_encode($ques['answer']) : $ques['answer'],
                    "user_id"=>$mne->user_id,
                    "ref_no"=>$mne->ref_no,
                    "type"=>$ques['type'],
                    "mne_json_id" => $mne->id     
                ]);
                
               
               //Subquestion Answer
               if($ques['options']){
                    foreach($ques['options'] as $options){
                        if($options['is_sub_section'] == 1){
                            foreach($options['subsection'][0]['questions'] as $subquestions){
                                //dump($subquestions);
                
                $mne_answer = DB::table("mne_answer")->insert([
                    "section_id"=>$subquestions['section_id'] ?? 0,
                    "question_id"=>$subquestions['id'],
                    "answer" => is_array($subquestions['answer']) ? json_encode($subquestions['answer']) : $subquestions['answer'],
                    "user_id"=>$mne->user_id,
                    "ref_no"=>$mne->ref_no,
                    "type"=>$subquestions['type'],
                    "mne_json_id" => $mne->id     
                ]);
                
                            }
                            
                        }
                    }
                }
                //Subquestion Answer End
                
            }
            } 
        
            
            $surveyform = SurveyData::where('ref_no', $mne->ref_no)->first();
            if($surveyform){
            MNE::where('id',$mne->id)->update(['survey_id' => $surveyform->id, 'lot_id' => $surveyform->lot_id,'district_id' => $surveyform->district_id,'tehsil_id' => $surveyform->tehsil_id,'uc_id' => $surveyform->uc_id]);     
            }
                
            }
 }}    
    
if(!function_exists("construction_destructure")){
function construction_destructure($id){
	        $contructions_answer=null;
            $a = 0;
            $b = 0;
            $c = 0;
            $d = null;
            $action_condition = 0;
            
            $construction = Construction::findOrFail($id);
            $contructions_answer_exist = DB::table("contructions_answer")->where('construction_json_id', $construction->id)->get();
            if($contructions_answer_exist->count() > 0){
            DB::table("contructions_answer")->where('construction_json_id',$construction->id)->delete();
            }

            $form = json_decode($construction->json,true);
            //dump($form);
            if(isset($form['sections'])){
            foreach($form['sections'] as $item){
            foreach($item['questions'] as $ques){

                $contructions_answer = DB::table("contructions_answer")->insert([
                    "question_id"=>$ques['id'],
                    "answer" => is_array($ques['answer']) ? json_encode($ques['answer']) : $ques['answer'],
                    "user_id"=>$construction->user_id,
                    "ref_no"=>$construction->ref_no,
                    "stage"=> $construction->stage,
                    "type"=>$ques['type'],
                    "construction_json_id" => $construction->id     
                ]);
                
                if($ques['id'] == 2550){
                   if($ques['answer'] == 'Yes'){$a = 1;}else{$a = 0;} 
                }
                if($ques['id'] == 2552){
                    if($ques['answer'] == 'Yes'){$b = 1;}else{$b = 0;}
                }
                if($ques['id'] == 2554){
                    if($ques['answer'] == 'Yes'){$c = 1;}else{$c = 0;}
                }
                
                if($ques['id'] == 2571){
                    if($ques['answer']){$d = $ques['answer'];}else{$d = null;}
                }
                
               
               
               //Subquestion Answer
               if($ques['options']){
                    foreach($ques['options'] as $options){
                        if($options['is_sub_section'] == 1){
                            foreach($options['subsection'][0]['questions'] as $subquestions){
                                //dump($subquestions);
                
                $contructions_answer = DB::table("contructions_answer")->insert([
                    "question_id"=>$subquestions['id'],
                    "answer" => is_array($subquestions['answer']) ? json_encode($subquestions['answer']) : $subquestions['answer'],
                    "user_id"=>$construction->user_id,
                    "ref_no"=>$construction->ref_no,
                    "stage"=> $construction->stage,
                    "type"=>$subquestions['type'],
                    "construction_json_id" => $construction->id     
                ]);
                
                            }
                            
                        }
                    }
                }
                //Subquestion Answer End
                
            }
            } 
        
            $action_condition = $a + $b + $c;   
            $surveyform = SurveyData::where('ref_no', $construction->ref_no)->first();
            if($surveyform){
            Construction::where('id',$construction->id)->update(['survey_id' => $surveyform->id, 'lot_id' => $surveyform->lot_id,'district_id' => $surveyform->district_id,'tehsil_id' => $surveyform->tehsil_id,'uc_id' => $surveyform->uc_id, 'action_condition'=> $action_condition, 'ref_number'=> $d]);     
            }
                
            }
 }} 



if(!function_exists("update_question_answer")){
function update_question_answer($sid, $qid, $answer){ 
 $ans_exist = DB::table('answers')->where('question_id',$qid)->where('survey_form_id',$sid)->select('id')->first();
 if($ans_exist){
 return $update_ans = DB::table('answers')->where('question_id', $qid)->where('survey_form_id', $sid)->update(['answer' => $answer]);
 }else{return ''; }
}}
 
if(!function_exists("update_changebeneficiary_status")){
function update_changebeneficiary_status($cb_id,$role_id,$status,$last_action){
$role = Role::findORFail($role_id);
$changebeneficiary = ChangeBeneficiary::findORFail($cb_id);
$changebeneficiary->role_id = $role->id;
$changebeneficiary->role_name = $role->name;
$changebeneficiary->status = $status;
$changebeneficiary->last_action = $last_action;
$changebeneficiary->last_action_role_id = Auth::user()->role;
$changebeneficiary->action_by = Auth::user()->id;
$changebeneficiary->action_date = Carbon::now()->toDateTimeString(); 
$changebeneficiary->save();

if($status == 'C' && $role->id == 48){
//$surveyform = SurveyData::findORFail($changebeneficiary->survey_id);



}//Complete Action

}}


if(!function_exists("update_mne_status")){
function update_mne_status($mne_id,$role_id,$status,$last_action){
$role = Role::findORFail($role_id);
$mne = MNE::findORFail($mne_id);
$mne->role_id = $role->id;
$mne->role_name = $role->name;
$mne->status = $status;
$mne->last_action = $last_action;
$mne->last_action_role_id = Auth::user()->role;
$mne->action_by = Auth::user()->id;
$mne->action_date = Carbon::now()->toDateTimeString(); 
$mne->save();

if($status == 'C' && $role->id == 51){
//$surveyform = SurveyData::findORFail($mne->survey_id);
}//Complete Action

}}

if(!function_exists("update_construction_departmentwise_status")){
function update_construction_departmentwise_status($construction_id, $role_id, $status, $comment){
    // Retrieve the role
    $role = Role::findOrFail($role_id);
    $construction = Construction::findOrFail($construction_id);
    
    
    // Check if the status record already exists
    $departmentwise_status_exist = ConstructionDepartmentStatus::where('construction_id', $construction_id)->where('role_id', $role->id)->first();
    $action_by = Auth::check() ? Auth::id() : null;
    
    
    if ($departmentwise_status_exist) {
        $departmentwise_status_exist->update(['status' => $status, 'action_by' => $action_by, 'comment' => $comment]);
    } else {
        ConstructionDepartmentStatus::create([
            'construction_id' => $construction_id,
            'ref_no' => $construction->ref_no,
            'stage' => $construction->stage,
            'role_id'         => $role->id,
            'role_name'       => $role->name,
            'status'          => $status,
            'action_by'       => $status === 'P' ? null : ($action_by ?? null),
            'comment'         => $status === 'P' ? null : ($comment ?? null)
        ]);
    }
}}

 

if(!function_exists("update_construction_status")){
    function update_construction_status($construction_id, $role_id, $status, $last_action) {
        $role = Role::findOrFail($role_id);
        $construction = Construction::findOrFail($construction_id);
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Update Construction
        $construction->update([
            'role_id' => $role->id,
            'role_name' => $role->name,
            'status' => $status,
            'last_action' => $last_action,
            'last_action_role_id' => $user->role,
            'action_by' => $user->id,
            'action_date' => Carbon::now()->toDateTimeString()
        ]);

        // Reject Condition
        if ($status == 'R' && $role->id == 27) {
            if ($construction->action_condition == 3) {
                $stages = [
                    'Stage 2' => 'Stage 1',
                    'Stage 3' => 'Stage 2',
                    'Stage 4' => 'Stage 3',
                ];
                $updateValue = $stages[$construction->stage] ?? null;

                if ($updateValue) {
                    DB::table('ndma_verifications')->where('b_reference_number', $construction->ref_no)->update(['construction_condition' => $updateValue]);
                }
            }
        }

        // Completion Condition
        if ($status == 'C' && $role->id == 48) {
            $stage = $construction->stage;

            $stages = [
                'Stage 1' => ['trench_no' => 2, 'trench_action' => 'move_to_second_trench', 'stage_status' => 'Stage 1'],
                'Stage 2' => ['trench_no' => 3, 'trench_action' => 'move_to_third_trench', 'stage_status' => 'Stage 2'],
                'Stage 3' => ['trench_no' => 4, 'trench_action' => 'move_to_fourth_trench', 'stage_status' => 'Stage 3'],
                'Stage 4' => ['trench_no' => 5, 'trench_action' => 'move_to_fivth_trench', 'stage_status' => 'Stage 4'],
            ];

            if (!isset($stages[$stage])) {
                return response()->json(['error' => 'Invalid stage'], 400);
            }

            $trench_no = $stages[$stage]['trench_no'];
            $trench_action = $stages[$stage]['trench_action'];
            $stage_status = $stages[$stage]['stage_status'];

            $surveyform = SurveyData::where('ref_no', $construction->ref_no)->first();
            if (!$surveyform) {
                return response()->json(['error' => 'Survey data not found'], 404);
            }

            DB::table('verify_beneficairy')->insert([
                'survey_id' => $surveyform->id,
                'construction_id' => $construction->id,
                'ref_no' => $construction->ref_no,
                'type' => 'verify',
                'trench_no' => $trench_no
            ]);

            $trench_history_lastInsertId = DB::table('trench_history')->insertGetId([
                'action_by' => $user->id,
                'trench_level' => $trench_no,
                'ref_no' => $construction->ref_no,
                'amount' => get_trench_amount($trench_no)->amount,
            ]);

            DB::table('finance_activities')->insert([
                'survey_id' => $surveyform->id,
                'ref_no' => $construction->ref_no,
                'action' => $trench_action,
                'user_id' => $user->id,
                'table_name' => 'trench_history',
                'primary_id' => $trench_history_lastInsertId
            ]);

            DB::table('ndma_verifications')->where('b_reference_number', $construction->ref_no)->update(['stage_status' => $stage_status]);
        }
}}


 
    
if(!function_exists("update_construction_status_old")){
function update_construction_status_old($construction_id,$role_id,$status,$last_action){
    
$role = Role::findORFail($role_id);
$construction = Construction::findORFail($construction_id);
$construction->role_id = $role->id;
$construction->role_name = $role->name;
$construction->status = $status;
$construction->last_action = $last_action;
$construction->last_action_role_id = Auth::user()->role;
$construction->action_by = Auth::user()->id;
$construction->action_date = Carbon::now()->toDateTimeString();
$construction->save();



if ($status == 'R' && isset($role->id) && $role->id == 27) {
    $construction = Construction::find($construction_id);
    if (!$construction) {
        return response()->json(['error' => 'Construction not found'], 404);
    }

    if ($construction->action_condition == 3) {
        $updateValue = null;
        if ($construction->stage == 'Stage 2') {$updateValue = 'Stage 1';} elseif ($construction->stage == 'Stage 3') {$updateValue = 'Stage 2';}
        DB::table('ndma_verifications')->where('b_reference_number', $construction->ref_no)->update(['construction_condition' => $updateValue]);
    }
}//For Reject



if ($status == 'C' && isset($role->id) && $role->id == 48) {  
    $construction = Construction::find($construction_id);
    if (!$construction) {
        return response()->json(['error' => 'Construction not found'], 404);
    }
    $stage = $construction->stage;

    // Define stage mappings to avoid repetitive conditions
    $stages = [
        'Stage 1' => ['trench_no' => 2, 'trench_action' => 'move_to_second_trench', 'stage_status' => 'Stage 2'],
        'Stage 2' => ['trench_no' => 3, 'trench_action' => 'move_to_third_trench', 'stage_status' => 'Stage 3'],
        'Stage 3' => ['trench_no' => 4, 'trench_action' => 'move_to_fourth_trench', 'stage_status' => 'Stage 4'],
    ];

    if (isset($stages[$stage])) {
        $trench_no = $stages[$stage]['trench_no'];
        $trench_action = $stages[$stage]['trench_action'];
        $stage_status = $stages[$stage]['stage_status'];

        // Fetch survey form
        $surveyform = SurveyData::where('ref_no', $construction->ref_no)->first();
        if (!$surveyform) {
            return response()->json(['error' => 'Survey data not found'], 404);
        }

        // Insert into verify_beneficairy
        DB::table('verify_beneficairy')->insert([
            'survey_id' => $surveyform->id,
            'construction_id' => $construction->id,
            'ref_no' => $construction->ref_no,
            'type' => 'verify',
            'account_number' => '',
            'bank_name' => '',
            'branch_name' => '',
            'bank_address' => '',
            'trench_no' => $trench_no
        ]);

        // Insert into trench_history
        $trench_history_lastInsertId = DB::table('trench_history')->insertGetId([
            'action_by' => Auth::user()->id ?? '',
            'trench_level' => $trench_no,
            'ref_no' => $construction->ref_no,
            'amount' => get_trench_amount($trench_no)->amount,
        ]);

        // Insert into finance_activities
        DB::table('finance_activities')->insert([
            'survey_id' => $surveyform->id,
            'ref_no' => $construction->ref_no,
            'action' => $trench_action,
            'user_id' => Auth::user()->id ?? '',
            'table_name' => 'trench_history',
            'primary_id' => $trench_history_lastInsertId
        ]);

        // Update ndma_verifications
        DB::table('ndma_verifications')->where('b_reference_number', $construction->ref_no)->update(['stage_status' => $stage_status]);
    }
}
//For Complete





}}     



if(!function_exists("update_gender_status")){
    
function update_gender_status($construction_id,$role_id,$status){
$role = Role::findORFail($role_id);

$construction = GenderSafeguard::findORFail($construction_id);
$construction->role_id = $role->id;
$construction->role_name = $role->name;
$construction->status = $status;
$construction->action_by = Auth::user()->id;
$construction->action_date = Carbon::now()->toDateTimeString(); 

if ($status == 'A') {
    $construction->is_complete = 1;
} elseif ($status == 'R') {
    $form_id=$construction->form_id;
    $ref_no=$construction->unique_name_of_vrc;
    $last_case=GenderSafeguard::where('form_id',$form_id)
    ->where('unique_name_of_vrc',$ref_no)
    ->where('status','R')->first();
    if($last_case){
    $last_case->past_reject=1;
    $last_case->save();
    }
    $construction->is_complete = 2;
} else {
    $construction->is_complete = 0;
}

$construction->save();



}}     
if(!function_exists("update_environment_status")){
    
function update_environment_status($construction_id,$role_id,$status){
$role = Role::findORFail($role_id);
$construction = Environment::findORFail($construction_id);
$construction->role_id = $role->id;
$construction->role_name = $role->name;
$construction->status = $status;
$construction->action_by = Auth::user()->id;
$construction->action_date = Carbon::now()->toDateTimeString(); 
$construction->save();

}}     
if(!function_exists("update_social_status")){
    
function update_social_status($construction_id,$role_id,$status){
$role = Role::findORFail($role_id);

$construction = SocialSafeguard::findORFail($construction_id);
$construction->role_id = $role->id;
$construction->role_name = $role->name;
$construction->status = $status;
$construction->action_by = Auth::user()->id;
$construction->action_date = Carbon::now()->toDateTimeString(); 

if ($status == 'A') {
    $construction->is_complete = 1;
} elseif ($status == 'R') {
    $form_id=$construction->form_id;
    $ref_no=$construction->unique_name_of_vrc;
    $last_case=SocialSafeguard::where('form_id',$form_id)
    ->where('unique_name_of_vrc',$ref_no)
    ->where('status','R')->first();
    if($last_case){
    $last_case->past_reject=1;
    $last_case->save();
    }
    $construction->is_complete = 2;
} else {
    $construction->is_complete = 0;
}
$construction->save();

// if($status == 'C' && $role->id == 48){
// $construction = GenderSafeguard::findORFail($construction_id);
  


// }

}}     



if(!function_exists("update_environment_case_status")){
    
function update_environment_case_status($construction_id,$role_id,$status){
$role = Role::findORFail($role_id);
$construction = EnvironmentCaseJson::findORFail($construction_id);
$construction->role_id = $role->id;
$construction->role_name = $role->name;
$construction->status = $status;
if ($status == 'A') {
    
    $construction->is_complete = 1;
} elseif ($status == 'R') {
    $form_id=$construction->form_id;
    $ref_no=$construction->ref_no;
    $last_case=EnvironmentCaseJson::where('form_id',$form_id)->where('ref_no',$ref_no)->where('status','R')->first();
    if($last_case){
    $last_case->past_reject=1;
    $last_case->save();
    }
    $construction->is_complete = 2;
} else {
    $construction->is_complete = 0;
}

$construction->action_by = Auth::user()->id;
$construction->action_date = Carbon::now()->toDateTimeString(); 
$construction->save();



}}     
    
    
    
    
    if (!function_exists("uploadfilesglobally")){
    function uploadfilesglobally($id, $files, $filetitle, $path)
    {
        if($files){
        $images_name=[];    
        if(is_array($files)){
            $uploadedFiles = [];
            foreach($files as $key => $evidence_file){
			$file = $evidence_file;
			
			$mimeType = $file->getClientMimeType();
			$getfileSize = $file->getSize();
			$width = null;
            $height = null;
            if(str_starts_with($mimeType, 'image/')) {
            $pathname = $file->getPathname();
            $imageInfo = getimagesize($pathname);
            if($imageInfo) {
                $width = $imageInfo[0];  // Image width
                $height = $imageInfo[1]; // Image height
            }
            }
            
			$filefullname = $file->getClientOriginalName();
		    
		    $onlyfilename = pathinfo($filefullname, PATHINFO_FILENAME);
		    $getfilename = Str::slug($onlyfilename);
		    //$getfilename = Str::before($filefullname, '.' );
			
            $extension = $file->getClientOriginalExtension();
            
            //$filerename = $image->survey_form_id.'_'.$image->section_id.'_'.$image->question_id.'_'.date('YmdHis');
			$filerename = $filetitle.$key."_".$id."_".$getfilename."_".date('YmdHis').'.'.$extension;
			$filepath = $file->move(public_path('uploads/'.$path), $filerename);
			//$filesize = $filepath->getSize();
			
            //$filepath = $file->storeAs($path, $filerename, 'public'); //If Storage Save in storage folder
			
			$uploadedFiles[] = [
                    'file_name' => $filerename,
                    //'path' => $filepath, If Storage
                    //'url' => Storage::url($filepath), If Storage
                    //'url2' => Storage::disk('public')->url($filepath),
                    'image_name' => $filefullname,
                    'only_image_name' => $getfilename,
                    'mime_type' => $mimeType,
                    'width' => $width,
                    'height' => $height,
                    'extension' => $extension,
                    'bytes' => $getfileSize, //Storage::disk('public')->size($filepath),
                ];

				}//loop end
				
			return response()->json([
            'message' => 'Files processed successfully!',
            'files' => $uploadedFiles,
            'images_name'=>$images_name
            ]);
				
				
        }else{
            
            $file = $files;
            
            $mimeType = $file->getClientMimeType();
            $getfileSize = $file->getSize();
            $width = null;
            $height = null;
            if(str_starts_with($mimeType, 'image/')) {
            $pathname = $file->getPathname();
            $imageInfo = getimagesize($pathname);
            if($imageInfo) {
                $width = $imageInfo[0];  // Image width
                $height = $imageInfo[1]; // Image height
            }
            }
            
			$filefullname = $file->getClientOriginalName();
			$onlyfilename = pathinfo($filefullname, PATHINFO_FILENAME);
		    $getfilename = Str::slug($onlyfilename);
		    //$getfilename = Str::before($filefullname, '.' );
            $extension = $file->getClientOriginalExtension();
			$filerename = $filetitle."_".$id."_".date('YmdHis').'.'. $extension;
			
			$filepath = $file->move(public_path('uploads/'.$path), $filerename);
			//$filepath = $file->storeAs($path, $filerename, 'public'); //If Storage Save in storage folder
            
            $uploadedFile = [
                    'file_name' => $filerename,
                    //'path' => $filepath, //If Storage
                    //'url' => Storage::url($filepath), //If Storage
                    //'url2' => Storage::disk('public')->url($filepath),
                    'image_name' => $filefullname,
                    'only_image_name' => $getfilename,
                    'mime_type' => $mimeType,
                    'width' => $width,
                    'height' => $height,
                    'extension' => $extension,
                    'bytes' => $getfileSize,  //Storage::disk('public')->size($filepath), //If Storage

                ];

        return response()->json([
            'message' => 'File processed successfully!',
            'files' => $uploadedFile,
            ]);
            
        }		
        }//END IF File EXIST
    }
    }
    
    if(!function_exists("uploadfilechangebeneficiary")){
    function uploadfilechangebeneficiary($id, $files, $filetitle, $path, $cbid){
        if($files){
            $file = $files;
            $mimeType = $file->getClientMimeType();
            $getfileSize = $file->getSize();
            $width = null;
            $height = null;
            if(str_starts_with($mimeType, 'image/')) {
            $pathname = $file->getPathname();
            $imageInfo = getimagesize($pathname);
            if($imageInfo) {
                $width = $imageInfo[0];  // Image width
                $height = $imageInfo[1]; // Image height
            }
            }
            
			$filefullname = $file->getClientOriginalName();
			$onlyfilename = pathinfo($filefullname, PATHINFO_FILENAME);
		    $getfilename = Str::slug($onlyfilename);
            $extension = $file->getClientOriginalExtension();
			$filerename = $filetitle."_".$id."_".date('YmdHis').'.'. $extension;
			$filepath = $file->move(public_path('uploads/'.$path), $filerename);

                          $uploadedFile = [
                            "question_id" => $id,
                            "image" => [
                            "cropRect" => [
                            "width" => $width,
                            "y" => 0,
                            "height" => $height,
                            "x" => 0,
                            ],
                            "modificationDate" => date('YmdHis'),
                            "size" => $getfileSize,
                            "mime" => $mimeType,
                            "exif" => "",
                            "height" => $height,
                            "width" => $width,
                            "path" => $filerename,
                            "base64" => "",
                            ],
                            "fetchLocation" => []
                            
                            ];

                $jsondata = json_encode($uploadedFile);
                $changebeneficiary = ChangeBeneficiary::findOrFail($cbid);
                $changebeneficiary->cnic_front = $jsondata;
                $changebeneficiary->save();

                DB::table('change_beneficiary_files')->insert([
                    'survey_id' => $changebeneficiary->survey_id,
                    'ref_no' => $changebeneficiary->ref_no,
                    'cb_id' => $changebeneficiary->id,
                    'question_id' => $id,
                    'filename' => $filerename,
                    'originalname' => $filefullname,
                    'extension' => $extension,
                    'size' => getfilesize($getfileSize),
                    'mime' => $mimeType,
                    'width' => $width,
                    'height' => $height,
                    'created_by' => auth()->user()->id
                ]);
                

        return response()->json([
            'message' => 'File processed successfully!',
            'files' => $uploadedFile,
            ]);
            
        }//END IF File EXIST
    }
    }
    
     if (!function_exists("getfilesize")) {
    function getfilesize($bytes)
    {
		    if($bytes >= 1073741824){$file_size = number_format($bytes / 1073741824, 2) . ' GB';}
			elseif($bytes >= 1048576){$file_size  = number_format($bytes / 1048576, 2) . ' MB';}
			elseif($bytes >= 1024){$file_size  = number_format($bytes / 1024, 2) . ' KB';}
			elseif($bytes > 1){$file_size  = $bytes . ' bytes';}
			elseif($bytes == 1){$file_size  = $bytes . ' byte';}
			else{$file_size  = '0 bytes';}
			return $file_size;
    }}
if (!function_exists("get_question_location")) {
    function get_question_location($sfid, $qid)
    {
        
        $question_location = Answer::where('survey_form_id', $sfid)->where('question_id', $qid)->first();
        
      
      
       $html = '<p class="text text-danger">Location Not Available</p>';
        if ($question_location) {
               
            if (isset($question_location->answer)) {
                $locations_array = json_decode($question_location->answer);
                
              
         
                if (isset($locations_array->fatchLocation)) {
                    $data = (array) $locations_array->fatchLocation;
                    $data = array_slice($data, 3, null, true); // Slice as needed

                
                    
                    // Start HTML string
                    $html = '';

                    if (!empty($data)) {
                        
                        foreach ($data as $key => $item) {
                            if($key!='accuracy'){
                            $html .= '<h5>' . ucfirst($key) . ':-</h5><span>' . htmlspecialchars($item) . '</span>';
                                
                            }
                        }
                    } else {
                        // If no location data is available, return a message
                        $html = '<p>Location Not Available</p>';
                    }

                    return $html;
                    
                }
            }
        }

         return $html; // If no question location is found or data is missing
         
    }
}

if(!function_exists('saveImageLocation')){
function saveImageLocation()
{
   try {
        // Validate the uploaded file
        $request->validate([
            'media_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Generate a unique filename and move the file
        $imageName = 'media_file_' . time() . '.' . $request->media_file->extension();
        $request->media_file->move(public_path('uploads/exifimgs'), $imageName);

        $fullPath = public_path('uploads/exifimgs/' . $imageName);
        

        // Dynamic latitude and longitude
        $latitude = $request->latitude ?? '24.8287213';
        $longitude = $request->longitude ?? '67.0704709';

        // Check if ExifTool is installed
        $exifToolPath = '/bin/exiftool'; // Update this path if necessary
        $process = new Process(['which', 'exiftool']);
        $process->run();

        if (!$process->isSuccessful()) {
            return redirect()->back()->with('error', 'ExifTool is not installed or accessible.');
        }

        // Using Symfony Process to run ExifTool and add GPS data
        $command = [$exifToolPath, "-GPSLatitude=$latitude", "-GPSLatitudeRef=N", "-GPSLongitude=$longitude", "-GPSLongitudeRef=E", $fullPath];
        // $command = "$exifToolPath -GPSLatitude=$latitude -GPSLatitudeRef=N -GPSLongitude=$longitude -GPSLongitudeRef=E '$fullPath'";
      
        $process = new Process($command);
        $process->run();
         
        if (!$process->isSuccessful()) {
            // Handle the error if exiftool fails
            return redirect()->back()->with('error', 'Failed to add GPS data. Command output: ' . $process->getErrorOutput());
        }

        // Optionally, remove the original file created by ExifTool
        $removeBackupProcess = new Process(["rm", "$fullPath" . "_original"]);
        $removeBackupProcess->run();

        if (!$removeBackupProcess->isSuccessful()) {
            // Handle the error if backup removal fails
            Log::error('Failed to remove the original backup file', ['output' => $removeBackupProcess->getErrorOutput()]);
        }

        // Return success message
        return redirect()->route('manager.media.index')->with('success', 'Media file uploaded and geotagged successfully.');

    } catch (ProcessFailedException $e) {
        Log::error('Error in media file upload and geotagging', ['exception' => $e]);
        return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.');
    }
}
}


if (!function_exists("allow_access")) {
    function allow_access($current_user_id)
    {
        $allow_access = DB::table("users")
            ->join("roles", "users.role", "=", "roles.id")
            ->where("users.id", "=", $current_user_id)
            ->first();
        return $allow_access;
    }
    }
if (!function_exists("get_rejected_name")) {
    function get_rejected_name()
    {
        $name=null;
        
        // field supervisor
        if(Auth::user()->role=='30'){
          $name='Rejected by IP';    
        }
        else if(Auth::user()->role=='34'){
             $name='Rejected by DRC'; 
        }
        else if(Auth::user()->role=='36'){
             $name='Rejected by QA'; 
        }
        else if(Auth::user()->role=='37'){
             $name='Rejected by Selection Committee'; 
        }
        else if(Auth::user()->role=='38'){
             $name='Rejected by CEO'; 
        }
        else if(Auth::user()->role=='39'){
             $name='Rejected by CEO'; 
        }
        else if(Auth::user()->role=='40'){
             $name='Rejected by Finance'; 
        }
      
        return $name;
    }
    }


    if (!function_exists("hideReject")) {
        function hideReject($survey_form_id, $question_id)
        {
            
          
            if(Auth::user()->role==1){
                return 'hide functionality';
            }
                $data_found = QuestionsAcceptReject::where('survey_id',$survey_form_id)->where('ques_id',$question_id)->where('decision', 'reject')->select('id','created_role','comment')->first();
                
                if($data_found){
                    return true;
                }else{
                    return false;
                }
             
        }
    }
    if (!function_exists("hideRejectSecond")) {
        function hideRejectSecond()
        {
            if(Auth::user()->role==1){
                return false;
            }else{
                return true;
            }
        }
    }
    if (!function_exists("monitoring_evaluation")) {
        function monitoring_evaluation()
        {
            if(Auth::user()->role==51){
                return true;
            }else{
                return false;
            }
        }
    }
    
    if (!function_exists("is_hru_main")) {
        function is_hru_main()
        {
            if(Auth::user()->role==38){
                return true;
            }else{
                return false;
            }
        }
    } 
    
    
    if (!function_exists("is_monitoring_evaluation")) {
        function is_monitoring_evaluation($survey_id, $question_id)
        {
          return QuestionsAcceptReject::where('survey_id',$survey_id)->where('ques_id',$question_id)->where('decision', 'comment')->select('id','created_role','comment')->first();
        }
    }
    if (!function_exists("is_question_reject")) {
        function is_question_reject($survey_id, $question_id)
        {
          return QuestionsAcceptReject::where('survey_id',$survey_id)->where('ques_id',$question_id)->where('decision', 'reject')->select('id','created_role','comment')->first();
        }
    }
    
    
    if (!function_exists("singleReporting")) {
        function singleReporting($districtID, $status)
        {
                $reporting=DB::table('master_report')->where('district_id',$districtID)->where("role",$status)->count();
                return $reporting;
        }
    }


if(!function_exists("manage_report")){
  function manage_report($survey_form_id,$status,$last_status=null,$new_status=null,$current_user=null,$last_action_user_id=null)
{
        $data_found = DB::table("master_report")->where('survey_id', $survey_form_id)->first();
        if ($data_found) {
             $update_master_report= DB::table('master_report')
            ->where('survey_id', $survey_form_id)
            ->update(['role' => $status,'last_status'=>$last_status,'new_status'=>$new_status,'last_action_user_id'=>$last_action_user_id,'user_id'=>$current_user]);
            if($update_master_report){
                 DB::table('master_report_detail')
                ->insert([
                'survey_id' => $survey_form_id,
                'lot_id'=>$data_found->lot_id,
                'district_id'=>$data_found->district_id,
                'tehsil_id'=>$data_found->tehsil_id,
                'uc_id'=>$data_found->uc_id,
                'role'=>$status,
                'user_id'=>$current_user,
                'maaster_report_id'=>$data_found->id,
                'form_type'=>$data_found->form_type,
                'form_id'=>$data_found->form_id,
                'last_status'=>$last_status,
                'new_status'=>$new_status,
                'last_action_user_id'=>$last_action_user_id
                ]);
            }else{
                return false;
            }    
           
          
        }
        else{
            return false;
        }
        
        
     
        
     
}
}
if(!function_exists("update_suvrey_form_for_reporting")){
  function update_suvrey_form_for_reporting($m_role_id,$m_last_action,$m_last_action_role_id,$m_last_action_user_id,$survey_id,$status)
    {
           
       DB::table('survey_form')->where('id',$survey_id)->update([
           'm_status'=> $status,  
           'm_role_id'=> $m_role_id,  
           'm_last_action'=> $m_last_action,  
           'm_last_action_role_id'=> $m_last_action_role_id,  
           'm_last_action_user_id'=> $m_last_action_user_id, 
           'm_last_action_date'=> Carbon::now()->toDateTimeString(),
           
        ]);
    }
}



if(!function_exists("save_report_history")){
  function save_report_history($data)
{
    
   $required_data=DB::table('form_action_histories')->where('survey_id',$data->survey_id)->where("form_id",$data->form_id)->latest();        
   $form_action_histories=DB::table('form_action_histories')->insert([
       "survey_id"=>$data->survey_id,
       "form_id"=>$data->form_id,
       "last_status"=>$data->last_status,
       "new_status"=>$data->new_status,
       "action_by"=>$data->action_by,
       "user_role"=>$data->user_role,
       "comment"=>$data->comment,
    ]);   
}
}











if(!function_exists('lots')){
function lots(){
    if(Auth::user()->role==1){
    $lots=DB::table('lots')->select('id','name')->get();  
  
    }else{
        $lots=DB::table('lots')->whereIn('id',json_decode(Auth::user()->lot_id))->select('id','name')->get();
      
        
    }
    return $lots;
  
}
}

if(!function_exists('district')){
function district(){
    $district=DB::table('districts')->select('id','name')->get();
    return $district;
}
}
if(!function_exists('tehsil')){
function tehsil(){
    $tehsil=DB::table('tehsil')->select('id','name')->get();
    return $tehsil;
}
}
if(!function_exists('uc')){
function uc(){
    if(Auth::user()->role==1){
    $uc=DB::table('uc')->select('id','name')->get();
    }else{
        $user=User::find(Auth::user()->id)->select('uc_id')->first();
        $uc=DB::table('uc')->whereIn('id',json_decode($user->uc_id))->select('id','name')->get(); 
    }
    return $uc;
}
}
if(!function_exists('alluc')){
function alluc(){
 
    $uc=DB::table('uc')->select('id','name')->get();
    
    return $uc;
}
}
if(!function_exists('zone')){
function zone(){
    $zone=DB::table('zone')->select('id','name')->get();
    return $zone;
}
}
if (!function_exists('getTableData')) {
    function getTableData($table)
    {
        return DB::table($table)
            ->where('status',1)
            ->select('id', 'name')
            ->get();
    }
}
if (!function_exists('savingImage')) {
    function savingImage($image)
    {
        $extension = $image->getClientOriginalExtension();
        $filename = '_image' . time() . '.' . $extension;
        $image->move(public_path('admin/assets/img'), $filename);
        
        return $filename; // Return just the filename
    }
}



if(!function_exists('form_status')){
function form_status($survey_form_id,$updated_by){
    $form_status=FormStatus::where('form_id',$survey_form_id)->where('update_by',$updated_by)->first();
    return $form_status;
}
}





if(!function_exists('update_certified')){
function update_certified($id){
    $form_status=FormStatus::where('id',$id)->first();
    if($form_status->certification==0){
        $form_status->certification=1;
    }
    else{
        $form_status->certification=0;
    }
        $form_status->save();
   
   }
}






if(!function_exists('base64Image')){
function base64Image($imageData){
    if(isset($imageData)){
    $base64Image = $imageData->image->base64;
    if(isset($imageData->image->type)){
        $imageType = $imageData->image->type;
        }
    if(isset($imageData->image->mime)){
    $imageType = $imageData->image->mime;
    }
                               
    $dataUri = 'data:' . $imageType . ';base64,' . $base64Image;
    return $dataUri;
    }else{
        return null;
    }
}
}


if (!function_exists("destructureForm")) {
        function destructureForm($id)
        {
            
          $survey_form = DB::table('survey_form')
                         ->where('id', $id)
                         ->select('form_data', 'id')
                         ->first();
        if ($survey_form) {
            $survey_form_id = $survey_form->id;
            $survey_form_data = json_decode($survey_form->form_data);
            $dataToInsert = [];
            foreach ($survey_form_data->sections as $key => $item) {
                $form_id = $item->section->form_id;
                $section_id = $item->section->id;
                foreach ($item->questions as $quest) {
                    $checkbox_ids=[];
                    $map=[];
                    if($quest->question->type=='checkbox'){
                        if($quest->question->answer !=null){
                          foreach($quest->question->answer as $item){
                              $checkbox_ids[]=$item->option_id;
                          }
                          $answer = json_encode($checkbox_ids);
                        }
                    }
                    if($quest->question->type=='map'){
                        
                          foreach($quest->options as $item){
                              
                              $map[]=['label'=>$item->name,'answer'=>$item->answer];
                          }
                          
                          $answer = json_encode($map);
                  
                        }
                    
                    
                    else{
                        $answer = $quest->question->answer;  
                    }
                    $question_id = $quest->question->id;
                    
                  
                    if (is_array($answer) || is_object($answer)) {
                    $answer = json_encode($answer);
                    }
                    $question_type = $quest->question->type;
                    $dataToInsert[] = [
                        'form_id' => $form_id,
                        'section_id' => $section_id,
                        'question_id' => $question_id,
                        'answer' => $answer,
                        'question_type' => $question_type,
                        'survey_form_id' => $survey_form_id
                    ];
                }
            }
           
            if (!empty($dataToInsert)) {
                $save_data=DB::table('answers')->insert($dataToInsert);
                if($save_data){
                $image_ids=DB::table('answers')->where('survey_form_id',$survey_form_id)->where('question_type','image')->select('id')->get();
                foreach($image_ids as $answer){
                     $file = base64_file_save($answer->id);
                    if($file->getStatusCode() === 200) {
                    $filepath = $file->getData()->image;
                    $result = base64_remove_data($answer->id, $filepath);
                    
                }
 
                }
                json_form_data_all_question_modified($survey_form_id);
                Log::info('helper json_form_data_all_question_modified survey form ID: '.$survey_form_id);
            }
        }
        return 'done';
        
        }
    }
}


if (!function_exists("answer_count")) {
        function answer_count($id,$answer)
        {
            $answer=DB::table('answers')->where('question_id',$id)->where('answer',$answer)->count();
            return $answer;
        }
    }
    
    if (!function_exists("answer_sum")) {
        function answer_sum($id,$type=null)
        {
             
         $answer=DB::table('answers')
        ->where('question_id', $id)
        ->whereNotNull('answer')
        ->sum('answer');
        return $answer;
        }
    }
    if (!function_exists("get_answer")) {
        function get_answer($id,$survey_id,$type=null)
        {
             
         $answer=DB::table('answers')
        ->where('question_id', $id)
        ->where('survey_form_id',$survey_id)
        ->select('answer')
        ->first();
        
        return $answer;
        }
    }
    
    if (!function_exists("get_construction_answer")) {
        function get_construction_answer($qid,$construction_json_id)
        {
             
         $answer=DB::table('contructions_answer')
        ->where('question_id', $qid)
        ->where('construction_json_id',$construction_json_id)
        ->select('answer')
        ->first();
        
        return $answer;
        }
    }

if (!function_exists('is_json')) { 
    function is_json($string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}






if (!function_exists("allow_to_update_form")) {
        function allow_to_update_form()
        {
            $allow_to_update_form =
                DB::table("roles")
                    ->join("users", "users.role", "=", "roles.id")
                    ->where("users.id", Auth::user()->id)
                    ->select("allow_to_update_form")
                    ->first() ?? null;
            return $allow_to_update_form;
        }
    }    
if (!function_exists("certification")) {
        function certification($survey_form_id, $updated_by)
        {
            $certification_status = \DB::table("form_status")
                ->where("form_id", $survey_form_id)
                ->where("update_by", $updated_by)
                ->select("certification")
                ->first() ?? null;
            return $certification_status;
        }
    }    



//Functions created By Ayaz Ahmed Date 23-10-2024
if(!function_exists('logg'))
{
    function logg($data)
    {
        $data = array(
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'user_role' => Auth::user()->role,
                'user_role_name' => get_role_name(Auth::user()->role),
                'ip_address' => get_client_ip(), 
                'user_agent' => $_SERVER['HTTP_USER_AGENT'], 
                'description' => $data['description'], 
                'reference_tbl' => $data['referance_tbl'], 
                'reference_val' => $data['referance_val'], 
            );
        \DB::table('tbl_logs')->insert($data);
        
    }
}




if(!function_exists('get_client_ip'))
{
    function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}


    if (!function_exists("get_role_name")){
    function get_role_name($id){
        $role = Role::find($id);
        return $role->name ?? '';
    }}
    
    if (!function_exists("get_user_name")){
    function get_user_name($id){
        $user = User::find($id);
        return $user->name ?? '';
    }}
    
    if (!function_exists("get_ref_no")){
    function get_ref_no($id){
        $SurveyData = SurveyData::find($id);
        return $SurveyData->ref_no ?? '';
    }}
    
    if (!function_exists("check_surveyform_status")){
    function check_surveyform_status($id,$status){
    $exists = FormStatus::where('form_id', $id)->where('user_status', Auth::user()->role)->where('form_status', $status)->exists();
    return $exists ? 1 : 0;
    }}
    
    

    if (!function_exists("getquestionlabel")) {
    function getquestionlabel($id)
    {
        
        $question = Question::find($id);
        return $question->name ?? '';
        
        
    }
    }
    
    if (!function_exists("getoptionlabel")) {
    function getoptionlabel($id)
    {
        //dump($id->option_id);
        if(isset($id->option_id)){
            $option = Option::find($id->option_id);
            if($option){
            return $option->name ?? '';
            }
            
        }else{ 
                    if (is_object($id)) {
                                $option = Option::find($id->optionId);
                                if($option){
                                return $option->name ?? '';
                                }
                    }else{
                                $option = Option::find($id);
                                if($option){
                                return $option->name ?? '';
                                }
                    }
            
            
            
        }
    }
    }
    
    if (!function_exists("get_beneficiary_profile_image")) {
    function get_beneficiary_profile_image($sfid)
    {
        $profileimage = Answer::where('survey_form_id',$sfid)->where('question_id',285)->first();
        if($profileimage){
        if(isset($profileimage->answer)){
        $image = json_decode($profileimage->answer);
            if(isset($image->image->path)){
            return $image->image->path;
            }else{
                return '';
            }
            
        } else{
            return null;
        }   
        //return base64Image($image);
        }
    }
    }
    
    
    if (!function_exists("get_question_image")) {
    function get_question_image($sfid, $qid)
    {
        $question_image = Answer::where('survey_form_id',$sfid)->where('question_id', $qid)->first();
        
        if($question_image){
        if(isset($question_image->answer)){
        $image = json_decode($question_image->answer);
        }    
        
        //return base64Image($image);
        if(isset($image->image->path)){
            return asset('uploads/surveyform_files').'/'.$image->image->path;
        }
        
        }
    }
    }
    
    
    if (!function_exists("get_beneficiary_question_ans")) {
    function get_beneficiary_question_ans($sfid, $qid)
    {
        $beneficiary_question_ans = DB::table('answers')->where('survey_form_id',$sfid)->where('question_id', $qid)->first();
        //$beneficiary_question_ans = Answer::where('survey_form_id',$sfid)->where('question_id', $qid)->first();
        if($beneficiary_question_ans){
        return $beneficiary_question_ans->answer;
        }else{
        return 'Not Available';    
        }
    }
    }
    
    
    
    
    
    if (!function_exists("uploadfiles")) {
    function uploadfiles($id, $files, $filetitle, $path)
    {
        if($files){
        if(is_array($files)){
            foreach($files as $evidence_file){
			$file = $evidence_file;
			$filefullname = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
			$filerename = $filetitle."_".$id."_".date('YmdHis').'.'. $extension;
			$filepath = $file->move(public_path('uploads/'.$path), $filerename);
              
	        $bytes = $filepath->getSize();
		    if($bytes >= 1073741824){$file_size = number_format($bytes / 1073741824, 2) . ' GB';}
			elseif($bytes >= 1048576){$file_size  = number_format($bytes / 1048576, 2) . ' MB';}
			elseif($bytes >= 1024){$file_size  = number_format($bytes / 1024, 2) . ' KB';}
			elseif($bytes > 1){$file_size  = $bytes . ' bytes';}
			elseif($bytes == 1){$file_size  = $bytes . ' byte';}
			else{$file_size  = '0 bytes';}

		    ComplaintFile::create([
		        'complaint_id' => $id,
                'name' => $filerename,
				'extension' => $extension,
				'size' => $file_size,
                'created_by' => auth()->user()->id ?? 0,
				'updated_by' => auth()->user()->id ?? 0
                
            ]);
				}//loop end
        }else{
            
            $file = $files;
			$filefullname = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
			$filerename = $filetitle."_".$id."_".date('YmdHis').'.'. $extension;
			$filepath = $file->move(public_path('uploads/'.$path), $filerename);
              
	        $bytes = $filepath->getSize();
		    if($bytes >= 1073741824){$file_size = number_format($bytes / 1073741824, 2) . ' GB';}
			elseif($bytes >= 1048576){$file_size  = number_format($bytes / 1048576, 2) . ' MB';}
			elseif($bytes >= 1024){$file_size  = number_format($bytes / 1024, 2) . ' KB';}
			elseif($bytes > 1){$file_size  = $bytes . ' bytes';}
			elseif($bytes == 1){$file_size  = $bytes . ' byte';}
			else{$file_size  = '0 bytes';}

		    ComplaintFile::create([
		        'complaint_id' => $id,
                'name' => $filerename,
				'extension' => $extension,
				'size' => $file_size,
                'created_by' => auth()->user()->id ?? 0,
				'updated_by' => auth()->user()->id ?? 0
                
            ]);
            
        }		
        }//END IF File EXIST
    }
    }
    
    //Overall Complaints Counter
    if(!function_exists('get_total_complaint')){
        function get_total_complaint(){
            if(Auth::user()->role == 56 || Auth::user()->role == 39 || Auth::user()->role == 1){
    	      $complaints = Complaint::whereNot('grievance_type', 1)->get();
    	    }elseif(Auth::user()->role == 57){ 
    	        $complaints = Complaint::whereNot('grievance_type', 1)->where('assign_to', Auth::user()->id);
            }else{
               $complaints = Complaint::whereNot('grievance_type', 1)->where('id', 0);
            }
            
    	      return $complaints; 
        }
    }
    
    if(!function_exists('get_total_pending_complaint')){
        function get_total_pending_complaint(){
                if(Auth::user()->role == 56 || Auth::user()->role == 39 || Auth::user()->role == 1){
                $complaints = Complaint::whereNot('grievance_type', 1)->where('status', 'Pending');
                }elseif(Auth::user()->role == 57){ 
                $complaints = Complaint::whereNot('grievance_type', 1)->where('status', 'Pending')->where('assign_to', Auth::user()->id);
                }else{
                $complaints = Complaint::whereNot('grievance_type', 1)->where('id', 0);
                }
    	      return $complaints; 
        }
    }
    
    if(!function_exists('get_total_inprocess_complaint')){
        function get_total_inprocess_complaint(){
                if(Auth::user()->role == 56 || Auth::user()->role == 39 || Auth::user()->role == 1){
                $complaints = Complaint::whereNot('grievance_type', 1)->where('status', 'In Process');
                }elseif(Auth::user()->role == 57){ 
                $complaints = Complaint::whereNot('grievance_type', 1)->where('status', 'In Process')->where('assign_to', Auth::user()->id);
                }else{
                $complaints = Complaint::whereNot('grievance_type', 1)->where('id', 0);
                }
    	      return $complaints; 
        }
    }
    
    if(!function_exists('get_total_closed_complaint')){
        function get_total_closed_complaint(){
    	      if(Auth::user()->role == 56 || Auth::user()->role == 39 || Auth::user()->role == 1){
                $complaints = Complaint::whereNot('grievance_type', 1)->where('status', 'Closed');
                }elseif(Auth::user()->role == 57){ 
                $complaints = Complaint::whereNot('grievance_type', 1)->where('status', 'Closed')->where('assign_to', Auth::user()->id);
                }else{
                $complaints = Complaint::whereNot('grievance_type', 1)->where('id', 0);
                }
    	      return $complaints; 
        }
    }
    
    if(!function_exists('get_total_returned_complaint')){
        function get_total_returned_complaint(){
    	      if(Auth::user()->role == 56 || Auth::user()->role == 39 || Auth::user()->role == 1){
                $complaints = Complaint::whereNot('grievance_type', 1)->where('status', 'Returned');
                }elseif(Auth::user()->role == 57){ 
                $complaints = Complaint::whereNot('grievance_type', 1)->where('status', 'Returned')->where('assign_to', Auth::user()->id);
                }else{
                $complaints = Complaint::whereNot('grievance_type', 1)->where('id', 0);
                }
    	      return $complaints; 
        }
    }
    
    if(!function_exists('get_forward_total_complaint')){
    function get_forward_total_complaint()
    {
	
	if(Auth::user()->role == 56 || Auth::user()->role == 39 || Auth::user()->role == 1){
$complaint_ids = ComplaintRemark::whereDate('created_at', Carbon::today())->whereIn('status', ['Forward'])->pluck('complaint_id')->unique()->all();	
$complaints = Complaint::whereNot('grievance_type', 1)->whereIn('status',['In Process','Pending','Requirement'])->whereIn('id', $complaint_ids);                
                }elseif(Auth::user()->role == 57){ 
                //$complaints = Complaint::where('status', 'Forward')->where('assign_to', Auth::user()->id);
$complaint_ids = ComplaintRemark::whereDate('created_at', Carbon::today())->whereIn('status', ['Forward'])->pluck('complaint_id')->unique()->all();	
$complaints = Complaint::whereNot('grievance_type', 1)->whereIn('status',['In Process','Pending','Requirement'])->whereIn('id', $complaint_ids)->where('assign_to', Auth::user()->id);
                }else{
                $complaints = Complaint::whereNot('grievance_type', 1)->where('id', 0);
                }
    	      return $complaints;	
		
    }
}
    
    
    
    //Today Complaints Counter
    if(!function_exists('get_today_total_complaint')){
        function get_today_total_complaint(){
            if(Auth::user()->role == 56 || Auth::user()->role == 39 || Auth::user()->role == 1){
    	       $complaints = Complaint::whereNot('grievance_type', 1)->whereDate('created_at', Carbon::today());
    	    }elseif(Auth::user()->role == 57){ 
    	       $complaints = Complaint::whereNot('grievance_type', 1)->whereDate('created_at', Carbon::today())->where('assign_to', Auth::user()->id);
            }else{
               $complaints = Complaint::whereNot('grievance_type', 1)->where('id', 0);
            }
            
    	      return $complaints; 
        }
    }
    
    
    //Total Complaints Counter
    if(!function_exists('get_total_exclusioncases_complaint')){
        function get_total_exclusioncases_complaint(){
            if(Auth::user()->role == 56 || Auth::user()->role == 1){
    	       $complaints = Complaint::where('grievance_type', 1);
            }else{
               $complaints = Complaint::where('id', 0);
            }
            
    	      return $complaints; 
        }
    }
    
    //Today Complaints Counter
    if(!function_exists('get_today_exclusioncases_complaint')){
        function get_today_exclusioncases_complaint(){
            if(Auth::user()->role == 56 || Auth::user()->role == 1){
    	       $complaints = Complaint::where('grievance_type', 1)->whereDate('created_at', Carbon::today());
            }else{
               $complaints = Complaint::where('id', 0);
            }
            
    	      return $complaints; 
        }
    }
    
    
    
    
    
    
    
    
    
    
    if(!function_exists('survey_answer_savefile')){
    function survey_answer_savefile($survey_form_id, $question_id){
    $answer = DB::table('answers')->where('question_type', 'image')->where('survey_form_id', $survey_form_id)->where('question_id', $question_id)->select( 'id')->first();
    if($answer){
        $answerid = $answer->id; 
        $file = base64_file_save($answerid);
        if($file->getStatusCode() === 200) {
            $filepath = $file->getData()->image;
            $result = base64_remove_data($answerid, $filepath);
            //return $result->getData()->message;
            return response()->json(['message' => 'Image successfully uploaded and remove base64 data.', 'filename' => $file->getData()->image, 'base64' => $result->getData()->message]);
        }else{
          return response()->json(['message' => $file->getData()->message], 404);  
        }
    }else{
       return response()->json(['message' => 'Answer id not found.'], 404); 
    }
    
    }
    }
    
    
    //Base64 File Save
    if(!function_exists('base64_file_save')){
    function base64_file_save($id){
    $image = DB::table('answers')->where('id', $id)->select('survey_form_id','section_id','question_id','answer')->first();
    // $answer=$image;
    $imageRename = $image->survey_form_id.'_'.$image->section_id.'_'.$image->question_id.'_'.date('YmdHis');
    
    if (!$image) {return response()->json(['message' => 'Answer not found.'], 404);}
    
    $image = json_decode($image->answer);
    
    if (!isset($image->image)) {
     return response()->json(['message' => 'Image data not found in the answer.'], 400);
    }
    
    if (!$image->image->base64) {
     return response()->json(['message' => 'base64 data not found in the answer.'], 400);
    }
    
    $mimeType = $image->image->mime;
    $base64Image = $image->image->base64;
    
    $base64Image='data:image/jpeg;base64,'.$base64Image;
    if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
        
        $fileExtension = $matches[1];
        $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
        $imageData = base64_decode($base64Image);

        if ($imageData === false) {
            return response()->json(['message' => 'Failed to decode base64 image.'], 500);
        }

        $imageName = $imageRename.'.'.$fileExtension;
        $imagePath = public_path('uploads/surveyform_files/' . $imageName);

        if (file_put_contents($imagePath, $imageData)) {
            // dashboardnewimg($answer);
            return response()->json([
                'message' => 'Image successfully uploaded.',
                //'image_url' => asset('uploads/surveyform_files/' . $imageName),
                'image' => $imageName,
                
            ]);
        } else {
            return response()->json(['message' => 'Failed to save the image.'], 500);
        }
        
    } else {
            return response()->json(['message' => 'Invalid base64 image format.'], 400);
    }
    
    
        }
    }
    
    
    //Base64 Remove Data
    if(!function_exists('base64_remove_data')){
    function base64_remove_data($id, $filepath){
 
    $record = DB::table('answers')->where('id', $id)->select('answer')->first();

    if (!$record) {return response()->json(['message' => 'Answer not found.'], 404);}
    // Decode the JSON data
    $jsonData = json_decode($record->answer, true);
    
    if(!isset($jsonData['image']) || !isset($jsonData['image']['base64'])){
     return response()->json(['message' => 'Image data not found in the answer.'], 400);
    }

// Update the specific fields
$jsonData['image']['path'] = $filepath;
$jsonData['image']['base64'] = '';

// Encode the JSON data and save it back
$updateanswer = DB::table('answers')
    ->where('id', $id)
    ->update(['answer' => json_encode($jsonData)]);

    if($updateanswer == 1){
    return response()->json([
                'message' => 'Image path successfully updated.',
                //'answer' => $updateanswer,
            ]);
    }else{
      return response()->json(['message' => 'Image path not updated'], 400);  
    }
    
    
    
        }
    }
    
    
    
    if(!function_exists('json_form_data_all_question_modified')){
    function json_form_data_all_question_modified($survey_form_id){
    $survey_form = DB::table('survey_json')->where('survey_id', $survey_form_id)->select('json')->first();
    $form_data = $survey_form->json;
    $form_data_array = json_decode($form_data, true);
    
    if(isset($form_data_array['sections']['Upload Photos & Documents']['questions'])){
    $questions = $form_data_array['sections']['Upload Photos & Documents']['questions'];
    foreach ($questions as $key => $item){
    $result = json_form_data_single_question_answer_modified($survey_form_id, $item['question']['section_id'], $item['question']['id']);    
    //echo $result->getData()->message."<br />";
    }
    return response()->json(['survey_form_id' => $survey_form_id, 'message' => 'Form data successfully json updated.'], 200);
    }else{
    return response()->json(['survey_form_id' => $survey_form_id, 'message' => 'Form data something wrong.'], 400);    
    }
    }}
    
    
    
    if(!function_exists('json_form_data_single_question_answer_modified')){
    function json_form_data_single_question_answer_modified($survey_form_id, $section_id, $question_id){
    $update_answers = DB::table('answers')->where('survey_form_id', $survey_form_id)->where('section_id', $section_id)->where('question_id', $question_id)->select('answer')->first();
    $survey_form = DB::table('survey_json')->where('survey_id', $survey_form_id)->select('json')->first();
    $form_data = $survey_form->json;
    $form_data_array = json_decode($form_data, true);
    if(isset($form_data_array['sections']['Upload Photos & Documents']['questions'])){
    $questions = $form_data_array['sections']['Upload Photos & Documents']['questions'];
    foreach ($questions as $key => $item){
    if(isset($item['question']['id'], $item['question']['section_id']) && $item['question']['id'] == $question_id && $item['question']['section_id'] == $section_id){ 
        $form_data_array['sections']['Upload Photos & Documents']['questions'][$key]['question']['answer'] = $update_answers->answer ?? '';
        break; // Exit the loop since we found the match
    }
    }
    
    
    //echo json_encode($form_data_array);
    
    $update_survey_form = DB::table('survey_json')->where('survey_id', $survey_form_id)->update(['json' => json_encode($form_data_array)]);
    if($update_survey_form == 1){
      return response()->json(['message' => 'Form data successfully updated.'], 200);
    }else{
      return response()->json(['message' => 'Form data not updated.'], 400);  
    }
    
    //return response()->json(['message' => 'Form data successfully updated.'], 200);
    }else{
    return response()->json(['message' => 'Form data something wrong.'], 400);    
    }
    }}
    
    
    
    
    
    if(!function_exists('destructure_form_new')){
    function destructure_form_new($survey_form_id){
   
      
    $survey_form = DB::table('survey_json')
                             ->where('survey_id', $survey_form_id)
                             ->select('json as form_data', 'survey_id as id')
                             ->first();
                             
                  

            if($survey_form){
            DB::table('dummy')->insert(['name'=>$survey_form_id, 'note'=>'HELPER START']);    
            $survey_form_id = $survey_form->id;
            $survey_form_data = json_decode($survey_form->form_data);
            $dataToInsert = [];
            foreach ($survey_form_data->sections as $key => $item) {
                $form_id = $item->section->form_id;
                $section_id = $item->section->id;
                foreach ($item->questions as $quest) {
                    $checkbox_ids=[];
                    $map=[];
                    if($quest->question->type=='checkbox'){
                        if($quest->question->answer !=null){
                          foreach($quest->question->answer as $item){
                              $checkbox_ids[]=$item->option_id;
                          }
                          $answer = json_encode($checkbox_ids);
                        }
                    }
                    if($quest->question->type=='map'){
                        
                          foreach($quest->options as $item){
                              
                              $map[]=['label'=>$item->name,'answer'=>$item->answer];
                          }
                          
                          $answer = json_encode($map);
                  
                        }
                    else{
                        $answer = $quest->question->answer;  
                    }
                    $question_id = $quest->question->id;
                    if (is_array($answer) || is_object($answer)) {
                    $answer = json_encode($answer);
                    }
                    $question_type = $quest->question->type;
                    
                    //AYAZ UPDATE ADDITIONAL COLUMNS START

                    if($question_id == 350){ DB::table('survey_form')->where('id', $survey_form_id)->update(['cnic_expiry_status' => $answer]); }
                    if($question_id == 351){ DB::table('survey_form')->where('id', $survey_form_id)->update(['date_of_birth' => $answer]); }
                    if($question_id == 352){ DB::table('survey_form')->where('id', $survey_form_id)->update(['preferred_bank' => $answer]); }
                    
                    if($question_id == 616){ DB::table('survey_form')->where('id', $survey_form_id)->update(['mother_maiden_name' => $answer]); }
                    if($question_id == 617){ DB::table('survey_form')->where('id', $survey_form_id)->update(['city_of_birth' => $answer]); }
                    if($question_id == 618){ DB::table('survey_form')->where('id', $survey_form_id)->update(['date_of_insurence_of_cnic' => $answer]); }
                    
                    if($question_id == 656){ DB::table('survey_form')->where('id', $survey_form_id)->update(['marital_status' => $answer]); }
                    if($question_id == 657){ DB::table('survey_form')->where('id', $survey_form_id)->update(['next_kin_name' => $answer]); }
                    if($question_id == 658){ DB::table('survey_form')->where('id', $survey_form_id)->update(['cnic_of_kin' => $answer]); }
                    if($question_id == 671){ DB::table('survey_form')->where('id', $survey_form_id)->update(['conatact_of_next_kin' => $answer]); }
                    if($question_id == 672){ DB::table('survey_form')->where('id', $survey_form_id)->update(['relation_cnic_of_kin' => $answer]); }
                    if($question_id == 675){ DB::table('survey_form')->where('id', $survey_form_id)->update(['expiry_date' => $answer]); }
                    if($question_id == 2000){ DB::table('survey_form')->where('id', $survey_form_id)->update(['village_name' => $answer]); }
                    
                    if($question_id == 243){ DB::table('survey_form')->where('id', $survey_form_id)->update(['status_of_land' => $answer]); }
                    if($question_id == 246){ DB::table('survey_form')->where('id', $survey_form_id)->update(['socio_legal_status' => $answer]); }
                    if($question_id == 247){ DB::table('survey_form')->where('id', $survey_form_id)->update(['evidence_type' => $answer]); }
                    if($question_id == 248){ DB::table('survey_form')->where('id', $survey_form_id)->update(['bank_ac_wise' => $answer]); }
                    if($question_id == 646){ DB::table('survey_form')->where('id', $survey_form_id)->update(['proposed_beneficiary' => $answer]); }
                    if($question_id == 730){ DB::table('survey_form')->where('id', $survey_form_id)->update(['reconstruction_wise' => $answer]); }
                    // if($question_id == 756){ DB::table('survey_form')->where('id', $survey_form_id)->update(['construction_type' => $answer]); }
                    if($question_id == 760){ DB::table('survey_form')->where('id', $survey_form_id)->update(['construction_type' => $answer]); }
                    
                    
                                        // extra column
                    if($question_id == 250){ DB::table('survey_form')->where('id', $survey_form_id)->update(['account_number' => $answer]); }
                    if($question_id == 251){ DB::table('survey_form')->where('id', $survey_form_id)->update(['bank_name' => $answer]); }
                    if($question_id == 252){ DB::table('survey_form')->where('id', $survey_form_id)->update(['branch_name' => $answer]); }
                    if($question_id == 253){ DB::table('survey_form')->where('id', $survey_form_id)->update(['bank_address' => $answer]); }
                    if($question_id == 654){ DB::table('survey_form')->where('id', $survey_form_id)->update(['father_name' => $answer]); }
                    // coordinates
                    if($question_id == 416){ DB::table('survey_form')->where('id', $survey_form_id)->update(['coordinates' => $answer]); }

                    if($question_id == 652){ if($answer == 'Female'){$gender_score = 10;}else{$gender_score = 0;}
                        DB::table('survey_form')->where('id', $survey_form_id)->update(['gender' => $answer, 'gender_score' => $gender_score]); }
                        
                    if($question_id == 968){ if($answer == 'Yes'){$disability_score = 10;}else{$disability_score = 0;}
                        DB::table('survey_form')->where('id', $survey_form_id)->update(['disability' => $answer, 'disability_score' => $disability_score]); }
                        
                    if($question_id == 240){ if($answer == 'Owner'){$landownership_score = 10;}elseif($answer == 'Tenant'){$landownership_score = 5;}else{$landownership_score = 0;}
                        DB::table('survey_form')->where('id', $survey_form_id)->update(['landownership' => $answer, 'landownership_score' => $landownership_score]); }
                    
                    if($question_id == 704){ if($answer == 'Yes'){$bisp_score = 10;}else{$bisp_score = 0;}
                        DB::table('survey_form')->where('id', $survey_form_id)->update(['is_bisp' => $answer, 'bisp_score' => $bisp_score]); }
                    
                    if($question_id == 670){
                        DB::table('survey_form')->where('id', $survey_form_id)->update(['monthly_income' => $answer]);
                        if(is_numeric($answer) && (int)$answer == $answer) {
                        DB::table('survey_form')->where('id', $survey_form_id)->whereRaw('monthly_income REGEXP "^[0-9]+$"')->where('monthly_income', '<', 37000)->update(['monthly_income_score' => 10]);
                        }
                    }
                    
                    if($question_id == 2242){ if($answer == 'Yes'){$is_vulnerable_women_score = 10;}else{$is_vulnerable_women_score = 0;}
                        DB::table('survey_form')->where('id', $survey_form_id)->update(['is_vulnerable_women' => $answer, 'is_vulnerable_women_score' => $is_vulnerable_women_score]); }
                        DB::table('survey_form')->where('id', $survey_form_id)->update(['total_scores' => DB::raw('COALESCE(gender_score, 0) + COALESCE(disability_score, 0) + COALESCE(landownership_score, 0) + COALESCE(bisp_score, 0) + COALESCE(monthly_income_score, 0) + COALESCE(is_vulnerable_women_score, 0)')]);
                    //AYAZ UPDATE ADDITIONAL COLUMNS END
                    
                    update_answer_for_cnic($survey_form_id);
                    updateAnswerForContactNumber($survey_form_id);
                    update_answer_for_name($survey_form_id);
                    
                    $dataToInsert[] = [
                        'form_id' => $form_id,
                        'section_id' => $section_id,
                        'question_id' => $question_id,
                        'answer' => $answer,
                        'question_type' => $question_type,
                        'survey_form_id' => $survey_form_id
                    ];
                }
            }
           
            if (!empty($dataToInsert)) {
                
                $save_data = DB::table('answers')->insert($dataToInsert);
                if($save_data){
                $image_ids = DB::table('answers')->where('survey_form_id',$survey_form_id)->where('question_type','image')->select('id','section_id','question_id')->get();
                foreach($image_ids as $answer){
                       
                     $file = base64_file_save($answer->id);
                    if($file->getStatusCode() === 200) {
                    $filepath = $file->getData()->image;
                    $result = base64_remove_data($answer->id, $filepath);
                
                
                    
                }
 
                }
                
                
                $result2 = json_form_data_all_question_modified($survey_form_id);
                //Log::info('json_form_data_all_question_modified survey form ID: '.$survey_form_id);
            }
                
               
                
                
            }
            
            
        //Update Beneficiary CNIC Path    
        // update_beneficiary_cnic_path($survey_form_id);
        DB::table('dummy')->insert(['name'=>$survey_form_id, 'note'=>'HELPER END']);
        }//end check survey form
        
    
    }
    }
    
    
    
        if(!function_exists('update_beneficiary_cnic_path')){
        function update_beneficiary_cnic_path($survey_form_id){
            $a = DB::table('survey_form as sf')->join('answers as ans', 'sf.id', '=', 'ans.survey_form_id')
            ->where('ans.question_id', 286)
            ->where('sf.proposed_beneficiary', 'Yes')
            ->where('sf.id', $survey_form_id)
            ->whereRaw('JSON_VALID(ans.answer) = 1')
            ->update(['sf.b_f_cnic' => DB::raw("CONCAT('https://mis.hru.org.pk/uploads/surveyform_files/', JSON_UNQUOTE(JSON_EXTRACT(ans.answer, '$.image.path')))")]);
            
            $b = DB::table('survey_form as sf')->join('answers as ans', 'sf.id', '=', 'ans.survey_form_id')
            ->where('ans.question_id', 288)
            ->where('sf.proposed_beneficiary', 'No')
            ->where('sf.id', $survey_form_id)
            ->whereRaw('JSON_VALID(ans.answer) = 1')
            ->update(['sf.b_f_cnic' => DB::raw("CONCAT('https://mis.hru.org.pk/uploads/surveyform_files/', JSON_UNQUOTE(JSON_EXTRACT(ans.answer, '$.image.path')))")]); 
            
            $c = DB::table('survey_form as sf')->join('answers as ans', 'sf.id', '=', 'ans.survey_form_id')
            ->where('ans.question_id', 287)
            ->where('sf.proposed_beneficiary', 'Yes')
            ->where('sf.id', $survey_form_id)
            ->whereRaw('JSON_VALID(ans.answer) = 1')
            ->update(['sf.b_b_cnic' => DB::raw("CONCAT('https://mis.hru.org.pk/uploads/surveyform_files/', JSON_UNQUOTE(JSON_EXTRACT(ans.answer, '$.image.path')))")]);
            
            $d = DB::table('survey_form as sf')->join('answers as ans', 'sf.id', '=', 'ans.survey_form_id')
            ->where('ans.question_id', 2537)
            ->where('sf.proposed_beneficiary', 'No')
            ->where('sf.id', $survey_form_id)
            ->whereRaw('JSON_VALID(ans.answer) = 1')
            ->update(['sf.b_b_cnic' => DB::raw("CONCAT('https://mis.hru.org.pk/uploads/surveyform_files/', JSON_UNQUOTE(JSON_EXTRACT(ans.answer, '$.image.path')))")]);
            
            
            // $e = DB::table('survey_form as sf')->join('answers as ans', 'sf.id', '=', 'ans.survey_form_id')
            // ->where('ans.question_id', 645)
            // ->where('sf.proposed_beneficiary', 'Yes')
            // ->where('sf.id', $survey_form_id)
            // ->update(['sf.beneficiary_name' => ans.answer]);
            
            // $f = DB::table('survey_form as sf')->join('answers as ans', 'sf.id', '=', 'ans.survey_form_id')
            // ->where('ans.question_id', 648)
            // ->where('sf.proposed_beneficiary', 'No')
            // ->where('sf.id', $survey_form_id)
            // ->update(['sf.beneficiary_name' => ans.answer]);
   
        }
        }
        if(!function_exists('updateAnswerForContactNumber')){
        function updateAnswerForContactNumber($id){	
	    $check_condition=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',663)->first();
	    
	    $answer=null;
	    if(isset($check_condition) && $check_condition->answer=='Self'){
    	       $answer=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',664)->first();
    	 
	    }
	    else if(isset($check_condition) && $check_condition->answer=='Relative'){
    	       $answer=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',666)->first();
	    }
	    else{
	        $answer=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',668)->first();
	    } 
	    
	    if(isset($answer->answer)){
    	   $beneficiary_name=DB::table('survey_form')->where('id',$id)->update(['beneficiary_number'=>$answer->answer]);
    	 }
    	    return true;
	   }
	
	  
	    
	
        }
    
    

    function dashboardnewimg($answer){
        
        $image_details=json_decode($answer->answer);
        if(isset($image_details->fetchLocation) && isset($image_details->fetchLocation->latitude)){
            $longitude=$image_details->fetchLocation->longitude;
            $latitude=$image_details->fetchLocation->latitude;
            
            $image_location=$image_details;
            $image_name=$image_details->image->path;
            $fullPath = public_path('uploads/surveyform_files/' . $image_name);

            $exifToolPath = '/bin/exiftool'; // Update this path if necessary
            $process = new Process(['which', 'exiftool']);
            $process->run();
        
            if (!$process->isSuccessful()) {
                return ['error' => 'ExifTool is not installed or accessible.'];
            }

            // Using Symfony Process to run ExifTool and add GPS data
            $command = [$exifToolPath, "-GPSLatitude=$latitude", "-GPSLatitudeRef=N", "-GPSLongitude=$longitude", "-GPSLongitudeRef=E", $fullPath];
            $process = new Process($command);
            $process->run();
        
            if (!$process->isSuccessful()) {
                // Handle the error if ExifTool fails
                return ['error' => 'Failed to add GPS data. Command output: ' . $process->getErrorOutput()];
            }
        
            // Optionally, remove the original file created by ExifTool
            $removeBackupProcess = new Process(["rm", "$fullPath" . "_original"]);
            $removeBackupProcess->run();
        
            if (!$removeBackupProcess->isSuccessful()) {
                // Log the error if backup removal fails
                Log::error('Failed to remove the original backup file', ['output' => $removeBackupProcess->getErrorOutput()]);
            }
        
            // Return success response
          Log::error('SUCCESSSSSSSS!!!!');;
        }
    }
    
    if(!function_exists('upper_role_id_master_report')){
    function upper_role_id_master_report(){
          $role = null;
        if(Auth::user()->role=='27'){
          $role = Role::findOrFail(30);
        }
        elseif(Auth::user()->role=='30'){
             $role = Role::findOrFail(34);
        }
        elseif(Auth::user()->role=='34'){
             $role = Role::findOrFail(36);
        }
        elseif(Auth::user()->role=='36'){
             $role = Role::findOrFail(37);
        }
        elseif(Auth::user()->role=='37'){
             $role = Role::findOrFail(38);
        }
        elseif(Auth::user()->role=='38'){
             $role = Role::findOrFail(40);
        }
        elseif(Auth::user()->role=='40'){
             $role = Role::findOrFail(48);
        }
        elseif(Auth::user()->role=='48'){
             $role = Role::findOrFail(48);
        }
        return $role;
    }}
    
    if(!function_exists('lower_role_id_master_report')){
    function lower_role_id_master_report(){
          $role = null;
        if(Auth::user()->role=='30'){
          $role = Role::findOrFail(27);
        }
        elseif(Auth::user()->role=='34'){
             $role = Role::findOrFail(30);
        }
        elseif(Auth::user()->role=='36'){
             $role = Role::findOrFail(34);
        }
        elseif(Auth::user()->role=='37'){
             $role = Role::findOrFail(36);
        }
        elseif(Auth::user()->role=='38'){
             $role = Role::findOrFail(37);
        }
        elseif(Auth::user()->role=='40'){
             $role = Role::findOrFail(38);
        }
        elseif(Auth::user()->role=='48'){
             $role = Role::findOrFail(40);
             
        }
        return $role;
    }}
    
    
    if(!function_exists('upper_role_name_form_status')){
    function upper_role_name_form_status(){
        $updated_by_name=null;
        if(Auth::user()->role=='30'){
          $updated_by_name='IP';    
        }
        elseif(Auth::user()->role=='34'){
             $updated_by_name='HRU'; 
        }
        elseif(Auth::user()->role=='36'){
             $updated_by_name='PSIA'; 
        }
        elseif(Auth::user()->role=='37'){
             $updated_by_name='HRU_Main'; 
        }
        elseif(Auth::user()->role=='38'){
             $updated_by_name='CEO'; 
        }
        elseif(Auth::user()->role=='39'){
             $updated_by_name='CEO'; 
        }
        elseif(Auth::user()->role=='40'){
             $updated_by_name='Finance'; 
             
        }
        return $updated_by_name;
    }}
    
    
    if(!function_exists('same_role_name_form_status')){
    function same_role_name_form_status(){
        $updated_by_name=null;
        if(Auth::user()->role=='30'){
          $updated_by_name='field supervisor';    
        }
        elseif(Auth::user()->role=='34'){
             $updated_by_name='IP'; 
        }
        elseif(Auth::user()->role=='36'){
             $updated_by_name='HRU'; 
        }
        elseif(Auth::user()->role=='37'){
             $updated_by_name='PSIA'; 
        }
        elseif(Auth::user()->role=='38'){
             $updated_by_name='HRU_Main'; 
        }
        elseif(Auth::user()->role=='39'){
             $updated_by_name='COO'; 
        }
        elseif(Auth::user()->role=='40'){
             $updated_by_name='CEO'; 
        }
        
        return $updated_by_name;
    }}
    if(!function_exists('lower_role_name')){
    function lower_role_name(){
        $updated_by_name=null;
        if(Auth::user()->role=='30'){
          $updated_by_name='field supervisor';    
        }
        elseif(Auth::user()->role=='34'){
             $updated_by_name='field supervisor'; 
        }
        elseif(Auth::user()->role=='36'){
             $updated_by_name='IP'; 
        }
        elseif(Auth::user()->role=='37'){
             $updated_by_name='HRU'; 
        }
        elseif(Auth::user()->role=='38'){
             $updated_by_name='PSIA'; 
        }
        elseif(Auth::user()->role=='40'){
             $updated_by_name='HRU_Main'; 
        }
        return $updated_by_name;
    }}
    


if(!function_exists('update_answer_for_cnic')){
  function update_answer_for_cnic($id){
        $answer=null;
	    $check_condition=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',646)->first();
	   
	   
	    if(isset($check_condition) && $check_condition->answer=='Yes'){
	           $second_check_condition=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',649)->first();
	      
	          
	           if(isset($second_check_condition) && $second_check_condition->answer=='Yes'){
    	       $answer=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',650)->first();
    	  
	           }else if(isset($second_check_condition) && $second_check_condition->answer=='No'){
    	       $answer=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',651)->first();
	           }
    	
    	       if(isset($answer)){
    	       $beneficiary_name=DB::table('survey_form')->where('id',$id)->update(['cnic2'=>$answer->answer]);
    	       }
	    }
	    else if(isset($check_condition) && $check_condition->answer=='No') {
    	       $answer=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',2304)->first();
    	       if(isset($answer)){
    	       $beneficiary_name=DB::table('survey_form')->where('id',$id)->update(['cnic2'=>$answer->answer]);
    	       }
	    }
	      
	      
	  
	   return 'done';
	 
	   
	}
	}
	
	
	if(!function_exists('update_answer_for_name')){
	function update_answer_for_name($id){
	    $check_condition=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',646)->first();
	 
	    if(isset($check_condition) && $check_condition->answer=='Yes'){
    	       $answer=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',645)->first();
    	       if($answer->answer){
    	   
    	       $beneficiary_name=DB::table('survey_form')->where('id',$id)->update(['beneficiary_name'=>$answer->answer]);
    	       }
	    }
	           else if(isset($check_condition) && $check_condition->answer=='No') {
        	       $answer=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',648)->first();
        	       if($answer->answer){
        	   
        	       $beneficiary_name=DB::table('survey_form')->where('id',$id)->update(['beneficiary_name'=>$answer->answer]);
        	       }
	    }
	   return 'Done';
	 
	   
	}
	}
	


    
    
    /*
    if(!function_exists('upper_role_comment')){
    function upper_role_comment($survey_form_id, $status){
        $upper_role = '';
        $role = Role::find(Auth::user()->role) ?? null;  
        if($role->name == 'Field SuperVisor'){
        $upper_role = 'IP';
        }elseif($role->name == 'IP'){
        $upper_role = 'HRU';
        }
        
        $last_comment = DB::table('form_status')->where('form_id', $survey_form_id)->where('update_by', $upper_role)->where('form_status',$status)->orderBy('id','Desc')->first();
        if($last_comment){
        return $last_comment->comment;
        }else{
        return 'not available';
        }
    }}
    */
    
if(!function_exists('rejected_by_department')){
function rejected_by_department($a){
$unique_ids = FormStatus::where('update_by', $a)->where('form_status', 'R')->pluck('form_id')->unique()->all();
return $unique_ids;
}}



if(!function_exists('no_action_perform')){
function no_action_perform($a, $b){
if($b == 'IP'){
$upper = 'HRU';
}elseif($b == 'HRU'){
$upper = 'PSIA'; 
}elseif($b == 'PSIA'){
$upper = 'HRU_MAIN';
}elseif($b == 'HRU_MAIN'){
$upper = 'COO';
}elseif($b == 'COO'){
$upper = 'CEO';
}else{
$upper = '';    
}


$form_A = FormStatus::where('update_by', $a)->where('form_status', 'A')->get();
$form_P = FormStatus::where('update_by', $b)->where('form_status', 'P');
$form_R = FormStatus::where('update_by', $upper)->where('form_status', 'R'); //->whereNotIn('form_id',$remove_survey_form_ids);
$survey_form_ids = $form_P->select('form_id')->union($form_R->select('form_id'))->pluck('form_id');

$filter_data = [];
foreach ($form_A as $item) {
    $check_item = FormStatus::where('update_by', $b)->where('form_id', $item->form_id)->first();
    if(!$check_item){ $filter_data[] = $item->form_id; }
}
$filter_data = collect($filter_data);

$mergedCollection = $filter_data->merge($survey_form_ids);
$formStatuses = $mergedCollection->filter()->sort()->unique()->values()->all();
//return $formStatuses;

$unique_ids = [];
foreach($formStatuses as $item){
  $checkitem = FormStatus::where('update_by', $a)->where('form_id', $item)->whereIn('form_status', ['P','R'])->first();
  if(!$checkitem){ $unique_ids[] = $item; }
}
return $unique_ids;

    /*
    $formStatuses = FormStatus::where('update_by', $a)->where('form_status', 'A')->get();
    $final_data=[];
    foreach($formStatuses  as $item){
    $check_ip = FormStatus::where('update_by', $b)->where('form_id', $item->form_id)->first();
    if(!$check_ip){
        $final_data[]=$item->form_id;
    }}
    return $final_data;
    */

}}
    
    
    if(!function_exists('missing_document_highlight')){
    function missing_document_highlight($surveyId){
   
    $find_missing_document = DB::table('comment_missing_documents')->where('survey_id',$surveyId)->first();
    if($find_missing_document){
        return true;
    }
    else{
        return false;
    }
    }}
    
    if(!function_exists('missing_document_file')){
    function missing_document_file($sid, $qid){
    return DB::table("missing_document_files")->where('survey_id', $sid)->where('question_id', $qid)->first();
    }}
    
    
    //Functions End
    
    
    
    
//Master Report Summary function here
if(!function_exists('report_genderhousehold_count')){
function report_genderhousehold_count($id,$value){
 return SurveyData::where('district_id',$id)->where('gender',$value);
}}

if(!function_exists('report_disabilityhousehold_count')){
function report_disabilityhousehold_count($id,$value){
 return SurveyData::where('district_id',$id)->where('gender',$value)->where('disability','Yes');
}}

if(!function_exists('report_women_headed_household_count')){
function report_women_headed_household_count($id,$value){
 return SurveyData::where('district_id',$id)->where('gender',$value)->where('is_vulnerable_women','Yes');
}}

if(!function_exists('report_male_relatives_disabilities_count')){
function report_male_relatives_disabilities_count($id,$value){
 return SurveyData::where('district_id',$id)->where('gender',$value)->where('disability','Yes')->whereHas('getsection117', function ($q) {$q->where('q_971', 'Male');});
}}

if(!function_exists('report_q_2243_optionwise_count')){
function report_q_2243_optionwise_count($id,$gender,$option){
return SurveyData::where('district_id',$id)->where('gender',$gender)->where('is_vulnerable_women','Yes')->whereHas('getsection117', function ($q) use ($option){
            $q->whereRaw("JSON_CONTAINS(q_2243, JSON_OBJECT('option_id', ?), '$')", intval($option));
            });
}}

if(!function_exists('report_monthly_income_count')){
function report_monthly_income_count($id,$gender,$income1,$income2){
 return SurveyData::where('district_id', $id)->where('gender', $gender)->whereRaw("monthly_income REGEXP '^[0-9]+$'")->whereBetween('monthly_income', [$income1, $income2]);
}}

if(!function_exists('report_tranche_count')){
function report_tranche_count($id,$tranche){
return SurveyData::where('district_id', $id)->whereHas('getverifybeneficairytranche', function ($q) use ($tranche) {$q->where('trench_no', $tranche); });
}}

if(!function_exists('report_plint_stage1_count')){
function report_plint_stage1_count($id){
return Construction::where('district_id', $id)->where('stage', 'Stage 1')->where('action_condition', 3)->where('role_id', 48)->whereIn('status', ['P','C']);
}}

if(!function_exists('report_lintel_stage2_count')){
function report_lintel_stage2_count($id){
return Construction::where('district_id', $id)->where('stage', 'Stage 2')->where('action_condition', 3)->where('role_id', 48)->whereIn('status', ['P','C']);
}}

if(!function_exists('report_roof_stage3_count')){
function report_roof_stage3_count($id){
return Construction::where('district_id', $id)->where('stage', 'Stage 3')->where('action_condition', 3)->where('role_id', 48)->whereIn('status', ['P','C']);
}}

if(!function_exists('report_district_count')){
function report_district_count($id,$con){
 if($con == 'target'){
 return DB::table('ndma_verifications')->where('district',$id);
 }elseif($con == 'collect'){
 return DB::table('survey_form')->where('district_id',$id);
 }
}}

if(!function_exists('report_finance_activity_count')){
function report_finance_activity_count($id,$value){
 return SurveyData::where('district_id',$id)->whereHas('getfinanceactivity', function ($q) use($value) {$q->where('action', $value);});
}}

if(!function_exists('report_verify_beneficairy_count')){
function report_verify_beneficairy_count($id,$value){
 if($value == 1){   
 return SurveyData::where('district_id',$id)->whereHas('getverifybeneficairy', function ($q) use($value) {$q->where('trench_no', $value);});
 }elseif($value == 0){
 return SurveyData::where('district_id',$id)->whereHas('getverifybeneficairy', function ($q) use($value) {$q->whereNotNull('type')->where('trench_no', $value);});    
 }else{
 return 'N/A';
 }
 
 
}}


if(!function_exists('report_department_pending_count')){
function report_department_pending_count($status,$department,$district){
    return SurveyData::where('m_role_id', $department)->where('m_status', $status)->where('district_id', $district);
}}

if(!function_exists('report_department_rejected_count')){
function report_department_rejected_count($status,$department,$district){
    return SurveyData::where('m_last_action_role_id', $department)->where('m_last_action', $status)->where('district_id', $district);
}}

if(!function_exists('report_department_status_count')){
function report_department_status_count($status,$department,$district){
    return FormStatus::where('update_by', $department)->where('form_status', $status)->whereHas('surveyform', function ($q) use($district) {$q->where('district_id', $district);});
}}


if(!function_exists('report_department_cirtified_count')){
function report_department_cirtified_count($department,$district){
    return FormStatus::where('update_by', $department)->where('certification', 1)->whereHas('surveyform', function ($q) use($district) {$q->where('district_id', $district);});
}}


if(!function_exists('report_finance_column_wise_count')){
function report_finance_column_wise_count($status,$department,$district,$column,$col_val){
    if($status){
    return FormStatus::where('update_by', $department)->where('form_status', $status)->whereHas('surveyform', function ($q) use($district,$column,$col_val) {$q->where('district_id', $district)->where($column, $col_val);});
}else{
    return FormStatus::where('update_by', $department)->whereHas('surveyform', function ($q) use($district,$column,$col_val) {$q->where('district_id', $district)->where($column, $col_val);});
}
        
}}


if(!function_exists('report_finance_missing_document_column_wise_count')){
function report_finance_missing_document_column_wise_count($department,$district,$column,$col_val){
return FormStatus::where('update_by', $department)->whereNotIn('form_status', ['A','R'])->whereHas('surveyform', function ($q) use($district,$column,$col_val) {$q->where('district_id', $district)->where($column, $col_val);});
}}



function numberToWords($number) {
    $ones = [
        '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine',
        'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 
        'seventeen', 'eighteen', 'nineteen'
    ];

    $tens = [
        '', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'
    ];

    $thousands = [
        '', 'thousand', 'million', 'billion', 'trillion'
    ];

    if ($number == 0) {
        return 'zero';
    }

    $words = '';
    $place = 0;

    while ($number > 0) {
        if ($number % 1000 != 0) {
            $group = $number % 1000;
            $groupWords = '';

            // Process hundreds
            if ($group >= 100) {
                $groupWords .= $ones[(int)($group / 100)] . ' hundred';
                $group %= 100;
                if ($group > 0) {
                    $groupWords .= ' and ';
                }
            }

            // Process tens and ones
            if ($group < 20) {
                $groupWords .= $ones[$group];
            } else {
                $groupWords .= $tens[(int)($group / 10)];
                if ($group % 10 > 0) {
                    $groupWords .= ' ' . $ones[$group % 10];
                }
            }

            $words = $groupWords . ($thousands[$place] ? ' ' . $thousands[$place] : '') . ' ' . $words;
        }

        $number = (int)($number / 1000);
        $place++;
    }

    return trim($words);
}





if(!function_exists('get_comment')){
function get_comment($modal,$primaryId,$questionId){
	    $comment= DB::table('comment_soc_gen_envi')->where('model_name',$modal)->where('primary_id',$primaryId)->where('question_id',$questionId)->first();
	    return $comment;
	}}
if(!function_exists('get_section')){
function get_section($sectionId){
           $section=QuestionTitle::find($sectionId);	    return $section;
	}}




//End
