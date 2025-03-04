@extends('dashboard.layout.master')
@section('content')
<style>
    .download_btn {
        width: 100%;
        display: flex;
        justify-content: end;
    }
</style>

<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
    @include('dashboard.layout.navbar')
    <!-- Navbar End -->
    <div class="container-fluid pt-4 px-4 form_width">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Edit PDMA Data</h6>
                    <form method="post" action="{{route('update.pdma',[$ndma_verifications->id])}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label">Ref No<span class="text text-danger">*</span></label>
                                <input type="number" value='{{$ndma_verifications->b_reference_number}}' class="form-control" name="b_reference_number">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Province<span class="text text-danger">*</span></label>
                                <input type="text" value='{{$ndma_verifications->province}}' class="form-control" name="province">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Cnic<span class="text text-danger">*</span></label>
                                <input type="text" value='{{$ndma_verifications->cnic}}' class="form-control" name="cnic">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Address<span class="text text-danger">*</span></label>
                                <input type="text" value='{{$ndma_verifications->address}}' class="form-control" name="address">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Beneficiary Name<span class="text text-danger">*</span></label>
                                <input type="text" value='{{$ndma_verifications->beneficiary_name}}' class="form-control" name="beneficiary_name">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Father Name<span class="text text-danger">*</span></label>
                                <input type="text" value='{{$ndma_verifications->father_name}}' class="form-control" name="father_name">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Contact Number<span class="text text-danger">*</span></label>
                                <input type="text" value='{{$ndma_verifications->contact_number}}' class="form-control" name="contact_number">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Gender<span class="text text-danger">*</span></label>
                                <input type="text" value='{{$ndma_verifications->gender}}' class="form-control" name="gender">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Age<span class="text text-danger">*</span></label>
                                <input type="text" value='{{$ndma_verifications->age}}' class="form-control" name="age">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Name next of kin<span class="text text-danger">*</span></label>
                                <input type="text" value='{{$ndma_verifications->name_next_of_kin}}' class="form-control" name="name_next_of_kin">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Cnic Of Kin<span class="text text-danger">*</span></label>
                                <input type="text" value='{{$ndma_verifications->cnic_of_kin}}' class="form-control" name="cnic_of_kin">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Cnic Of Kin<span class="text text-danger">*</span></label>
                                <input type="text" value='{{$ndma_verifications->cnic_of_kin}}' class="form-control" name="cnic_of_kin">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Damaged rooms<span class="text text-danger">*</span></label>
                                <input type="text" value='{{$ndma_verifications->damaged_rooms}}' class="form-control" name="damaged_rooms">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Damaged Type<span class="text text-danger">*</span></label>
                                <input type="text" value='{{$ndma_verifications->damaged_type}}' class="form-control" name="damaged_type">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Damaged Category<span class="text text-danger">*</span></label>
                                <input type="text" value='{{$ndma_verifications->damaged_category}}' class="form-control" name="damaged_category">
                            </div>
                            
                            
                            
                            <div class="mb-3 col-6">
                                <label class="form-label">{{ __('District') }}*</label>
                                {!! Form::select('district', $districts, $ndma_verifications->district, array('placeholder' => 'Select District', 'id'=>'district', 'class' => 'district form-control select2', 'required')) !!}
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">{{ __('Tehsil') }}*</label>
                                <div class="tehsil_list">{!! Form::select('tehsil', $tehsil, $ndma_verifications->tehsil, ['placeholder' => 'Select Tehsil', 'id'=>'tehsil', 'class' => 'form-control select2', 'required']) !!}</div>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">{{ __('UC') }}*</label>
                                <div class="uc_list">{!! Form::select('uc', $uc, $ndma_verifications->uc, ['placeholder' => 'Select UC', 'id'=>'uc', 'class' => 'form-control select2', 'required']) !!}</div>
                            </div>
                        
                            
                            
                            
                        </div>


                        <button type="submit" class="btn btn-primary" o >Update</button>
                        <a onclick="history.back()" class="btn back_button">Go Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <!--<script src="{{asset('dashboard\js\ip_create.js')}}"></script>-->

    <script>
        function reloadPage() {
            
        setTimeout(() => {
            window.location.reload();
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
                title: "Data uploading  and kindly check your downloaded sheet!"
            })
            
        }, 3000); 
}

        
    </script>

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
    
@push('ayiscss')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
@endpush    
@push('ayisscript') 
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$(document).ready(function() {
$('.select2').select2();    
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
	
	
	
	
});
</script>
@endpush    