
@if(count($questions) > 0)
 @foreach($questions as $question)

     @if($question->type == "checkbox")
     <x-backend.environment2.typecheckbox :question="$question"/>
     @elseif($question->type == "map")
     <x-backend.environment2.typemap :question="$question"/>
     @elseif($question->type == "radio")
  
     <x-backend.environment2.typeradio :question="$question"/>
     @elseif($question->type == "image")
     @else
     <x-backend.environment2.typetext :question="$question"/>
     @endif
 @endforeach
@endif

@if(count($questions) > 0)
<div class="row">
 @foreach($questions as $question)
     @if($question->type == "image")
     <x-backend.environment2.typeimage :question="$question"/>
     @endif
 @endforeach
</div>
@endif