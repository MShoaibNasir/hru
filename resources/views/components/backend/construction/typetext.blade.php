@if(count($question->consructionanswer) > 0) 
            @foreach($question->consructionanswer as $answer)
        
            <div class="card mb-2">
            <h5 id="question_{{ $question->id }}" class="card-header {{ $question->type }}">{{ $question->name }}</h5>
              <div class="card-body">
                <p class="card-text">{{ $answer->answer }} </p>
              </div>
            </div>
            @endforeach
@endif
