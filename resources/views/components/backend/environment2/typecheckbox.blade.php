@if(count($question->consructionanswer) > 0)
<div class="card mb-2">
  <h5 id="question_{{ $question->id }}" class="card-header {{ $question->type }}">{{ $question->name }}</h5>
  <div class="card-body">
          <p class="card-text">
                                @foreach($question->consructionanswer as $answer)
                                <?php $checkbox = json_decode($answer->answer); ?>
                                    @if($checkbox)
                                        @foreach($checkbox as $item)
                                          <span class="badge bg-primary">{{ getoptionlabel($item) }}</span>
                                        @endforeach
                                    @endif
                                @endforeach
                                </p>
      </div>
</div>
@endif