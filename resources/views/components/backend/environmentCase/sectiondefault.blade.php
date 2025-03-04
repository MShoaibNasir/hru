
@if(count($questions) > 0)
 @foreach($questions as $question)
     @if($question->type == "checkbox")
     <x-backend.environmentCase.typecheckbox :question="$question" :surveyId=$surveyformid/>
     @elseif($question->type == "map")
     <x-backend.environmentCase.typemap :question="$question" :surveyId=$surveyformid />
     @elseif($question->type == "radio")
     <x-backend.environmentCase.typeradio :question="$question" :surveyId=$surveyformid />
     @elseif($question->type == "image")
     @else
     <x-backend.environmentCase.typetext :question="$question" :surveyId=$surveyformid :surveyId=$surveyformid />
     @endif
 @endforeach
@endif

@if(count($questions) > 0)
<div class="row">
 @foreach($questions as $question)
     @if($question->type == "image")
     <x-backend.environmentCase.typeimage :question="$question" :surveyId=$surveyformid />
     @endif
 @endforeach
</div>
@endif