<div class="row">
<div class="mb-3 col-6">
                            <label class="form-label">{{ __('Damage Date') }}</label>
                                {!! Form::date('damage_date', null, array('placeholder' => 'Damage Date', 'class' => 'form-control')) !!}
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">{{ __('Type of Construction') }}</label>
                                {!! Form::select('type_construction', ['kacha'=>'Kacha', 'pakka'=>'Pakka'], null, ['placeholder' => 'Type of Construction', 'class' => 'form-control']) !!}
                            </div>
                            

<div class="mb-3 col-6">
                            <label class="form-label">{{ __('Damage Category') }}</label>
                                {!! Form::select('damage_category', ['partially'=>'Partially', 'fully'=>'Fully'], null, ['placeholder' => 'Damage Category', 'class' => 'form-control']) !!}
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">{{ __('No. of Rooms Damaged') }}</label>
                                {!! Form::number('no_of_rooms_damage', null, array('placeholder' => 'No. of Rooms Damaged', 'class' => 'form-control')) !!}
                            </div>                            
</div>


	
    	
    	
