@if($fetch_district_list)
{!! Form::select('district_id', $fetch_district_list, null, array('placeholder' => 'Select District', 'class' => 'district form-control select2', 'id'=>'district_id', 'required')) !!}
@else
{!! Form::select('district_id', [], null, ['placeholder' => 'Select District', 'class' => 'form-control select2', 'required']) !!}
@endif