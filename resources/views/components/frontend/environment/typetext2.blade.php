@if(count($question->useranswer) > 0)
<div class="card mb-2">
  <h5 id="question_{{ $question->id }}" class="card-header {{ $question->type }}">{{ $question->name }}</h5>
  <div class="card-body">
  
    @if(count($question->useranswer) > 0)
         
          
    <p class="card-text">
            @foreach($question->useranswer as $answer)
            {!! $answer->answer ?? '<span class="badge bg-danger">Not Available</span>' !!}
            {{-- is_array($answer->answer) ? 'Not Available' : ($answer->answer ?? 'Not Available') --}}
            @endforeach
    </p>
                   {{--
                   @if(count($question->decision) == 0)
                   <div class="d-flex gap-4 justify-content-end decision_{{ $question->id }}">
                    <a class="btn btn-sm btn-success is_approved" style="height:30px;" survey_id="{{ $question->useranswer[0]->survey_form_id }}" ques_id="{{ $question->id }}" action="accept" href="javascript:void(0)">Accept</a>
                    <a class="btn btn-sm btn-danger is_approved" style="height:30px;" survey_id="{{ $question->useranswer[0]->survey_form_id }}" ques_id="{{ $question->id }}" action="reject" href="javascript:void(0)">Reject</a>
                   </div>
                   @endif
                   --}}
        @else
        <p class="card-text"><span class="badge bg-danger">Not Available</span></p>
        @endif
  </div>
</div> 
@endif