<div class="row form-group">
    			<label class="col-md-3 control-label">Damage Date</label>
    			<div class="col-md-9">
    			{!! Form::date('damage_date', null, array('placeholder' => 'Damage Date', 'class' => 'form-control')) !!}
    			</div>
    	</div>
    	
    	<div class="row form-group required">
		<label class="col-md-3 control-label">{{ __('Type of Construction') }}</label>
		<div class="col-md-9">
			{!! Form::select('type_construction', ['kacha'=>'Kacha', 'pakka'=>'Pakka'], null, ['placeholder' => 'Type of Construction', 'class' => 'form-control']) !!}
		</div>
	</div>
	<div class="row form-group required">
		<label class="col-md-3 control-label">{{ __('Damage Category') }}</label>
		<div class="col-md-9">
			{!! Form::select('damage_category', ['partially'=>'Partially', 'fully'=>'Fully'], null, ['placeholder' => 'Damage Category', 'class' => 'form-control']) !!}
		</div>
	</div>
    	
    	<div class="row form-group">
    			<label class="col-md-3 control-label">No. of Rooms Damaged</label>
    			<div class="col-md-9">
    			{!! Form::number('no_of_rooms_damage', null, array('placeholder' => 'No. of Rooms Damaged', 'class' => 'form-control')) !!}
    			</div>
    	</div>
    	
    	
