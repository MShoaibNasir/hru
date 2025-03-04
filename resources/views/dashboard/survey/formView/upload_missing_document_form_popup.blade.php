<div id="uploadform_loader"></div>
<div id="uploadform_message">
<div class="alert alert-danger print-error-msg" style="display:none">
<strong>Whoops!</strong> There were some problems with your input.<br><br>
<ul></ul>
</div>
</div>

<div id="uploadform">
<div class="col-md-12">
{!! Form::open(['route' => 'upload_missing_document_form_submit', "method" => "POST", 'files'=>'true', "class" => "margin-bottom-5", "id" => "upload_missing_document_form_submit"]) !!}	
{!! Form::hidden('comment_id', $comment_id) !!}
{!! Form::hidden('survey_id', $survey_id) !!}
{!! Form::hidden('decision', $decision) !!}

<div class="row mb-3">
<label class="col-md-4">Evidence Type</label>
<div class="col-md-8">{!! Form::select('evidence_type', $evidence_type, null, ['placeholder' => 'Evidence Type', 'class' => 'form-control select2', 'required']) !!}</div>
</div>

<div class="row mb-3">
<label class="col-md-4">Land Ownership Evidence 1</label>
<div class="col-md-8">{!! Form::file('question_290', null, array('class' => 'form-control', 'required')) !!}</div>
</div>

<div class="row mb-3">
<label class="col-md-4">Land Ownership Evidence 2</label>
<div class="col-md-8">{!! Form::file('question_291', null, array('class' => 'form-control', 'required')) !!}</div>
</div>


<div class="row mb-3">
<div class="col-md-12">{!! Form::submit("Submit", ["class"=>"btn btn-primary"]) !!}</div>
</div>
	

{!! Form::close() !!}
</div>
</div>