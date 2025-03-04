@if(isset($question->genderimage))
    <div class="col-md-6">
        <div class="card pb-3 mb-3">
            <img src="{{ asset('storage/gender_safe_guard/' . $question->genderimage->name) }}" 
                 class="question_{{ $question->id }} card-img-top myImg rotating-image" 
                 alt="{{ $question->name }}">
            <button class="button_rotate">Rotate</button>
            <div class="card-body">
                <p class="card-text">{{ $question->name }}</p>

                @php
                    $answer = json_decode($question->gender_safeguard_answer[0]->answer ?? '');
                @endphp
                
                

                @if(isset($answer->fatchLocation))
                    <h5>Longitude:-</h5><span>{{ $answer->fatchLocation->longitude ?? '' }}</span>
                    <h5>Latitude:-</h5><span>{{ $answer->fatchLocation->latitude ?? '' }}</span>
                @endif
    
    @php
    $comment=get_comment('gender',$surveyId,$answer->question_id);
    @endphp
                @if(Auth::user()->role==61 && $formId==46)
                    @if($comment)    
                        <a class="btn btn-danger" style='float:right;' href='{{route("environment_case.delete_comment",[$question->id,$surveyId])}}' surveyid={{$surveyId}}  question_id={{$question->id}} >Revert</a>
                    @else
                        <button class="btn btn-success take_comment" style='float:right;' surveyid={{$surveyId}}  question_id={{$question->id}}>Add Comment</button>
                    @endif
            @endif 
                
            @if(Auth::user()->role==61 && $formId==46)
                    @if($comment) 
                        <div class="m-3 alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Comment </strong> {{$comment->comment ?? null}}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif   
            @endif
    
        
    
    
    
    
            </div>
        </div>
    </div>
@endif
