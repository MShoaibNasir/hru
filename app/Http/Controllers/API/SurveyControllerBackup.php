<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\NdmaVerification;
use App\Models\Form;
use App\Models\QuestionTitle; 
use App\Models\SignUpRetrictions;
use App\Models\SurveyData;
use App\Jobs\DestructureForm;
use DB;
class SurveyController extends BaseController
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;
        return $this->sendResponse($success, 'User register successfully.');
    }


   


 

public function survey_form(Request $request)
{
    if ($request->user_id == null) {
        return response()->json([
            'success' => false,
            'validation_error' => 'User ID is required'
        ]);
    }

    $user_id = $request->user_id;
    $user = User::find($user_id);
    
    $lot_data = json_decode($user->lot_id ?? null);
    $lots = DB::table('lots')->whereIn('id', $lot_data)->select('name')->get();
    $lots_data = $lots->pluck('name')->toArray();

    $district_data = json_decode($user->district_id ?? null);
    $districts = DB::table('districts')->whereIn('id', $district_data)->select('name', 'zone_id')->get();
   
    
    $districts_data = $districts->pluck('name')->toArray();
   
 
    $zone_ids = $districts->pluck('zone_id')->toArray();
  
    $tehsil_data = json_decode($user->tehsil_id ?? null);
    $tehsils = DB::table('tehsil')->whereIn('id', $tehsil_data)->select('name')->get();
    $tehsil_data = $tehsils->pluck('name')->toArray();

    $zones = DB::table('zone')->whereIn('id', $zone_ids)->select('name')->get();
    $zone_data = $zones->pluck('name')->toArray();

    $uc_data = json_decode($user->uc_id ?? null);
    $uc = DB::table('uc')->whereIn('id', $uc_data)->select('name')->get();
    $uc_data = $uc->pluck('name')->toArray();
  
    $forms = DB::table("form")
        ->select("id", "name")
        ->where('status', 1)
        ->orderBy('sequence', 'ASC')
        ->get();

    $result = [];
    
     
    
    foreach ($forms as $form) {
        $result[$form->name] = [
            "sections" => [],
            "sub_sections" => [],
            "form_id" => $form->id
        ];
         

        $sections = DB::table("question_title")
            ->where("form_id", $form->id)
            ->select("id", "name", "sub_heading", "form_id", "sub_section", "option_id")
            ->orderBy('sequence', 'ASC')
            ->get();
         

        foreach ($sections as $section) {
            if ($section->sub_section && $section->sub_section == 'true') {
                $sub_section_questions = DB::table("questions")
                    ->where("section_id", $section->id)
                    ->select("id", "name", "option_id", "placeholder", "section_id", "type", "answer", "related_question", "is_mandatory", "is_editable", 'range_number', 'location_condition')
                    ->orderBy('sequence', 'ASC')
                    ->get();

                $new_final_array = [];

                foreach ($sub_section_questions as $index => $question) {
                    $options = DB::table("options")
                        ->where("question_id", $question->id)
                        ->where("section_id", $section->id)
                        ->select("id as option_id", "name", "question_id", "answer", "variable_type")
                        ->get();

                    $new_final_array[] = [
                        'questions' => $question,
                        'options' => $options->isNotEmpty() ? $options : null,
                    ];
                }

                $result[$form->name]["sub_sections"][$section->option_id][] = [
                    "id" => $section->id,
                    "name" => $section->name,
                    "sub_heading" => $section->sub_heading,
                    "form_id" => $section->form_id,
                    "sub_section" => $section->sub_section,
                    "option_id" => $section->option_id,
                    "questions" => $new_final_array
                ];
            } else {
                // Main section handling
                
                $result[$form->name]["sections"][$section->name] = [
                    "section" => $section,
                    "questions" => []
                ];
               

                $questions = DB::table("questions")
                    ->where("section_id", $section->id)
                    ->select("id", "name", "option_id", "placeholder", "section_id", "type", "answer", "related_question", "is_mandatory", "is_editable", "range_number", "location_condition")
                    ->orderBy('sequence', 'ASC')
                    ->get();
                    

                foreach ($questions as $index => $question) {
                    $options = DB::table("options")
                        ->where("question_id", $question->id)
                        ->where("section_id", $section->id)
                        ->select("id as option_id", "name", "question_id", "answer", "variable_type", "is_replicable")
                        ->get();
                        

                    if ($question->location_condition == 'lot') {
                        $user_lot = [];
                        foreach ($lots_data as $index => $item) {
                            $user_lot[] = [
                                'option_id' => $question->id . '_' . ($index + 1),
                                'name' => $item,
                                'question_id' => $question->id,
                                'answer' => null,
                                'variable_type' => null,
                                'is_replicable' => 0,
                                'parent_index' => 0
                            ];
                        }
                        $options = collect($user_lot);
                       
                    }
                   
                    if ($question->location_condition == 'district') {
                        $user_district = [];
                    
                        foreach ($districts_data as $index => $item) {
                         
                            $user_district[] = [
                                'option_id' => $question->id . '_' . ($index + 1),
                                'name' => $item,
                                'question_id' => $question->id,
                                'answer' => null,
                                'variable_type' => null,
                                'is_replicable' => 0,
                                'parent_index' => 0
                            ];
                        }
                        $options = collect($user_district);
                    }
                    if ($question->location_condition == 'tehsil') {
                        $user_tehsil = [];
                        foreach ($tehsil_data as $index => $item) {
                            $user_tehsil[] = [
                                'option_id' => $question->id . '_' . ($index + 1),
                                'name' => $item,
                                'question_id' => $question->id,
                                'answer' => null,
                                'variable_type' => null,
                                'is_replicable' => 0,
                                'parent_index' => 0
                            ];
                        }
                        $options = collect($user_tehsil);
                    }
                    if ($question->location_condition == 'uc') {
                        $user_uc = [];
                        foreach ($uc_data as $index => $item) {
                            $user_uc[] = [
                                'option_id' => $question->id . '_' . ($index + 1),
                                'name' => $item,
                                'question_id' => $question->id,
                                'answer' => null,
                                'variable_type' => null,
                                'is_replicable' => 0,
                                'parent_index' => 0
                            ];
                        }
                        $options = collect($user_uc);
                    }
                    if ($question->location_condition == 'zone') {
                        $user_zone = [];
                        foreach ($zone_data as $index => $item) {
                            $user_zone[] = [
                                'option_id' => $question->id . '_' . ($index + 1),
                                'name' => $item,
                                'question_id' => $question->id,
                                'answer' => null,
                                'variable_type' => null,
                                'is_replicable' => 0,
                                'parent_index' => 0
                            ];
                        }
                        $options = collect($user_zone);
                    }
                    
                    
                    $result[$form->name]["sections"][$section->name]["questions"][] = [
                        "question" => $question,
                        "options" => $options->isNotEmpty() ? $options : null,
                    ];
                }
            }
        }
    }

    return $result;
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
    
public function ndma_verifications_new(Request $request)
{
    ini_set('memory_limit', '512M'); 
    ini_set('max_execution_time', '300'); 


    if($request->page==null || count($request->uc) <= 0 ){
        return response()->json(['error' => 'all fields required'], 400);
    }
    $ucValues = $request->uc;

   
    $perPage = $request->input('per_page', 15); 
    $page = $request->page;

   
    if (empty($ucValues) || count($ucValues) > 100) { 
        return response()->json(['error' => 'Invalid input'], 400);
    }

   
    $NdmaVerification = NdmaVerification::whereIn('uc', $ucValues)
        ->join('districts', 'ndma_verifications.district', '=', 'districts.id')
        ->join('tehsil', 'ndma_verifications.tehsil', '=', 'tehsil.id')
        ->join('uc', 'ndma_verifications.uc', '=', 'uc.id')
        ->select('ndma_verifications.*', 'districts.name as district_name', 'tehsil.name as tehsil_name', 'uc.name as uc_name')
        ->paginate($perPage, ['*'], 'page', $page);

    // Format the results
    $ndma = $NdmaVerification->map(function ($item) {
        $item->cnic = $this->formate_cnic($item->cnic);
        $item->contact_number = '+92' . $item->contact_number;
        return $item;
    });

    // Return paginated data
    return response()->json([
        'data' => $ndma,
        'meta' => [
            'current_page' => $NdmaVerification->currentPage(),
            'last_page' => $NdmaVerification->lastPage(),
            'per_page' => $NdmaVerification->perPage(),
            'total' => $NdmaVerification->total(),
        ],
    ]);
}


public function ndma_verifications(Request $request)
{
        
         $ucValues= $request->uc;
         $NdmaVerification = NdmaVerification::whereIn('uc', $ucValues)
         ->join('districts','ndma_verifications.district','=','districts.id')
         ->join('tehsil','ndma_verifications.tehsil','=','tehsil.id')
         ->join('uc','ndma_verifications.uc','=','uc.id')
         ->select('ndma_verifications.*','districts.name as district_name','tehsil.name as tehsil_name','uc.name as uc_name')
         ->get();
        $ndma=[];
        foreach($NdmaVerification as $item){
            $item->cnic= $this->formate_cnic($item->cnic);
            $item->contact_number= '+92'.$item->contact_number;
            $ndma[]=$item;
        }
        return $ndma;
    }

    
    
  
  
public function form_data_upload(Request $request)
{
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'form_id' => 'required',
            'form_data' => 'required',
            'uc_id' => 'required',
            'beneficiary_details' => 'required',
            "created_at"=>'required',
            // "mobile_version"=>"required"
            
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
          $beneficiary_details=json_decode($request->beneficiary_details);
          $beneficiary_cnic= $beneficiary_details->cnic;
          $beneficiary_number= $beneficiary_details->b_reference_number;
         
          $b_cnic=$beneficiary_cnic;
         
         
        //   those ref no not allowed to register whose already registerd
          if(!$request->rejected_form_id){
              $survey_ids = DB::table('survey_form')->pluck('ref_no')->toArray();
              if (in_array($beneficiary_number,$survey_ids)) {
              return $this->sendError('Validation Error.', "This beneficiary is already registered in the system!");
              }
          }
          
          
         $generated_id_data=DB::table('uc')->where('uc.id',$request->uc_id)
        ->join('tehsil','uc.tehsil_id','=','tehsil.id')
        ->join('districts','tehsil.district_id','=','districts.id')
        ->join('lots','districts.lot_id','=','lots.id')
        ->select('uc.name as uc_name','uc.id as uc_id','tehsil.id as tehsil_id','tehsil.name as tehsil_name','districts.id as district_id','districts.name as district_name','lots.name as lot_name','lots.id as lot_id')
        ->first();
        $last_survey_id=DB::table('survey_form')->select('id')->latest()->first();
       
        if(!$last_survey_id){
         $last_survey_id=0;
        }else{
            $last_survey_id=$last_survey_id->id;
        }
        $generated_id = 'HRU-' . $generated_id_data->lot_name . '-' . $generated_id_data->district_name . '-' . $generated_id_data->tehsil_name . '-' . $generated_id_data->uc_name . '-' . $beneficiary_number . '-1-' . (intval($last_survey_id) + 1);
        if($request->rejected_form_id){
          
            $survey_form=DB::table('survey_form')->where('id',$request->rejected_form_id)->update([
            'user_id'=>$request->user_id,
            'form_id'=>$request->form_id,
            "action_by"=>$request->user_id,
            "status"=>'field supervisor',
            "lot_id"=>intval($generated_id_data->lot_id),
            "uc_id"=>intval($generated_id_data->uc_id),
            "district_id"=>intval($generated_id_data->district_id),
            "tehsil_id"=>intval($generated_id_data->tehsil_id),
            'form_data'=>$request->form_data,
            'uc_id'=>$request->uc_id,
            'beneficiary_details'=>$request->beneficiary_details,
            'cnic'=>$b_cnic,
            'ref_no'=>$beneficiary_number,
            'save_time'=>$request->created_at,
            "option_ids"=>$request->option_ids ?? null,
            "mobile_version"=>$request->mobile_version ?? null
            
        ]);
            
             $form_status=DB::table('form_status')->where('form_id',$request->rejected_form_id)
             ->where('update_by','field supervisor')
             ->where('form_status','R')
             ->update(['form_status'=>'P']);
               
             
            $master_report=DB::table('master_report')->where('survey_id',$request->rejected_form_id)->select('new_status','user_id')->first();
             
            if($master_report){
            manage_report($request->rejected_form_id,'field supervisor',$master_report->new_status,'P',$request->user_id,$master_report->user_id);
            }
          

            //  delete previous answers
              $delete_previous_answer=DB::table('answers')->where('survey_form_id',$request->rejected_form_id)->delete();

              dispatch(new DestructureForm($request->rejected_form_id));
        }
        else{
            
        $survey_form_id = SurveyData::insertGetId([
        'user_id' => $request->user_id,
        'form_id' => $request->form_id,
        "lot_id" => intval($generated_id_data->lot_id),
        "uc_id" => intval($generated_id_data->uc_id),
        "district_id" => intval($generated_id_data->district_id),
        "tehsil_id" => intval($generated_id_data->tehsil_id),
        'form_data' => $request->form_data,
        'uc_id' => $request->uc_id,
        'beneficiary_details' => $request->beneficiary_details,
        'cnic' => $b_cnic,
        'ref_no' => $beneficiary_number,
        'save_time' => $request->created_at,
        "generated_id" => $generated_id,
        "option_ids"=>$request->option_ids ?? null,
        "mobile_version"=>$request->mobile_version ?? null,
        "action_by"=>$request->user_id,
        "status"=>'field supervisor',
        ]);
        $master_report=DB::table("master_report")->insertGetId([
        'user_id' => $request->user_id,
        "lot_id" => intval($generated_id_data->lot_id),
        "uc_id" => intval($generated_id_data->uc_id),
        "district_id" => intval($generated_id_data->district_id),
        "tehsil_id" => intval($generated_id_data->tehsil_id),
        "survey_id"=>$survey_form_id,
        "role"=>"field supervisor",
        'form_id'=>8,
        'form_type'=>'dammage assessment',
        'last_status'=>'P',
        'new_status'=>'P',
        'last_action_user_id'=>$request->user_id
        
        ]);
        $master_report_detail=DB::table("master_report_detail")->insert([
        'user_id' => $request->user_id,
        "lot_id" => intval($generated_id_data->lot_id),
        "uc_id" => intval($generated_id_data->uc_id),
        "district_id" => intval($generated_id_data->district_id),
        "tehsil_id" => intval($generated_id_data->tehsil_id),
        "survey_id"=>$survey_form_id,
        "role"=>"field supervisor",
        "maaster_report_id"=>$master_report,
        'form_id'=>8,
        'form_type'=>'dammage assessment',
        'last_status'=>'P',
        'new_status'=>'P',
        'last_action_user_id'=>$request->user_id
        ]);
        dispatch(new DestructureForm($survey_form_id));
        }
        return response(['success'=>'survey form submit successfully!']);
        
    }
    
    
    
    
public function modified_survey_form(Request $request) {
$form_data = Form::where('status',1)->where('id',8)->select('name as formName', 'id as formId', 'isUnion', 'isModified')->get();

$FormDetails = [];
$Question = []; // Initialize $Question here
$SubFormDetails = [];
$subQuestion = []; // Initialize $subQuestion here

foreach ($form_data as $item) {
    // Fetch main question titles
    $question_title = QuestionTitle::where('form_id', $item->formId)->where('option_id', null)->get();
    // Fetch sub-section titles
    $question_sub_title = QuestionTitle::where('form_id', $item->formId)->where('option_id', '!=', null)->get();

    // Process main question titles
    if ($question_title->isNotEmpty()) {
        $details = [];
        foreach ($question_title as $qt) {
            $details[] = [
                'name' => $qt->name,
                'formId' => $item->formId,
                'detailId' => $qt->id,
                'isModified' => true,
                'isRepeatableStatus' => $qt->isRepeatableStatus
            ];
        }

        $FormDetails[] = [
            'formName' => $item->formName,
            'formId' => $item->formId,
            'details' => $details
        ];

        // Find section and its questions for main questions
        foreach ($details as $detail) {
            $questions_list = [];
            $questions = DB::table('questions')->where('section_id', $detail['detailId'])->where('option_id',null)->get();

            foreach ($questions as $ques) {
                $options = DB::table('options')->where('question_id', $ques->id)
                    ->select('name as title', 'id as optionId', 'is_sub_section as isSectionStatusId')->get();
                $questions_list[] = [
                    'questionId' => $ques->id,
                    'questionTitle' => $ques->name,
                    'questionSubTitle' => null,
                    'answer' => $ques->answer,
                    'type' => $ques->type,
                 
                    'options' => $options
                ];
            }

            // Append to $Question array
            $Question[] = [
                'formId' => $item->formId,
                'formName' => $item->formName,
                'detailName' => $detail['name'],
                'detailId' => $detail['detailId'],
                'questionList' => $questions_list
            ];
        }
    } else {
        $FormDetails[] = [
            'formName' => $item->name,
            'formId' => $item->formId,
            'details' => []
        ];
    }

    // Process sub-section titles
    if ($question_sub_title->isNotEmpty()) {
        $subDetails = [];
        foreach ($question_sub_title as $qt) {
            $subDetails[] = [
                'name' => $qt->name,
                'formId' => $item->formId,
                'subDetailId' => $qt->id,
                'isModified' => true,
                'optionId'=>$qt->option_id,
                'isRepeatableStatus' => $qt->isRepeatableStatus
            ];
        }

        $SubFormDetails[] = [
            'formName' => $item->formName,
            'formId' => $item->formId,
            'subDetails' => $subDetails
        ];

        // Find section and its questions for sub-sections
        foreach ($subDetails as $detail) {
            $subQuestionsList = [];
            $subQuestions = DB::table('questions')->where('section_id', $detail['subDetailId'])->where('option_id','!=',null)->get();

            foreach ($subQuestions as $subQues) {
                $subOptions = DB::table('options')->where('question_id', $subQues->id)
                    ->select('name as title', 'id as optionId', 'is_sub_section as isSectionStatusId')->get();
                $subQuestionsList[] = [
                    'questionId' => $subQues->id,
                    'questionTitle' => $subQues->name,
                    'questionSubTitle' => null,
                    'answer' => $subQues->answer,
                    'type' => $subQues->type,
                    'options' => $subOptions,
                    'formId' => $item->formId,
                    
                ];
            }

            // Append to $subQuestion array
            $subQuestion[] = [
                'formId' => $item->formId,
                'formName' => $item->formName,
                'detailName' => $detail['name'],
                'subDetailId' => $detail['subDetailId'],
                'questionList' => $subQuestionsList
            ];
        }
    } else {
        $SubFormDetails[] = [
            'formName' => $item->name,
            'formId' => $item->formId,
            'subDetails' => []
        ];
    }
}

return [
    'formList' => $form_data,
    'formDetails' => $FormDetails,
    'question' => $Question,
    'subFormDetails' => $SubFormDetails,
    'subQuestion' => $subQuestion
];
}
public function survey_form_latest_data(Request $request) {
$form_data = Form::where('status',1)->where('id',8)->select('name as formName', 'id as formId', 'isUnion', 'isModified')->orderBy('sequence','ASC')->get();

$FormDetails = [];
$Question = []; // Initialize $Question here
$SubFormDetails = [];
$subQuestion = []; // Initialize $subQuestion here

foreach ($form_data as $item) {
    // Fetch main question titles
    $question_title = QuestionTitle::where('form_id', $item->formId)->where('option_id', null)->orderBy('sequence','ASC')->get();
  
    // Fetch sub-section titles
    $question_sub_title = QuestionTitle::where('form_id', $item->formId)->where('option_id', '!=', null)->get();

    // Process main question titles
    if ($question_title->isNotEmpty()) {
        $details = [];
        foreach ($question_title as $qt) {
            $details[] = [
                'name' => $qt->name,
                'formId' => $item->formId,
                'detailId' => $qt->id,
                'isModified' => true,
                'isRepeatableStatus' => $qt->isRepeatableStatus
            ];
        }
       
        $FormDetails[] = [
            'formName' => $item->formName,
            'formId' => $item->formId,
            'details' => $details
        ];
   
        // Find section and its questions for main questions
       
        foreach ($details as $detail) {
            $questions_list = [];
            $questions = DB::table('questions')->where('section_id', $detail['detailId'])->where('option_id',null)->get();
            foreach ($questions as $ques) {
                $options = DB::table('options')->where('question_id', $ques->id)
                    ->select('name as title', 'id as optionId', 'is_sub_section as isSectionStatusId')->get();
                $questions_list[] = [
                    'questionId' => $ques->id,
                    'questionTitle' => $ques->name,
                    'questionSubTitle' => null,
                    'answer' => $ques->answer,
                    'type' => $ques->type,
                 
                    'options' => $options
                ];
            }

            // Append to $Question array
            $Question[] = [
                'formId' => $item->formId,
                'formName' => $item->formName,
                'detailName' => $detail['name'],
                'detailId' => $detail['detailId'],
                'questionList' => $questions_list
            ];
        }
        
    } else {
        $FormDetails[] = [
            'formName' => $item->name,
            'formId' => $item->formId,
            'details' => []
        ];
    }
    

    // Process sub-section titles
    if ($question_sub_title->isNotEmpty()) {
        $subDetails = [];
        foreach ($question_sub_title as $qt) {
            $subDetails[] = [
                'name' => $qt->name,
                'formId' => $item->formId,
                'subDetailId' => $qt->id,
                'isModified' => true,
                'optionId'=>$qt->option_id,
                'isRepeatableStatus' => $qt->isRepeatableStatus
            ];
        }

        $SubFormDetails[] = [
            'formName' => $item->formName,
            'formId' => $item->formId,
            'subDetails' => $subDetails
        ];

        // Find section and its questions for sub-sections
        foreach ($subDetails as $detail) {
            $subQuestionsList = [];
            $subQuestions = DB::table('questions')->where('section_id', $detail['subDetailId'])->where('option_id','!=',null)->get();

            foreach ($subQuestions as $subQues) {
                $subOptions = DB::table('options')->where('question_id', $subQues->id)
                    ->select('name as title', 'id as optionId', 'is_sub_section as isSectionStatusId')->get();
                $subQuestionsList[] = [
                    'questionId' => $subQues->id,
                    'questionTitle' => $subQues->name,
                    'questionSubTitle' => null,
                    'answer' => $subQues->answer,
                    'type' => $subQues->type,
                    'options' => $subOptions,
                    'formId' => $item->formId,
                    
                ];
            }
           

            // Append to $subQuestion array
            $subQuestion[] = [
                'formId' => $item->formId,
                'formName' => $item->formName,
                'detailName' => $detail['name'],
                'subDetailId' => $detail['subDetailId'],
                'questionList' => $subQuestionsList
            ];
        }
    } else {
        $SubFormDetails[] = [
            'formName' => $item->name,
            'formId' => $item->formId,
            'subDetails' => []
        ];
    }
}

 

return [
    'formList' => $form_data,
    'formDetails' => $FormDetails,
    'question' => $Question,
    'subFormDetails' => $SubFormDetails,
    'subQuestion' => $subQuestion
];
}
   
    
    
 public function rejectedFormOld(Request $request)
{
    
   if($request->userId==null){
       return response()->json([
        'success' => false,
        'validation_error'=>'user id is required'
    ]);
 }

   $rejectedForms=DB::table('survey_form')
    ->join('form_status','survey_form.id','=','form_status.form_id')
    ->join('form','survey_form.form_id','=','form.id')
     ->where('survey_form.user_id',$request->userId)
    ->where('form_status.form_status','R')
    ->where('form_status.update_by','field supervisor')
    ->select('survey_form.mobile_version','survey_form.option_ids','survey_form.beneficiary_details','survey_form.id as rejected_form_id','form_status.comment as rejected_comment','survey_form.created_at','survey_form.form_data','survey_form.form_id','form.name as form_name','survey_form.uc_id','survey_form.user_id','survey_form.id'
    ,DB::raw("'rejected' as type")
    )
    ->get();
  
   $final_data = [];
foreach ($rejectedForms as $item) {
    $rejected_ques_comments = [];  

    $questions = DB::table("questions_accept_reject")
                   ->where("survey_id", $item->id)
                   ->select("ques_id", "comment")
                   ->get();

    $rejected_ques_ids = $questions->pluck('ques_id')->toArray();
    $comments = $questions->pluck('comment')->toArray();

    foreach ($rejected_ques_ids as $key => $ques_id) {
        $rejected_ques_comments[] = ['id' => $ques_id, 'comments' => $comments[$key]];
    }

    $item->rejected_ques_ids = json_encode($rejected_ques_ids);
    $item->comments = json_encode($rejected_ques_comments);

    $final_data[] = $item;
}

   
    
    $total=count($rejectedForms);
    return response()->json([
        'success' => true,
        'data' => $final_data,
        'total'=>$total
        
    ]);
    
}
public function rejectedForm(Request $request)
{
    // Validate that userId is present in the request
    if ($request->userId == null) {
        return response()->json([
            'success' => false,
            'validation_error' => 'user id is required'
        ]);
    }

    // Fetch rejected forms based on userId and form status 'R'
    $rejectedForms = DB::table('survey_form')
        ->join('form_status', 'survey_form.id', '=', 'form_status.form_id')
        ->join('form', 'survey_form.form_id', '=', 'form.id')
        ->where('survey_form.user_id', $request->userId)
        ->where('form_status.form_status', 'R')
        ->where('form_status.update_by', 'field supervisor')
        ->select('survey_form.mobile_version', 'survey_form.option_ids', 'survey_form.beneficiary_details', 
                 'survey_form.id as rejected_form_id', 'form_status.comment as rejected_comment', 
                 'survey_form.created_at', 'survey_form.form_data', 'survey_form.form_id', 'form.name as form_name', 
                 'survey_form.uc_id', 'survey_form.user_id', 'survey_form.id', 
                 DB::raw("'rejected' as type"))
        ->get();

    $final_data = [];
    
    // Iterate through rejected forms to fetch question details
    foreach ($rejectedForms as $form) {
        $rejected_ques_ids = [];
        $comments = [];

        // Fetch questions related to the rejected form
        $questions = DB::table("questions_accept_reject")
            ->where("survey_id", $form->id)
            ->select("ques_id", "comment")
            ->get();

        // Collect question IDs and their associated comments
        $rejected_ques_ids = $questions->pluck('ques_id')->toArray();
        $comments = $questions->pluck('comment')->toArray();

        // If there are any rejected questions, process them
        if (count($rejected_ques_ids) > 0) {
            // Fetch all options related to the rejected questions
            $options = DB::table('options')
                ->whereIn('question_id', $rejected_ques_ids)
                ->select('id', 'question_id')
                ->get();
                
            // Process the options, child questions, and child options
            $all_option_ids = [];
            $all_child_question_ids = [];
            $all_grandchild_question_ids = [];
            
            foreach ($options as $option) {
                $all_option_ids[] = $option->id;

                // Get child questions related to this option
                $child_questions = DB::table('questions')
                    ->where('option_id', $option->id)
                    ->select('id')
                    ->get();

                foreach ($child_questions as $child) {
                    $all_child_question_ids[] = $child->id;

                    // Get child options for each child question
                    $child_options = DB::table('options')
                        ->where('question_id', $child->id)
                        ->select('id')
                        ->get();

                    foreach ($child_options as $child_option) {
                        // Get grandchild questions for each child option
                        $grandchild_questions = DB::table('questions')
                            ->where('option_id', $child_option->id)
                            ->select('id')
                            ->get();

                        foreach ($grandchild_questions as $grandchild) {
                            $all_grandchild_question_ids[] = $grandchild->id;
                        }
                    }
                }
            }

            // Combine all the question IDs (parent, child, and grandchild) into a single array
            $rejected_ques_ids = array_merge($rejected_ques_ids, $all_option_ids, $all_child_question_ids, $all_grandchild_question_ids);

            // Add corresponding comments (currently setting as null for new questions)
            $comments = array_merge($comments, array_fill(0, count($all_option_ids), null));
            $comments = array_merge($comments, array_fill(0, count($all_child_question_ids), null));
            $comments = array_merge($comments, array_fill(0, count($all_grandchild_question_ids), null));
        }

        // Prepare the rejected question data with IDs and comments
        $rejected_ques_comments = [];
        foreach ($rejected_ques_ids as $key => $ques_id) {
            $rejected_ques_comments[] = ['id' => $ques_id, 'comments' => $comments[$key] ?? null];
        }

        // Store the result for this form
        $form->rejected_ques_ids = json_encode($rejected_ques_ids);
        $form->comments = json_encode($rejected_ques_comments);

        // Add the form to the final data array
        $final_data[] = $form;
    }

    // Return the response with all the rejected forms and their details
    $total = count($rejectedForms);
    return response()->json([
        'success' => true,
        'data' => $final_data,
        'total' => $total
    ]);
}


public function submittedForm(Request $request)
{
    
    $request->validate([
        'userId' => 'required|integer|exists:users,id', 
    ]);

    
    $submittedForms = DB::table('survey_form')
        ->where('user_id', $request->userId)
        ->select('beneficiary_details', 'created_at as submitted_date', 'user_id', 'id')
        ->get();

    
    $rejectedData = [];
    $finalData = [];

    
    foreach ($submittedForms as $item) {
        $formStatus = DB::table('form_status')
            ->where('form_id', $item->id)
            ->where('update_by', 'field supervisor')
            ->where('form_status', 'R')
            ->first();

        if (!$formStatus) {
            $finalData[] = $item;            
        }
    }


    $countSubmitted = count($finalData);

    return response()->json([
        'success' => true,
        'data' => $finalData,
        'total' => $countSubmitted
    ]);
}





public function submittedForm2(Request $request)
{
    if ($request->userId == null) {
        return response()->json([
            'success' => false,
            'validation_error' => 'user id is required'
        ]);
    }

    $submittedForms = DB::table('survey_form')
        ->where('user_id', $request->userId)
        ->select('beneficiary_details', 'created_at as submitted_date', 'user_id', 'id')
        ->get();
    
     

    if ($submittedForms->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No submitted forms found for this user'
        ]);
    }

    $required_ids = $submittedForms->pluck('id')->map('intval')->toArray();
    $form_status = DB::table('form_status')
        ->where('form_status', 'R')
        ->where('update_by', 'field supervisor')
        ->pluck('form_id')
        ->toArray();

    $final_form_status = array_diff($form_status, $required_ids);

 

    if (empty($final_form_status)) {
        return response()->json([
            'success' => true,
            'data' => $submittedForms,
            'message' => 'All forms have status R'
        ]);
    }

    $submittedFormsSecond = DB::table('survey_form')
        ->whereIn('id', $final_form_status)
        ->where('user_id', $request->userId)
        ->select('beneficiary_details', 'created_at as submitted_date', 'user_id', 'id')
        ->get();

    return response()->json([
        'success' => true,
        'data' => $submittedFormsSecond,
        'total' => $submittedFormsSecond->count()
    ]);
}






 public function approvedForm(Request $request)
{
   if($request->userId==null){
       return response()->json([
        'success' => false,
        'validation_error'=>'user id is required'
    ]);
 }

   $approvedForms=DB::table('survey_form')
    ->join('form_status','survey_form.id','=','form_status.form_id')
    ->join('form','survey_form.form_id','=','form.id')
    ->where('survey_form.user_id',$request->userId)
    ->where('form_status.form_status','A')
    ->where('form_status.update_by','field supervisor')
    ->select('survey_form.beneficiary_details')
    ->get();
    $count_approved=count($approvedForms);
    return response()->json([
        'success' => true,
        'data' => $approvedForms,
        'total'=>$count_approved
    ]);
}






public function survey_form_vrc(Request $request)
{
    
    
    $data=$this->common_query_for_vrc($request->all(),33);
    return $data;
}


public function ndma_data_for_vrc(Request $request)
    {
        
         $ucValues= $request->uc;
         $NdmaVerification = NdmaVerification::whereIn('uc', $ucValues)
         ->join('districts','ndma_verifications.district','=','districts.id')
         ->join('tehsil','ndma_verifications.tehsil','=','tehsil.id')
         ->join('uc','ndma_verifications.uc','=','uc.id')
         ->join('survey_form','survey_form.ref_no','=','ndma_verifications.b_reference_number')
         ->join('answers','survey_form.id','=','answers.survey_form_id')
         ->where('answers.question_id', 968)
         ->select('ndma_verifications.*','districts.name as district_name','tehsil.name as tehsil_name','uc.name as uc_name','answers.answer as disability')
         ->get();
        $ndma=[];
        foreach($NdmaVerification as $item){
            $item->cnic= $this->formate_cnic($item->cnic);
            $item->contact_number= '+92'.$item->contact_number;
            $ndma[]=$item;
        }
        return $ndma;
    }
    
    
    
    
protected function common_query_for_vrc($request,$id)
{

    
    if ($request['user_id'] == null) {
        return response()->json([
            'success' => false,
            'validation_error' => 'User ID is required'
        ]);
    }

    $user_id = $request['user_id'];
    $user = User::find($user_id);
    
    $lot_data = json_decode($user->lot_id ?? null);
    $lots = DB::table('lots')->whereIn('id', $lot_data)->select('name')->get();
    $lots_data = $lots->pluck('name')->toArray();

    $district_data = json_decode($user->district_id ?? null);
    $districts = DB::table('districts')->whereIn('id', $district_data)->select('name', 'zone_id')->get();
    
    $districts_item = $districts->pluck('name')->toArray();
    $zone_ids = $districts->pluck('zone_id')->toArray();

    $tehsil_data = json_decode($user->tehsil_id ?? null);
    $tehsils = DB::table('tehsil')->whereIn('id', $tehsil_data)->select('name')->get();
    $tehsil_data = $tehsils->pluck('name')->toArray();

    $zones = DB::table('zone')->whereIn('id', $zone_ids)->select('name')->get();
    $zone_data = $zones->pluck('name')->toArray();

    $uc_data = json_decode($user->uc_id ?? null);
    $uc = DB::table('uc')->whereIn('id', $uc_data)->select('name')->get();
    $uc_data = $uc->pluck('name')->toArray();
       
    $forms = DB::table("form")
        ->select("id", "name")
        ->where('id',intval($id))
        ->where('status', 1)
        ->orderBy('sequence', 'ASC')
        ->get();
        return $forms;

    $result = [];
    
    foreach ($forms as $form) {
        $result[$form->name] = [
            "sections" => [],
            "sub_sections" => [],
            "form_id" => $form->id
        ];

        $sections = DB::table("question_title")
            ->where("form_id", $form->id)
            ->select("id", "name", "sub_heading", "form_id", "sub_section", "option_id")
            ->orderBy('sequence', 'ASC')
            ->get();
          

        foreach ($sections as $section) {
            if ($section->sub_section && $section->sub_section == 'true') {
                $sub_section_questions = DB::table("questions")
                    ->where("section_id", $section->id)
                    ->select("id", "name", "option_id", "placeholder", "section_id", "type", "answer", "related_question", "is_mandatory", "is_editable", 'range_number', 'location_condition')
                    ->orderBy('sequence', 'ASC')
                    ->get();

                $new_final_array = [];

                foreach ($sub_section_questions as $index => $question) {
                    $options = DB::table("options")
                        ->where("question_id", $question->id)
                        ->where("section_id", $section->id)
                        ->select("id as option_id", "name", "question_id", "answer", "variable_type")
                        ->get();

                    $new_final_array[] = [
                        'questions' => $question,
                        'options' => $options->isNotEmpty() ? $options : null,
                    ];
                }

                $result[$form->name]["sub_sections"][$section->option_id][] = [
                    "id" => $section->id,
                    "name" => $section->name,
                    "sub_heading" => $section->sub_heading,
                    "form_id" => $section->form_id,
                    "sub_section" => $section->sub_section,
                    "option_id" => $section->option_id,
                    "questions" => $new_final_array
                ];
            } else {
                // Main section handling
                $result[$form->name]["sections"][$section->name] = [
                    "section" => $section,
                    "questions" => []
                ];
           

                $questions = DB::table("questions")
                    ->where("section_id", $section->id)
                    ->select("id", "name", "option_id", "placeholder", "section_id", "type", "answer", "related_question", "is_mandatory", "is_editable", "range_number", "location_condition")
                    ->orderBy('sequence', 'ASC')
                    ->get();
                   

                foreach ($questions as $index => $question) {
                    $options = DB::table("options")
                        ->where("question_id", $question->id)
                        ->where("section_id", $section->id)
                        ->select("id as option_id", "name", "question_id", "answer", "variable_type", "is_replicable")
                        ->get();


                    if ($question->location_condition == 'lot') {
                        $user_lot = [];
                        foreach ($lots_data as $index => $item) {
                            $user_lot[] = [
                                'option_id' => $question->id . '_' . ($index + 1),
                                'name' => $item,
                                'question_id' => $question->id,
                                'answer' => null,
                                'variable_type' => null,
                                'is_replicable' => 0,
                                'parent_index' => 0
                            ];
                        }
                        $options = collect($user_lot);
                    }
                    if ($question->location_condition == 'district') {
                       
                        $user_district = [];
                      
                        foreach ($districts_item as $index => $item) {
                            
                            $user_district[] = [
                                'option_id' => $question->id . '_' . ($index + 1),
                                'name' => $item,
                                'question_id' => $question->id,
                                'answer' => null,
                                'variable_type' => null,
                                'is_replicable' => 0,
                                'parent_index' => 0
                            ];
                        }
                        $options = collect($user_district);
                    }
                    if ($question->location_condition == 'tehsil') {
                        $user_tehsil = [];
                        foreach ($tehsil_data as $index => $item) {
                            $user_tehsil[] = [
                                'option_id' => $question->id . '_' . ($index + 1),
                                'name' => $item,
                                'question_id' => $question->id,
                                'answer' => null,
                                'variable_type' => null,
                                'is_replicable' => 0,
                                'parent_index' => 0
                            ];
                        }
                        $options = collect($user_tehsil);
                    }
                    if ($question->location_condition == 'uc') {
                        $user_uc = [];
                        foreach ($uc_data as $index => $item) {
                            $user_uc[] = [
                                'option_id' => $question->id . '_' . ($index + 1),
                                'name' => $item,
                                'question_id' => $question->id,
                                'answer' => null,
                                'variable_type' => null,
                                'is_replicable' => 0,
                                'parent_index' => 0
                            ];
                        }
                        $options = collect($user_uc);
                    }
                    if ($question->location_condition == 'zone') {
                        $user_zone = [];
                        foreach ($zone_data as $index => $item) {
                            $user_zone[] = [
                                'option_id' => $question->id . '_' . ($index + 1),
                                'name' => $item,
                                'question_id' => $question->id,
                                'answer' => null,
                                'variable_type' => null,
                                'is_replicable' => 0,
                                'parent_index' => 0
                            ];
                        }
                        $options = collect($user_zone);
                    }
                    $result[$form->name]["sections"][$section->name]["questions"][] = [
                        "question" => $question,
                        "options" => $options->isNotEmpty() ? $options : null,
                    ];
                }
            }
        }
    }

    return $result;
}    





public function couting_beneficiary(Request $request,$id){
	   if ($id == null) {
    return response()->json([
        'success' => false,
        'message' => 'User ID is required',
        'status' => 400
    ], 400);
}

	 $user_ucs=DB::table("users")
	 ->where("id",$id)->select("uc_id")->first();
	 $uc_ids=json_decode($user_ucs->uc_id);
	 $toal_beneficiary=0;
	 foreach($uc_ids as $uc){
	     $toal_beneficiary +=DB::table("ndma_verifications")->where('uc',$uc)->count();
	 }
	 
	  return $toal_beneficiary;
	}






}
