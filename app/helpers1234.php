<?php

use Illuminate\Support\Facades\DB; // Ensure you're using the correct namespace
use App\Models\FormStatus;
use App\Models\Question;
use App\Models\Option;
use App\Models\QuestionTitle;
use App\Models\Answer;
use App\Models\QuestionsAcceptReject;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use App\Models\User;
use App\Models\Role;
use App\Models\Complaint;
use App\Models\ComplaintFile;
use App\Models\ComplaintRemark;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


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
		
			
            $extension = $file->getClientOriginalExtension();
			$filerename = $filetitle.$key."_".$id."_".date('YmdHis').'.'. $extension;
			$images_name[]=$filefullname;
			//$filepath = $file->move(public_path('uploads/'.$path), $filerename);
            $filepath = $file->storeAs($path, $filerename, 'public');
			
			$uploadedFiles[] = [
                    'file_name' => $filerename,
                    'path' => $filepath,
                    'url' => Storage::url($filepath),
                    //'bytes' => $filepath->getSize(),
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
            $extension = $file->getClientOriginalExtension();
			$filerename = $filetitle."_".$id."_".date('YmdHis').'.'. $extension;
			//$filepath = $file->move(public_path('uploads/'.$path), $filerename);
			$filepath = $file->storeAs($path, $filerename, 'public');
            
            $uploadedFile = [
                    'file_name' => $filerename,
                    'path' => $filepath,
                    'url' => Storage::url($filepath),
                    //'url2' => Storage::disk('public')->url($filepath),
                    //'fileSize' => Storage::disk('public')->size($filepath),
                ];

        return response()->json([
            'message' => 'File processed successfully!',
            'files' => $uploadedFile,
            ]);
            
        }		
        }//END IF File EXIST
    }
    }
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
             $name='Rejected by COO'; 
        }
        else if(Auth::user()->role=='39'){
             $name='Rejected by CEO'; 
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
  function manage_report($survey_form_id,$status,$last_status=null,$new_status=null,$current_user,$last_action_user_id=null)
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
            
            $option = Option::find($id);
            if($option){
            return $option->name ?? '';
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
        $beneficiary_question_ans = Answer::where('survey_form_id',$sfid)->where('question_id', $qid)->first();
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
    	      $complaints = Complaint::all();
    	    }elseif(Auth::user()->role == 57){ 
    	        $complaints = Complaint::where('assign_to', Auth::user()->id);
            }else{
               $complaints = Complaint::where('id', 0);
            }
            
    	      return $complaints; 
        }
    }
    
    if(!function_exists('get_total_pending_complaint')){
        function get_total_pending_complaint(){
                if(Auth::user()->role == 56 || Auth::user()->role == 39 || Auth::user()->role == 1){
                $complaints = Complaint::where('status', 'Pending');
                }elseif(Auth::user()->role == 57){ 
                $complaints = Complaint::where('status', 'Pending')->where('assign_to', Auth::user()->id);
                }else{
                $complaints = Complaint::where('id', 0);
                }
    	      return $complaints; 
        }
    }
    
    if(!function_exists('get_total_inprocess_complaint')){
        function get_total_inprocess_complaint(){
                if(Auth::user()->role == 56 || Auth::user()->role == 39 || Auth::user()->role == 1){
                $complaints = Complaint::where('status', 'In Process');
                }elseif(Auth::user()->role == 57){ 
                $complaints = Complaint::where('status', 'In Process')->where('assign_to', Auth::user()->id);
                }else{
                $complaints = Complaint::where('id', 0);
                }
    	      return $complaints; 
        }
    }
    
    if(!function_exists('get_total_closed_complaint')){
        function get_total_closed_complaint(){
    	      if(Auth::user()->role == 56 || Auth::user()->role == 39 || Auth::user()->role == 1){
                $complaints = Complaint::where('status', 'Closed');
                }elseif(Auth::user()->role == 57){ 
                $complaints = Complaint::where('status', 'Closed')->where('assign_to', Auth::user()->id);
                }else{
                $complaints = Complaint::where('id', 0);
                }
    	      return $complaints; 
        }
    }
    
    if(!function_exists('get_total_returned_complaint')){
        function get_total_returned_complaint(){
    	      if(Auth::user()->role == 56 || Auth::user()->role == 39 || Auth::user()->role == 1){
                $complaints = Complaint::where('status', 'Returned');
                }elseif(Auth::user()->role == 57){ 
                $complaints = Complaint::where('status', 'Returned')->where('assign_to', Auth::user()->id);
                }else{
                $complaints = Complaint::where('id', 0);
                }
    	      return $complaints; 
        }
    }
    
    if(!function_exists('get_forward_total_complaint')){
    function get_forward_total_complaint()
    {
	
	if(Auth::user()->role == 56 || Auth::user()->role == 39 || Auth::user()->role == 1){
$complaint_ids = ComplaintRemark::whereDate('created_at', Carbon::today())->whereIn('status', ['Forward'])->pluck('complaint_id')->unique()->all();	
$complaints = Complaint::whereIn('status',['In Process','Pending','Requirement'])->whereIn('id', $complaint_ids);                
                }elseif(Auth::user()->role == 57){ 
                //$complaints = Complaint::where('status', 'Forward')->where('assign_to', Auth::user()->id);
$complaint_ids = ComplaintRemark::whereDate('created_at', Carbon::today())->whereIn('status', ['Forward'])->pluck('complaint_id')->unique()->all();	
$complaints = Complaint::whereIn('status',['In Process','Pending','Requirement'])->whereIn('id', $complaint_ids)->where('assign_to', Auth::user()->id);
                }else{
                $complaints = Complaint::where('id', 0);
                }
    	      return $complaints;	
		
    }
}
    
    
    
    //Today Complaints Counter
    if(!function_exists('get_today_total_complaint')){
        function get_today_total_complaint(){
            if(Auth::user()->role == 56 || Auth::user()->role == 39 || Auth::user()->role == 1){
    	       $complaints = Complaint::whereDate('created_at', Carbon::today());
    	    }elseif(Auth::user()->role == 57){ 
    	       $complaints = Complaint::whereDate('created_at', Carbon::today())->where('assign_to', Auth::user()->id);
            }else{
               $complaints = Complaint::where('id', 0);
            }
            
    	      return $complaints; 
        }
    }
    
    
    //Total Complaints Counter
    if(!function_exists('get_total_exclusioncases_complaint')){
        function get_total_exclusioncases_complaint(){
            if(Auth::user()->role == 56){
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
            if(Auth::user()->role == 56){
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
    $survey_form = DB::table('survey_json_split_6')->where('survey_id', $survey_form_id)->select('json')->first();
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
    $survey_form = DB::table('survey_json_split_6')->where('survey_id', $survey_form_id)->select('json')->first();
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
    
    $update_survey_form = DB::table('survey_json_split_6')->where('survey_id', $survey_form_id)->update(['json' => json_encode($form_data_array)]);
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
    //dd($survey_form_id);
    // $survey_form = DB::table('survey_form')
    //                          ->where('id', $survey_form_id)
    //                          ->select('form_data', 'id')
    //                          ->first();
      //dd($survey_form_id);
      
    $survey_form = DB::table('survey_json_split_6')
                             ->where('survey_id', $survey_form_id)
                             ->select('json as form_data', 'survey_id as id')
                             ->first();
                             
                        //dd($survey_form);

            if($survey_form){
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
            
            
            
            
        }//end check survey form
    
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
             $updated_by_name='COO'; 
        }
        elseif(Auth::user()->role=='39'){
             $updated_by_name='CEO'; 
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
        return $updated_by_name;
    }}
    
    
    
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
    
    //Functions End
