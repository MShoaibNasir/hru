{{--<div class="mb-3 col-12">--}}
<div class="card border-danger">
  <div class="card-header bg-danger text-white">Follow Up And Action Taken Form</div>
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

{!! Form::model($complaint, ['method' => 'POST','files'=>'true','route' => ['complaints.followupstore', $complaint->id], 'class'=>'followup_form form-horizontal']) !!}

<fieldset class="border p-3 mb-4">
                        {{--<legend class="mb-3">Form</legend>--}}
                        <div class="row">
                            <div class="mb-3 col-12">
                                <label class="form-label">{{ __('Action Taken') }}</label>
@if($complaint->status == 'Closed')
{!! Form::select('action_taken', ['Closed' => 'Closed', 'Reopen' => 'Reopen'], null, ['placeholder' => 'Select Action', 'class' => 'action_taken form-control select2', 'required' => 'true']) !!}
@else
 @if(Auth::user()->role == 53)
 {!! Form::select('action_taken', ['Closed' => 'Closed', 'In Process' => 'In Process', 'Forward' => 'Forward'], null, ['placeholder' => 'Select Action', 'class' => 'action_taken form-control select2', 'required' => 'true']) !!}
 @else
{!! Form::select('action_taken', ['Closed' => 'Closed', 'Returned' => 'Returned', 'In Process' => 'In Process', 'Forward' => 'Forward'], null, ['placeholder' => 'Select Action', 'class' => 'action_taken form-control select2', 'required' => 'true']) !!}
 @endif
@endif
                            </div>
                            
                            <div id="Assignofficers"></div>

                            
                            <div class="mb-3 col-12">
                                <label class="form-label">{{ __('Follow Up Details') }}</label>
                                {!! Form::textarea('remarks', null, array('placeholder' => 'Follow Up Details', 'class' => 'form-control', 'required' => 'true')) !!}
                            </div>
                            <div class="mb-3 col-12">
                                <label class="form-label">{{ __('File Attachment') }}</label>
                                {!! Form::file('attachment', null, array('class' => '')) !!}
                            </div>
                            
                        </div>
                        </fieldset>


<button type="submit" name="save" value="save" class="btn btn-success">Save</button>
{!! Form::close() !!}
<!-- END FORM-->
</div></div></div>