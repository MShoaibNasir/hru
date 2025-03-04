@if(count($questions) > 0)
@if(count($questions[0]->useranswer) > 0)
@if($questions[0]->useranswer[0]->answer == 'No')
<div class="card mb-2">
  <div class="card-body">
        <p class="card-text"><span class="badge bg-primary">No</span></p>
  </div>
</div>
@elseif($questions[0]->useranswer[0]->answer == '')
<div class="card mb-2">
  <div class="card-body">
        <p class="card-text"><span class="badge bg-primary">Empty</span></p>
  </div>
</div>
@else
 @foreach($questions as $question)
   {{--@if($question->useranswer[0]->answer)--}}
     @if($question->type == "checkbox")
     <x-frontend.survey.typecheckbox :question="$question"/>
     @elseif($question->type == "map")
     <x-frontend.survey.typemap :question="$question"/>
     @elseif($question->type == "radio")
     <x-frontend.survey.typeradio :question="$question"/>
     @elseif($question->type == "image")
     <x-frontend.survey.typeimage :question="$question"/>
     @else
     <x-frontend.survey.typetext :question="$question"/>
     @endif
    {{-- @endif --}}
 @endforeach
@endif
@endif
@endif