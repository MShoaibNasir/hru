<div class="record_view">
    <h3 class="form-section">Beneficiary Form Status</h3> 
    
    @if($changebeneficiary->type == 'New')
    <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Survey ID:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $changebeneficiary->survey_id }} </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Created Date:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ Carbon\Carbon::parse($changebeneficiary->created_at)->format('d-m-Y g:i A') }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
																<div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Current Status:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static"> <span class="badge bg-primary">{{ $changebeneficiary->status }}</span> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <!--/row 1-->
    <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Reference No:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $changebeneficiary->ref_no }} </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Last Action Date:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ isset($changebeneficiary->action_date) ? Carbon\Carbon::parse($changebeneficiary->action_date)->format('d-m-Y g:i A') : 'No Action Perform' }}
</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
																<div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Current Department:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static"> <span class="badge bg-primary">{{ $changebeneficiary->role_name }}</span> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <!--/row 1-->
    @else
    <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Survey ID:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $changebeneficiary->survey_id }} </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Reference No:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $changebeneficiary->ref_no }} </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Created Date:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ Carbon\Carbon::parse($changebeneficiary->created_at)->format('d-m-Y g:i A') }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
																
                                                            </div>
                                                            <!--/row 1-->
    @endif
                                                            
    <h3 class="form-section">Beneficiary Details</h3>  
    <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Beneficiary Name:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $changebeneficiary->name_beneficiary }} </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Father/Husband Name:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $changebeneficiary->name_father_husband }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
																<div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">CNIC No:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $changebeneficiary->cnic }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <!--/row 3.1-->
    <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">New Next of Kin:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $changebeneficiary->next_kin_name }} </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Next of kin CNIC No:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $changebeneficiary->cnic_of_kin }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
																<div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Reason for changing beneficiary:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $changebeneficiary->reason_change_beneficiary }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <!--/row 3.2-->                                                        
                                                            

                                                            <h3 class="form-section">Beneficiary Location</h3>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Lot:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $changebeneficiary->getlot->name ?? '' }} </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">District:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $changebeneficiary->getdistrict->name ?? '' }} </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                            
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Tehsil:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $changebeneficiary->gettehsil->name ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
																<div class="col-md-6">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">UC:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $changebeneficiary->getuc->name ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <!--/row 3.3-->
                                                            
                                                            
                        @if($changebeneficiary->type == 'New')
                                                            <h3 class="form-section">Bank Account Information</h3>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">{{ __('Date of Issuance of CNIC / Smart card') }}:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ Carbon\Carbon::parse($changebeneficiary->cnic_issue_date)->format('d-m-Y') }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        @if($changebeneficiary->lifetime_cnic)
                                                                        <label class="control-label col-md-6">{{ __('CNIC Lifetime') }}:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">Yes</p>
                                                                        </div>
                                                                        @else
                                                                        <label class="control-label col-md-6">{{ __('CNIC Expiry Date') }}:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ Carbon\Carbon::parse($changebeneficiary->cnic_expiry_date)->format('d-m-Y') }}</p>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">{{ __('Mothers Maiden Name') }}:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $changebeneficiary->mother_maiden_name ?? '' }} </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                
                                                            </div>
                                                            <!--/row 3.3-->
                                                            
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">{{ __('Date of Birth') }}:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ Carbon\Carbon::parse($changebeneficiary->date_of_birth)->format('d-m-Y') }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">{{ __('City of Birth') }}:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $changebeneficiary->city_of_birth ?? '' }} </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <!--/row 3.3-->
                                                            @endif
                                                            
                                                            
                                                            
   
                                                            
                                                            
</div>