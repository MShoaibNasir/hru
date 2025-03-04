<!-- Start Complaint Detail--> 
  {{--<div class="card border-primary">
  <div class="card-header bg-primary text-white">Complaint Detail</div>
  <div class="card-body">--}}
    <div class="record_view">
    <h3 class="form-section">Ticket Details</h3>  
    <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Ticket No:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">TN#{{ $complaint->ticket_no }} </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Created Date:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ Carbon\Carbon::parse($complaint->created_at)->format('d-m-Y g:i A') }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
																<div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Current Status:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static"> <span class="badge bg-primary">{{ $complaint->status }}</span> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <!--/row 1-->
    <h3 class="form-section">Complainant Information</h3>  
    <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Source/Channel:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $complaint->getsourcechannel->name ?? '' }} </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-8">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">PIU:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $complaint->getpiu->name ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
																
                                                            </div>
                                                            <!--/row 2-->
                                                            
    <h3 class="form-section">Personal Details</h3>  
    <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Full Name:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $complaint->full_name }} </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Father Name:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $complaint->father_name }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
																<div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">CNIC No:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $complaint->cnic }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <!--/row 3.1-->
                                                            
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">HRU Beneficiary ID:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $complaint->hru_beneficiary_id }} </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Mobile No:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $complaint->mobile }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
																<div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Email ID:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $complaint->email }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <!--/row 3.2-->
                                                            
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">District:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $complaint->getdistrict->name ?? '' }} </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                            
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Tehsil:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $complaint->gettehsil->name ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
																<div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">UC:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $complaint->getuc->name ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <!--/row 3.3-->
                                                            
                                                            <div class="row">
																<div class="col-md-12">
                                                                    <div class="row"> 
																	    <label class="control-label col-md-2">Postal Address:</label>
                                                                        <div class="col-md-10">
                                                                            <p class="form-control-static"> {{ $complaint->postal_address }} </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <!--/row 3.4-->
                                                            
    <h3 class="form-section">Grievance Registration</h3>  
    <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Grievance Type:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $complaint->getgrievancetype->name }} </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="col-md-8">
                                                                    <div class="row">
                                                                        <label class="control-label col-md-6">Subject:</label>
                                                                        <div class="col-md-6">
                                                                            <p class="form-control-static">{{ $complaint->subject }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
																
                                                            </div>
                                                            <!--/row 4.1-->
                                                            
                                                            <div class="row">
																<div class="col-md-12">
                                                                    <div class="row"> 
																	    <label class="control-label col-md-2">Description:</label>
                                                                        <div class="col-md-10">
                                                                            <p class="form-control-static"> {{ $complaint->description }} </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <!--/row 4.2-->
                                                            
                                                            
    </div>

{{--</div></div></div>--}}
<!-- END Complaint Detail-->
@push('ayisscript')
<script type="text/javascript">
$(document).ready(function(){
 $('body').on('change', '.action_taken', function(e){
     e.preventDefault();
		var action_taken = $(e.target).find("option:selected").val();
		if(action_taken == 'Forward'){
		    alert('Load GRM Users');
            //$('.select2').select2();
		    
		$.ajax({
              url: "{{ route('complaints.fetch_grm_users') }}",
              type: 'POST',
              data: {action_taken: action_taken, _token: '{{csrf_token()}}'},
              beforeSend: function(){$('#Assignofficers').html('Processing...');},
              success: function (response) {
				 $('#Assignofficers').empty(); 
				 $('#Assignofficers').html(response); 
				 $('.select2').select2();
			  },
              error: function (response){$('#Assignofficers').empty();}
              });
              
			 }else{
		  $('#Assignofficers').empty();
		  return false;
		      } 
    });
    });
 </script>   
 @endpush