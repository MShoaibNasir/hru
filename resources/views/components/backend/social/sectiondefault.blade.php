
@if(count($questions) > 0)
 @foreach($questions as $question)
@php
$section=get_section($question->section_id);
$form_id=$section->form_id;

@endphp
     @if($question->type == "checkbox")
     <x-backend.social.typecheckbox :question="$question" :surveyId="$surveyformid" :formId="$form_id" />
     @elseif($question->type == "map")
     <x-backend.social.typemap :question="$question" :surveyId="$surveyformid" :formId="$form_id" />
     @elseif($question->type == "radio")
  
     <x-backend.social.typeradio :question="$question" :surveyId="$surveyformid" :formId="$form_id" />
     @elseif($question->type == "image")
     @else
     <x-backend.social.typetext :question="$question"  :surveyId="$surveyformid" :formId="$form_id" />
     @endif
 @endforeach
@endif

@if(count($questions) > 0)
<div class="row">
 @foreach($questions as $question)
     @if($question->type == "image")
     <x-backend.social.typeimage :question="$question" :surveyId="$surveyformid"  :formId="$form_id" />
     @endif
 @endforeach
</div>
@endif