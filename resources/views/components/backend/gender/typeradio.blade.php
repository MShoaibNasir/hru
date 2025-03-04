
@if(count($question->gender_safeguard_answer) > 0)

<div class="card mb-2">
  <h5 id="question_{{ $question->id }}" class="card-header {{ $question->type }}">{{ $question->name }}</h5>
  <div class="card-body">
    <p class="card-text">
            @foreach($question->gender_safeguard_answer as $answer)
            {!! $answer->answer ?? '<span class="badge bg-danger">Not Available</span>' !!}
            @endforeach
        </p>
        @php
        $comment=get_comment('gender',$surveyId,$answer->question_id);
        @endphp
        
        @if(Auth::user()->role==61  && $formId==46)
        @if($comment)    
        <a class="btn btn-danger" style='float:right;' href='{{route("environment_case.delete_comment",[$answer->question_id,$surveyId])}}' surveyid={{$surveyId}}  question_id={{$answer->question_id}} >Revert</a>
        @else
        <button class="btn btn-success take_comment" style='float:right;' surveyid={{$surveyId}}  question_id={{$answer->question_id}}>Add Comment</button>
        @endif
        @endif     
    
    
        @if(Auth::user()->role==61 && $formId==46)
            @if($comment) 
            <br>
            <br>
            <br>
                <div class="m-3 alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Comment </strong> {{$comment->comment ?? null}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif   
        @endif

       
  </div>
</div> 

@endif