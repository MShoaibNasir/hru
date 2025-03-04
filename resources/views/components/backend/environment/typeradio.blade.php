
@if(count($question->gender_safeguard_answer) > 0)

<div class="card mb-2">
  <h5 id="question_{{ $question->id }}" class="card-header {{ $question->type }}">{{ $question->name }}</h5>
  <div class="card-body">
    <p class="card-text">
            @foreach($question->gender_safeguard_answer as $answer)
            {!! $answer->answer ?? '<span class="badge bg-danger">Not Available</span>' !!}
            {{-- is_array($answer->answer) ? 'Not Available' : ($answer->answer ?? 'Not Available') --}}
            @endforeach
        </p>
       
  </div>
</div> 

@endif