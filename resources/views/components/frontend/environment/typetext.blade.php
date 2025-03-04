
@if(count($question->useranswer) > 0) 
            @php
             $show_button = hideRejectSecond();
             $show_button_mne = monitoring_evaluation();
             $comment123 = is_monitoring_evaluation($question->useranswer[0]->survey_form_id, $question->id);
             $comment = $question->mnecomment()->where('survey_id',$question->useranswer[0]->survey_form_id)->select('created_role','comment')->first();
             $decisionresult = $question->decision()->where('survey_id',$question->useranswer[0]->survey_form_id)->select('created_role','comment')->first();
             $cnic_expiry = get_answer(350,$question->useranswer[0]->survey_form_id);
             $skip_question=null;
             @endphp
@if($cnic_expiry->answer=='Life time')
<?php 
$skip_question = 675;
?>

@endif             

@if($question->id != $skip_question )
    
            @foreach($question->useranswer as $answer)
            <div class="card mb-2">
            <h5 id="question_{{ $question->id }}" class="card-header {{ $question->type }}">{{ $question->name }}</h5>
              <div class="card-body">
                <p class="card-text">{{ $answer->answer }} </p>
          
           
                @if(Auth::check() && Auth::user()->role == 38)
                <!-- Button trigger modal -->
                <button type="button"  class="btn btn-primary modal_item" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Edit Answers
                </button>
                @endif
        
        @if($show_button_mne==true)
        @if($comment)
        @php
        $comment_id=\DB::table('questions_accept_reject')->where('ques_id',$question->id)->where('survey_id',$question->useranswer[0]->survey_form_id)->select('id')->first();
        @endphp
     
        <div class="d-flex gap-4 justify-content-end revert_{{ $question->id }}">
        <a class="btn btn-sm btn-success comment_revert" style="height:30px;" comment_id="{{ $comment_id->id }}" ques_id="{{ $question->id }}"  href="javascript:void(0)">Remove Comment</a>
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
                        <a class="btn btn-sm btn-success rejection_revert question_data" answer="{{ $answer->answer }}" style="height:30px;" survey_id="{{ $question->useranswer[0]->survey_form_id }}" ques_id="{{ $question->id }}" action="revert" href="javascript:void(0)">Revert</a>
                    </div>
                    @else
                       <div class="d-flex gap-4 justify-content-end reject_{{ $question->id }}">
                        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn question_data" answer="{{ $answer->answer }}" style="height:30px;" survey_id="{{ $question->useranswer[0]->survey_form_id }}" ques_id="{{ $question->id }}" action="reject" href="javascript:void(0)">Reject</a>
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
            @endforeach
            
@endif


<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Answer</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <span>Old Value:- </span>  <p id='insert_answer'></p>
        <input type='text' id='new_value' class='form-control'>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary save_changes_question">Save changes</button>
      </div>
    </div>
  </div>
</div>
@endif
