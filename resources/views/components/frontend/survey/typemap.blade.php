@if(count($question->useranswer) > 0)
   
@php
$decisionresult = $question->decision()->where('survey_id',$question->useranswer[0]->survey_form_id)->select('created_role','comment')->first();
@endphp




<div class="card mb-2">
  <h5 id="question_{{ $question->id }}" class="card-header {{ $question->type }}">{{ $question->name }}</h5>
  <div class="card-body">
          <p class="card-text">
                                @foreach($question->useranswer as $answer)
                                <?php $checkbox = json_decode($answer->answer); 
                                ?>
                                    @if(isset($checkbox[0]->answer))
                                    @if(isset($checkbox[1]->answer))
<iframe 
 width="100%" 
  height="240" 
  frameborder="0" 
  scrolling="no" 
  marginheight="0" 
  marginwidth="0" 
 src="https://maps.google.com/maps?q={{ $checkbox[0]->answer }},{{ $checkbox[1]->answer }}&hl=en&z=14&amp&output=embed"></iframe>                                        
                                    @endif
                                    @endif
                                @endforeach
                                </p>
      </div>
</div>
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