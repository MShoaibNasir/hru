@if(count($question->useranswer) > 0)
                @foreach($question->useranswer as $answer)
                @php
                $image = json_decode($answer->answer);
                $show_button=hideRejectSecond();
                $show_button_mne = monitoring_evaluation();
                @endphp
                
                
                @if(isset($image))
                <?php $image = $image->image->path; ?>
                <div class="col-md-3">
                 <div class="card pb-3 mb-3">
  <img src="{{ asset('uploads/surveyform_files') }}/{{ $image }}" class="question_{{ $question->id }} card-img-top myImg" alt="{{ $question->name }}">
  <div class="card-body">
    <p class="card-text">{{ $question->name }}</p>
  </div>
  
                @if($show_button_mne==true)
                <?php $comment = is_monitoring_evaluation($question->useranswer[0]->survey_form_id, $question->id); ?>
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
                   
                   @if(check_surveyform_status($question->useranswer[0]->survey_form_id, 'R') != 1 && check_surveyform_status($question->useranswer[0]->survey_form_id, 'A') != 1)
                       @if(count($question->decision) == 0 && $show_button==true)
                       <div class="d-flex gap-4 justify-content-end reject_{{ $question->id }}">
                        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $question->useranswer[0]->survey_form_id }}" ques_id="{{ $question->id }}" action="reject" href="javascript:void(0)">Reject</a>
                       </div>
                       @elseif(count($question->decision) > 0 && $show_button==true)
                       <div class="d-flex gap-4 justify-content-end revert_{{ $question->id }}">
                        <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $question->useranswer[0]->survey_form_id }}" ques_id="{{ $question->id }}" action="revert" href="javascript:void(0)">Revert</a>
                       </div>
                       @endif
                   @endif
                   
                @endif
</div>
</div>
                @endif
                @endforeach
                                
                                
@endif















