@if(count($question->useranswer) > 0)
             @php 
             
             $show_button = hideRejectSecond();
             $show_button_mne = monitoring_evaluation();
             $comment123 = is_monitoring_evaluation($question->useranswer[0]->survey_form_id, $question->id);
             $comment = $question->mnecomment()->where('survey_id',$question->useranswer[0]->survey_form_id)->select('created_role','comment')->first();
             $decisionresult = $question->decision()->where('survey_id',$question->useranswer[0]->survey_form_id)->select('created_role','comment')->first();
             $show_button_hru_main = is_hru_main();
             $comment_missing_document = $question->comment_missing_document()->where('survey_id',$question->useranswer[0]->survey_form_id)->select('id','created_role','comment')->first();
             $get_father_husband_condition = get_answer(653,$question->useranswer[0]->survey_form_id);
             $skip_question=null;
             $missing_document_file = missing_document_file($question->useranswer[0]->survey_form_id, 290);
            @endphp
            
@if($get_father_husband_condition->answer=='Husband')
<?php $skip_question = 654; ?>
@else
<?php $skip_question = 655; ?>
@endif

@if($question->id != $skip_question )

<div class="card mb-2">
  <h5 id="question_{{ $question->id }}" class="card-header {{ $question->type }}">{{ $question->name }}</h5>
  <div class="card-body">
    <p class="card-text">
        @if($question->id == 246)
            @foreach($question->useranswer as $answer)
                 @if($answer->answer !== 'Un-disputed')
                  <img src="https://mis.hru.org.pk/images/cocio-legal-status.jpeg" style="width:100px; height:100px;" alt="{{ $answer->answer ?? '' }}" />
                  {{ $answer->answer ?? '' }}
                 @else
                 {{ $answer->answer ?? '' }}
                 @endif
            @endforeach
        @elseif($question->id == 247)
            @foreach($question->useranswer as $answer)
                 @if($answer->answer == 'No Evidence Available')
                  
                  @if($missing_document_file)
                  {{ $missing_document_file->evidence_type ?? '' }}
                  @else
                  <img src="https://mis.hru.org.pk/images/no-evidence.jpg" style="width:100px; height:100px;" alt="{{ $answer->answer ?? '' }}" />
                  {{ $answer->answer ?? '' }}
                  @endif
                  
                  
                 @else
                 {{ $answer->answer ?? '' }}
                 @endif
            @endforeach    
        @else
            @foreach($question->useranswer as $answer)

            <div class='edit_button_div'>
            {!! $answer->answer ?? '<span class="badge bg-danger">Not Available</span>' !!}
            @if(Auth::user()->role==62)
            <button type="button"  class="btn btn-primary btn-sm edit_question_btn"  data-bs-toggle="modal" survey_id={{$question->useranswer[0]->survey_form_id}}  question_id={{$answer->question_id}}  data-bs-target="#exampleModal">
            Edit Answers
            </button>
            @endif
            </div>
            @endforeach
        @endif    
            
        </p>
            
            
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
                   {{-- @if($decisionresult)
                    <div class="d-flex gap-4 justify-content-end revert_{{ $question->id }}">
                        <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $question->useranswer[0]->survey_form_id }}" ques_id="{{ $question->id }}" action="revert" href="javascript:void(0)">Revert</a>
                    </div>
                    @else
                       <div class="d-flex gap-4 justify-content-end reject_{{ $question->id }}">
                        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $question->useranswer[0]->survey_form_id }}" ques_id="{{ $question->id }}" action="reject" href="javascript:void(0)">Reject</a>
                       </div>
                    @endif  --}}
                @endif
                
                @endif
                @endif
                
                
    {{--            
    @if($show_button_hru_main==true && $question->id == 247)
        
        @if($comment_missing_document)
        <div class="d-flex gap-4 justify-content-end missing_document_comment_remove_{{ $question->id }}">
        <a class="btn btn-sm btn-success missing_document_comment_remove_btn my-2" style="height:30px;" comment_id="{{ $comment_missing_document->id }}" ques_id="{{ $question->id }}"  href="javascript:void(0)">Remove Land Document Missing</a>
        </div>
        @else
        <div class="d-flex gap-4 justify-content-end missing_document_{{ $question->id }}">
        <a class="btn btn-sm btn-danger missing_document_btn my-2" style="height:30px;" survey_id="{{ $question->useranswer[0]->survey_form_id }}" ques_id="{{ $question->id }}" action="missing_document" href="javascript:void(0)">Land Document Missing</a>
        </div>
        @endif
       
    @endif

    @if($comment_missing_document)
    <div class="my-3 alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $comment_missing_document->created_role }}: </strong> {{ $comment_missing_document->comment }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    --}}
                
                
    {{--            
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
    --}}
            
               
  </div>
</div> 
@endif
@endif