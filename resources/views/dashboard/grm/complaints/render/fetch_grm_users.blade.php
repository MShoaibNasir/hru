@if($fetch_grm_users)
<div class="mb-3 col-12">
<label class="form-label">{{ __('Select GRM Officer To Forward Complaint') }}</label>
{!! Form::select('assign_to', $fetch_grm_users, null, array('placeholder' => 'Select GRM Officer', 'class' => 'form-control select2', 'required')) !!}
</div>
@endif