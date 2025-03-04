<div class="mb-3 col-12">
                                <label class="form-label">{{ __('Subject') }}</label>
                                {!! Form::text('subject', null, array('placeholder' => 'Subject', 'class' => 'form-control')) !!}
                            </div>
                            <div class="mb-3 col-12">
                                <label class="form-label">{{ __('Description') }}</label>
                                {!! Form::textarea('description', null, array('placeholder' => 'Description', 'class' => 'form-control')) !!}
                            </div>