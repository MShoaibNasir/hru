{!! Form::open(array('route' => 'changebeneficiary.store','method'=>'POST', 'files'=>'true', 'class'=>'')) !!}
                        
                        <fieldset class="border p-3 mb-4">
                        <legend class="mb-3">Reference Number</legend>
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label">{{ __('Reference Number') }}*</label>
                                {!! Form::text('ref_no', null, array('placeholder' => 'Reference Number','class' => 'form-control', 'required')) !!}
                                {{-- Form::select('ref_noo', $references, null, ['placeholder' => 'Reference Number', 'class' => 'form-control select2', 'required']) --}}
                            </div>
                        </div>
                        </fieldset>
                        
                        <fieldset class="border p-3 mb-4">
                        <legend class="mb-3">Change Beneficiary Information</legend>
                        <div class="row">
                            <div class="mb-3 col-4">
                                <label class="form-label">{{ __('Beneficiary Name') }}*</label>
                                {!! Form::text('name_beneficiary', null, array('placeholder' => 'Beneficiary Name','class' => 'form-control', 'required')) !!}
                            </div>
                            <div class="mb-3 col-4">
                                <label class="form-label">{{ __('Father Name/Husband') }}*</label>
                                {!! Form::text('name_father_husband', null, array('placeholder' => 'Father Name/Husband','class' => 'form-control', 'required')) !!}
                            </div>
                            <div class="mb-3 col-4">
                                <label class="form-label">{{ __('CNIC No') }}*</label>
                                {!! Form::text('cnic', null, array('placeholder' => 'CNIC No','class' => 'form-control', 'id' => 'mask_cnic', 'required')) !!}
			                    <span class="help-block">99999-9999999-9</span>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="mb-3 col-4">
                                <label class="form-label">{{ __('New Next of Kin') }}*</label>
                                {!! Form::text('next_kin_name', null, array('placeholder' => 'New Next of Kin','class' => 'form-control', 'required')) !!}
                            </div>
                            <div class="mb-3 col-4">
                                <label class="form-label">{{ __('Next of kin CNIC No') }}*</label>
                                {!! Form::text('cnic_of_kin', null, array('placeholder' => 'Next of kin CNIC No','class' => 'form-control', 'id' => 'mask_cnic_kin', 'required')) !!}
			                    <span class="help-block">99999-9999999-9</span>
                            </div>
                            <div class="mb-3 col-4">
                                <label class="form-label">{{ __('Reason for changing beneficiary') }}*</label>
                                {!! Form::select('reason_change_beneficiary', ['Death'=>'Death', 'Prison'=>'Prison', 'Other'=>'Other Specify'], null, ['placeholder' => 'Select Reason', 'class' => 'otherspecify form-control', 'required']) !!}
                                <div class="mt-3 otherspecifyfield"></div>
                            </div>
                        </div>
                        
                        
                        </fieldset>
                        
                        
                        
                        
                        <fieldset class="border p-3 mb-4">
                        <legend class="mb-3">Bank Account Information</legend>
                        <div class="row">
                            <div class="mb-3 col-4">
                                <label class="form-label">{{ __('Date of Issuance of CNIC / Smart card') }}*</label>
                                {!! Form::date('cnic_issue_date', null, array('placeholder' => 'Date of Issuance', 'class' => 'form-control', 'max' => Carbon\Carbon::yesterday()->format('Y-m-d'), 'required')) !!}
                            </div>
                            <div class="mb-3 col-4">
                                <input type="checkbox" id="lifetime_cnic" name="lifetime_cnic">
                                <label for="lifetime_cnic">Lifetime CNIC</label>
                                <br />
                                
                                
                                <label class="form-label">{{ __('CNIC Expiry Date') }}*</label>
                                {!! Form::date('cnic_expiry_date', null, array('placeholder' => 'CNIC Expiry Date','id'=>'cnic_expiry_date', 'class' => 'form-control', 'required')) !!}
                            </div>
                            <div class="mb-3 col-4">
                                <label class="form-label">{{ __('Mothers Maiden Name') }}*</label>
                                {!! Form::text('mother_maiden_name', null, array('placeholder' => 'Mothers Maiden Name', 'class' => 'form-control', 'required')) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-4">
                                <label class="form-label">{{ __('Date of Birth') }}*</label>
                                {!! Form::date('date_of_birth', null, array('placeholder' => 'Date of Birth', 'class' => 'form-control', 'max' => Carbon\Carbon::yesterday()->format('Y-m-d'), 'required')) !!}
                            </div>
                            <div class="mb-3 col-4">
                                <label class="form-label">{{ __('City of Birth') }}*</label>
                                {!! Form::text('city_of_birth', null, array('placeholder' => 'City of Birth', 'class' => 'form-control', 'required')) !!}
                            </div>
                        </div>
                        </fieldset>
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        <fieldset class="border p-3 mb-4">
                        <legend class="mb-3">Upload Documents</legend>
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label">{{ __('Change beneficiary image') }}*</label>
                            </div>
                            <div class="mb-3 col-12">
                                {!! Form::file('change_beneficiary_image', null, array('class' => '', 'required')) !!}
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('CNIC Front Photographs') }}*</label>
                            </div>
                            <div class="mb-3 col-12">
                                {!! Form::file('cnic_front', null, array('class' => '', 'required')) !!}
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('CNIC Back Photographs') }}*</label>
                            </div>
                            <div class="mb-3 col-12">
                                {!! Form::file('cnic_back', null, array('class' => '', 'required')) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label">{{ __('Evidence uploads (Death Certificate, Court Order or any other legally accepted document)') }}*</label>
                            </div>
                            <div class="mb-3 col-12">
                                {!! Form::file('evidence_uploads', null, array('class' => '', 'required')) !!}
                            </div>
                            
                        </div>
                        
                        
                        
                        </fieldset>

                        <button type="submit" class="btn btn-primary">Submit Beneficiary</button>
                    {!! Form::close() !!}