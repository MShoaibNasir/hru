

@if(isset($question->environment_answer))
@if($question->environmentimage)

@php


$comment=get_comment('environment',$surveyId,$question->id);


$image=\DB::table('environment_case_files')
->where('environment_case_id',$question->environment_answer[0]->environment_case_json_id)
->where('question_id',$question->environmentimage->question_id)
->select('name')->first();


@endphp

            <div class="col-md-6">
            <div class="card pb-3 mb-3">
            <img src="{{ asset('storage/environemnt') }}/{{ $image->name }}" class="question_{{ $question->id }} card-img-top myImg rotating-image" alt="{{ $question->name }}">
            <button class="button_rotate">Rotate</button>
            <div class="card-body">
            <p class="card-text">{{ $question->name }}</p>
            @if(isset(json_decode($question->environment_answer)[0]->answer))
            @php
            $coordinates=json_decode($question->environment_answer[0]->answer);
            @endphp
            <div>
            <h5>Longitude:-</h5><span>{{ $coordinates->fatchLocation->longitude ?? '' }}</span>
            </div>
            <div>
            <h5>Latitude:-</h5><span>{{ $coordinates->fatchLocation->latitude ?? '' }}</span>
            </div>
            @endif
            <div class='parent_div'>
            @if(Auth::user()->role==62)
                @if($comment)    
                <a class="btn btn-danger my-4" style='float:right;' href='{{route("environment_case.delete_comment",[$question->id,$surveyId])}}' surveyid={{$surveyId}}  question_id={{$question->id}} >Revert</a>
                @else
                <button class="btn btn-success take_comment my-4" style='float:right;' surveyid={{$surveyId}}  question_id={{$question->id}}>Add Comment</button>
                @endif
            @endif
            
            @if(Auth::user()->role==62)
            <br><br><br>
                @if($comment)
                <div class="m-3 alert alert-danger alert-dismissible fade show my-4" role="alert">
                <strong>Comment </strong> {{$comment->comment ?? null}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
               @endif
              @endif 
            </div>
            </div>
            </div>
            </div>
@endif
@endif



