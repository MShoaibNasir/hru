
@if(count($questions) > 0)
 @foreach($questions as $question)
 
 @php
$section=get_section($question->section_id);
$form_id=$section->form_id;


@endphp

     @if($question->type == "checkbox")
     <x-backend.gender.typecheckbox :question="$question"  :surveyId="$surveyformid" :formId="$form_id" />
     @elseif($question->type == "map")
     <x-backend.gender.typemap :question="$question" :surveyId="$surveyformid" :formId="$form_id" />
     @elseif($question->type == "radio")
  
     <x-backend.gender.typeradio :question="$question" :surveyId="$surveyformid" :formId="$form_id" />
     @elseif($question->type == "image")
     @else
     <x-backend.gender.typetext :question="$question" :surveyId="$surveyformid" :formId="$form_id" />
     @endif
 @endforeach
@endif

@if(count($questions) > 0)
<div class="row">
 @foreach($questions as $question)
     @if($question->type == "image")
     <x-backend.gender.typeimage :question="$question" :surveyId="$surveyformid" :formId="$form_id" />
     @endif
 @endforeach
</div>
@endif