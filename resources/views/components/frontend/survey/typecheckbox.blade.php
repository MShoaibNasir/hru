@if(count($question->useranswer) > 0)
            @php
             $show_button = hideRejectSecond();
             $show_button_mne = monitoring_evaluation();
             $comment123 = is_monitoring_evaluation($question->useranswer[0]->survey_form_id, $question->id);
             $comment = $question->mnecomment()->where('survey_id',$question->useranswer[0]->survey_form_id)->select('created_role','comment')->first();
             $decisionresult = $question->decision()->where('survey_id',$question->useranswer[0]->survey_form_id)->select('created_role','comment')->first();
             $remains_dammaged_house = get_answer(756,$question->useranswer[0]->survey_form_id);
             $Resilient_construction = get_answer(745,$question->useranswer[0]->survey_form_id);
             $construction_done_by = get_answer(747,$question->useranswer[0]->survey_form_id);
             $services_mason = get_answer(749,$question->useranswer[0]->survey_form_id);
             $skip_question=null;
             $skip_question_second=null;
             $skip_question_third=null;
             $skip_question_four=null;
             $skip_question_five=null;
             $skip_question_six=null;
            @endphp
@if($remains_dammaged_house->answer=='No')
<?php 

$skip_question = 768;
$skip_question_second = 770;
$skip_question_third = 772;
?>
@endif

@if($construction_done_by->answer=='Govt' || $construction_done_by->answer=='Loan' || $construction_done_by->answer=='Owner Himself')
<?php 

$skip_question = 748;

?>
@endif 

@if($Resilient_construction->answer=='Yes')
<?php 
$skip_question = 746;
?>
@endif
@if($services_mason->answer=='No')
<?php 
$skip_question = 750;
$skip_question_second = 751;
?>
@endif

@if($question->id != $skip_question && $question->id != $skip_question_second  && $question->id != $skip_question_third && $question->id != $skip_question_four && $question->id != $skip_question_five && $question->id != $skip_question_six)
            
<div class="card mb-2">
  <h5 id="question_{{ $question->id }}" class="card-header {{ $question->type }}">{{ $question->name }}</h5>
  <div class="card-body">
      
          
          <p class="card-text">
                                @foreach($question->useranswer as $answer)
                                <?php $checkbox = json_decode($answer->answer); ?>
                                    @if($checkbox)
                                        @foreach($checkbox as $item)
                                          <span class="badge bg-primary">{{ getoptionlabel($item) }}</span>
                                        @endforeach
                                    @endif
                                @endforeach
                                </p>
            
            @if($show_button_mne==true)
        @if($comment)
        <div class="d-flex gap-4 justify-content-end revert_{{ $question->id }}">
        <a class="btn btn-sm btn-success comment_revert" style="height:30px;" comment_id="{{ $comment->id }}" ques_id="{{ $question->id }}"  href="javascript:void(0)">Remove Comment</a>
        </div>
        @else
        <div class="d-flex gap-4 justify-content-end reject_{{ $question->id }}">
        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $question->useranswer[0]->survey_form_id }}" ques_id="{{ $question->id }}" action="comment" href="javascript:void(0)">Comment</a>
        </div>
        @endif
                   
                @else
                
                @if($show_button==true)
                
                @if(check_surveyform_status($question->useranswer[0]->survey_form_id, 'R') != 1 && check_surveyform_status($question->useranswer[0]->survey_form_id, 'A') != 1)
                    @if($decisionresult)
                    <div class="d-flex gap-4 justify-content-end revert_{{ $question->id }}">
                        <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $question->useranswer[0]->survey_form_id }}" ques_id="{{ $question->id }}" action="revert" href="javascript:void(0)">Revert</a>
                    </div>
                    @else
                       <div class="d-flex gap-4 justify-content-end reject_{{ $question->id }}">
                        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $question->useranswer[0]->survey_form_id }}" ques_id="{{ $question->id }}" action="reject" href="javascript:void(0)">Reject</a>
                       </div>
                    @endif
                @endif
                
                @endif
                @endif
                
    @if($comment)
    <div class="my-3 alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $comment->created_role }}: </strong> {{ $comment->comment }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if($decisionresult)
    <div class="my-3 alert alert-primary alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $decisionresult->created_role }}: </strong> {{ $decisionresult->comment }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif 
               
            
      </div>
</div>
@endif
@endif