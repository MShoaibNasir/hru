<div class="col-md-12">
{!! Form::open(['route' => 'mne_action_form_submit', "method" => "post", "class" => "margin-bottom-5", "id" => "mne_action_form_submit"]) !!}	
{!! Form::hidden('mne_id', $mne_id) !!}
{!! Form::hidden('decision', $decision) !!}


<div class="row mb-3">
	<div class="col-md-4">Take Action</div>	
	<div class="col-md-8"><span class="badge bg-{{ $decision === 'approve' ? 'success' : ($decision === 'reject' ? 'danger' : '') }}">{{ $decision }}</span></div>	 
</div>

	


<div class="row margin-bottom-15">
<label class="col-md-4">Comment:</label>
<div class="col-md-8">{!! Form::textarea('comment', null, ['placeholder' => 'Comment', 'class' => 'form-control']) !!}</div>
</div>	

<div class="row margin-bottom-15">
<div class="col-md-12">{!! Form::submit("Submit", ["class"=>"btn btn-primary"]) !!}</div>
</div>
	

{!! Form::close() !!}
</div>