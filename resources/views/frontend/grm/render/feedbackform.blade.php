<?php $allow_attachment = $complaint->complain_followup_remarks()->latest()->first()->allow_attachment; ?>
{!! Form::open(['route' => 'feedbackform', "method" => "post", "files" => "true", "class" => "mb-3", "id" => "feedbackform"]) !!}							
{!! Form::hidden('complaint_id', $complaint->id) !!}
{!! Form::hidden('status', $complaint->status) !!}






{{--
<div class="d-flex">
<label class="thumbs d-flex justify-content-center align-items-center"><input type="radio" name="reaction" value="LIKE" class="" required /><i class="fa fa-thumbs-up"></i></label>
<label class="thumbs d-flex justify-content-center align-items-center"><input type="radio" name="reaction" value="UNLIKE" class="" required /><i class="fa fa-thumbs-down"></i></label>
</div>
--}}




@if($allow_attachment)
<label class="mb-2">Remarks: </label>
<p>{{ $complaint->complain_followup_remarks()->latest()->first()->remark }}</p>



@for($i=0; $i < $allow_attachment; $i++)
<br />
<label>Attachment {{ $i+1 }}:</label>
{!! Form::file('attachment[]', array('class' => 'form-control', 'required' => 'true')) !!}
@endfor
@else
<label class="mb-4">FeedBack Reaction: </label><br>
<div class="reaction">
<label>
  <input type="radio" name="reaction" value="LIKE" required />
  <div class="box"><i class="fa fa-thumbs-up"></i></div>
</label>

  <label>
  <input type="radio" name="reaction" value="UNLIKE" required />
  <div class="box"><i class="fa fa-thumbs-down"></i></div>
</label>
</div>
<br><br>
<label>Last Feedback:</label>
{!! Form::text('feedback', $remarks->remark, array('placeholder' => 'Last Closing Statement','class' => 'form-control','disabled'=>'disabled')) !!}
<label>FeedBack Comments:</label>
{!! Form::textarea('feedback', null, array('placeholder' => 'Type your message...','class' => 'form-control', 'rows'=>'3', 'required')) !!}
@endif



<br><br>
{!! Form::submit("Submit Feedback", ["class"=>"feedback-form-btn"]) !!}
{!! Form::close() !!}