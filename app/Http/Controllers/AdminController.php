<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SurveyData;
use App\Models\SurveyJson;
use App\Models\MasterReport;
use App\Models\DataQueries;
use App\Models\MasterReportDetail;
use App\Models\FormStatus;
use App\Models\Answer;
use App\Models\User;
use App\Models\Lot;
use App\Models\District;
use App\Models\Tehsil;
use App\Models\Role;
use Illuminate\Support\Str; 
use Auth;
use DB;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function dashboardnewimg(Request $request){
        $answers = DB::table("answers")
        ->whereBetween("id", [4800000, 4850000])
        ->where("question_type", "image")
        ->where("answer","!=", null)->whereJsonContains("answer->fetchLocation", (object)[])->get();
        // dd($answers);
        foreach ($answers as $answer) {
            $image_details=json_decode($answer->answer);
            if(isset($image_details->fetchLocation) && isset($image_details->fetchLocation->latitude)){
                $longitude=$image_details->fetchLocation->longitude;
                $latitude=$image_details->fetchLocation->latitude;
                $image_location=$image_details;
                $image_name=$image_details->image->path;
                $fullPath = public_path('uploads/surveyform_files/' . $image_name);
                if (file_exists($fullPath)) {
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
               
                }
                
            }
        }
          return ['success' => 'Media file uploaded and geotagged successfully.'];
        dd('done');
    }
    public function setStatus(Request $request) {
   


    // Get all forms with status 'R' updated by HRU
    $form_status = DB::table('form_status')
                     ->where('update_by', 'CEO')
                     ->where('form_status', 'R')
                      ->get();
                   
                     
                 
                       
                
                  

    // Flag to track if any match was found
    $statusFound = false;

    // Iterate over each form status
    foreach ($form_status as $item) {
      
        // Get the junior form status that matches the criteria
        $junior_form_status = DB::table('form_status')
                                 ->where('update_by', 'HRU_Main')
                                 ->where('form_status', 'A')
                                 ->where('form_id', $item->form_id)
                                 ->first();
       dd($junior_form_status);
       
                         
                                          

        // If junior form status is found
        if ($junior_form_status) {
             
         
            // Uncomment to update the form status to 'P'
            DB::table('form_status')
              ->where('update_by', 'HRU_Main')
              ->where('form_status', 'A')
              ->where('form_id', $item->form_id)
              ->update([
                  'form_status' => 'P'
              ]);
             
              
              DB::table('dummy')->insert([
                'name'=>$item->form_id,
                'note'=>'this query run for ceo in the table of form status'
                ]);
              
                  dd($junior_form_status);
                  
            

            // Flag that a match was found and break the loop if needed
            $statusFound = true;

            // Optional: Log information or perform additional actions
            Log::info('Updated form status for form_id: ' . $item->form_id);

            // If you want to stop after the first match, you can break the loop here
            // break;
        }
    }

    // Final output after loop
    if ($statusFound) {
        // Success message or additional logic
        dd("done");
    } else {
        // No matches found
        dd("not found");
    }
}

    public function dashboard(Request $requets){
        
        
        //dd($requets);
        
        
        $user=User::count();
        
        $lot=Lot::where('status',1)->count();
        $district=District::where('status',1)->count();
        $tehsil=Tehsil::where('status',1)->count();
        $survey_form=DB::table('survey_form')->count();
        $total_ndma_data=DB::table('ndma_verifications')->count();
        
        
    //     $result = DB::select(DB::raw("
    //     SELECT 
    //         lots.name AS lotName,
    //         lots.id AS lotId,
    //         districts.id as districtId,
    //         districts.name AS district,
    //         total_beneficiaries.total_beneficiary,
    //         validated_beneficiaries.validated_beneficiary
    //     FROM 
    //         lots
    //     LEFT JOIN 
    //         districts ON lots.id = districts.lot_id
    //     LEFT JOIN (
    //         SELECT 
    //             district, 
    //             COUNT(DISTINCT id) AS total_beneficiary
    //         FROM 
    //             ndma_verifications
    //         GROUP BY 
    //             district
    //     ) AS total_beneficiaries ON districts.id = total_beneficiaries.district
    //     LEFT JOIN (
    //         SELECT 
    //             district_id, 
    //             COUNT(DISTINCT id) AS validated_beneficiary
    //         FROM 
    //             vu_survey_formReport
    //         GROUP BY 
    //             district_id
    //     ) AS validated_beneficiaries ON districts.id = validated_beneficiaries.district_id
    //     GROUP BY lots.id,districts.id,lots.name,
    //         districts.name,
    //         total_beneficiaries.total_beneficiary,
    //         validated_beneficiaries.validated_beneficiary
            
    // "));
            $result=null;
            $lot_wise=null;
    
    
//         $lot_wise = DB::select(DB::raw("
//     SELECT 
//         lots.name AS lotName,
//         lots.id AS lotId,
//         COALESCE(SUM(total_beneficiaries.total_beneficiary), 0) AS total_beneficiary,
//         COALESCE(SUM(validated_beneficiaries.validated_beneficiary), 0) AS validated_beneficiary
//     FROM 
//         lots
//     LEFT JOIN districts ON lots.id = districts.lot_id
//     LEFT JOIN (
//         SELECT 
//             district, 
//             COUNT(DISTINCT id) AS total_beneficiary
//         FROM 
//             ndma_verifications
//         GROUP BY 
//             district
//     ) AS total_beneficiaries ON districts.id = total_beneficiaries.district
//     LEFT JOIN (
//         SELECT 
//             district_id, 
//             COUNT(DISTINCT id) AS validated_beneficiary
//         FROM 
//             vu_survey_formReport
//         GROUP BY 
//             district_id
//     ) AS validated_beneficiaries ON districts.id = validated_beneficiaries.district_id
//     GROUP BY 
//         lots.id, lots.name
// "));
    
        
        
return view('dashboard.index',['user'=>$user,'lot'=>$lot,'district'=>$district,'tehsil'=>$tehsil,'survey_form'=>$survey_form,'total_ndma_data'=>$total_ndma_data,'result'=>$result,'lot_wise'=>$lot_wise]);
    
        
        
    }
    
    
    function removeBase64($id)
        {
            
          $survey_form = DB::table('survey_form')
                         ->where('id', $id)
                         ->select('form_data', 'id')
                         ->first();
        if ($survey_form) {
  
            $survey_form_data = json_decode($survey_form->form_data);
            return $survey_form_data; 
            $dataToInsert = [];
            foreach ($survey_form_data->sections as $key => $item) {
                $form_id = $item->section->form_id;
                $section_id = $item->section->id;
                foreach ($item->questions as $quest) {
                    $checkbox_ids=[];
                    $map=[];
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
                DB::table('answers')->insert($dataToInsert);
            }
        }
        return 'done';
        
        }
        
        
        function saveImage(){
        //echo "Function is comment";
        // 154911
   
   $survey_forms = DB::table('survey_form')->whereBetween('id', [143179, 154911])->select('id')->get(); 
   //dd($survey_forms->count());
   
   foreach($survey_forms as $survey_form){
   $result = json_form_data_all_question_modified($survey_form->id);
   //echo $result->getData()->message."<br />"; 
   }
   echo 'Done'; 
   
        
        
        
            
            
   /* 
   $answers = DB::table('answers')
    ->where('question_type', 'image')
    ->whereNotNull('answer')
    ->whereBetween('id', [4829856, 4829856]) 
    ->select('id')
    ->get();
   
  

    
   foreach($answers as $answer){
    $file = base64_file_save($answer->id);
    if($file->getStatusCode() === 200) {
    $filepath = $file->getData()->image;
    $result = base64_remove_data($answer->id, $filepath);
    //echo $result->getData()->message;
    }
   }
   
   echo 'Done'; 
   */

   


     


 
    
    
    


}


function saveSurveyJson($start,$end){
    $survey_forms = SurveyData::whereBetween('id', [$start,$end])->select('id')->get();
    foreach($survey_forms as $survey_form) {
        $survey_json_not_exist = SurveyJson::where('survey_id', $survey_form->id)->get();
        $surveyform = SurveyData::where('id', $survey_form->id)->select('id', 'form_data')->first();
        if($survey_json_not_exist->count() > 0) {
        }else{
            SurveyJson::create([
                'survey_id' => $surveyform->id,
                'json' => $surveyform->form_data
            ]);
        }
    }
    
    echo 'Survey Json Done';
}



function saveReport(){

$master_report = MasterReport::pluck('survey_id');  




$master_report_exist = SurveyData::whereNotIn('id', $master_report)
->select('id')
// ->take(1)
->get();
// dd($master_report_exist);




foreach($master_report_exist as $item){
   
   $survey_form = SurveyData::where('id', $item->id)->first(); 
   $form_status= DB::table('form_status')->insert([
    'form_status'=>'P',
    'user_id'=>$survey_form->user_id,
    'form_id'=>$survey_form->id,
    'update_by'=>'field supervisor',
    'user_status'=>$survey_form->user_id,
    'created_at'=>$survey_form->created_at
    ]);
   
   $master_report = MasterReport::create([
    'survey_id' => $survey_form->id,
    'lot_id' => $survey_form->lot_id,
    'district_id' => $survey_form->district_id,
    'tehsil_id' => $survey_form->tehsil_id,
    'uc_id' => $survey_form->uc_id,
    'role' => 'field supervisor',
    'user_id' => $survey_form->user_id,
    'last_action_by' => $survey_form->user_id,
    'form_type' => 'dammage assessment',
    'form_id' => 8,
    'last_status' => 'P',
    'new_status' => 'P',
    'last_action_user_id' => $survey_form->user_id
]);
    $master_report_detail = MasterReportDetail::create([
        'maaster_report_id' => $master_report->id,
        'survey_id' => $survey_form->id,
        'lot_id' => $survey_form->lot_id,
        'district_id' => $survey_form->district_id,
        'tehsil_id' => $survey_form->tehsil_id,
        'uc_id' => $survey_form->uc_id,
        'role' => 'field supervisor',
        'user_id' => $survey_form->user_id,
        'last_action_by' => $survey_form->user_id,
        'form_type' => 'dammage assessment',
        'form_id' => 8,
        'last_status' => 'P',
        'new_status' => 'P',
        'last_action_user_id' => $survey_form->user_id
    ]);   
    
        DB::table('report_ids')->insert([
            'survey_id'=>$survey_form->id,    
            'master_report_id'=>$master_report->id,    
            'status'=>'P',
            'update_by'=>'field supervisor',
            'user_id'=>$survey_form->user_id,
            'note'=>'insert'
            
            
        ]);

    
    
}




   echo 'Save Report Done';
}



function saveReport_old(){

$master_report = MasterReport::pluck('survey_id')
// ->toArray()
;  
// dd($master_report);


// $uniqueArray = array_values(array_unique($master_report));

$master_report_exist = SurveyData::whereNotIn('id', $master_report)
// ->where('update_by','field supervisor')
->select('id','user_id','created_at')
// ->select('form_id','update_by','user_id','form_status')->orderBy('form_id','asc')
->take(2)
->get();



dd($master_report_exist);




foreach ($master_report_exist as $item) {
    
    // Check if any MasterReport has survey_id not in form_id list
    $survey_form = SurveyData::where('id', $item->form_id)->first(); 
  
    $master_report_latest = FormStatus::where('form_id', $item->form_id)->orderBy('id','DESC')->first();

 
    if($master_report_latest->form_status=='A'){
        
        if($master_report_latest->update_by=='field supervisor'){
            $role='IP';
        }
        else if($master_report_latest->update_by=='IP'){
            $role='HRU';
        }
        else if($master_report_latest->update_by=='HRU'){
            $role='PSIA';
        }
        else if($master_report_latest->update_by=='PSIA'){
            $role='HRU_MAIN';
        }
        else if($master_report_latest->update_by=='HRU_MAIN'){
            $role='COO';
        }
        else if($master_report_latest->update_by=='COO'){
            $role='CEO';
        }else if($master_report_latest->update_by=='CEO'){
            $role='Finance';    
        }else{
            $role='field supervisor';
        }
    }
    if($master_report_latest->form_status=='R'){
        
        if($master_report_latest->update_by=='field supervisor'){
            $role='validator';
        }
        else if($master_report_latest->update_by=='IP'){
            $role='field supervisor';
        }
        else if($master_report_latest->update_by=='HRU'){
            $role='IP';
        }
        else if($master_report_latest->update_by=='PSIA'){
            $role='HRU';
        }
        else if($master_report_latest->update_by=='HRU_MAIN'){
            $role='PSIA';
        }
        else if($master_report_latest->update_by=='COO'){
            $role='HRU_MAIN';
        }else if($master_report_latest->update_by=='CEO'){
            $role='COO';    
        }else{
            $role=$master_report_latest->update_by;
        }
    }
    if($master_report_latest->form_status=='H' || $master_report_latest->form_status=='P'){
        
        $role=$master_report_latest->update_by;
    }
    if(isset($survey_form->id)){

   
   
    $master_report_exist_data = MasterReport::where('survey_id', $survey_form->id)->first();
   
    if(!$master_report_exist_data){
        
    $master_reportt = MasterReport::create([
            'survey_id' => $survey_form->id,
            'lot_id' => $survey_form->lot_id,
            'district_id' => $survey_form->district_id,
            'tehsil_id' => $survey_form->tehsil_id,
            'uc_id' => $survey_form->uc_id,
            'role' => $role,
            'user_id' => $master_report_latest->user_id,
            'form_type' => 'dammage assessment',
            'form_id' => 8,
            'last_status' => $master_report_latest->form_status,
            'new_status' => $master_report_latest->form_status,
            'last_action_user_id' => $master_report_latest->user_id
        ]);
        DB::table('report_ids')->insert([
            'survey_id'=>$survey_form->id,    
            'master_report_id'=>$master_reportt->id,    
            'status'=>$master_report_latest->form_status,
            'update_by'=>$role,
            'user_id'=>$master_report_latest->user_id
            
            
        ]);
    //dump($master_reportt);
    
    $master_report_latest_all = FormStatus::where('form_id', $item->form_id)->get();
    $status_array=['field supervisor','IP','HRU','PSIA','HRU_MAIN','COO','CEO'];
    $i=0;
    foreach($master_report_latest_all as $master_report_latest_single){
        
        
    //     if($master_report_latest_single->update_by=='field supervisor'){
    //     $rolee='IP';
    // }
    // else if($master_report_latest_single->update_by=='IP'){
    //     $rolee='HRU';
    // }
    // else if($master_report_latest_single->update_by=='HRU'){
    //     $rolee='PSIA';
    // }
    // else if($master_report_latest_single->update_by=='PSIA'){
    //     $rolee='HRU_MAIN';
    // }
    // else if($master_report_latest_single->update_by=='HRU_MAIN'){
    //     $rolee='COO';
    // }
    // else if($master_report_latest_single->update_by=='COO'){
    //     $rolee='CEO';
    // }else{
    //     $rolee='field supervisor';
    // }
    
      if($master_report_latest_single->form_status=='A'){
        
        if($master_report_latest_single->update_by=='field supervisor'){
            $role='IP';
        }
        else if($master_report_latest_single->update_by=='IP'){
            $role='HRU';
        }
        else if($master_report_latest_single->update_by=='HRU'){
            $role='PSIA';
        }
        else if($master_report_latest_single->update_by=='PSIA'){
            $role='HRU_MAIN';
        }
        else if($master_report_latest_single->update_by=='HRU_MAIN'){
            $role='COO';
        }
        else if($master_report_latest_single->update_by=='COO'){
            $role='CEO';
        }else if($master_report_latest_single->update_by=='CEO'){
            $role='Finance';    
        }else{
            $role='field supervisor';
        }
    }
    if($master_report_latest_single->form_status=='R'){
        
        if($master_report_latest_single->update_by=='field supervisor'){
            $role='validator';
        }
        else if($master_report_latest_single->update_by=='IP'){
            $role='field supervisor';
        }
        else if($master_report_latest_single->update_by=='HRU'){
            $role='IP';
        }
        else if($master_report_latest_single->update_by=='PSIA'){
            $role='HRU';
        }
        else if($master_report_latest_single->update_by=='HRU_MAIN'){
            $role='PSIA';
        }
        else if($master_report_latest_single->update_by=='COO'){
            $role='HRU_MAIN';
        }else if($master_report_latest_single->update_by=='CEO'){
            $role='COO';    
        }else{
            $role=$master_report_latest_single->update_by;
        }
    }
    if($master_report_latest_single->form_status=='H' || $master_report_latest_single->form_status=='P'){
        
        $role=$master_report_latest_single->update_by;
    }
        
      
        $master_report_detail = MasterReportDetail::create([
            'maaster_report_id' => $master_reportt->id,
            'survey_id' => $survey_form->id,
            'lot_id' => $survey_form->lot_id,
            'district_id' => $survey_form->district_id,
            'tehsil_id' => $survey_form->tehsil_id,
            'uc_id' => $survey_form->uc_id,
            'role' => $status_array[$i],
            'user_id' => $master_report_latest_single->user_id,
            'form_type' => 'dammage assessment',
            'form_id' => 8,
            'last_status' => $master_report_latest_single->form_status,
            'new_status' => $master_report_latest_single->form_status,
            'last_action_user_id' => $master_report_latest_single->user_id
        ]);
        //dump($master_report_detail);
          $i++;
    }
}//Check MR dublication
    
    
}
 }


  echo 'Save Report Done';
}









function saveReport123(){

   
   $survey_forms = SurveyData::whereBetween('id', [143179, 159528])->select('id')->get(); 
   //dd($survey_forms->count());
   
   foreach($survey_forms as $survey_form_id){
        $master_report_exist = MasterReport::where('survey_id', $survey_form_id->id)->get();
        if($master_report_exist->count() > 0){
            //echo 'Exist';
        }else{
        $survey_form = SurveyData::where('id', $survey_form_id->id)->first();   
        if($survey_form){
        //dump($survey_form);
        $master_report = MasterReport::create([
            'survey_id' => $survey_form->id,
            'lot_id' => $survey_form->lot_id,
            'district_id' => $survey_form->district_id,
            'tehsil_id' => $survey_form->tehsil_id,
            'uc_id' => $survey_form->uc_id,
            'role' => 'field supervisor',
            'user_id' => $survey_form->user_id,
            'last_action_by' => $survey_form->user_id,
            'form_type' => 'dammage assessment',
            'form_id' => 8,
            'last_status' => 'P',
            'new_status' => 'P',
            'last_action_user_id' => $survey_form->user_id
        ]);
        $master_report_detail = MasterReportDetail::create([
            'maaster_report_id' => $master_report->id,
            'survey_id' => $survey_form->id,
            'lot_id' => $survey_form->lot_id,
            'district_id' => $survey_form->district_id,
            'tehsil_id' => $survey_form->tehsil_id,
            'uc_id' => $survey_form->uc_id,
            'role' => 'field supervisor',
            'user_id' => $survey_form->user_id,
            'last_action_by' => $survey_form->user_id,
            'form_type' => 'dammage assessment',
            'form_id' => 8,
            'last_status' => 'P',
            'new_status' => 'P',
            'last_action_user_id' => $survey_form->user_id
        ]);
        //echo 'New';
        }//check survey_form
        }
   
   
   
   }
   echo 'Save Report Done';
}



//Added By Ayaz
    public function userrejected(){ 
       
         $allow_to_update_form=DB::table('roles')
        ->join('users','users.role','=','roles.id')
        ->where('users.id',Auth::user()->id)
        ->select('allow_to_update_form')->first() ?? null ;
       
         $role = Role::find(Auth::user()->role) ?? null;
         $authenticate_user_uc=json_decode(Auth::user()->uc_id);
       
          
         $survey_data =DB::table('form_status')
         ->join('users','form_status.user_id','=','users.id')
         ->join('survey_form','form_status.form_id','=','survey_form.id')
         ->join('form','survey_form.form_id','=','form.id')
         ->join('roles','users.role','=','roles.id')
         ->select('form_status.id as form_status_id','users.name as validator_name','form_status.form_status',
         'form.name as form_name','survey_form.id as survey_form_id','form_status.is_m_and_e','survey_form.generated_id','roles.name as role_name','users.email as email','form_status.comment','survey_form.priority as priority','survey_form.beneficiary_details','survey_form.created_at as submission_date')
         
         ->where('form_status.update_by', $role->name)
         ->where('form_status.form_status','R')
         
         ->whereIn('survey_form.uc_id', $authenticate_user_uc)
         
         ->orderBy('survey_form.priority','Desc')
         ->get()->toArray();
         
         //dd($survey_data);
         
           
         
 
         
        
         
         
        return view('dashboard.survey.reject_form',['survey_data'=>$survey_data]);
    }//end userrejected
    
    
    public function destructure_test_ayis(){
        //$survey_forms = SurveyData::select('id')->get();
        //$survey_forms = SurveyData::whereBetween('id', [1,2])->select('id')->get();
        $survey_forms = SurveyData::where('id', 127294)->select('id')->get();
        //$survey_forms = SurveyData::where('id', 105220)->select('id')->get();
        
        //dd($survey_forms->count());
        foreach($survey_forms as $sid){
          //$answer_check = Answer::where('survey_form_id', $id->id)->select('id')->first();  
        
        
        $allquestions = Answer::where('survey_form_id', $sid->id)->pluck('question_id');
        //dd($allquestions);
        foreach($allquestions as $question_id){
          $answer_count = Answer::where('survey_form_id', $sid->id)->where('question_id', $question_id)->select('id','question_id')->get();
          //$answer_count = Answer::where('survey_form_id', $sid->id)->where('question_id', 475)->select('id','question_id')->orderBy('id', 'asc')->get();
          
          if ($answer_count->count() > 1) {
              
    // Get the first record
    $firstAnswer = $answer_count->first();

    // Delete all records except the first one
    
    $res = Answer::where('survey_form_id', $sid->id)
        ->where('question_id', $question_id)
        ->where('id', '!=', $firstAnswer->id)
        ->get();
        //->delete();
        
        dump($res);
        
        
        
}
          
        }
        
        }
        
    }
    
    function destructure_testt123(){
    $survey_form_ids=DB::table('survey_form')->pluck('id');
    foreach($survey_form_ids as $item){
    update_answer_for_cnic($item);
      
    }
        
    }
    
    
    function destructure_testt_123(){
    
     dd('Off');
      
    $get_survey_ids = Answer::select('survey_form_id')
    ->selectRaw('COUNT(survey_form_id) AS survey_ans_count')
    ->groupBy('survey_form_id')
    ->havingRaw('COUNT(survey_form_id) > 400')
    ->orderByDesc(DB::raw('COUNT(survey_form_id)'))
    ->limit(5000)
    ->pluck('survey_form_id');
    
    //dd($get_survey_ids); 
      
    foreach ($get_survey_ids as $sid) {
        $allquestion_ids = Answer::where('survey_form_id', $sid)->pluck('question_id');
        foreach($allquestion_ids as $question_id){
            $answer_count = Answer::where("survey_form_id", $sid)->where("question_id", $question_id)->select("id", "question_id")->orderBy("id", "asc")->get();
            if ($answer_count->count() > 1) {
                $firstAnswer = $answer_count->first();
                //$lastAnswer =  $answer_count->last();
                $res = Answer::where("survey_form_id", $sid)
                    ->where("question_id", $question_id)
                    ->where("id", "!=", $firstAnswer->id) // Exclude the first record
                    //->where("id", "!=", $lastAnswer->id)  // Exclude the last record
                    //->get();
                    ->delete();
                    //dump($res->count());
            }
    }
        
    }
    
    echo "Done";
    
}
    
    
    
    
   public function destructure_test(){
      //dump(no_action_perform('field supervisor', 'IP'));
      
      $district_id = 23;
      //$data = report_finance_column_wise_count('', 'HRU_MAIN', $district_id, 'evidence_type', 'No Evidence Available') ?? 0;
      $data = report_finance_missing_document_column_wise_count('HRU_MAIN', $district_id, 'evidence_type', 'No Evidence Available') ?? 0;
      dump($data->pluck('form_id')->all());
      
      //$data = FormStatus::where('update_by', 'HRU_MAIN')->whereNotIn('form_status', ['A','R'])->whereHas('surveyform', function ($q) {
      //    $q->where('district_id', 23)->where('evidence_type','No Evidence Available');
      //});
      
      //dump($data->count());
      
      
   } 
    
    
    
    
    public function destructure_test123(){
        //  $id = 131072;
       //dd('ok');
         //$survey_forms = SurveyData::whereBetween('id', [170327,170328])->select('id')->get();
         //$survey_forms = SurveyData::whereBetween('id', [168496,170326])->select('id')->get();
         //$survey_forms = SurveyData::whereBetween('id', [1,110000])->select('id')->get();
         //$survey_forms = SurveyData::whereBetween('id', [110001,120000])->select('id')->get();
         //$survey_forms = SurveyData::whereBetween('id', [120001,135000])->select('id')->get();
         //$survey_forms = SurveyData::whereBetween('id', [135001,150000])->select('id')->get();
         //$survey_forms = SurveyData::whereBetween('id', [150001,170500])->select('id')->get();
         
         //$survey_forms = SurveyData::whereBetween('id', [120001,180000])->select('id')->get();
         
         $survey_forms = SurveyData::where('id', 131072)->select('id')->get();
         
         //dd($survey_forms->count());
         
         foreach($survey_forms as $id){
         $answer_check = Answer::where('survey_form_id', $id->id)->select('id')->first();
         if(!$answer_check){
         //destructure_form_new($id->id); 
         echo "Insert";
         }else{
          echo "Exist";   
         }
             
         }
         echo "done";
        
         
         
    }
    
    
    
    public function making_images($id){
	       $image_ids = DB::table('answers')->where('survey_form_id',$id)->where('question_type','image')->select('id','section_id','question_id')->get();
	    
	       
              
            foreach($image_ids as $answer){
                       
                        $file = base64_file_save($answer->id);
                        $image = DB::table('answers')->where('id', $answer->id)->select('survey_form_id','section_id','question_id','answer')->first();
                        dd($image->answer==null);
                        if($image->answer !=null || $image->answer !="67_86_784_20241207142232"){
                            dd($image);
                        }
                    
                        $imageRename = $image->survey_form_id.'_'.$image->section_id.'_'.$image->question_id.'_'.date('YmdHis');
                        dd($imageRename);
                      
                      
                        
                        
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
                        

                    
                    if($file->getStatusCode() === 200) {
                    $filepath = $file->getData()->image;
                    $result = base64_remove_data($answer->id, $filepath);
                
                
                    
                }
 
                }
	    
	    
	    
	}
	public function updateAnswer(){
	   // 173749
	    $ids = DB::table('survey_form')->whereBetween('id', [1, 184127])->whereNull('cnic2')
	   // ->take(1)
	    ->pluck('id');
	    dd($ids);
	 
	   // dd($ids);
	   // $ids=[100831];

	   foreach($ids as $id){
	    $check_condition=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',646)->first();
	    
	    
	    if(isset($check_condition) && $check_condition->answer=='Yes'){
	           $second_check_condition=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',649)->first();
	       
	           
	           if(isset($second_check_condition) && $second_check_condition->answer=='Yes'){
    	       $answer=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',650)->first();
    	   
	           }else if(isset($second_check_condition) && $second_check_condition->answer=='No'){
    	       $answer=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',651)->first();
	           }
    	 
    	       if($answer){
    	       $beneficiary_name=DB::table('survey_form')->where('id',$id)->update(['cnic2'=>$answer->answer]);
    	       }
	    }
	    else if($check_condition && $check_condition->answer=='No') {
    	       $answer=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',2304)->first();
    	       if($answer){
    	       $beneficiary_name=DB::table('survey_form')->where('id',$id)->update(['cnic2'=>$answer->answer]);
    	       }
	    }
	       
	       
	   }
	   echo 'done';
	  
	    
	}
	public function updateAnswerForName(){

	    $ids = DB::table('survey_form')->whereBetween('id', [1, 184127])->whereNull('beneficiary_name')
	    ->take(10)
	    ->pluck('id');

	   foreach($ids as $id){
	    $check_condition=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',646)->first();
	  
	    if(isset($check_condition) && $check_condition->answer=='Yes'){
    	       $answer=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',645)->first();
    	       if($answer->answer){
    	    
    	       $beneficiary_name=DB::table('survey_form')->where('id',$id)->update(['beneficiary_name'=>$answer->answer]);
    	       }
	    }
	           else if($check_condition && $check_condition->answer=='No') {
        	       $answer=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',648)->first();
        	       if($answer->answer){
        	    
        	       $beneficiary_name=DB::table('survey_form')->where('id',$id)->update(['beneficiary_name'=>$answer->answer]);
        	       }
	    }
	       
	       
	   }
	   echo 'done';
	  
	    
	}
	public function updateAnswerForContactNumber(){

	    $ids = DB::table('survey_form')->whereNull('beneficiary_number')
	    ->pluck('id');
	   foreach($ids as $id){
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
    	     DB::table('dummy')->insert([
                'name'=>$id,
                'note'=>'update_beneficiary_number'
                ]);
    	 }
	   }
	   echo 'done';
	  
	    
	}
	
	
	public function revetToCEO(){
        $form = DB::table('form_status')
        ->join('survey_form', 'form_status.form_id', '=', 'survey_form.id')
        ->join('tehsil', 'survey_form.tehsil_id', '=', 'tehsil.id')
        ->join('districts', 'survey_form.district_id', '=', 'districts.id')
        ->join('uc', 'survey_form.uc_id', '=', 'uc.id')
        ->select('survey_form.ref_no')
        ->where('survey_form.bank_ac_wise', 'No')
        ->where('form_status.form_status', 'A')
        ->where('form_status.update_by', 'CEO')
        ->pluck('survey_form.ref_no');  // Use pluck to get a single column array
      
      

	    
	    foreach($form as $id){
	    $survey_id=DB::table('survey_form')->where('ref_no',$id)->select('id')->first();
	    if($survey_id->id){
	        $remove_ceo_status=DB::table('form_status')->where('form_id',$survey_id->id)->where('update_by','CEO')->first();
	        if($remove_ceo_status){
	            $remove_ceo_status=DB::table('form_status')->where('form_id',$survey_id->id)->where('update_by','CEO')->delete();
	            DB::table('master_report')->where('survey_id',$survey_id->id)->update(['role'=>'CEO']);
	            DB::table('master_report_detail')->where('survey_id',$survey_id->id)->where('role','Finance')->delete();
	        }
	    }
	    
	    }
	    
	    echo "done";
	    
	    
	}
	public function deleteDuplicate(){
            $ref_no=[70883,
            71481,
            71937,
            106612,
            111964,
            140416,
            146609,
            147117,
            160188,
            161113,
            161509,
            161970,
            161987,
            162447,
            180387,
            182653,
            183146,
            183588,
            183589,
            183590,
            183623,
            183624,
            185019,
            185020,
            185046,
            185078,
            185870,
            185936,
            186179,
            186194,
            186282,
            187926,
            188696,
            188751,
            188754,
            188755,
            190590,
            190593,
            192357,
            192363,
            192372,
            192393,
            192596,
            194219,
            195279,
            195376,
            196933,
            197570,
            197573,
            198573,
            198669,
            198670,
            198764,
            200029,
            204123,
            205012,
            205117,
            205124,
            215926,
            215961,
            215963,
            216041,
            220128,
            220164,
            221173,
            221255,
            221258,
            221261,
            221328,
            233367,
            235286,
            237384,
            238784,
            238955,
            240780,
            243351,
            244096,
            254243,
            255557,
            258220,
            258223,
            258224,
            258625,
            261210,
            263462,
            266586,
            267943,
            267944,
            284426,
            301417,
            303538,
            303539,
            303543,
            303545,
            303787,
            305562,
            305850,
            309998,
            310949,
            311018,
            312696,
            315064,
            317045,
            321984,
            322000,
            322426,
            353723,
            353943,
            355268,
            355285,
            355295,
            355311
            ];
            dd('stop');
	        foreach($ref_no as $item){
	         
                $data = SurveyData::where('ref_no', $item)->first();
                if ($data) {
                    $data->delete();
                }
               
	        }
	        return "done";
	}
	
	public function found_data(){
	    dd("stop");
	    $required_ids=[];
	    $ids=DB::table('survey_form')->pluck('id');
	    foreach($ids as $id){
	        $check=DB::table('form_status')->where('form_id',$id)->first();
	        if(!$check){
	            $required_ids[]=$id;
	            DB::table('save_tempory_data')->insert(['survey_id'=>$id]);
	        }
	    }
       dd("done");
	}
	
public function correctDataProcess(Request $request)
{
    // Fetch the survey form data
    dd('stop');
    $data=[201531, 201563, 201595, 201508, 201542, 201574, 201606, 201521, 201553, 201585, 201617, 201498, 201532, 201564, 201596, 201510, 201543, 201575, 201607, 201522, 201554, 201586, 201618, 201499, 201533, 201565, 201597, 201511, 201544, 201576, 201608, 201555, 201587, 201619, 201500, 201534, 201566, 201598, 201545, 201577, 201609, 201489, 201524, 201556, 201588, 201620, 201501, 201535, 201567, 201599, 201514, 201546, 201578, 201610, 201490, 201557, 201589, 201621, 201502, 201536, 201568, 201600, 201515, 201547, 201579, 201611, 201491, 201526, 201558, 201590, 201622, 201503, 201537, 201569, 201601, 201516, 201548, 201580, 201612, 201527, 201559, 201591, 201623, 201504, 201538, 201570, 201602, 201517, 201549, 201581, 201613, 201493, 201528, 201560, 201592, 201624, 201505, 201539, 201571, 201603, 201518, 201550, 201582, 201614, 201494, 201529, 201561, 201593, 201625, 201506, 201540, 201572, 201604, 201519, 201551, 201583, 201615, 201530, 201562, 201594, 201626, 201541, 201573, 201605, 201520, 201552, 201584, 201616];
    foreach($data as $id){
    $survey_form = DB::table('survey_json')->where('survey_id', $id)->first();
        
  
    
    // Check if survey form exists
    if (!$survey_form) {
        return response()->json(['error' => 'Survey form not found'], 404);
    }

   


       
        DB::table('survey_json_extra')->insert([
            'json' => $survey_form->json,
            'survey_id' => $survey_form->survey_id
        ]);

  
        
            $delete_first_data = SurveyJson::where('survey_id', $id)->first();
            
            if ($delete_first_data) {
        
                $delete_first_data->delete();
            }


        // Fetch the associated answers
        $answers = DB::table('answers')->where('survey_form_id', $id)->get();

        // Check if answers exist
        if ($answers->isNotEmpty()) {
            // Prepare data for bulk insert
            $answers_data = $answers->map(function ($answer) use ($id) {
                return [
                    'form_id' => $answer->form_id,
                    'section_id' => $answer->section_id,
                    'question_id' => $answer->question_id,
                    'answer' => $answer->answer,
                    'question_type' => $answer->question_type,
                    'survey_form_id' => $answer->survey_form_id
                ];
            })->toArray();
        

            // Bulk insert into 'answers_extra'
            DB::table('answers_extra')->insert($answers_data);
                
                
            // Delete from 'answers'
            DB::table('answers')->where('survey_form_id', $id)->delete();
        }

        // Call destructure_form_new function
      
        destructure_form_new($id);

    
        
    

}

    // Return success response
    return response()->json(['success' => 'Data processed successfully'], 200);

}   

public function update_answer_for_name_data(){
    $data = DB::table('survey_form')
    ->whereNull('beneficiary_name')
    ->pluck('id');
 
    
  
  

    foreach($data as $id){
    DB::table('dummy')->insert([
        'name'=>$id,
        'note'=>'update beneficiary name'
        
    ]);    
    update_answer_for_name($id);
}
    dd("ok done");
}
public function update_answer_for_cnic(){
    $data=DB::table('survey_form')->whereNull('cnic2')->select('id')->get();
  
    foreach($data as $id){
        
        DB::table('dummy')->insert([
                'name'=>$id->id,
                'note'=>'update cnic'
                ]);
        update_answer_for_cnic($id->id);
                
    }
    dd("done");
    
}


public function updateAllData(){
    
    // for beneficiary name
     $data = DB::table('survey_form')
    ->whereNull('beneficiary_name')
    ->pluck('id');
    
      foreach($data as $id){
            DB::table('dummy')->insert([
                'name'=>$id,
                'note'=>'update beneficiary name'
                
            ]);    
    update_answer_for_name($id);
}

//   for cnic


   $data=DB::table('survey_form')->whereNull('cnic2')->select('id')->get();
   foreach($data as $id){
        
        DB::table('dummy')->insert([
                'name'=>$id->id,
                'note'=>'update cnic'
                ]);
        update_answer_for_cnic($id->id);
                
    }
   
   
       // Map question IDs to survey_form columns
        $questionToColumnMap = [
            350 => 'cnic_expiry_status',
            351 => 'date_of_birth',
            352 => 'preferred_bank',
            616 => 'mother_maiden_name',
            617 => 'city_of_birth',
            618 => 'date_of_insurence_of_cnic',
            656 => 'marital_status',
            657 => 'next_kin_name',
            658 => 'cnic_of_kin',
            671 => 'conatact_of_next_kin',
            672 => 'relation_cnic_of_kin',
            675 => 'expiry_date',
            2000 => 'village_name',
            243 => 'status_of_land',
            246 => 'socio_legal_status',
            247 => 'evidence_type',
            248 => 'bank_ac_wise',
            646 => 'proposed_beneficiary',
            730 => 'reconstruction_wise',
            756 => 'construction_type',
            760 => 'construction_type',
            250 => 'account_number',
            251 => 'bank_name',
            252 => 'branch_name',
            253 => 'bank_address',
            654 => 'father_name',
        ];

    // Fetch survey forms where required columns are null
    foreach ($questionToColumnMap as $questionId => $columnName) {
        $data = DB::table('survey_form')->whereNull($columnName)->select('id')->get();
     
        
        foreach ($data as $item) {
            // Fetch the corresponding answer for the survey_form_id and question_id
            $answer = DB::table('answers')
                ->where('survey_form_id', $item->id)
                ->where('question_id', $questionId)
                ->select('answer')
                ->first();
            
            if ($answer) {
              
                DB::table('survey_form')
                    ->where('id', $item->id)
                    ->update([$columnName => $answer->answer]);
                DB::table('dummy')->insert([
                'name'=>$id->id,
                'note'=>$columnName
                ]);    
                    
            }
        }
    }
    dd("query is working fine done");




    
}

public function CorrectupdateStatus(Request $request)
{
     dd('stop here');
    $junior_status = DB::table('form_status')
        ->where('update_by', 'PSIA')
        ->where('form_status', 'A')
        ->get();

    if ($junior_status->isNotEmpty()) {
        foreach ($junior_status as $item) {
          
            $senior_status = DB::table('form_status')
                ->where('update_by', 'COO')
                ->where('form_id', $item->form_id)
                ->where('form_status', 'A')
                ->first();

            if ($senior_status) {
                $mid_status = DB::table('form_status')
                    ->where('update_by', 'HRU_Main')
                    ->where('form_id', $item->form_id)
                    ->where('form_status', 'P')
                    ->first();

                if ($mid_status) {
                
                    DB::table('form_status')
                        ->where('update_by', 'HRU_Main')
                        ->where('form_id', $item->form_id)
                        ->where('form_status', 'P') 
                        ->update(['form_status' => 'A']);

                  
                    DB::table('dummy')->insert([
                        'name' => $item->form_id,
                        'note' => 'Correction of hrumain',
                    ]);
                }
            }
        }
    }

    dd('done');
}

public function revertStatus(){
    $item=240022;
    $data=
        [236030, 240264, 243949, 238549, 236590, 240022, 238125, 242029, 243514, 238327, 
        239206, 238792, 236342, 236649, 239937, 239195, 238569, 236603, 237820, 236161, 236166, 
        241895, 236232, 236850, 238913, 236120, 237047, 237586, 236109, 236111, 236230, 238652, 
        236112, 244091, 236238, 236270, 239679, 239679, 239695, 242720, 241816, 239459, 240186, 
        241283, 240185, 240182, 236327, 243602, 243668, 241991, 238685, 236377, 235922, 235973, 
        237216
        ];
        dd('stop');
        foreach($data as $item){
       
        
    $survey_form=DB::table('survey_form')->where('ref_no',$item)->select('id')->first();
    if($survey_form){
        
    $form_status=DB::table('form_status')->where('form_id',$survey_form->id)->where('form_status','A')->where('update_by','PSIA')->first();
    if($form_status){
        dump('delete',$item);
        $form_status=DB::table('form_status')->where('form_id',$survey_form->id)->where('form_status','A')->where('update_by','PSIA')->delete();
     
    }else{
          dump('not delete',$item);
    }
    }else{
         dump('not delete',$item);
        
    }
    
   
    
}

}

public function show_graph(){
        $genderWiseData = DataQueries::getGenderWiseData();
        $bankWiseData = DataQueries::getBankWiseData();
        $tenantWiseData = DataQueries::getTenantWiseData();
        $typeOfConstructionData = DataQueries::getTypeOfConstructionData();
        $houseVisibleData = DataQueries::getHouseVisibleData();
        $salaryWiseData = DataQueries::getSalaryWiseData();
        $surveys = DataQueries::getSurveyReportSection86Data();
        return view('dashboard.graph.index', compact('genderWiseData', 'bankWiseData', 'tenantWiseData', 'typeOfConstructionData', 'houseVisibleData', 'salaryWiseData', 'surveys'));
    }



}