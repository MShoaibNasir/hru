@if(count($questions) > 0)
        @foreach($questions as $question)
            @if(count($question->useranswer) > 0)
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
            @endif    
        @endforeach
@endif