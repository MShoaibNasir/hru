<div class="compare_data my-3">
    <h5>Reference No: {{ $ref_no ?? '' }}</h5>
    
    @if($beneficiary_details_data)
    <h3>Comparison Data</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th scope="col">Comparison Field</th>
                <th scope="col">PDNA INFO</th>
                <th scope="col">HRU INFO</th>
            </tr>
        </thead>
        <tbody>
            @php
            $i = 0;
            @endphp
            
            @foreach($beneficiary_details_data as $key => $item)
            <tr>
                <th scope="row">{{ $nameOfField[$i] }}</th>
                @php
                    $beneficiaryValue = $beneficiary_details_data[$key] ?? 'not available';
                    $hruValue = $hru_data[$key] ?? 'not available';
                    $edit_question_ids=[645,650,652,654,664,1003,1004,1005,2000];
                    $beneficiaryValueFilter = ucwords(strtolower($beneficiaryValue));
                    $hruValueFilter = ucwords(strtolower($hruValue));
                    $color = (trim($beneficiaryValueFilter) == trim($hruValueFilter)) ? 'green' : 'red';
                    
                @endphp

                <td style="color: {{ $color }};">
                    {{ $beneficiaryValueFilter }}   
                </td>
                <td style="color: {{ $color }};">
                    
                <input type='hidden' value='{{$surveyformid}}' id='surveyformid_for_edit'>
                <input type='hidden' value='{{$edit_question_ids[$i]}}' id='questionid_for_edit'>
                <input type='hidden' value='{{ $hruValueFilter }}' id='answer_for_edit'>  
                @if(Auth::check() && Auth::user()->role == 38)
                <i style='cursor:pointer; color:#083e42; margin-left:10px;' data-bs-toggle="modal" data-bs-target="#exampleModal" class="fas fa-edit modal_item_comparision"></i>
                @endif    
                    {{ $hruValueFilter }}
                </td>
            </tr>
            @php
            $i++;
            @endphp
            @endforeach
        </tbody>
    </table>
    @endif
</div>

<div id="section" class="data-view my-3">
    <div class="accordion-header" onclick="toggleAccordion(this)">CNIC Images</div>
    <div class="accordion-content">
        <div class="row">
@if($cnicfront)

     @php
     $result= hideReject($surveyformid,286);
     $front_side_location=get_question_location($surveyformid,286);
     $show_button_mne = monitoring_evaluation();
     $comment = is_monitoring_evaluation($surveyformid, 286);
     $reject_comment = is_question_reject($surveyformid, 286);
    @endphp



<div class="col-md-6">
<div class="card pb-3 mb-3">
  <img src="{{ $cnicfront }}" alt="CNIC Front Image" class='myImg rotating-image' />
   <button class="button_rotate">Rotate</button>
  <div class="card-body">
    <p class="card-text 286">CNIC Front Image</p>
    {!! $front_side_location !!}
    
    @if($show_button_mne==true)
        @if($comment)
        <a class="btn btn-sm btn-success comment_revert" style="height:30px;" comment_id="{{ $comment->id }}" ques_id="286"  href="javascript:void(0)">Remove Comment</a>
        @else
        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="286" action="comment" href="javascript:void(0)">Comment</a>
        @endif
        
    @else
        @if($result && $result!=="hide functionality")
        <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="286" action="revert" href="javascript:void(0)">Revert</a>
        @elseif($result==false && $result!="hide functionality")
        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="286" action="reject" href="javascript:void(0)">Reject</a>
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
@if($cnicback)
    @php
    $result= hideReject($surveyformid,287);
    $show_button_mne = monitoring_evaluation();
    $back_side_location=get_question_location($surveyformid,287);
    $comment = is_monitoring_evaluation($surveyformid, 287);
    $reject_comment = is_question_reject($surveyformid, 287);
    @endphp


<div class="col-md-6">
<div class="card pb-3 mb-3">
  <img src="{{ $cnicback }}" alt="CNIC Back Image" class='myImg rotating-image' />
    <button class="button_rotate">Rotate</button>
  <div class="card-body">
    <p class="card-text 287">CNIC Back Image</p>
    {!! $back_side_location !!}
      
    
    @if($show_button_mne==true)
        @if($comment)
        <a class="btn btn-sm btn-success comment_revert" style="height:30px;" comment_id="{{ $comment->id }}" ques_id="287"  href="javascript:void(0)">Remove Comment</a>
        @else
        <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="287" action="comment" href="javascript:void(0)">Comment</a>
        @endif
    @else
    @if($result && $result!=="hide functionality")
    <a class="btn btn-sm btn-success rejection_revert" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="287" action="revert" href="javascript:void(0)">Revert</a>
    @elseif($result==false && $result!="hide functionality")
    <a class="btn btn-sm btn-danger surveyquestion_rejectforms_btn" style="height:30px;" survey_id="{{ $surveyformid }}" ques_id="287" action="reject" href="javascript:void(0)">Reject</a>
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




    </div>
</div>