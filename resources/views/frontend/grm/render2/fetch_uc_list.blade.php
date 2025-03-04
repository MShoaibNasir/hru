@if($fetch_uc_list)
{!! Form::select('uc_id', $fetch_uc_list, null, array('placeholder' => 'Select UC', 'class' => 'form-control select2', 'id'=>'uc_id', 'required')) !!}
@else
{!! Form::select('uc_id', [], null, ['placeholder' => 'Select UC', 'class' => 'form-control select2', 'id'=>'uc_id', 'required']) !!}
@endif