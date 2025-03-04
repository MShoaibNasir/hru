
@if(count($question->social_safeguard_answer) > 0)

<div class="card mb-2">
  <h5 id="question_{{ $question->id }}" class="card-header {{ $question->type }}">{{ $question->name }}</h5>
  <div class="card-body">
    <p class="card-text">
            @foreach($question->social_safeguard_answer as $answer)
            @php
            $comment=get_comment('social',$surveyId,$answer->question_id);
            @endphp
            {!! $answer->answer ?? '<span class="badge bg-danger">Not Available</span>' !!}
                @if(Auth::user()->role==63 && $formId==45)
                    @if($comment)    
                        <a class="btn btn-danger" style='float:right;' href='{{route("environment_case.delete_comment",[$answer->question_id,$surveyId])}}' surveyid={{$surveyId}}  question_id={{$answer->question_id}} >Revert</a>
                    @else
                        <button class="btn btn-success take_comment" style='float:right;' surveyid={{$surveyId}}  question_id={{$answer->question_id}}>Add Comment</button>
                    @endif
                @endif 
                
                @if(Auth::user()->role==63 && $formId==45)
                    @if($comment) 
                        <div class="m-3 alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Comment </strong> {{$comment->comment ?? null}}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif   
                @endif
            @endforeach
        </p>
       
  </div>
</div> 

@endif