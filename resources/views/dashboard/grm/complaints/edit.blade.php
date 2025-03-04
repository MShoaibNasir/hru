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
                    <h6 class="mb-4">GRM Edit Complaint Form</h6>
                    
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
                    
                    {!! Form::model($complaint, ['method' => 'PATCH', 'files'=>'true', 'route' => ['complaints.update', $complaint->id], 'class'=>'']) !!}
                        <fieldset class="border p-3 mb-4">
                        <legend class="mb-3">Complainant Information</legend>
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label">{{ __('Source/Channel') }}*</label>
                                {!! Form::select('source_channel', $source_channels, null, ['placeholder' => 'Source/Channel', 'class' => 'form-control select2', 'required']) !!}
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">{{ __('PIU') }}*</label>
                                {!! Form::select('piu', $pius, null, ['placeholder' => 'Select PIU', 'class' => 'form-control select2', 'required']) !!}
                            </div>
                        </div>
                        </fieldset>
                        
                        <fieldset class="border p-3 mb-4">
                        <legend class="mb-3">Personal Details</legend>
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label">{{ __('Full Name') }}*</label>
                                {!! Form::text('full_name', null, array('placeholder' => 'Full Name','class' => 'form-control', 'required')) !!}
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">{{ __('Father Name') }}*</label>
                                {!! Form::text('father_name', null, array('placeholder' => 'Father Name','class' => 'form-control', 'required')) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label">{{ __('CNIC No') }}*</label>
                                {!! Form::text('cnic', null, array('placeholder' => 'CNIC No','class' => 'form-control', 'id' => 'mask_cnic', 'required')) !!}
			                    <span class="help-block">99999-9999999-9</span>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">{{ __('HRU Beneficiary ID') }}</label>
                                {!! Form::text('hru_beneficiary_id', null, array('placeholder' => 'HRU Beneficiary ID','class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label">{{ __('Mobile No') }}*</label>
                                {!! Form::text('mobile', null, array('placeholder' => 'Mobile No','class' => 'form-control', 'maxlength' => '13', 'required')) !!}
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">{{ __('Email ID (If Any)') }}</label>
                                {!! Form::email('email', null, array('placeholder' => 'Email ID (If Any)', 'class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-4">
                                <label class="form-label">{{ __('District') }}*</label>
                                {!! Form::select('district_id', $districts, null, array('placeholder' => 'Select District', 'class' => 'district form-control select2', 'required')) !!}
                            </div>
                            <div class="mb-3 col-4">
                                <label class="form-label">{{ __('Tehsil') }}*</label>
                                <div class="tehsil_list">{!! Form::select('tehsil_id', $tehsil, null, ['placeholder' => 'Select Tehsil', 'class' => 'form-control select2', 'required']) !!}</div>
                            </div>
                            <div class="mb-3 col-4">
                                <label class="form-label">{{ __('UC') }}*</label>
                                <div class="uc_list">{!! Form::select('uc_id', $uc, null, ['placeholder' => 'Select UC', 'class' => 'form-control select2', 'required']) !!}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12">
                                <label class="form-label">{{ __('Postal Address') }}</label>
                                {!! Form::textarea('postal_address', null, array('placeholder' => 'Postal Address', 'class' => 'form-control')) !!}
                            </div>
                        </div>
                        </fieldset>
                        
                        <fieldset class="border p-3 mb-4">
                        <legend class="mb-3">Grievance Registration</legend>
                        <div class="row">
                            <div class="mb-3 col-12">
                                <label class="form-label">{{ __('Grievance Type') }}*</label>
                                {!! Form::select('grievance_type', $grievance_types, null, ['placeholder' => 'Grievance Type', 'class' => 'grievance_type form-control select2']) !!}
                            </div>
                            
                            <div class="grievance_fields">
        @if($complaint->grievance_type == 1)
        @else
        @endif
        
    	<div class="exclusioncasesfields"><x-backend.grm.complaint.grievance_type_exclusion_cases /></div>
    	<div class="defaultfields"><x-backend.grm.complaint.grievance_type_defaultfields /></div>
    	
	</div>
                            
                        </div>
                        </fieldset>
                        
                        

                        <button type="submit" class="btn btn-primary">Update Complaint</button>
                    </form>
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
    
	$('body').on('change', '.district', function(e){
	    alert();
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
	
	
	
	
});

$(document).ready(function() {

        $('.grievance_type').change(function() {
            var grievance_type = $(".grievance_type").val();
            //alert(grievance_type);
            if(grievance_type==1){
                $(".grievance_fields .defaultfields").hide();
                $(".grievance_fields .exclusioncasesfields").show();
            }else{
                $(".grievance_fields .exclusioncasesfields").hide();
                $(".grievance_fields .defaultfields").show();
            }
                
            }); 
            
            
            @if($complaint->grievance_type == 1)
              $(".grievance_fields .defaultfields").hide();
              $(".grievance_fields .exclusioncasesfields").show();
            @else
              $(".grievance_fields .exclusioncasesfields").hide();
              $(".grievance_fields .defaultfields").show();
            @endif
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