@if($fetch_tehsil_list)
{!! Form::select('tehsil_id', $fetch_tehsil_list, null, array('placeholder' => 'Select Tehsil', 'class' => 'tehsil form-control select2', 'id'=>'tehsil_id', 'required')) !!}
@else
{!! Form::select('tehsil_id', [], null, ['placeholder' => 'Select Tehsil', 'class' => 'form-control select2', 'required']) !!}
@endif