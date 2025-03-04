@extends('dashboard.layout.master')
@section('content')

<div class="content">
    <!-- Navbar Start -->
     @include('dashboard.layout.navbar')
    <!-- Navbar End -->
    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Total Missing Documents</h6>
            </div>
            
            <div class="row">

            <div class="col-md-12">
                <div class="pdmadatalist">
                                  <!--Toolbar-->
                                    	<div class="toolbar">
                                        	<div class="filters-toolbar-wrapper">

                                        	    <div class="row">
                                        	<div class="filters-toolbar__item mb-3 col-md-3">
                                                <label class="form-label">{{ __('Search By Lot') }}</label>
                                                {!! Form::select('lot', $lots, null, array('placeholder' => 'Select Lot', 'class' => 'lot form-control select2', 'id'=>'lot_id')) !!}
                                            </div>        
                                            <div class="filters-toolbar__item mb-3 col-md-3">
                                                <label class="form-label">{{ __('Search By District') }}</label>
                                                <div class="district_list">{!! Form::select('district', [], null, ['placeholder' => 'Select District', 'class' => 'form-control select2', 'id'=>'district_id']) !!}</div>
                                            </div>
                                            <div class="filters-toolbar__item mb-3 col-md-3">
                                                <label class="form-label">{{ __('Search By Tehsil') }}</label>
                                                <div class="tehsil_list">{!! Form::select('tehsil', [], null, ['placeholder' => 'Select Tehsil', 'class' => 'form-control select2', 'id'=>'tehsil_id']) !!}</div>
                                            </div>
                                            <div class="filters-toolbar__item mb-3 col-md-3">
                                                <label class="form-label">{{ __('Search By UC') }}</label>
                                                <div class="uc_list">{!! Form::select('uc', [], null, ['placeholder' => 'Select UC', 'class' => 'form-control select2', 'id'=>'uc_id']) !!}</div>
                                            </div>
                                        </div>
                                        	    
                                            	
                                                    
                                                    
                                                    
                                                    <div class="row">
                                                        
                                                        <div class="filters-toolbar__item mb-3 col-md-6">
                										<label for="Search">Search By Refrence Number</label>
                										{{ Form::text('b_reference_number',null,['class'=>'form-control', 'id'=>'b_reference_number','placeholder'=>'Refrence Number']) }}
                										</div>
                                                        
                                                        <div class="filters-toolbar__item mb-3 col-md-6">
                										<label for="Search">Search By Gender</label>
                										{!! Form::select('gender', $gender, null, ['placeholder' => 'Select Gender', 'class' => 'gender form-control select2', 'id'=>'gender']) !!}
                										</div>
                										
                										
                										
 
                									
                                                    </div>
                                                    
                                                    



                                                    
                                                    
                
                <div class="row">										
                										
                									<div class="filters-toolbar__item mb-3 col-md-4">
                                                      <label for="Sorting">Sort By</label>
                                                      {{ Form::select('sorting',[ 'id' => 'Survey ID', 'ref_no' => 'Refrence Number'],'id',['class'=>'form-control', 'id'=>'sorting']) }}
                                                    </div>
                                                    
                									<div class="filters-toolbar__item mb-3 col-md-4">
                                                      <label for="Direction">Direction</label>
                                                      {{ Form::select('direction',['asc' => 'ASC', 'desc' => 'DESC'],'desc',['class'=>'form-control', 'id'=>'direction']) }}
                                                    </div>
                                                    
                										
                									<div class="filters-toolbar__item mb-3 col-md-4">
                                                      <label for="Quantity">Quantity</label>
                                                      {{ Form::select('qty', [5=>5, 10=>10, 25=>25, 50=>50, 100=>100, 500=>500, 1000=>1000, 5000=>5000], 5,['class'=>'form-control', 'id'=>'qty']) }}
                                                    </div>
                                                    
                                                    
                 
                
                                                </div>
                                            </div>
                                        </div>
                                        <!--End Toolbar-->
                                    <div class="filter_data"></div>
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


<x-frontend.survey.upload_missing_document_modal />
@endsection
@push('ayiscss')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<style>
.pdmadatalist .form-group{margin-bottom:15px;}
.pdmadatalist label{display:block; text-align:left;} 
.pdmadatalist .select2-container{width:100%!important; text-align:left;}
</style>
@endpush
@push('ayisscript') 
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$(document).ready(function() {
$('.select2').select2();    
    
	$('body').on('change', '.lot', function(e){
     e.preventDefault();
		var lot_id = $(e.target).find("option:selected").val();
		$.ajax({
              url: "{{ route('report.fetch_district_list') }}",
              type: 'POST',
              data: {lot_id: lot_id, _token: '{{csrf_token()}}'},
              beforeSend: function(){
                  $('.district_list').html('District list Processing...');
                  $('.tehsil_list').html('Tehsil list Processing...');
                  $('.uc_list').html('UC list Processing...');
              },
              success: function (response) {
                 $('.district_list').empty();				 
				 $('.district_list').html(response);
				 $('.tehsil_list').empty();				 
				 $('.tehsil_list').html('<select name="tehsil_id" class="form-control select2" id="tehsil_id"><option value="">Select Tehsil</option></select>');
                 $('.uc_list').empty();
                 $('.uc_list').html('<select name="uc_id" class="form-control select2" id="uc_id"><option value="">Select UC</option></select>');				 
				 $('.select2').select2();
				 //filter_data();
			  },
              error: function (response){$('.district_list').empty(); $('.tehsil_list').empty(); $('.uc_list').empty(); }
              });
    });
	
	$('body').on('change', '.district', function(e){
     e.preventDefault();
		var district_id = $(e.target).find("option:selected").val();
		$.ajax({
              url: "{{ route('report.fetch_tehsil_list') }}",
              type: 'POST',
              data: {district_id: district_id, _token: '{{csrf_token()}}'},
              beforeSend: function(){
                  $('.tehsil_list').html('Tehsil list Processing...');
                  $('.uc_list').html('UC list Processing...');
              },
              success: function (response) {
				 $('.tehsil_list').empty();				 
				 $('.tehsil_list').html(response);
                 $('.uc_list').empty();
                 $('.uc_list').html('<select name="uc_id" class="form-control select2" id="uc_id"><option value="">Select UC</option></select>');				 
				 $('.select2').select2();
				 filter_data();
			  },
              error: function (response){$('.tehsil_list').empty(); $('.uc_list').empty(); }
              });
    });
	
	$('body').on('change', '.tehsil', function(e){
     e.preventDefault();
		var tehsil_id = $(e.target).find("option:selected").val();
		$.ajax({
              url: "{{ route('report.fetch_uc_list') }}",
              type: 'POST',
              data: {tehsil_id: tehsil_id, _token: '{{csrf_token()}}'},
              beforeSend: function(){$('.uc_list').html('UC List Processing...');},
              success: function (response) {
				 $('.uc_list').empty(); 
				 $('.uc_list').html(response); 
				 $('.select2').select2();
				 filter_data();
			  },
              error: function (response){$('.uc_list').empty();}
              });
    });
	
	
	
	
});

$(document).ready(function(){	
    filter_data();
    function filter_data(currentpage)
    {
        $('.filter_data').html('<div id="loading"></div>');
        var action = 'fetch_data';
        var sorting = $("#sorting").val();
        var direction = $("#direction").val();
        var qty = $("#qty").val();
        
        var lot_id = $("#lot_id").val();
        var district_id = $("#district_id").val();
        var tehsil_id = $("#tehsil_id").val();
        var uc_id = $("#uc_id").val();
        
        var gender = $("#gender").val();
        
        var evidence_type = $("#evidence_type").val();

        var b_reference_number = $("#b_reference_number").val();

        


        
        var ayis_page = currentpage ?? 1;

       $.ajax({
          type: 'POST',
          data:{action:action, lot_id:lot_id, district_id:district_id, tehsil_id:tehsil_id, uc_id:uc_id, gender:gender, evidence_type:evidence_type, b_reference_number:b_reference_number, sorting:sorting, direction:direction, qty:qty, ayis_page:ayis_page, _token: '{{csrf_token()}}'},
          url: "{{ route('total_missing_document_datalist_fetch_data') }}",
		   beforeSend: function(){$('.filter_data').html('<center><img src="{{ asset('images/loading.gif') }}" width="100" alt="Loader" /></center>');},
           success:function(data){
               
                  $('.filter_data').html(data);
              },
           error: function(data){console.log(data);}
  }); 
        
    }

    function get_filter(class_name)
    {
        var filter = [];
        $('.'+class_name+':checked').each(function(){
            filter.push($(this).val());
        });
        return filter;
    }
    
    

    $('.common_selector').click(function(){
        filter_data();
    });
	
    $("#b_reference_number").on('keyup keydown', function() {
		filter_data();
    });
    
  

    $('body').on('change', '#sorting, #direction, #qty, #lot_id, #district_id, #tehsil_id, #uc_id, #gender, #evidence_type', function(e){
            e.preventDefault();
            filter_data();
    });
      
    $('body').on('click','.pagination a',function(f){
        f.preventDefault();
        var url = $(this).attr('href');
        var currentpage = url.split('page=')[1];
        filter_data(currentpage);
    });
    
});









$(function() {
$('body').on('click', '.upload_mission_documents', function(e){
        e.preventDefault();
        var comment_id = $(this).attr('comment_id');
        var survey_id = $(this).attr('survey_id');
        var decision = $(this).attr('action');
        $('#upload_missing_document_modal').modal('show');
        $.ajax({
              url: "{{ route('upload_missing_document_form') }}",
              type: 'POST',
              data:{_token: '{{csrf_token()}}', comment_id:comment_id, survey_id:survey_id, decision:decision},
              //dataType: 'JSON',
                  beforeSend: function(){$('#upload_missing_document_modal #modaldata').html('Processing...');},
                  success: function (response) {
                      $('#upload_missing_document_modal #modaldata').empty();
                      $('#upload_missing_document_modal #modaldata').html(response); 
                      
                  },
                   error: function (response){
                       $('#upload_missing_document_modal #modaldata').empty();
                       $('#upload_missing_document_modal #modaldata').html('Error 401');
                   }
        });
	
	
});
$('body').on('submit', '#upload_missing_document_form_submit', function(f) {
         f.preventDefault();
         //var formData = $(this).serialize();
		 var formData = new FormData(this);
		 //var ques_id = formData.get('ques_id');
		 //console.log(ques_id);
		 
              $.ajax({
              url: "{{ route('upload_missing_document_form_submit') }}", 
              type: 'POST',
              data:formData,
		      contentType: false,
              processData:false,
			  
                  beforeSend: function(){
                      //$('#upload_missing_document_modal #modaldata #uploadform_loader').html('Processing...');
                      $('#upload_missing_document_modal #modaldata #uploadform_loader').html('<center><img src="{{ asset('images/preloader.gif') }}" width="100" alt="Loader" /></center>');
                  },
                  
                  success: function (response) {
                      console.log(response);
                      $('#upload_missing_document_modal #modaldata #uploadform_loader').empty();
                      $("#upload_missing_document_modal #modaldata").trigger("reset");
                      $('#upload_missing_document_modal #modaldata').empty();
                      $('#upload_missing_document_modal #modaldata').html(response);
                       setTimeout(function(){
                         $('.btn-close').click();
                         window.location.reload();
                       }, 5000);
                      
                  },
                   error: function(response){
				    $('#upload_missing_document_modal #modaldata #uploadform_loader').empty();
				    //$('#upload_missing_document_modal #modaldata').html('Error 401');
					//$('#upload_missing_document_modal #modaldata').html(response.responseJSON.errors);
    					
                        $('body #upload_missing_document_modal').find(".print-error-msg").find("ul").html('');
                        $('body #upload_missing_document_modal').find(".print-error-msg").css('display','block');
                        $.each( response.responseJSON.errors, function( key, value ) {
                            $('body #upload_missing_document_modal').find(".print-error-msg").find("ul").append('<li>'+value+'</li>');
                        });
                        
                }
                   
                   
                   
                   
              });
              

        });
});
</script>
@endpush

