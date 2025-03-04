@extends('dashboard.layout.master')
@section('content')


<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
    @include('dashboard.layout.navbar')
    <!-- Navbar End -->

<div class="row">
<div class="col-md-12">
        
@if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
         {{ $message }}
        </div>
       @elseif ($message = Session::get('error'))
        <div class="alert alert-danger">
         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
         {{ $message }}
         </div>
	    @endif                
                
                
   @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif    
</div></div>    
    
    
    
    
    
    
<div class="row">
<div class="col-md-10 offset-md-1"> 
<div class="boxcontainer my-5">
<div class="message"></div>

<div class="card border-primary my-3">
@if($changebeneficiary->type == 'New')    
<div class="card-header bg-primary text-white">Change Beneficiary Form Review</div>
@else
<div class="card-header bg-primary text-white">Change Beneficiary Backup Data</div>
@endif
<div class="card-body">
<x-backend.changebeneficiary.changebeneficiaryview :changebeneficiary="$changebeneficiary" />
</div></div>

@if($changebeneficiary->type == 'New')
<div class="card border-primary my-3">
<div class="card-header bg-primary text-white">Change Beneficiary Files</div>
<div class="card-body">
<x-backend.changebeneficiary.changebeneficiaryfiles :changebeneficiary="$changebeneficiary" />
</div></div>

<div class="card border-danger my-3">
<div class="card-header bg-danger text-white">Change Beneficiary Report Trail History</div>
<div class="card-body">
<x-backend.changebeneficiary.changebeneficiarytrail :changebeneficiary="$changebeneficiary" />
</div></div>
@endif


@if($changebeneficiary)
    @if($changebeneficiary->status == 'P' && $changebeneficiary->type == 'New')
    @if(Auth::user()->role == $changebeneficiary->role_id)
        @if(in_array($changebeneficiary->lot_id, json_decode(Auth::user()->lot_id)))
        @if(in_array($changebeneficiary->district_id, json_decode(Auth::user()->district_id)))
        @if(in_array($changebeneficiary->tehsil_id, json_decode(Auth::user()->tehsil_id)))
        @if(in_array($changebeneficiary->uc_id, json_decode(Auth::user()->uc_id)))
            <div class="row changebeneficiary_take_action_btn">
            <div class="col-md-12 my-3">
                <div class="d-flex justify-content-center align-items-center gap-3">
                    <Button class="btn btn-success take_action" action="approve" cb_id="{{ $changebeneficiary->id }}">Approve</Button>
                    @if(Auth::user()->role != 30)
                    <Button class="btn btn-danger take_action" action="reject" cb_id="{{ $changebeneficiary->id }}">Reject</Button>
                    @endif
                </div>
            </div>    
            </div>
        @endif @endif @endif @endif
    @endif
    @endif
@endif

            
</div>
</div>
</div>
</div>



<x-backend.changebeneficiary.changebeneficiary_modal />

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

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
<script>
$(function() {
$('body').on('click', '.take_action', function(e){
        e.preventDefault();
        var cb_id = $(this).attr('cb_id');
        var decision = $(this).attr('action');
        $('#changebeneficiary_modal').modal('show');
        if (confirm('Are you sure you want to action this item?')) {
        $.ajax({
              url: "{{ route('changebeneficiary_action_form') }}",
              type: 'POST',
              data:{_token: '{{csrf_token()}}', cb_id:cb_id, decision:decision},
              //dataType: 'JSON',
                  beforeSend: function(){$('#changebeneficiary_modal #modaldata').html('Processing...');},
                  success: function (response) {
                      $('#changebeneficiary_modal #modaldata').empty();
                      $('#changebeneficiary_modal #modaldata').html(response); 
                      
                  },
                   error: function (response){
                       $('#changebeneficiary_modal #modaldata').empty();
                       $('#changebeneficiary_modal #modaldata').html('Error 401');
                   }
        });
        }
	
	
});
$('body').on('submit', '#changebeneficiary_action_form_submit', function(f) {
         f.preventDefault();
         //var formData = $(this).serialize();
		 var formData = new FormData(this);
		 //var ques_id = formData.get('ques_id');
		 //console.log(ques_id);
		 
              $.ajax({
              url: "{{ route('changebeneficiary_action_form_submit') }}", 
              type: 'POST',
              data:formData,
		      contentType: false,
              processData:false,
			  
                  beforeSend: function(){$('#changebeneficiary_modal #modaldata').html('Processing...');},
                  success: function (response){
                      console.log(response);
                      $("#changebeneficiary_modal #modaldata").trigger("reset");
                      $('#changebeneficiary_modal #modaldata').empty();
                      $('#changebeneficiary_modal #modaldata').html(response);
                      $('.changebeneficiary_take_action_btn').empty();
                       setTimeout(function(){
                         $('.btn-close').click();
                         window.location.reload();
                       }, 2000);
                      
                  },
                   error: function (response){
                       $('#changebeneficiary_modal #modaldata').empty();
                       $('#changebeneficiary_modal #modaldata').html('Error 401');
                   }
              });
              

        });        
        
        
    
    });
</script>
@endsection
@push('ayiscss')
<link href="{{ asset('dashboard/css/components.min.css?v=1') }}" rel="stylesheet">
<style>
 .alert-success {
    position:inherit;
    right:inherit; 
    top:inherit; 
}   
</style>
@endpush