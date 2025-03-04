@if(count($questions) > 0)
 @foreach($questions as $question)
     @if($question->type == "checkbox")
     <x-backend.construction.typecheckbox :question="$question"/>
     @elseif($question->type == "map")
     <x-backend.construction.typemap :question="$question"/>
     @elseif($question->type == "radio")
     <x-backend.construction.typeradio :question="$question"/>
     @elseif($question->type == "image")
     @else
     <x-backend.construction.typetext :question="$question"/>
     @endif
 @endforeach
@endif

{{--
@if(count($questions) > 0)
<div class="row">
 @foreach($questions as $question)
     @if($question->type == "image")
     <x-backend.construction.typeimage :stage="$stage" :question="$question"/>
     @endif
 @endforeach
</div>
@endif
--}}