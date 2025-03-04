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
use App\Models\QuestionTitle; 
use App\Models\SignUpRetrictions;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Jobs\destructureForm;
class MNEController extends BaseController
{
   
public function ndma_data_for_mne(Request $request){
        
         $ucValues= $request->uc;
         $NdmaVerification = NdmaVerification::whereIn('uc', $ucValues)
         ->join('districts','ndma_verifications.district','=','districts.id')
         ->join('tehsil','ndma_verifications.tehsil','=','tehsil.id')
         ->join('uc','ndma_verifications.uc','=','uc.id')
         ->join('survey_form','survey_form.ref_no','=','ndma_verifications.b_reference_number')
         ->join('users','users.id','=','survey_form.user_id')
         ->select('ndma_verifications.*','districts.name as district_name','tehsil.name as tehsil_name','uc.name as uc_name','users.id as user_id','survey_form.id as survey_form_id')
         ->get();
        $ndma=[];
        foreach($NdmaVerification as $item){
           
            $item->cnic= $this->formate_cnic($item->cnic);
            $item->contact_number= '+92'.$item->contact_number;
          
            $field_supervisor=DB::table('user_sign_up_data')->where('user_id',$item->user_id)->select('sign_up_source')->first();
                $disability = DB::table('answers')
                ->where('question_id', 968)
                ->where('survey_form_id', $item->survey_form_id)
                ->first();
            
           
            if($disability){
            $item['disability']=$disability->answer;
            }else{
               $item['disability']='NO'; 
            }
            $item['ip_name']='not available';
            if(isset($field_supervisor)){
            $ip=DB::table('user_sign_up_data')->where('user_id',$field_supervisor->sign_up_source)->select('sign_up_source')->first();
            $ip=DB::table('users')->where('id',$ip->sign_up_source)->select('name')->first();
             $item['ip_name']=$ip->name;   
            }
            
          
            $vrc_committee=DB::table('vrc_committee')
            ->join('vrc_formation','vrc_formation.id','=','vrc_committee.vrc_formation_id')
            ->select('vrc_formation.name_of_vrc as vrc_formation_name')
            ->where('beneficiary_id',$item->b_reference_number)->first();
            
          
            if($vrc_committee){
               
                $item['vrc']=$vrc_committee->vrc_formation_name;
                
            }else{
                $item['vrc']='This beneficiary is not a member of any VRC!';
            }
            $ndma[]=$item;
        }
        return $ndma;
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

public function mne_data_upload(Request $request){
        
               
            $mne_json = DB::table("mne_json")->insertGetId([
                
                "form_id"=>$request->form_id,
                "form_name"=>$request->form_name,
                "mobile_version"=>$request->mobile_version ?? null,
                "user_id"=>$request->user_id,
                "ref_no"=>$request->ref_no,
                "json"=> $request->form_data,
                ]);
                
           
              if($request->hasFile('Images')){    
              
              $images_data = uploadfilesconstruction($mne_json, $request->Images, 'mne_', 'mne_files');  
              $data = $images_data->getData();
              
                  foreach($data->files as $key=>$item){
                  DB::table("mne_files")->insert([
    		        'mne_id' => $mne_json,
    		        'question_id' => $item->only_image_name,
                    'name' => $item->file_name,
    				'extension' => $item->extension,
    				'size' => getfilesize($item->bytes),
                    'created_by' => auth()->user()->id ?? 0,
    				'updated_by' => auth()->user()->id ?? 0
                    
                ]);
                
                
                  }
               
              }
           
                     
           
            $mne_answer=null;
            
            $form= json_decode($request->form_data,true);

     
            foreach($form['sections'] as $item){
            foreach($item['questions'] as $ques){

              
            $mne_answer=DB::table("mne_answer")->insert([
                "section_id"=>$item['section']['id'],
                "question_id"=>$ques['question']['id'],
                "answer" => is_array($ques['question']['answer']) ? json_encode($ques['question']['answer']) : $ques['question']['answer'],
                "user_id"=>$request->user_id,
                "ref_no"=>$request->ref_no,
                "type"=>$ques['question']['type'],
                "mne_json_id"=>$mne_json     
            ]);
              
            //Subquestion Answer
               if($ques['options']){
                
                    foreach($ques['options'] as $options){
                                    //  return $options;
            // die;
                        if($options['is_sub_section'] == 1){
                   
                            foreach($options['subsection'][0]['questions'] as $subquestions){
                                //dump($subquestions);
                
                $mne_answer = DB::table("mne_answer")->insert([
                    "section_id"=>$subquestions['section_id'] ?? 0,
                    "question_id"=>$subquestions['id'],
                    "answer" => is_array($subquestions['answer']) ? json_encode($subquestions['answer']) : $subquestions['answer'],
                    "user_id"=>$request->user_id,
                    "ref_no"=>$request->ref_no,
                    "type"=>$subquestions['type'],
                    "mne_json_id" => $mne_json     
                ]);
                
                            }
                            
                        }
                    }
                }
                //Subquestion Answer End 
            
        }
        }
            $surveyform = SurveyData::where('ref_no', $request->ref_no)->first();
            MNE::where('id',$mne_json)->update(['lot_id' => $surveyform->lot_id,'district_id' => $surveyform->district_id,'tehsil_id' => $surveyform->tehsil_id,'uc_id' => $surveyform->uc_id]);
    
            if($mne_answer){
                 return response()->json(['success'=>"MNE form submit successfully!"],200);
            }else{
                 return response()->json(['error'=>"some error found data not uploaded"],400);
            }     
                        
        
        
}    

public function survey_form_mne()
{
    $form = Form::with(['sections' => function($q){ 
        $q->with(['questions'=> function($q){
            $q->with(['options'=> function($q){
            $q->with(['subsection'=> function($q){ $q->with(['questions'=> function($q){ $q->with('options'); }])->where('sub_section','true')->where('form_id', 31); }]);
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


}



    















