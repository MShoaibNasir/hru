
@php
$show_button_mne = monitoring_evaluation();
@endphp
@if(count($questions) > 0)
 @foreach($questions as $question)
     @if($question->type == "checkbox")
     <x-frontend.environment.typecheckbox :question="$question"/>
     @elseif($question->type == "map")
     <x-frontend.environment.typemap :question="$question"/>
     @elseif($question->type == "radio")
     <x-frontend.environment.typeradio :question="$question"/>
     @elseif($question->type == "image")
     <x-frontend.environment.typeimage :question="$question"/>
     @else
     <x-frontend.environment.typetext :question="$question"/>
     @endif
 @endforeach
@endif

@if($sectionid == 41)
@php 

$a_check = get_answer(247,$surveyformid);
if($a_check->answer=='No Evidence Available'){
$a=false;
$b=false;
}else{
$a = get_question_image($surveyformid, 290);
$b = get_question_image($surveyformid, 291);
}
$location_a=get_question_location($surveyformid, 290);
$location_b=get_question_location($surveyformid, 291);
@endphp
<div class="row">
@if($a)
<div class="col-md-6">
<div class="card pb-3 mb-3">
  <img src="{{ $a }}" alt="{{ getquestionlabel(290) }}" class='myImg rotating-image'  />
  <button class="button_rotate">Rotate</button>
  <div class="card-body">
    <p class="card-text 290">{{ getquestionlabel(290) }}</p>
    {!! $location_a !!}
    
    @php
    $result= hideReject($surveyformid,290);
    $comment = is_monitoring_evaluation($surveyformid, 290);
    $reject_comment = is_question_reject($surveyformid, 290);
    @endphp
    
    @if($show_button_mne==true)
        @if($comment)
        <a class="btn btn-sm btn-success comment_revert" style="height:30px;" comment_id="{{ $comment->id }}" ques_id="290"  href="javascript:void(0)">Remove Comment</a>
        @else
        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="290" action="comment" href="javascript:void(0)">Comment</a>
        @endif
        
    @else
        @if(check_surveyform_status($surveyformid, 'R') != 1 && check_surveyform_status($surveyformid, 'A') != 1)
            @if($result && $result!=="hide functionality")
            <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="290" action="revert" href="javascript:void(0)">Revert</a>
             @elseif($result==false && $result!="hide functionality")
            <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="290" action="reject" href="javascript:void(0)">Reject</a>
            @endif
        @endif
    @endif
    
    @if($comment)
    <div class="my-3 alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $comment->created_role ?? '' }}: </strong> {{ $comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if($reject_comment)
    <div class="my-3 alert alert-primary alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $reject_comment->created_role ?? '' }}: </strong> {{ $reject_comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
  </div>
  </div></div>
@else
<?php $missing_document_file = missing_document_file($surveyformid, 290); ?>
    @if($missing_document_file)
    <div class="col-md-6">
    <div class="card pb-3 mb-3">
    <img src="{{ asset('uploads/surveyform_files') }}/{{ $missing_document_file->filename }}" alt="{{ getquestionlabel(290) }}" class='myImg rotating-image'  />
    <button class="button_rotate">Rotate</button>
    <div class="card-body">
    <p class="card-text 290">{{ getquestionlabel(290) }}</p>
    </div>
    </div></div>
    @endif
@endif
@if($b)
<div class="col-md-6">
<div class="card pb-3 mb-3">
  <img src="{{ $b }}" alt="{{ getquestionlabel(291) }}" class='myImg rotating-image' />
  <button class="button_rotate">Rotate</button>
  <div class="card-body">
    <p class="card-text 291">{{ getquestionlabel(291) }}</p>
    {!! $location_b !!}
    @php
    $result= hideReject($surveyformid,291);
    $comment = is_monitoring_evaluation($surveyformid, 291);
    $reject_comment = is_question_reject($surveyformid, 291);
    @endphp
    
    @if($show_button_mne==true)
        @if($comment)
        <a class="btn btn-sm btn-success comment_revert" style="height:30px;" comment_id="{{ $comment->id }}" ques_id="291"  href="javascript:void(0)">Remove Comment</a>
        @else
        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="291" action="comment" href="javascript:void(0)">Comment</a>
        @endif
    @else
        @if(check_surveyform_status($surveyformid, 'R') != 1 && check_surveyform_status($surveyformid, 'A') != 1)
            @if($result && $result!=="hide functionality")
            <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="291" action="revert" href="javascript:void(0)">Revert</a>
             @elseif($result==false && $result!="hide functionality")
            <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="291" action="reject" href="javascript:void(0)">Reject</a>
            @endif
        @endif
    @endif
    
    @if($comment)
    <div class="my-3 alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $comment->created_role ?? '' }}: </strong> {{ $comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if($reject_comment)
    <div class="my-3 alert alert-primary alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $reject_comment->created_role ?? '' }}: </strong> {{ $reject_comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
  </div>
  </div></div>
@else
<?php $missing_document_file = missing_document_file($surveyformid, 291); ?>
    @if($missing_document_file)
    <div class="col-md-6">
    <div class="card pb-3 mb-3">
    <img src="{{ asset('uploads/surveyform_files') }}/{{ $missing_document_file->filename }}" alt="{{ getquestionlabel(291) }}" class='myImg rotating-image'  />
    <button class="button_rotate">Rotate</button>
    <div class="card-body">
    <p class="card-text 291">{{ getquestionlabel(291) }}</p>
    </div>
    </div></div>
    @endif
@endif
</div>
@endif



@if($sectionid == 86)
@php 
$a = get_question_image($surveyformid, 285);
$location_a=get_question_location($surveyformid, 285);

$location_b=get_question_location($surveyformid, 2305);
$location_bb=get_question_location($surveyformid, 2537);

$bd_check = get_answer(646,$surveyformid);
if($bd_check->answer=='Yes'){
$b=false;
$bb=false;
$d=false;
}else{
$b = get_question_image($surveyformid, 2305);
$bb = get_question_image($surveyformid, 2537);
$d = get_question_image($surveyformid, 2306);
}



$location_c=get_question_location($surveyformid, 289);

$location_d=get_question_location($surveyformid, 2306);
$e = get_question_image($surveyformid, 2076);
$location_e=get_question_location($surveyformid, 2076);
$f = get_question_image($surveyformid, 2499);
$location_f=get_question_location($surveyformid, 2499);
$g = get_question_image($surveyformid, 2536);
$location_g=get_question_location($surveyformid, 2536);





$hc_check = get_answer(642, $surveyformid);
if($hc_check->answer=='Yes'){
$h=false;
$c=false;
}else{
$h = get_question_image($surveyformid, 288);
$c = get_question_image($surveyformid, 289);
}







$location_h=get_question_location($surveyformid, 288);


@endphp
<div class="row">
@if($a)
<div class="col-md-6">
<div class="card pb-3 mb-3">
  <img src="{{ $a }}" alt="{{ getquestionlabel(285) }}" class='myImg rotating-image' />
  <button class="button_rotate">Rotate</button>
  <div class="card-body">
    <p class="card-text 285">{{ getquestionlabel(285) }} </p>
    {!! $location_a !!}
    @php
    $result= hideReject($surveyformid,285);
    $comment = is_monitoring_evaluation($surveyformid, 285);
    $reject_comment = is_question_reject($surveyformid, 285);
    @endphp
    
    @if($show_button_mne==true)
        @if($comment)
        <a class="btn btn-sm btn-success comment_revert" style="height:30px;" comment_id="{{ $comment->id }}" ques_id="285"  href="javascript:void(0)">Remove Comment</a>
        @else
        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="285" action="comment" href="javascript:void(0)">Comment</a>
        @endif
    @else
       @if(check_surveyform_status($surveyformid, 'R') != 1 && check_surveyform_status($surveyformid, 'A') != 1)
            @if($result && $result!=="hide functionality")
            <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="285" action="revert" href="javascript:void(0)">Revert</a>
             @elseif($result==false && $result!="hide functionality")
            <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="285" action="reject" href="javascript:void(0)">Reject</a>
            @endif
        @endif
     @endif
     
     
     @if($comment)
    <div class="my-3 alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $comment->created_role ?? '' }}: </strong> {{ $comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if($reject_comment)
    <div class="my-3 alert alert-primary alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $reject_comment->created_role ?? '' }}: </strong> {{ $reject_comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
  </div>
  </div></div>
@endif
@if($b)
<div class="col-md-6">
<div class="card pb-3 mb-3">
  <img src="{{ $b }}" alt="{{ getquestionlabel(2305) }}"  class='myImg rotating-image' />
  <button class="button_rotate">Rotate</button>
  <div class="card-body">
    <p class="card-text 2305">{{ getquestionlabel(2305) }}</p>
    {!! $location_b !!}
    
     @php
    $result= hideReject($surveyformid,2305);
    $comment = is_monitoring_evaluation($surveyformid, 2305);
    $reject_comment = is_question_reject($surveyformid, 2305);
    @endphp
    @if($show_button_mne==true)
        @if($comment)
        <a class="btn btn-sm btn-success comment_revert" style="height:30px;" comment_id="{{ $comment->id }}" ques_id="2305"  href="javascript:void(0)">Remove Comment</a>
        @else
        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2305" action="comment" href="javascript:void(0)">Comment</a>
        @endif
    @else
        @if(check_surveyform_status($surveyformid, 'R') != 1 && check_surveyform_status($surveyformid, 'A') != 1)
            @if($result && $result!=="hide functionality")
            <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2305" action="revert" href="javascript:void(0)">Revert</a>
             @elseif($result==false && $result!="hide functionality")
            <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2305" action="reject" href="javascript:void(0)">Reject</a>
            @endif
        @endif
    @endif
    
    @if($comment)
    <div class="my-3 alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $comment->created_role ?? '' }}: </strong> {{ $comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if($reject_comment)
    <div class="my-3 alert alert-primary alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $reject_comment->created_role ?? '' }}: </strong> {{ $reject_comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
  </div>
  </div></div>
@endif
@if($bb)
<div class="col-md-6">
<div class="card pb-3 mb-3">
  <img src="{{ $bb }}" alt="{{ getquestionlabel(2537) }}"  class='myImg rotating-image' />
  <button class="button_rotate">Rotate</button>
  <div class="card-body">
    <p class="card-text 2537">{{ getquestionlabel(2537) }}</p>
    {!! $location_bb !!}
    
     @php
    $result= hideReject($surveyformid,2537);
    $comment = is_monitoring_evaluation($surveyformid, 2537);
    $reject_comment = is_question_reject($surveyformid, 2537);
    @endphp
    @if($show_button_mne==true)
        @if($comment)
        <a class="btn btn-sm btn-success comment_revert" style="height:30px;" comment_id="{{ $comment->id }}" ques_id="2537"  href="javascript:void(0)">Remove Comment</a>
        @else
        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2537" action="comment" href="javascript:void(0)">Comment</a>
        @endif
    @else
        @if(check_surveyform_status($surveyformid, 'R') != 1 && check_surveyform_status($surveyformid, 'A') != 1)
            @if($result && $result!=="hide functionality")
            <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2537" action="revert" href="javascript:void(0)">Revert</a>
             @elseif($result==false && $result!="hide functionality")
            <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2537" action="reject" href="javascript:void(0)">Reject</a>
            @endif
        @endif
    @endif
    
    @if($comment)
    <div class="my-3 alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $comment->created_role ?? '' }}: </strong> {{ $comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if($reject_comment)
    <div class="my-3 alert alert-primary alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $reject_comment->created_role ?? '' }}: </strong> {{ $reject_comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
  </div>
  </div></div>
@endif




@if($h)
<div class="col-md-6">
<div class="card pb-3 mb-3">
  <img src="{{ $h }}" alt="{{ getquestionlabel(288) }}" class='myImg rotating-image' />
  <button class="button_rotate">Rotate</button>
  <div class="card-body">
    <p class="card-text 288">{{ getquestionlabel(288) }}</p>
     {!! $location_h !!}
    
     @php
    $result= hideReject($surveyformid,288);
    $comment = is_monitoring_evaluation($surveyformid, 288);
    $reject_comment = is_question_reject($surveyformid, 288);
    @endphp
    @if($show_button_mne==true)
        @if($comment)
        <a class="btn btn-sm btn-success comment_revert" style="height:30px;" comment_id="{{ $comment->id }}" ques_id="288"  href="javascript:void(0)">Remove Comment</a>
        @else
        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="288" action="comment" href="javascript:void(0)">Comment</a>
        @endif
    @else
        @if(check_surveyform_status($surveyformid, 'R') != 1 && check_surveyform_status($surveyformid, 'A') != 1)
            @if($result && $result!=="hide functionality")
            <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="288" action="revert" href="javascript:void(0)">Revert</a>
             @elseif($result==false && $result!="hide functionality")
            <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="288" action="reject" href="javascript:void(0)">Reject</a>
            @endif
        @endif
    @endif
    
    @if($comment)
    <div class="my-3 alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $comment->created_role ?? '' }}: </strong> {{ $comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if($reject_comment)
    <div class="my-3 alert alert-primary alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $reject_comment->created_role ?? '' }}: </strong> {{ $reject_comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
  </div>
  </div></div>
@endif
@if($c)
<div class="col-md-6">
<div class="card pb-3 mb-3">
  <img src="{{ $c }}" alt="{{ getquestionlabel(289) }}" class='myImg rotating-image' />
  <button class="button_rotate">Rotate</button>
  <div class="card-body">
    <p class="card-text 289">{{ getquestionlabel(289) }}</p>
    {!! $location_c !!}
    
     @php
    $result= hideReject($surveyformid,289);
    $comment = is_monitoring_evaluation($surveyformid, 289);
    $reject_comment = is_question_reject($surveyformid, 289);
    @endphp
    @if($show_button_mne==true)
        @if($comment)
        <a class="btn btn-sm btn-success comment_revert" style="height:30px;" comment_id="{{ $comment->id }}" ques_id="289"  href="javascript:void(0)">Remove Comment</a>
        @else
        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="289" action="comment" href="javascript:void(0)">Comment</a>
        @endif
    @else
        @if(check_surveyform_status($surveyformid, 'R') != 1 && check_surveyform_status($surveyformid, 'A') != 1)
            @if($result && $result!=="hide functionality")
            <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="289" action="revert" href="javascript:void(0)">Revert</a>
             @elseif($result==false && $result!="hide functionality")
            <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="289" action="reject" href="javascript:void(0)">Reject</a>
            @endif
        @endif
    @endif
    
    @if($comment)
    <div class="my-3 alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $comment->created_role ?? '' }}: </strong> {{ $comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if($reject_comment)
    <div class="my-3 alert alert-primary alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $reject_comment->created_role ?? '' }}: </strong> {{ $reject_comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
  </div>
  </div></div>
@endif
@if($d)
<div class="col-md-6">
<div class="card pb-3 mb-3">
  <img src="{{ $d }}" alt="{{ getquestionlabel(2306) }}"  class='myImg rotating-image' />
  <button class="button_rotate">Rotate</button>
  <div class="card-body">
    <p class="card-text 2306">{{ getquestionlabel(2306) }}</p>
     {!! $location_d !!}
    
    @php
    $result= hideReject($surveyformid,2306);
    $comment = is_monitoring_evaluation($surveyformid, 2306);
    $reject_comment = is_question_reject($surveyformid, 2306);
    @endphp
    
    @if($show_button_mne==true)
        @if($comment)
        <a class="btn btn-sm btn-success comment_revert" style="height:30px;" comment_id="{{ $comment->id }}" ques_id="2306"  href="javascript:void(0)">Remove Comment</a>
        @else
        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2306" action="comment" href="javascript:void(0)">Comment</a>
        @endif
    @else
        @if(check_surveyform_status($surveyformid, 'R') != 1 && check_surveyform_status($surveyformid, 'A') != 1)
            @if($result && $result!=="hide functionality")
            <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2306" action="revert" href="javascript:void(0)">Revert</a>
             @elseif($result==false && $result!="hide functionality")
            <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2306" action="reject" href="javascript:void(0)">Reject</a>
            @endif
        @endif
    @endif
    
    @if($comment)
    <div class="my-3 alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $comment->created_role ?? '' }}: </strong> {{ $comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if($reject_comment)
    <div class="my-3 alert alert-primary alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $reject_comment->created_role ?? '' }}: </strong> {{ $reject_comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
  </div>
  </div></div>
@endif
@if($e)
<div class="col-md-6">
<div class="card pb-3 mb-3">
  <img src="{{ $e }}" alt="{{ getquestionlabel(2076) }}"  class='myImg rotating-image'  />
   <button class="button_rotate">Rotate</button>
  <div class="card-body">
    <p class="card-text 2076">{{ getquestionlabel(2076) }}</p>
    {!! $location_e !!}
    
    @php
    $result= hideReject($surveyformid,2076);
    $comment = is_monitoring_evaluation($surveyformid, 2076);
    $reject_comment = is_question_reject($surveyformid, 2076);
    @endphp
    
    @if($show_button_mne==true)
        @if($comment)
        <a class="btn btn-sm btn-success comment_revert" style="height:30px;" comment_id="{{ $comment->id }}" ques_id="2076"  href="javascript:void(0)">Remove Comment</a>
        @else
        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2076" action="comment" href="javascript:void(0)">Comment</a>
        @endif
    @else
        @if(check_surveyform_status($surveyformid, 'R') != 1 && check_surveyform_status($surveyformid, 'A') != 1)
            @if($result && $result!=="hide functionality")
            <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2076" action="revert" href="javascript:void(0)">Revert</a>
             @elseif($result==false && $result!="hide functionality")
            <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2076" action="reject" href="javascript:void(0)">Reject</a>
            @endif
        @endif
    @endif
    
    
    @if($comment)
    <div class="my-3 alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $comment->created_role ?? '' }}: </strong> {{ $comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if($reject_comment)
    <div class="my-3 alert alert-primary alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $reject_comment->created_role ?? '' }}: </strong> {{ $reject_comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
  </div>
  </div></div>
@endif
@if($f)
<div class="col-md-6">
<div class="card pb-3 mb-3">
  <img src="{{ $f }}" alt="{{ getquestionlabel(2499) }}" class='myImg rotating-image'/>
     <button class="button_rotate">Rotate</button>
  <div class="card-body">
    <p class="card-text 2499">{{ getquestionlabel(2499) }}</p>
     {!! $location_f !!}
     
    @php
    $result= hideReject($surveyformid,2499);
    $comment = is_monitoring_evaluation($surveyformid, 2499);
    $reject_comment = is_question_reject($surveyformid, 2499);
    @endphp
    @if($show_button_mne==true)
        @if($comment)
        <a class="btn btn-sm btn-success comment_revert" style="height:30px;" comment_id="{{ $comment->id }}" ques_id="2499"  href="javascript:void(0)">Remove Comment</a>
        @else
        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2499" action="comment" href="javascript:void(0)">Comment</a>
        @endif
    @else
        @if(check_surveyform_status($surveyformid, 'R') != 1 && check_surveyform_status($surveyformid, 'A') != 1)
            @if($result && $result!=="hide functionality")
            <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2499" action="revert" href="javascript:void(0)">Revert</a>
             @elseif($result==false && $result!="hide functionality")
            <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2499" action="reject" href="javascript:void(0)">Reject</a>
            @endif
        @endif
    @endif

@if($comment)
    <div class="my-3 alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $comment->created_role ?? '' }}: </strong> {{ $comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if($reject_comment)
    <div class="my-3 alert alert-primary alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $reject_comment->created_role ?? '' }}: </strong> {{ $reject_comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
  </div>
  </div></div>
@endif

@if($g)
<div class="col-md-6">
<div class="card pb-3 mb-3">
  <img src="{{ $g }}" alt="{{ getquestionlabel(2536) }}"  class='myImg rotating-image' />
     <button class="button_rotate">Rotate</button>
  <div class="card-body">
    <p class="card-text 2536">{{ getquestionlabel(2536) }}</p>
     {!! $location_g !!}
     
    @php
    $result= hideReject($surveyformid,2536);
    $comment = is_monitoring_evaluation($surveyformid, 2536);
    $reject_comment = is_question_reject($surveyformid, 2536);
    @endphp
    
    @if($show_button_mne==true)
        @if($comment)
        <a class="btn btn-sm btn-success comment_revert" style="height:30px;" comment_id="{{ $comment->id }}" ques_id="2536"  href="javascript:void(0)">Remove Comment</a>
        @else
        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2536" action="comment" href="javascript:void(0)">Comment</a>
        @endif
    @else
        @if(check_surveyform_status($surveyformid, 'R') != 1 && check_surveyform_status($surveyformid, 'A') != 1)
            @if($result && $result!=="hide functionality")
            <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2536" action="revert" href="javascript:void(0)">Revert</a>
             @elseif($result==false && $result!="hide functionality")
            <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2536" action="reject" href="javascript:void(0)">Reject</a>
            @endif
        @endif
    @endif
    
    
    @if($comment)
    <div class="my-3 alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $comment->created_role ?? '' }}: </strong> {{ $comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if($reject_comment)
    <div class="my-3 alert alert-primary alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $reject_comment->created_role ?? '' }}: </strong> {{ $reject_comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

  </div>
  </div></div>
@endif
</div>
@endif




@if($sectionid == 97)
@php 
$a_check = get_answer(756,$surveyformid);
if($a_check->answer=='No'){
$a=false;
$b=false;
}else{

$a = get_question_image($surveyformid, 293);
$b = get_question_image($surveyformid, 294);

}
$location_a=get_question_location($surveyformid, 293);

$location_b=get_question_location($surveyformid, 294);
@endphp
<div class="row">
@if($a)
<div class="col-md-6">
<div class="card pb-3 mb-3">
  <img src="{{ $a }}" alt="{{ getquestionlabel(293) }}" class='myImg rotating-image' /> 
  <button class="button_rotate">Rotate</button>
  <div class="card-body">
    <p class="card-text 293">{{ getquestionlabel(293) }}</p>
    {!! $location_a !!}
   
    
    @php
    $result= hideReject($surveyformid,293);
    $comment = is_monitoring_evaluation($surveyformid, 293);
    $reject_comment = is_question_reject($surveyformid, 293);
    @endphp
    @if($show_button_mne==true)
        @if($comment)
        <a class="btn btn-sm btn-success comment_revert" style="height:30px;" comment_id="{{ $comment->id }}" ques_id="293"  href="javascript:void(0)">Remove Comment</a>
        @else
        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="293" action="comment" href="javascript:void(0)">Comment</a>
        @endif
    @else
        @if(check_surveyform_status($surveyformid, 'R') != 1 && check_surveyform_status($surveyformid, 'A') != 1)
            @if($result && $result!=="hide functionality")
            <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="293" action="revert" href="javascript:void(0)">Revert</a>
             @elseif($result==false && $result!="hide functionality")
            <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="293" action="reject" href="javascript:void(0)">Reject</a>
            @endif
        @endif
    @endif
    
    @if($comment)
    <div class="my-3 alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $comment->created_role ?? '' }}: </strong> {{ $comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if($reject_comment)
    <div class="my-3 alert alert-primary alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $reject_comment->created_role ?? '' }}: </strong> {{ $reject_comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
  </div>
  </div></div>
@endif
@if($b)
<div class="col-md-6">
<div class="card pb-3 mb-3">
  <img src="{{ $b }}" alt="{{ getquestionlabel(294) }}" class='myImg rotating-image' />
   <button class="button_rotate">Rotate</button>
  <div class="card-body">
    <p class="card-text 294">{{ getquestionlabel(294) }}</p>
    {!! $location_b !!}
    
    @php
    $result= hideReject($surveyformid,294);
    $comment = is_monitoring_evaluation($surveyformid, 294);
    $reject_comment = is_question_reject($surveyformid, 294);
    @endphp
    
    @if($show_button_mne==true)
        @if($comment)
        <a class="btn btn-sm btn-success comment_revert" style="height:30px;" comment_id="{{ $comment->id }}" ques_id="294"  href="javascript:void(0)">Remove Comment</a>
        @else
        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="294" action="comment" href="javascript:void(0)">Comment</a>
        @endif
    @else
        @if(check_surveyform_status($surveyformid, 'R') != 1 && check_surveyform_status($surveyformid, 'A') != 1)
            @if($result && $result!=="hide functionality")
            <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="294" action="revert" href="javascript:void(0)">Revert</a>
             @elseif($result==false && $result!="hide functionality")
            <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="294" action="reject" href="javascript:void(0)">Reject</a>
            @endif
        @endif
    @endif
    
    
    @if($comment)
    <div class="my-3 alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $comment->created_role ?? '' }}: </strong> {{ $comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if($reject_comment)
    <div class="my-3 alert alert-primary alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $reject_comment->created_role ?? '' }}: </strong> {{ $reject_comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
  </div>
  </div></div>
@endif
</div>
@endif




@if($sectionid == 102)
@php 
$a = get_question_image($surveyformid, 2074);
$location_a=get_question_location($surveyformid, 2074);

@endphp
<div class="row">
@if($a)
<div class="col-md-6">
<div class="card pb-3 mb-3">
  <img src="{{ $a }}" alt="{{ getquestionlabel(2074) }}" class='myImg' />
  <div class="card-body">
    <p class="card-text 2074">{{ getquestionlabel(2074) }}</p>
    {!! $location_a !!}
    
    @php
    $result= hideReject($surveyformid,2074);
    $comment = is_monitoring_evaluation($surveyformid, 2074);
    $reject_comment = is_question_reject($surveyformid, 2074);
    @endphp
    @if($show_button_mne==true)
        @if($comment)
        <a class="btn btn-sm btn-success comment_revert" style="height:30px;" comment_id="{{ $comment->id }}" ques_id="2074"  href="javascript:void(0)">Remove Comment</a>
        @else
        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2074" action="comment" href="javascript:void(0)">Comment</a>
        @endif
    @else
        @if(check_surveyform_status($surveyformid, 'R') != 1 && check_surveyform_status($surveyformid, 'A') != 1)
            @if($result && $result!=="hide functionality")
            <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2074" action="revert" href="javascript:void(0)">Revert</a>
             @elseif($result==false && $result!="hide functionality")
            <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="2074" action="reject" href="javascript:void(0)">Reject</a>
            @endif
        @endif
    @endif
    
    
    @if($comment)
    <div class="my-3 alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $comment->created_role ?? '' }}: </strong> {{ $comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if($reject_comment)
    <div class="my-3 alert alert-primary alert-dismissible fade show" role="alert">
    <strong>Comment By {{ $reject_comment->created_role ?? '' }}: </strong> {{ $reject_comment->comment ?? '' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
  </div>
  </div></div>
@endif
</div>
@endif