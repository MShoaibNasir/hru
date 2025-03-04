<div class="mb-3 col-12">
<div class="card">
  <div class="card-header">Assign Complaint</div>
  <div class="card-body">

<!-- BEGIN FORM-->
@if (isset($errors) && $errors->any())
	<div class="col-md-12"><br />
            <div class="alert alert-danger alert-alt">
                <strong><i class="fa fa-bug fa-fw"></i> Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
			</div>
    @endif


{!! Form::open(array('route' => 'complaints.assigncomplaint','method'=>'POST', 'files'=>'true', 'class'=>'form-horizontal')) !!}
{!! Form::hidden('complaint_id', encrypt($complaint->id)) !!}

<fieldset class="border p-3 mb-4">
                        <legend class="mb-3">Assign Complaint Form</legend>
                        <div class="row">
                            <div class="mb-3 col-12">
                                <label class="form-label">{{ __('User') }}</label>
                                {!! Form::select('user', [], null, ['placeholder' => 'Select User', 'class' => 'form-control select2', 'required']) !!}
                            </div>
                            <div class="mb-3 col-12">
                                <label class="form-label">{{ __('Remarks Detail') }}</label>
                                {!! Form::textarea('remarks', null, array('placeholder' => 'Remarks Detail', 'class' => 'form-control', 'required' => 'true')) !!}
                            </div>
                            
                            
                        </div>
                        </fieldset>
<button type="submit" class="btn btn-success">Save</button>
{!! Form::close() !!}
<!-- END FORM-->
</div>
</div>
</div>