<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/*
/usr/local/bin/php /home/misoldhruorg/public_html/artisan destrucutreForm:cron && /usr/local/bin/php /home/misoldhruorg/public_html/artisan queue:work >> /dev/null 2>&1
*/

class DestructureForm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $survey_form_id;

    /**
     * Create a new job instance.
     *
     * @param int $survey_form_id
     * @return void
     */
    public function __construct($survey_form_id)
    {
        $this->survey_form_id = $survey_form_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Processing survey form ID: ' . $this->survey_form_id);

        try {
             $answer_check_survey_id = DB::table('answers')->where('survey_form_id', $this->survey_form_id)->first();
             if(!$answer_check_survey_id){
            
            
            DB::table('dummy')->insert(['name'=>$this->survey_form_id, 'note'=>'JOB START']);
            
            // $survey_form = DB::table('survey_form')
            //                  ->where('id', $this->survey_form_id)
            //                  ->select('form_data', 'id')
            //                  ->first();
            
            
              $survey_form = DB::table('survey_json')
                             ->where('survey_id', $this->survey_form_id)
                             ->select('json as form_data', 'survey_id as id')
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
                  
                    }else{
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
                $answer_check=DB::table('answers')->where('survey_form_id',$survey_form_id)->first();
                if(!$answer_check){
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
                
               
                
                
            }
            
            
            
        //Update Beneficiary CNIC Path    
        update_beneficiary_cnic_path($survey_form_id);    
        }//check survey_json exist
        
        
        DB::table('dummy')->insert(['name'=>$this->survey_form_id, 'note'=>'JOB END']);
        }//check answer exist
        
        
        
        
        
        } catch (\Exception $e) {
            Log::error('Error processing survey form ID: ' . $this->survey_form_id . ' - ' . $e->getMessage());
        }

        return 'done';
    }
}
