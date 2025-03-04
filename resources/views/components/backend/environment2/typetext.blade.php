@if(count($question->gender_safeguard_answer) > 0) 
            @foreach($question->gender_safeguard_answer as $answer)
        
            <div class="card mb-2">
            <h5 id="question_{{ $question->id }}" class="card-header {{ $question->type }}">{{ $question->name }}</h5>
              <div class="card-body">
                <p class="card-text">{{ $answer->answer }} </p>
              </div>
            </div>
            @endforeach
@endif
