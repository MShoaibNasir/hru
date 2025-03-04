<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\NdmaVerification;
use App\Models\Form;
use App\Models\SurveyData;
use App\Models\Construction;
use App\Models\ConstructionFile;
use App\Models\QuestionTitle; 
use App\Models\SignUpRetrictions;

use Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use DB;

class ConstructionController extends BaseController
{
    
    
    //Ayaz Construction Module Start
//Construction Form Stage 01
public function survey_form_construction_stage1()
{
    $form = Form::with(['sections' => function($q){ 
        $q->with(['questions'=> function($q){
            $q->with(['options'=> function($q){
            $q->with(['subsection'=> function($q){ $q->with(['questions'=> function($q){ $q->with('options'); }])->where('sub_section','true')->where('form_id', 27); }]);
        }]); 
        }]);
    }])->where('id',27)->select('id','name')->first();

    if(!$form){
        return response()->json([
            'success' => false,
            'message' => 'Form not found'
        ]);
    }
    return response()->json($form, 200);
    //return $this->commonQueryForm(27,NULL); //OLD Shoaib
}

//Construction Form Stage 02
public function survey_form_construction_stage2()
{
    $form = Form::with(['sections' => function($q){ 
        $q->with(['questions'=> function($q){
            $q->with(['options'=> function($q){
            $q->with(['subsection'=> function($q){ $q->with(['questions'=> function($q){ $q->with('options'); }])->where('sub_section','true')->where('form_id', 28); }]);
        }]); 
        }]);
    }])->where('id',28)->select('id','name')->first();
    
    if(!$form){
        return response()->json([
            'success' => false,
            'message' => 'Form not found'
        ]);
    }
    
    return response()->json($form, 200);
}

//Construction Form Stage 03
public function survey_form_construction_stage3()
{
    $form = Form::with(['sections' => function($q){ 
        $q->with(['questions'=> function($q){
            $q->with(['options'=> function($q){
            $q->with(['subsection'=> function($q){ $q->with(['questions'=> function($q){ $q->with('options'); }])->where('sub_section','true')->where('form_id', 29); }]);
        }]); 
        }]);
    }])->where('id',29)->select('id','name')->first();
    
    if(!$form){
        return response()->json([
            'success' => false,
            'message' => 'Form not found'
        ]);
    }
    
    return response()->json($form, 200);
}


//Construction Form Stage Upload 01
public function survey_form_construction_stage1_upload_oldfunction(Request $request){
    dd('Maintenance');
        
    $check_stage1_c = DB::table('construction_json')->where('ref_no',$request->ref_no)->where('action_condition', 3)->where('status', 'C')->where('stage', 'Stage 1')->first();
    $check_stage1_p = DB::table('construction_json')->where('ref_no',$request->ref_no)->where('action_condition', 3)->where('status', 'P')->where('stage', 'Stage 1')->first();
    $check_stage1_r = DB::table('construction_json')->where('ref_no',$request->ref_no)->where('action_condition', 3)->where('status', 'R')->where('stage', 'Stage 1')->first();
         if(!$check_stage1_c){
           if(!$check_stage1_p || $check_stage1_r){
            $construction_json=DB::table("construction_json")->insertGetId([
                "stage"=>'Stage 1', //$request->stage,
                "mobile_stage"=>$request->stage ?? null,
                "user_id"=>$request->user_id,
                "ref_no"=>$request->ref_no,
                "json"=> $request->form_data,
                'version'=>$request->version ?? null
                ]);
              
           if($request->hasFile('Images')){     
           $images_data = uploadfilesconstruction($construction_json, $request->Images, 'cs1_', 'construction_first_stage');  
           $data = $images_data->getData();
        
               foreach($data->files as $key=>$item){
               
               ConstructionFile::create([
		        'construction_id' => $construction_json,
		        'question_id' => $item->only_image_name,
		        'stage' => 1,
                'name' => $item->file_name,
				'extension' => $item->extension,
				'size' => getfilesize($item->bytes),
                'created_by' => auth()->user()->id ?? 0,
				'updated_by' => auth()->user()->id ?? 0
                
            ]);
        
            
               }
           //}else{
           // return response()->json(['message' => 'Something went wrong please resubmit construction form!', 'response' => $request->Images]);
           }
           
           
           
            $contructions_answer=null;
           
            $form= json_decode($request->form_data,true);
            // $form= json_decode(json_decode($request->form_data,true),true);
            
            $a = 0;
            $b = 0;
            $c = 0;
            $d = null;
            $action_condition = 0;
            foreach($form['sections'] as $item){
            foreach($item['questions'] as $ques){

                $contructions_answer = DB::table("contructions_answer")->insert([
                    "question_id"=>$ques['id'],
                    "answer" => is_array($ques['answer']) ? json_encode($ques['answer']) : $ques['answer'],
                    "user_id"=>$request->user_id,
                    "ref_no"=>$request->ref_no,
                    "stage"=> 'Stage 1', //$request->stage,
                    "type"=>$ques['type'],
                    "construction_json_id" => $construction_json     
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
                    "user_id"=>$request->user_id,
                    "ref_no"=>$request->ref_no,
                    "stage"=> 'Stage 1', //$request->stage,
                    "type"=>$subquestions['type'],
                    "construction_json_id" => $construction_json     
                ]);
                
                            }
                            
                        }
                    }
                }
                //Subquestion Answer End    
                
                
            }
            } 
        
            $action_condition = $a + $b + $c; 
        
        
            $surveyform = SurveyData::where('ref_no', $request->ref_no)->first();
            Construction::where('id',$construction_json)->update(['survey_id' => $surveyform->id, 'lot_id' => $surveyform->lot_id,'district_id' => $surveyform->district_id,'tehsil_id' => $surveyform->tehsil_id,'uc_id' => $surveyform->uc_id, 'action_condition'=> $action_condition, 'ref_number'=> $d]);
            if($action_condition==3){
                DB::table('ndma_verifications')->where('b_reference_number',$request->ref_no)->update(['construction_condition'=>'Stage 1']);
            }
            if($contructions_answer){
                 return response()->json(['success'=>"construction stage 1 data uploaded successfully"],200);
            }else{
                 return response()->json(['error'=>"some error found data not uploaded"],400);
            } 
            
}else{ return response()->json(['error' => 'The  requirement of stage 1 of this ref no : ' . $request->ref_no . ' is already exist'], 400);
}//check exist                        

}else{ return response()->json(['error' => 'The  requirement of stage 1 of this ref no : ' . $request->ref_no . ' is already completed'], 400);
}//check completed        
        
}

public function survey_form_construction_stage1_upload(Request $request)
{

    $ref_no = $request->input('ref_no');
    $user_id = $request->input('user_id');
    $stage = $request->input('stage', 'Stage 1');
    $form_data = $request->input('form_data');
    $version = $request->input('version');

    // Check if construction stage 1 already exists
    $existing_stage1 = DB::table('construction_json')
        ->where('ref_no', $ref_no)
        ->where('action_condition', 3)
        ->where('stage', 'Stage 1')
        ->whereIn('status', ['C', 'P', 'R'])
        ->get()
        ->keyBy('status');

    if (isset($existing_stage1['C'])) {
        return response()->json(['error' => "The requirement of stage 1 for ref no: $ref_no is already completed"], 400);
    }

    if (!isset($existing_stage1['P']) || isset($existing_stage1['R'])) {
        // Insert new construction stage 1 entry
        $construction_json_id = DB::table('construction_json')->insertGetId([
            "stage" => 'Stage 1',
            "mobile_stage" => $stage,
            "user_id" => $user_id,
            "ref_no" => $ref_no,
            "json" => $form_data,
            "version" => $version
        ]);

        // Handle Image Uploads
        if ($request->hasFile('Images')) {
            $images_data = uploadfilesconstruction($construction_json_id, $request->Images, 'cs1_', 'construction_first_stage');
            $data = $images_data->getData();

            foreach ($data->files as $item) {
                ConstructionFile::create([
                    'construction_id' => $construction_json_id,
                    'question_id' => $item->only_image_name,
                    'stage' => 1,
                    'name' => $item->file_name,
                    'extension' => $item->extension,
                    'size' => getfilesize($item->bytes),
                    'created_by' => auth()->user()->id ?? 0,
                    'updated_by' => auth()->user()->id ?? 0 
                ]);
            }
        }

        // Decode form data safely
        $form = json_decode($form_data, true);
        if (!isset($form['sections'])) {
            return response()->json(['error' => 'Invalid form data'], 400);
        }

        // Initialize variables
        $action_condition = 0;
        $ref_number = null;
        $answer_data = [];

        // Process Questions
        foreach ($form['sections'] as $section) {
            foreach ($section['questions'] as $question) {
                $answer = is_array($question['answer']) ? json_encode($question['answer']) : $question['answer'];
                $answer_data[] = [
                    "question_id" => $question['id'],
                    "answer" => $answer,
                    "user_id" => $user_id,
                    "ref_no" => $ref_no,
                    "stage" => 'Stage 1',
                    "type" => $question['type'],
                    "construction_json_id" => $construction_json_id
                ];

                // Process Action Condition
                if (in_array($question['id'], [2550, 2552, 2554]) && $question['answer'] == 'Yes') {
                    $action_condition++;
                }
                if ($question['id'] == 2571) {
                    $ref_number = $question['answer'] ?? null;
                }

                // Handle Subquestions
                if (!empty($question['options'])) {
                    foreach ($question['options'] as $option) {
                        if (!empty($option['is_sub_section']) && isset($option['subsection'][0]['questions'])) {
                            foreach ($option['subsection'][0]['questions'] as $subquestion) {
                                $sub_answer = is_array($subquestion['answer']) ? json_encode($subquestion['answer']) : $subquestion['answer'];
                                $answer_data[] = [
                                    "question_id" => $subquestion['id'],
                                    "answer" => $sub_answer,
                                    "user_id" => $user_id,
                                    "ref_no" => $ref_no,
                                    "stage" => 'Stage 1',
                                    "type" => $subquestion['type'],
                                    "construction_json_id" => $construction_json_id
                                ];
                            }
                        }
                    }
                }
            }
        }

        // Bulk Insert Answers
        if (!empty($answer_data)) { 
            DB::table("contructions_answer")->insert($answer_data);
        }

        // Update Construction JSON Data
        $surveyform = SurveyData::where('ref_no', $ref_no)->first();
        Construction::where('id', $construction_json_id)->update([
            'survey_id' => $surveyform->id ?? null,
            'lot_id' => $surveyform->lot_id ?? null,
            'district_id' => $surveyform->district_id ?? null,
            'tehsil_id' => $surveyform->tehsil_id ?? null,
            'uc_id' => $surveyform->uc_id ?? null,
            'action_condition' => $action_condition,
            'ref_number' => $ref_number
        ]);

        // Update NDMA Verifications Table
        if ($action_condition == 3 && DB::table('ndma_verifications')->where('b_reference_number', $ref_no)->exists()) {
            DB::table('ndma_verifications')->where('b_reference_number', $ref_no)->update(['construction_condition' => 'Stage 1']);
        }

        return response()->json(['success' => "Construction stage 1 data uploaded successfully"], 200);
    }

    return response()->json(['error' => "The requirement of stage 1 for ref no: $ref_no already exists"], 400);
}






//Construction Form Stage Upload 02
public function survey_form_construction_stage2_upload(Request $request){
        
        //if(!isset($request->Images) || !isset($request->form_data) || !isset($request->user_id) || !isset($request->ref_no) || !isset($request->stage)){
         //   return response()->json(['error'=>"Validation error all fields are required!"], 400);
         //}
        $check_stage1_completed = DB::table('construction_json')->where('ref_no',$request->ref_no)->where('action_condition', 3)->where('status', 'C')->where('stage', 'Stage 1')->first();
        
        if (!is_null($check_stage1_completed)) {

        $check_exist = DB::table('construction_json')->where('ref_no',$request->ref_no)->where('action_condition', 3)->where('stage', 'Stage 2')->first();
    
           if(is_null($check_exist)) {
           
            $construction_json=DB::table("construction_json")->insertGetId([
                "stage"=>'Stage 2', //$request->stage,
                "mobile_stage"=>$request->stage ?? null,
                "user_id"=>$request->user_id,
                "ref_no"=>$request->ref_no,
                "json"=>$request->form_data,
                'version'=>$request->version ?? null
                ]);
                
           if($request->hasFile('Images')){     
           $images_data = uploadfilesconstruction($construction_json, $request->Images, 'cs2_', 'construction_second_stage');
           $data = $images_data->getData();
               foreach($data->files as $key=>$item){
               ConstructionFile::create([
		        'construction_id' => $construction_json,
		        'question_id' => $item->only_image_name,
		        'stage' => 2,
                'name' => $item->file_name,
				'extension' => $item->extension,
				'size' => getfilesize($item->bytes),
                'created_by' => auth()->user()->id ?? 0,
				'updated_by' => auth()->user()->id ?? 0
                
            ]);
            
               }
           
           
           //}else{
           // return response()->json(['message' => 'Something went wrong please resubmit construction form!', 'response' => $request->Images]);
           }
           
           
            
            $contructions_answer=null;
           
            $form= json_decode($request->form_data,true);
            //$form= json_decode(json_decode($request->form_data,true),true);
            
            $a = 0;
            $b = 0;
            $c = 0;
            $action_condition = 0;
            foreach($form['sections'] as $item){
            foreach($item['questions'] as $ques){
                  
            $contructions_answer=DB::table("contructions_answer")->insert([
                "question_id"=>$ques['id'],
                "answer" => is_array($ques['answer']) ? json_encode($ques['answer']) : $ques['answer'],
                "user_id"=>$request->user_id,
                "ref_no"=>$request->ref_no,
                "stage"=>'Stage 2', //$request->stage,
                "type"=>$ques['type'],
                "construction_json_id"=>$construction_json     
            ]);
            
                if($ques['id'] == 277396){
                   if($ques['answer'] == 'Yes'){$a = 1;}else{$a = 0;} 
                }
                if($ques['id'] == 277398){
                    if($ques['answer'] == 'Yes'){$b = 1;}else{$b = 0;}
                }
                if($ques['id'] == 277400){
                    if($ques['answer'] == 'Yes'){$c = 1;}else{$c = 0;}
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
                    "user_id"=>$request->user_id,
                    "ref_no"=>$request->ref_no,
                    "stage"=> 'Stage 2', //$request->stage,
                    "type"=>$subquestions['type'],
                    "construction_json_id" => $construction_json     
                ]);
                
                            }
                            
                        }
                    }
                }
                //Subquestion Answer End 
        }
        }
            
            $action_condition = $a + $b + $c;
            $surveyform = SurveyData::where('ref_no', $request->ref_no)->first();
            Construction::where('id',$construction_json)->update(['survey_id' => $surveyform->id, 'lot_id' => $surveyform->lot_id,'district_id' => $surveyform->district_id,'tehsil_id' => $surveyform->tehsil_id,'uc_id' => $surveyform->uc_id, 'action_condition'=> $action_condition]);
             if($action_condition==3){
                DB::table('ndma_verifications')->where('b_reference_number',$request->ref_no)->update(['construction_condition'=>'Stage 2']);
            }
            if($contructions_answer){
                 return response()->json(['success'=>"construction stage 2 data uploaded successfully"],200);
            }else{
                 return response()->json(['error'=>"some error found data not uploaded"],400);
            }     
                       
}else{ 
    // return response()->json(['error' => 'Already ref no: ' . $request->ref_no . ' exist in stage 2'], 400);
    return response()->json(['error' => 'The  requirement of stage 2 of this ref no : ' . $request->ref_no . ' is already exist'], 400);

}//check exist 
}else{
return response()->json(['error' => 'The  requirement of stage 2 of this ref no : ' . $request->ref_no . ' Stage 1 is not completed'], 400);    
}//check Completed       
}


//Construction Form Stage Upload 03
public function survey_form_construction_stage3_upload(Request $request){
        
        //if(!isset($request->Images) || !isset($request->form_data) || !isset($request->user_id) || !isset($request->ref_no) || !isset($request->stage)){
            //return response()->json(['error'=>"Validation error all fields are required!"], 400);
         //}
        $check_stage2_completed = DB::table('construction_json')->where('ref_no',$request->ref_no)->where('action_condition', 3)->where('status', 'C')->where('stage', 'Stage 2')->first();
        
        if (!is_null($check_stage2_completed)) {
           $check_exist = DB::table('construction_json')->where('ref_no',$request->ref_no)->where('action_condition', 3)->where('stage', 'Stage 3')->first();
           if(is_null($check_exist)) {
           
            $construction_json=DB::table("construction_json")->insertGetId([
                "stage"=>'Stage 3', //$request->stage,
                "mobile_stage"=>$request->stage ?? null,
                "user_id"=>$request->user_id,
                "ref_no"=>$request->ref_no,
                "json"=>$request->form_data,
                'version'=>$request->version ?? null
                ]);
                
           if($request->hasFile('Images')){     
           $images_data = uploadfilesconstruction($construction_json, $request->Images, 'cs3_', 'construction_third_stage'); 
           $data = $images_data->getData();
               foreach($data->files as $key=>$item){
               ConstructionFile::create([
		        'construction_id' => $construction_json,
		        'question_id' => $item->only_image_name,
		        'stage' => 3,
                'name' => $item->file_name,
				'extension' => $item->extension,
				'size' => getfilesize($item->bytes),
                'created_by' => auth()->user()->id ?? 0,
				'updated_by' => auth()->user()->id ?? 0
                
            ]);
               }
           
           //}else{
           // return response()->json(['message' => 'Something went wrong please resubmit construction form!', 'response' => $request->Images]);
           }
           
           
            
            $contructions_answer=null;
            $form= json_decode($request->form_data,true);
            //$form= json_decode(json_decode($request->form_data,true),true);
            
            //dd($form);
            
            $a = 0;
            $b = 0;
            $c = 0;
            $action_condition = 0;
            foreach($form['sections'] as $item){
            foreach($item['questions'] as $ques){
                  
            $contructions_answer=DB::table("contructions_answer")->insert([
                "question_id"=>$ques['id'],
                "answer" => is_array($ques['answer']) ? json_encode($ques['answer']) : $ques['answer'],
                "user_id"=>$request->user_id,
                "ref_no"=>$request->ref_no,
                "stage"=>'Stage 3', //$request->stage,
                "type"=>$ques['type'],
                "construction_json_id"=>$construction_json     
            ]);
            
            
                if($ques['id'] == 277407){
                   if($ques['answer'] == 'Yes'){$a = 1;}else{$a = 0;} 
                }
                if($ques['id'] == 277409){
                    if($ques['answer'] == 'Yes'){$b = 1;}else{$b = 0;}
                }
                if($ques['id'] == 277411){
                    if($ques['answer'] == 'Yes'){$c = 1;}else{$c = 0;}
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
                    "user_id"=>$request->user_id,
                    "ref_no"=>$request->ref_no,
                    "stage"=> 'Stage 3', //$request->stage,
                    "type"=>$subquestions['type'],
                    "construction_json_id" => $construction_json     
                ]);
                
                            }
                            
                        }
                    }
                }
                //Subquestion Answer End 
            
        }
        }
    
            $action_condition = $a + $b + $c;
            $surveyform = SurveyData::where('ref_no', $request->ref_no)->first();
            Construction::where('id',$construction_json)->update(['survey_id' => $surveyform->id, 'lot_id' => $surveyform->lot_id,'district_id' => $surveyform->district_id,'tehsil_id' => $surveyform->tehsil_id,'uc_id' => $surveyform->uc_id, 'action_condition'=> $action_condition]);
             if($action_condition==3){
                DB::table('ndma_verifications')->where('b_reference_number',$request->ref_no)->update(['construction_condition'=>'Stage 3']);
            }
            if($contructions_answer){
                 return response()->json(['success'=>"construction stage 3 data uploaded successfully"],200);
            }else{
                 return response()->json(['error'=>"some error found data not uploaded"],400);
            }     
                        
}else{ 
    // return response()->json(['error' => 'Already ref no: ' . $request->ref_no . ' exist in stage 3'], 400);
    return response()->json(['error' => 'The  requirement of stage 3 of this ref no : ' . $request->ref_no . ' is already exist'], 400);
}//check exist    
}else{
return response()->json(['error' => 'The  requirement of stage 3 of this ref no : ' . $request->ref_no . ' Stage 2 is not completed'], 400);            
}//check Completed        
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


    
}