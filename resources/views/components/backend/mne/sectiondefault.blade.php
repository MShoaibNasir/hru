@if(count($questions) > 0)
 @foreach($questions as $question)
     @if($question->type == "checkbox")
     <x-backend.mne.typecheckbox :question="$question"/>
     @elseif($question->type == "map")
     <x-backend.mne.typemap :question="$question"/>
     @elseif($question->type == "radio")
     <x-backend.mne.typeradio :question="$question"/>
     @elseif($question->type == "image")
     @else
     <x-backend.mne.typetext :question="$question"/>
     @endif
 @endforeach
@endif

@if(count($questions) > 0)
<div class="row">
 @foreach($questions as $question)
     @if($question->type == "image")
     <x-backend.mne.typeimage :question="$question"/>
     @endif
 @endforeach
</div>
@endif