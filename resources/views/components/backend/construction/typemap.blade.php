@if(count($question->consructionanswer) > 0)
@if(isset(json_decode($question->consructionanswer)[0]->answer))

<h5>Longitude:-</h5><span>{{ json_decode(json_decode($question->consructionanswer)[0]->answer)->fetchLocation->longitude ?? '' }}</span>
<h5>Latitude:-</h5><span>{{ json_decode(json_decode($question->consructionanswer)[0]->answer)->fetchLocation->latitude ?? '' }}</span>
            
<div class="card my-2">
  <h5 id="question_{{ $question->id }}" class="card-header {{ $question->type }}">GPS Coordinates</h5>
  <div class="card-body">
          <p class="card-text">
                                @if(isset(json_decode(json_decode($question->consructionanswer)[0]->answer)->fetchLocation->longitude))
            @if(isset(json_decode(json_decode($question->consructionanswer)[0]->answer)->fetchLocation->latitude))
            
            <?php 
            $longitude = json_decode(json_decode($question->consructionanswer)[0]->answer)->fetchLocation->longitude;
            $latitude = json_decode(json_decode($question->consructionanswer)[0]->answer)->fetchLocation->latitude;
            ?>
            <iframe 
 width="100%" 
  height="240" 
  frameborder="0" 
  scrolling="no" 
  marginheight="0" 
  marginwidth="0" 
 src="https://maps.google.com/maps?q={{ $latitude }},{{ $longitude }}&hl=en&z=12&amp&output=embed"></iframe>
 @endif
 @endif
                                </p>
      </div>
</div>
@endif
@endif