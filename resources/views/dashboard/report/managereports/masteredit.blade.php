<div id="uploadform_loader"></div>
<div id="uploadform_message">
<div class="alert alert-danger print-error-msg" style="display:none">
<strong>Whoops!</strong> There were some problems with your input.<br><br>
<ul></ul>
</div>
</div>

<div id="uploadform">
<div class="col-md-12">
{!! Form::model($data, ['method' => 'PATCH', 'route' => ['masterreportupdate', $data->id], 'class'=>'masterreportupdate']) !!}
{!! Form::hidden('report_id', $data->id) !!}



<div class="row mb-2">
	<div class="col-md-4">role</div>	
	<div class="col-md-8">{!! Form::select('role', ['validator'=>'Validator', 'field supervisor'=>'Field Supervisor', 'IP'=>'IP', 'HRU'=>'HRU', 'PSIA'=>'PSIA', 'HRU_MAIN'=>'HRU_MAIN', 'COO'=>'COO', 'CEO'=>'CEO', 'Finance'=>'Finance'], null, ['placeholder' => 'Select Department', 'class' => 'role form-control', 'id'=>'role']) !!}</div>	 
</div>

<div class="row mb-2">
	<div class="col-md-4">user_id</div>	
	<div class="col-md-8">{!! Form::text('user_id', null, array('placeholder' => 'user_id','class' => 'form-control')) !!}</div>	 
</div>


<div class="row mb-2">
	<div class="col-md-4">last_status</div>	
	<div class="col-md-8">{!! Form::select('last_status', ['P'=>'Pending', 'R'=>'Reject','A'=>'Approved', 'H'=>'Hold'], null, ['placeholder' => 'Select Last Status', 'class' => 'form-control', 'id'=>'last_status']) !!}</div>	 
</div>

<div class="row mb-2">
	<div class="col-md-4">new_status</div>	
	<div class="col-md-8">{!! Form::select('new_status', ['P'=>'Pending', 'R'=>'Reject','A'=>'Approved', 'H'=>'Hold'], null, ['placeholder' => 'Select New Status', 'class' => 'form-control', 'id'=>'new_status']) !!}</div>	 
</div>

<div class="row mb-2">
	<div class="col-md-4">last_action_user_id</div>	
	<div class="col-md-8">{!! Form::text('last_action_user_id', null, array('placeholder' => 'last_action_user_id','class' => 'form-control')) !!}</div>	 
</div>



<div class="row mb-2">
<div class="col-md-12">{!! Form::submit("Submit", ["class"=>"btn btn-primary"]) !!}</div>
</div>
	

{!! Form::close() !!}
</div>
</div>


