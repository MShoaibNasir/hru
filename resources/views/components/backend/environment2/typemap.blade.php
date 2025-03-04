@if(count($question->consructionanswer) > 0)
<div class="card mb-2">
  <h5 id="question_{{ $question->id }}" class="card-header {{ $question->type }}">{{ $question->name }}</h5>
  <div class="card-body">
          <p class="card-text">
                                @foreach($question->consructionanswer as $answer)
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
@endif