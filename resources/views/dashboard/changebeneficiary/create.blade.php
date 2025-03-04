@extends('dashboard.layout.master')
@section('content')
<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
    @include('dashboard.layout.navbar')
    <!-- Navbar End -->
    <div class="container-fluid pt-4 px-4 form_width">
        
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Change Beneficiary Form</h6>
                    
        @if (count($errors) > 0)
        <div class="row">
        <div class="mb-3 col-12">
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        </div></div>
    @endif




                    
                    {!! Form::open(array('route' => 'changebeneficiary.store','method'=>'POST', 'files'=>'true', 'class'=>'')) !!}
                        
                        <fieldset class="border p-3 mb-4">
                        <legend class="mb-3">Reference Number</legend>
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label">{{-- count($references) --}}{{ __('Reference Number') }}*</label>
                                {{-- Form::text('ref_noo', null, array('placeholder' => 'Reference Number','class' => 'form-control', 'required')) --}}
                                {!! Form::select('ref_no', $references, null, ['placeholder' => 'Reference Number', 'class' => 'form-control select2', 'required']) !!}
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
                </div>
            </div>
        </div>
    </div>
@push('ayiscss')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
@endpush
@push('ayisscript') 
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
<script>





$(document).ready(function() {
$('.select2').select2();    
$("#mask_cnic").inputmask("99999-9999999-9", {"clearIncomplete":true});
$("#mask_cnic_kin").inputmask("99999-9999999-9", {"clearIncomplete":true});

	$('body').on('change', '.district', function(e){
	    //alert();
     e.preventDefault();
		var district_id = $(e.target).find("option:selected").val();
		$.ajax({
              url: "{{ route('complaints.fetch_tehsil_list') }}",
              type: 'POST',
              data: {district_id: district_id, _token: '{{csrf_token()}}'},
              beforeSend: function(){$('.tehsil_list').html('Tehsil list Processing...');},
              success: function (response) {
				 $('.tehsil_list').empty();				 
				 $('.tehsil_list').html(response);
                 $('.uc_list').empty();
                 $('.uc_list').html('<select class="form-control select2"><option>Select UC</option></select>');				 
				 $('.select2').select2();
			  },
              error: function (response){$('.tehsil_list').empty(); $('.uc_list').empty(); }
              });
    });
	
	$('body').on('change', '.tehsil', function(e){
     e.preventDefault();
		var tehsil_id = $(e.target).find("option:selected").val();
		$.ajax({
              url: "{{ route('complaints.fetch_uc_list') }}",
              type: 'POST',
              data: {tehsil_id: tehsil_id, _token: '{{csrf_token()}}'},
              beforeSend: function(){$('.uc_list').html('UC List Processing...');},
              success: function (response) {
				 $('.uc_list').empty(); 
				 $('.uc_list').html(response); 
				 $('.select2').select2();
			  },
              error: function (response){$('.uc_list').empty();}
              });
    });
    
    $('body').on('change', '.otherspecify', function(e){
     e.preventDefault();
		var reason_id = $(e.target).find("option:selected").val();
		
		if(reason_id == 'Other'){
		//alert(reason_id);
		 $('.otherspecifyfield').html('<input type="text" name="otherspecify" placeholder="Other Specify Here" class="form-control" required>');   
		}else{
		  $('.otherspecifyfield').empty();  
		}
		
    });
	

$('body').on('change', '#lifetime_cnic', function(){
	$('#cnic_expiry_date').prop('disabled', $(this).is(':checked'));	
});
    





	
	
	
});


</script>
@endpush

@if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                toast: true,         // This enables the toast mode
                position: 'top-end', // Position of the toast
                showConfirmButton: false, // Hides the confirm button
                timer: 3000          // Time to show the toast in milliseconds
            });
        </script>
    @endif
    @if(session('success'))
        <script>

            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: "{{ session('success') }}"
            });
        </script>
    @endif
    @endsection