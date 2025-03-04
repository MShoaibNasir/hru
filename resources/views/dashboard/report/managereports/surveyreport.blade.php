@extends('dashboard.layout.master')
@section('content')

<div class="content">
    <!-- Navbar Start -->
     @include('dashboard.layout.navbar')
    <!-- Navbar End -->
    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">MASTER REPORT CUSTOMIZATION</h6>
            </div>
            
            <div class="row">

            <div class="col-md-12">
{{--                
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
--}}

<!--************************* END SESSION SETFLASH MESSAGES   ************************-->
<div class="alert alert-danger print-error-msg" style="display:none">
<strong>Whoops!</strong> There were some problems with your input.<br><br>
<ul></ul>
</div>                
                
                
                
                
                
                <div class="pdmadatalist">
                                  <!--Toolbar-->
                                    	<div class="toolbar">
                                        	<div class="filters-toolbar-wrapper">
                                        	    
                                        	    
                                        	    
                										
                										
                									

                                        	   {!! Form::open(array('method' => 'POST', 'class'=> '', 'id' => 'reportForm')) !!}
<div class="row">
    <div class="col-md-12 form-group">
         <label for="Search">Search By Refrence Number</label>
        {{ Form::text('reference_number',null,['class'=>'form-control', 'id'=>'reference_number','placeholder'=>'Refrence Number']) }}
    </div>

    
    <div class="col-md-12 form-group">
	<button type="submit" name="search" value="search" id="submit_btn" class="btn btn-primary btn-lg btn-block">Get Results</button>
    </div>
</div>
{!! Form::close() !!}
                                                    
                                                    



                                                    
                                                    
                
               
                                            </div>
                                        </div>
                                        <!--End Toolbar-->
                                    <div class="report_data"></div>
                                </div>
            </div> 
      
            </div>
        </div>
    </div>

    
    <script>

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


<x-frontend.survey.reporttrail_modal />
@endsection
@push('ayiscss')
<style>
.pdmadatalist .form-group{margin-bottom:15px;}
.pdmadatalist label{display:block; text-align:left;} 

</style>
@endpush
@push('ayisscript') 

<script>
$(document).ready(function() {
   
$('body').on('submit', '#reportForm', function(i){
        i.preventDefault();      

          $.ajax({ 
            url: "{{ route('managesurveyreportsubmit') }}",
		    type: 'POST',
			data: new FormData(this),
		    contentType: false,
            processData:false,
              beforeSend: function(){ 
			      $('.report_data').empty();
				  $('.report_data').html('<center><img src="{{ asset('images/loading.gif') }}" width="100" alt="Loader" /></center>');
				  $('#submit_btn').attr("disabled","disabled");
                  $('#reportForm').css("opacity",".5");
			  },
              success: function (response) {
				  $('body').find(".print-error-msg").css('display','none');
                  $('.report_data').empty();
				  $('.report_data').html(response);
              },
               complete:function(response){ 
			    $('#reportForm').css("opacity","");
                $("#submit_btn").removeAttr("disabled");
                //loaddatatable();
			   },
               error: function(response){
				    $('.report_data').empty();
					//$('.policydetail').html(response.responseJSON.errors);
                    $('body').find(".print-error-msg").find("ul").html('');
                    $('body').find(".print-error-msg").css('display','block');
                    $.each( response.responseJSON.errors, function( key, value ) {
                        $('body').find(".print-error-msg").find("ul").append('<li>'+value+'</li>');
                    });
                }
          });
   });    


$('body').on('click', '.masterreport_trail_btn', function(e){
        e.preventDefault();
        
        var report_id = $(this).attr('report_id');
        var survey_id = $(this).attr('survey_id');
        
        $('#reporttrail_modal').modal('show');
        
        $.ajax({
              url: "{{ route('masterreport_trail_form') }}",
              type: 'GET',
              data:{_token: '{{csrf_token()}}', report_id:report_id, survey_id:survey_id},
              //dataType: 'JSON',
                  beforeSend: function(){$('#reporttrail_modal #modaldata').html('Processing...');},
                  success: function (response){
                      $('#reporttrail_modal #modaldata').empty();
                      $('#reporttrail_modal #modaldata').html(response); 
                      
                  },
                   error: function (response){
                       $('#reporttrail_modal #modaldata').empty();
                       $('#reporttrail_modal #modaldata').html('Error 401');
                   }
        });
});

$('body').on('submit', '.masterreportupdate', function(f) {
         f.preventDefault();
		 var formData = new FormData(this);
              $.ajax({
              url: "{{ route('masterreportupdate') }}", 
              type: 'POST',
              data:formData,
		      contentType: false,
              processData:false,
			  
                  beforeSend: function(){
                      $('#reporttrail_modal #modaldata #uploadform_loader').html('<center><img src="{{ asset('images/preloader.gif') }}" width="100" alt="Loader" /></center>');
                  },
                  
                  success: function (response) {
                      console.log(response);
                      $('#reporttrail_modal #modaldata #uploadform_loader').empty();
                      $("#reporttrail_modal #modaldata").trigger("reset");
                      $('#reporttrail_modal #modaldata').empty();
                      $('#reporttrail_modal #modaldata').html(response);
                      
                       //setTimeout(function(){
                       //  $('.btn-close').click();
                       //  window.location.reload();
                       //}, 5000);
                      
                  },
                   error: function(response){
				    $('#reporttrail_modal #modaldata #uploadform_loader').empty();
				    //$('#reporttrail_modal #modaldata').html('Error 401');
					//$('#reporttrail_modal #modaldata').html(response.responseJSON.errors);
                        $('body #reporttrail_modal').find(".print-error-msg").find("ul").html('');
                        $('body #reporttrail_modal').find(".print-error-msg").css('display','block');
                        $.each( response.responseJSON.errors, function( key, value ) {
                            $('body #reporttrail_modal').find(".print-error-msg").find("ul").append('<li>'+value+'</li>');
                        });
                }
              });
        });





$('body').on('click', '.report_trail_btn', function(e){
        e.preventDefault();
        var report_id = $(this).attr('report_id');
        var report_type = $(this).attr('report_type');
        var action = $(this).attr('action');
        var survey_id = $(this).attr('survey_id');
        $('#reporttrail_modal').modal('show');
        
        $.ajax({
              url: "{{ route('report_trail_form') }}",
              type: 'GET',
              data:{_token: '{{csrf_token()}}', report_id:report_id, report_type:report_type, action:action, survey_id:survey_id},
              //dataType: 'JSON',
                  beforeSend: function(){$('#reporttrail_modal #modaldata').html('Processing...');},
                  success: function (response){
                      $('#reporttrail_modal #modaldata').empty();
                      $('#reporttrail_modal #modaldata').html(response); 
                      
                  },
                   error: function (response){
                       $('#reporttrail_modal #modaldata').empty();
                       $('#reporttrail_modal #modaldata').html('Error 401');
                   }
        });
});	

$('body').on('submit', '.reportdetailstore', function(f) {
         f.preventDefault();
		 var formData = new FormData(this);
              $.ajax({
              url: "{{ route('reportdetailstore') }}", 
              type: 'POST',
              data:formData,
		      contentType: false,
              processData:false,
			  
                  beforeSend: function(){
                      $('#reporttrail_modal #modaldata #uploadform_loader').html('<center><img src="{{ asset('images/preloader.gif') }}" width="100" alt="Loader" /></center>');
                  },
                  
                  success: function (response) {
                      console.log(response);
                      $('#reporttrail_modal #modaldata #uploadform_loader').empty();
                      $("#reporttrail_modal #modaldata").trigger("reset");
                      $('#reporttrail_modal #modaldata').empty();
                      $('#reporttrail_modal #modaldata').html(response);
                      
                       //setTimeout(function(){
                       //  $('.btn-close').click();
                       //  window.location.reload();
                       //}, 5000);
                      
                  },
                   error: function(response){
				    $('#reporttrail_modal #modaldata #uploadform_loader').empty();
				    //$('#reporttrail_modal #modaldata').html('Error 401');
					//$('#reporttrail_modal #modaldata').html(response.responseJSON.errors);
                        $('body #reporttrail_modal').find(".print-error-msg").find("ul").html('');
                        $('body #reporttrail_modal').find(".print-error-msg").css('display','block');
                        $.each( response.responseJSON.errors, function( key, value ) {
                            $('body #reporttrail_modal').find(".print-error-msg").find("ul").append('<li>'+value+'</li>');
                        });
                }
              });
        });

$('body').on('submit', '.reportdetailupdate', function(f) {
         f.preventDefault();
		 var formData = new FormData(this);
              $.ajax({
              url: "{{ route('reportdetailupdate') }}", 
              type: 'POST',
              data:formData,
		      contentType: false,
              processData:false,
			  
                  beforeSend: function(){
                      $('#reporttrail_modal #modaldata #uploadform_loader').html('<center><img src="{{ asset('images/preloader.gif') }}" width="100" alt="Loader" /></center>');
                  },
                  
                  success: function (response) {
                      console.log(response);
                      $('#reporttrail_modal #modaldata #uploadform_loader').empty();
                      $("#reporttrail_modal #modaldata").trigger("reset");
                      $('#reporttrail_modal #modaldata').empty();
                      $('#reporttrail_modal #modaldata').html(response);
                      
                       //setTimeout(function(){
                       //  $('.btn-close').click();
                       //  window.location.reload();
                       //}, 5000);
                      
                  },
                   error: function(response){
				    $('#reporttrail_modal #modaldata #uploadform_loader').empty();
				    //$('#reporttrail_modal #modaldata').html('Error 401');
					//$('#reporttrail_modal #modaldata').html(response.responseJSON.errors);
                        $('body #reporttrail_modal').find(".print-error-msg").find("ul").html('');
                        $('body #reporttrail_modal').find(".print-error-msg").css('display','block');
                        $.each( response.responseJSON.errors, function( key, value ) {
                            $('body #reporttrail_modal').find(".print-error-msg").find("ul").append('<li>'+value+'</li>');
                        });
                }
              });
        });
        
        
        
        
        
$('body').on('submit', '.formstatusstore', function(f) {
         f.preventDefault();
		 var formData = new FormData(this);
              $.ajax({
              url: "{{ route('formstatusstore') }}", 
              type: 'POST',
              data:formData,
		      contentType: false,
              processData:false,
			  
                  beforeSend: function(){
                      $('#reporttrail_modal #modaldata #uploadform_loader').html('<center><img src="{{ asset('images/preloader.gif') }}" width="100" alt="Loader" /></center>');
                  },
                  
                  success: function (response) {
                      console.log(response);
                      $('#reporttrail_modal #modaldata #uploadform_loader').empty();
                      $("#reporttrail_modal #modaldata").trigger("reset");
                      $('#reporttrail_modal #modaldata').empty();
                      $('#reporttrail_modal #modaldata').html(response);
                      
                       //setTimeout(function(){
                       //  $('.btn-close').click();
                       //  window.location.reload();
                       //}, 5000);
                      
                  },
                   error: function(response){
				    $('#reporttrail_modal #modaldata #uploadform_loader').empty();
				    //$('#reporttrail_modal #modaldata').html('Error 401');
					//$('#reporttrail_modal #modaldata').html(response.responseJSON.errors);
                        $('body #reporttrail_modal').find(".print-error-msg").find("ul").html('');
                        $('body #reporttrail_modal').find(".print-error-msg").css('display','block');
                        $.each( response.responseJSON.errors, function( key, value ) {
                            $('body #reporttrail_modal').find(".print-error-msg").find("ul").append('<li>'+value+'</li>');
                        });
                }
              });
        });

$('body').on('submit', '.formstatusupdate', function(f) {
         f.preventDefault();
		 var formData = new FormData(this);
              $.ajax({
              url: "{{ route('formstatusupdate') }}", 
              type: 'POST',
              data:formData,
		      contentType: false,
              processData:false,
			  
                  beforeSend: function(){
                      $('#reporttrail_modal #modaldata #uploadform_loader').html('<center><img src="{{ asset('images/preloader.gif') }}" width="100" alt="Loader" /></center>');
                  },
                  
                  success: function (response) {
                      console.log(response);
                      $('#reporttrail_modal #modaldata #uploadform_loader').empty();
                      $("#reporttrail_modal #modaldata").trigger("reset");
                      $('#reporttrail_modal #modaldata').empty();
                      $('#reporttrail_modal #modaldata').html(response);
                      
                       //setTimeout(function(){
                       //  $('.btn-close').click();
                       //  window.location.reload();
                       //}, 5000);
                      
                  },
                   error: function(response){
				    $('#reporttrail_modal #modaldata #uploadform_loader').empty();
				    //$('#reporttrail_modal #modaldata').html('Error 401');
					//$('#reporttrail_modal #modaldata').html(response.responseJSON.errors);
                        $('body #reporttrail_modal').find(".print-error-msg").find("ul").html('');
                        $('body #reporttrail_modal').find(".print-error-msg").css('display','block');
                        $.each( response.responseJSON.errors, function( key, value ) {
                            $('body #reporttrail_modal').find(".print-error-msg").find("ul").append('<li>'+value+'</li>');
                        });
                }
              });
        });        
        
        
        
        
        

	
});


</script>
@endpush