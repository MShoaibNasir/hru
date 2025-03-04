

@if(isset($question->socialimage))

@php



if (!empty($question->social_safeguard_answer) && isset($question->social_safeguard_answer[0])) {
    $social_id = $question->social_safeguard_answer[0]->social_safeguard_json_id;
} else {
    $social_id = null; 
}

if ($social_id !== null) {
    $image = \DB::table('social_files')
        ->where('social_id', $social_id)
        ->where('question_id', intval(optional($question->socialimage)->question_id))
        ->select('name')
        ->first();
} else {
    $image = null; 
}



@endphp

@if (!empty($question->social_safeguard_answer) && isset($question->social_safeguard_answer[0]) && isset($image))

            @php
            $comment=get_comment('social',$surveyId,$question->id);
            @endphp

            <div class="col-md-6">
            <div class="card pb-3 mb-3">
            <img src="{{ asset('storage/social_safe_guard') }}/{{ $image->name }}" class="question_{{ $question->id }} card-img-top myImg rotating-image" alt="{{ $question->name }}">
            <button class="button_rotate">Rotate</button>
            <div class="card-body">
            <p class="card-text">{{ $question->name }}</p>
            @if(isset(json_decode($question->social_safeguard_answer)[0]->answer))
            <h5>Longitude:-</h5><span>{{ json_decode(json_decode($question->social_safeguard_answer)[0]->answer)->fatchLocation->longitude ?? '' }}</span>
            <h5>Latitude:-</h5><span>{{  json_decode(json_decode($question->social_safeguard_answer)[0]->answer)->fatchLocation->latitude ?? '' }}</span>
            @endif
            @if(Auth::user()->role==63 && $formId==45)
                    @if($comment)    
                        <a class="btn btn-danger" style='float:right;' href='{{route("environment_case.delete_comment",[$question->id,$surveyId])}}' surveyid={{$surveyId}}  question_id={{$question->id}} >Revert</a>
                    @else
                        <button class="btn btn-success take_comment" style='float:right;' surveyid={{$surveyId}}  question_id={{$question->id}}>Add Comment</button>
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
            
            
            </div>
            </div>
            </div>
@endif            
@endif



