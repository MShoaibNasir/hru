<div class="card mb-2">
  <h5 id="question_{{ $question->id }}" class="card-header {{ $question->type }}">{{ $question->name }}123</h5>
  <div class="card-body">
    {{--<h5 class="card-title">Special title treatment</h5>--}}
    @if(count($question->useranswer) > 0)
    <p class="card-text">
            @foreach($question->useranswer as $answer)
            {!! $answer->answer ?? '<span class="badge bg-danger">Not Available</span>' !!}
            {{-- is_array($answer->answer) ? 'Not Available' : ($answer->answer ?? 'Not Available') --}}
            @endforeach
            @php
             $show_button=hideRejectSecond();
            @endphp
        </p>
                  
                   @if(count($question->decision) == 0 && $show_button==true)
                   <div class="d-flex gap-4 justify-content-end reject_{{ $question->id }}">
                    <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $question->useranswer[0]->survey_form_id }}" ques_id="{{ $question->id }}" action="reject" href="javascript:void(0)">Reject</a>
                   </div>
                @elseif(count($question->decision) > 0 && $show_button==true)
                <div class="d-flex gap-4 justify-content-end revert_{{ $question->id }}">
                    <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $question->useranswer[0]->survey_form_id }}" ques_id="{{ $question->id }}" action="revert" href="javascript:void(0)">Revert</a>
                   </div>
                @endif
        @else
        <p class="card-text"><span class="badge bg-danger">Not Available</span></p>
        @endif
    
    {{--<a href="#" class="btn btn-primary">Go somewhere</a>--}}
    
                   
  </div>
</div> 