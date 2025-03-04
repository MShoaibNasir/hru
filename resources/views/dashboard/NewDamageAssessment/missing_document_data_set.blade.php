@extends('dashboard.layout.master')
@section('content')

<div class="content">
    <!-- Navbar Start -->
     @include('dashboard.layout.navbar')
    <!-- Navbar End -->
    @php
    $lots=lots();
    @endphp
    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Missing Data Document</h6>
            </div>
            
            <div class="row">

            <div class="col-md-12">
                <div class="pdmadatalist">
                                  <!--Toolbar-->
                                    	<div class="toolbar">
                                        	<div class="filters-toolbar-wrapper">
                                        	   
                                        <div class="row">
                                            <div class="filters-toolbar__item mb-3 col-md-6">

                                                <label class="">{{ __('Search By Lots') }}</label>
                                                <select class='form-control' id='lot_id'>
                                                    <option value=''>Select Lot</option>
                                                    @foreach($lots as $item)
                                                    <option value='{{$item->id}}'>{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                           
                                        
                                     
                                       	     
                                          
                                                    
                
                                               
                									<div class="filters-toolbar__item mb-3 col-md-6">
                                                      <label for="Quantity">Quantity</label>
                                                      {{ Form::select('qty', [10=>10, 25=>25, 50=>50, 100=>100, 500=>500, 1000=>1000], 10,['class'=>'form-control', 'id'=>'qty']) }}
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

    
<!-- Button trigger modal -->
<x-frontend.survey.upload_missing_document_modal />
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
});

$(document).ready(function(){	
    filter_data();
    function filter_data(currentpage)
    {
        $('.filter_data').html('<div id="loading"></div>');
        var action = 'fetch_data';
      
        var qty = $("#qty").val();
        var lot_id = $("#lot_id").val();
        

        
        
        var ayis_page = currentpage ?? 1;

       $.ajax({
          type: 'POST',
          data:{action:action,lot_id:lot_id, qty:qty, ayis_page:ayis_page, _token: '{{csrf_token()}}'},
          url: "{{ route('total_missing_datalist_fetch_data') }}",
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
    
            /*
            $(".color:checked").each(function(){
                colors.push($(this).val());
                colorName.push(this.name);
                $("#filter-item-color").html('<b>Color: </b>'+colorName);
            });
            */
    
    

    $('.common_selector').click(function(){
        filter_data();
    });
	
    
    
    

    $('body').on('change', '#qty, #lot_id', function(e){
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