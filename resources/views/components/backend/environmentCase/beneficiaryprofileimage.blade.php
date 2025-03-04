
    @php
    $result= hideReject($surveyformid,285);
    $comment = is_monitoring_evaluation($surveyformid, 285);
    $reject_comment = is_question_reject($surveyformid, 285);
    $show_button_mne = monitoring_evaluation();
    $certification_status=certification($surveyformid,'HRU');
    
    $socio_legal_status = get_beneficiary_question_ans($surveyformid, 246);
    $evidence_type = get_beneficiary_question_ans($surveyformid, 247);
    @endphp
    

 
    
<div class="profile-header text-center mt-2 position-relative">
@if($beneficiaryProfileImage)
<img src="{{ asset('uploads/surveyform_files/'.$beneficiaryProfileImage) }}" alt="Reference No: {{ $ref_no ?? '' }}" class="profile-img myImg" />
@else
<img src="https://placehold.co/300x300?text=No Beneficiary Profile Image" alt="Reference No: {{ $ref_no ?? '' }}" class="profile-img myImg" />
@endif
    
    @if(Auth::user()->role!=30 || Auth::user()->role!=34 || Auth::user()->role!=35 ||  Auth::user()->role!=36 || Auth::user()->role!=37)
    @if(isset($certification_status) && $certification_status->certification==1)
    <img src='https://mis.hru.org.pk/images/certification.jpg'  style="position:absolute; right:0px; top:0px; width:150px; height:150px; border:3px solid green;" />
    @endif
    @endif
    
    
    
    
                 @if($socio_legal_status)
                 @if($socio_legal_status !== 'Un-disputed')
                  <img src="https://mis.hru.org.pk/images/cocio-legal-status.jpeg" style="width:50px; height:50px; border:3px solid green;" alt="{{ $socio_legal_status ?? '' }}" />
                 @endif
                 @endif
                 
                 @if($evidence_type)
                 @if($evidence_type == 'No Evidence Available')
                  <img src="https://mis.hru.org.pk/images/no-evidence.jpg" style="width:50px; height:50px; border:3px solid green;" alt="{{ $evidence_type ?? '' }}" />
                 @endif
                 @endif
   
    
    
    
    
<h1>Beneficiary Profile</h1>

   
   @if($show_button_mne==true)
        @if($comment)
        <a class="btn btn-sm btn-success comment_revert" style="height:30px;" comment_id="{{ $comment->id }}" ques_id="285"  href="javascript:void(0)">Remove Comment</a>
        @else
        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="285" action="comment" href="javascript:void(0)">Comment</a>
        @endif
    @else
    
        @if($result && $result!=="hide functionality")
        <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="285" action="revert" href="javascript:void(0)">Revert</a>
        @elseif($result==false && $result!="hide functionality")
        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="285" action="reject" href="javascript:void(0)">Reject</a>
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
