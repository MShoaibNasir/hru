<div id="uploadform_loader"></div>
<div id="uploadform_message">
<div class="alert alert-danger print-error-msg" style="display:none">
<strong>Whoops!</strong> There were some problems with your input.<br><br>
<ul></ul>
</div>
</div>


<div id="uploadform">
<div class="col-md-12">
{!! Form::open(array('route' => 'formstatusstore','method'=>'POST', 'class'=>'formstatusstore')) !!}
{!! Form::hidden('form_id', $survey_id) !!}


<div class="row mb-2">
	<div class="col-md-4">role</div>	
	<div class="col-md-8">{!! Form::select('update_by', ['validator'=>'Validator', 'field supervisor'=>'Field Supervisor', 'IP'=>'IP', 'HRU'=>'HRU', 'PSIA'=>'PSIA', 'HRU_MAIN'=>'HRU_MAIN', 'COO'=>'COO', 'CEO'=>'CEO', 'Finance'=>'Finance'], null, ['placeholder' => 'Select Department', 'class' => 'role form-control', 'id'=>'role']) !!}</div>	 
</div>

<div class="row mb-2">
	<div class="col-md-4">user_id</div>	
	<div class="col-md-8">{!! Form::text('user_id', null, array('placeholder' => 'user_id','class' => 'form-control')) !!}</div>	 
</div>

<div class="row mb-2">
	<div class="col-md-4">form_status</div>	
	<div class="col-md-8">{!! Form::select('form_status', ['P'=>'Pending', 'R'=>'Reject','A'=>'Approved', 'H'=>'Hold'], null, ['placeholder' => 'Select Form Status', 'class' => 'form-control', 'id'=>'form_status']) !!}</div>	 
</div>

<div class="row margin-bottom-15">
<label class="col-md-4">Comment:</label>
<div class="col-md-8">{!! Form::textarea('comment', null, ['placeholder' => 'Comment', 'class' => 'form-control']) !!}</div>
</div>



<div class="row mb-2">
<div class="col-md-12">{!! Form::submit("Submit", ["class"=>"btn btn-primary"]) !!}</div>
</div>
	

{!! Form::close() !!}
</div>
</div>


