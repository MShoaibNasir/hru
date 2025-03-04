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

//Construction Form Stage 04
public function survey_form_construction_stage4()
{
    $form = Form::with(['sections' => function($q){ 
        $q->with(['questions'=> function($q){
            $q->with(['options'=> function($q){
            $q->with(['subsection'=> function($q){ $q->with(['questions'=> function($q){ $q->with('options'); }])->where('sub_section','true')->where('form_id', 48); }]);
        }]); 
        }]);
    }])->where('id',48)->select('id','name')->first();
    
    if(!$form){
        return response()->json([
            'success' => false,
            'message' => 'Form not found'
        ]);
    }
    
    return response()->json($form, 200);
}


//Construction Form Stage Upload 01
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
public function survey_form_construction_stage2_upload(Request $request)
{
    $check_stage1_completed = DB::table('construction_json')->where('ref_no',$request->ref_no)->where('action_condition', 3)->where('status', 'C')->where('stage', 'Stage 1')->first();
    if (!is_null($check_stage1_completed)) {
        
    $ref_no = $request->input('ref_no');
    $user_id = $request->input('user_id');
    $stage = $request->input('stage', 'Stage 2');
    $form_data = $request->input('form_data');
    $version = $request->input('version');

    // Check if construction stage 2 already exists
    $existing_stage2 = DB::table('construction_json')
        ->where('ref_no', $ref_no)
        ->where('action_condition', 3)
        ->where('stage', 'Stage 2')
        ->whereIn('status', ['C', 'P', 'R'])
        ->get()
        ->keyBy('status');

    if (isset($existing_stage2['C'])) {
        return response()->json(['error' => "The requirement of stage 2 for ref no: $ref_no is already completed"], 400);
    }

    if (!isset($existing_stage2['P']) || isset($existing_stage2['R'])) {
        // Insert new construction stage 2 entry
        $construction_json_id = DB::table('construction_json')->insertGetId([
            "stage" => 'Stage 2',
            "mobile_stage" => $stage,
            "user_id" => $user_id,
            "ref_no" => $ref_no,
            "json" => $form_data,
            "version" => $version
        ]);

        // Handle Image Uploads
        if ($request->hasFile('Images')) {
            $images_data = uploadfilesconstruction($construction_json_id, $request->Images, 'cs2_', 'construction_second_stage');
            $data = $images_data->getData();

            foreach ($data->files as $item) {
                ConstructionFile::create([
                    'construction_id' => $construction_json_id,
                    'question_id' => $item->only_image_name,
                    'stage' => 2,
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
                    "stage" => 'Stage 2',
                    "type" => $question['type'],
                    "construction_json_id" => $construction_json_id
                ];

                // Process Action Condition
                if (in_array($question['id'], [277396, 277398, 277400]) && $question['answer'] == 'Yes') {
                    $action_condition++;
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
                                    "stage" => 'Stage 2',
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
            'action_condition' => $action_condition
        ]);

        // Update NDMA Verifications Table
        if ($action_condition == 3 && DB::table('ndma_verifications')->where('b_reference_number', $ref_no)->exists()) {
            DB::table('ndma_verifications')->where('b_reference_number', $ref_no)->update(['construction_condition' => 'Stage 2']);
        }

        return response()->json(['success' => "Construction stage 2 data uploaded successfully"], 200);
    }

    return response()->json(['error' => "The requirement of stage 2 for ref no: $ref_no already exists"], 400);
    
    
}else{
return response()->json(['error' => 'The  requirement of stage 2 of this ref no : ' . $request->ref_no . ' Stage 1 is not completed'], 400);            
}//check Stage 2 is Completed     
    
}

//Construction Form Stage Upload 03
public function survey_form_construction_stage3_upload(Request $request)
{
    $check_stage2_completed = DB::table('construction_json')->where('ref_no',$request->ref_no)->where('action_condition', 3)->where('status', 'C')->where('stage', 'Stage 2')->first();
    if (!is_null($check_stage2_completed)) {
        
    $ref_no = $request->input('ref_no');
    $user_id = $request->input('user_id');
    $stage = $request->input('stage', 'Stage 3');
    $form_data = $request->input('form_data');
    $version = $request->input('version');

    // Check if construction stage 3 already exists
    $existing_stage3 = DB::table('construction_json')
        ->where('ref_no', $ref_no)
        ->where('action_condition', 3)
        ->where('stage', 'Stage 3')
        ->whereIn('status', ['C', 'P', 'R'])
        ->get()
        ->keyBy('status');

    if (isset($existing_stage3['C'])) {
        return response()->json(['error' => "The requirement of stage 3 for ref no: $ref_no is already completed"], 400);
    }

    if (!isset($existing_stage3['P']) || isset($existing_stage3['R'])) {
        // Insert new construction stage 2 entry
        $construction_json_id = DB::table('construction_json')->insertGetId([
            "stage" => 'Stage 3',
            "mobile_stage" => $stage,
            "user_id" => $user_id,
            "ref_no" => $ref_no,
            "json" => $form_data,
            "version" => $version
        ]);

        // Handle Image Uploads
        if ($request->hasFile('Images')) {
            $images_data = uploadfilesconstruction($construction_json_id, $request->Images, 'cs3_', 'construction_third_stage');
            $data = $images_data->getData();

            foreach ($data->files as $item) {
                ConstructionFile::create([
                    'construction_id' => $construction_json_id,
                    'question_id' => $item->only_image_name,
                    'stage' => 3,
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
                    "stage" => 'Stage 3',
                    "type" => $question['type'],
                    "construction_json_id" => $construction_json_id
                ];

                // Process Action Condition
                if (in_array($question['id'], [277407, 277409, 277411]) && $question['answer'] == 'Yes') {
                    $action_condition++;
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
                                    "stage" => 'Stage 3',
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
            'action_condition' => $action_condition
        ]);

        // Update NDMA Verifications Table
        if ($action_condition == 3 && DB::table('ndma_verifications')->where('b_reference_number', $ref_no)->exists()) {
            DB::table('ndma_verifications')->where('b_reference_number', $ref_no)->update(['construction_condition' => 'Stage 3']);
        }

        return response()->json(['success' => "Construction stage 3 data uploaded successfully"], 200);
    }

    return response()->json(['error' => "The requirement of stage 3 for ref no: $ref_no already exists"], 400);
    
    
}else{
return response()->json(['error' => 'The  requirement of stage 3 of this ref no : ' . $request->ref_no . ' Stage 2 is not completed'], 400);            
}//check Stage 2 is Completed 
    
    
}

//Construction Form Stage Upload 04
public function survey_form_construction_stage4_upload(Request $request)
{
  
    $check_stage3_completed = DB::table('construction_json')->where('ref_no',$request->ref_no)->where('action_condition', 2)->where('status', 'C')->where('stage', 'Stage 3')->first();
    if (!is_null($check_stage3_completed)) {
        
    $ref_no = $request->input('ref_no');
    $user_id = $request->input('user_id');
    $stage = $request->input('stage', 'Stage 4');
    $form_data = $request->input('form_data');
    $version = $request->input('version');

    // Check if construction stage 4 already exists
    $existing_stage4 = DB::table('construction_json')
        ->where('ref_no', $ref_no)
        ->where('action_condition', 2)
        ->where('stage', 'Stage 4')
        ->whereIn('status', ['C', 'P', 'R'])
        ->get()
        ->keyBy('status');

    if (isset($existing_stage4['C'])) {
        return response()->json(['error' => "The requirement of stage 4 for ref no: $ref_no is already completed"], 400);
    }

    if (!isset($existing_stage4['P']) || isset($existing_stage4['R'])) {
        // Insert new construction stage 4 entry
        $construction_json_id = DB::table('construction_json')->insertGetId([
            "stage" => 'Stage 4',
            "mobile_stage" => $stage,
            "user_id" => $user_id,
            "ref_no" => $ref_no,
            "json" => $form_data,
            "version" => $version
        ]);

        // Handle Image Uploads
        if ($request->hasFile('Images')) {
            $images_data = uploadfilesconstruction($construction_json_id, $request->Images, 'cs4_', 'construction_fourth_stage');
            $data = $images_data->getData();

            foreach ($data->files as $item) {
                ConstructionFile::create([
                    'construction_id' => $construction_json_id,
                    'question_id' => $item->only_image_name,
                    'stage' => 4,
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
                    "stage" => 'Stage 4',
                    "type" => $question['type'],
                    "construction_json_id" => $construction_json_id
                ];

                // Process Action Condition
                if (in_array($question['id'], [277463, 277466]) && $question['answer'] == 'Yes') {
                    $action_condition++;
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
                                    "stage" => 'Stage 4',
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
            'action_condition' => $action_condition
        ]);

        // Update NDMA Verifications Table
        if ($action_condition == 2 && DB::table('ndma_verifications')->where('b_reference_number', $ref_no)->exists()) {
            DB::table('ndma_verifications')->where('b_reference_number', $ref_no)->update(['construction_condition' => 'Stage 4']);
        }

        return response()->json(['success' => "Construction stage 4 data uploaded successfully"], 200);
    }

    return response()->json(['error' => "The requirement of stage 4 for ref no: $ref_no already exists"], 400);
    
    
}else{
return response()->json(['error' => 'The  requirement of stage 4 of this ref no : ' . $request->ref_no . ' Stage 4 is not completed'], 400);            
}//check Stage 3 is Completed 
    
    
}


    
}