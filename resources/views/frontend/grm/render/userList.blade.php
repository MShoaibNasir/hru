@if($users->isNotEmpty())
    {!! Form::select('user', $users, null, [
        'placeholder' => 'Select User',
        'class' => 'user form-control select2',
        'id' => 'user',
        'required'
    ]) !!}
@else
    {!! Form::select('user', [], null, [
        'placeholder' => 'Select User',
        'class' => 'form-control select2',
        'required'
    ]) !!}
@endif
