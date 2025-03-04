
@if(count($questions) > 0)
 @foreach($questions as $question)

     @if($question->type == "checkbox")
     <x-backend.gender.typecheckbox :question="$question"/>
     @elseif($question->type == "map")
     <x-backend.gender.typemap :question="$question"/>
     @elseif($question->type == "radio")
  
     <x-backend.gender.typeradio :question="$question"/>
     @elseif($question->type == "image")
     @else
     <x-backend.gender.typetext :question="$question"/>
     @endif
 @endforeach
@endif

@if(count($questions) > 0)
<div class="row">
 @foreach($questions as $question)
     @if($question->type == "image")
     <x-backend.gender.typeimage :question="$question"/>
     @endif
 @endforeach
</div>
@endif