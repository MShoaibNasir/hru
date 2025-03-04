@extends('dashboard.layout.master')
@section('content')

<div class="content">
    <!-- Navbar Start -->
     @include('dashboard.layout.navbar')
    <!-- Navbar End -->
    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">VRC Attendance List</h6>
            </div>
            

            
            
            <div class="row">

            <div class="col-md-12">
                <div class="pdmadatalist">
                                  <!--Toolbar-->
                                    	<div class="toolbar">
                                        	<div class="filters-toolbar-wrapper">
                                        
                                        	    
                                        	    	    
                                            	
                                            	<div class="row">
                                            	    {{--
                										<div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Search By Refrence Number</label>
                										{{ Form::text('b_reference_number',null,['class'=>'form-control', 'id'=>'b_reference_number','placeholder'=>'Refrence Number']) }}
                										</div> --}}
                										
                							
                                     
                							
                										
                									
                									
                                                    </div>
                                                    
                
                <div class="row">										
                										
                									<div class="filters-toolbar__item mb-3 col-md-4">
                                                      <label for="Sorting">Sort By</label>
                                                      {{ Form::select('sorting',[ 'id' => 'Id'],'id',['class'=>'form-control', 'id'=>'sorting']) }}
                                                    </div>
                                                    
                                                    
                									<div class="filters-toolbar__item mb-3 col-md-4">
                                                      <label for="Direction">Direction</label>
                                                      {{ Form::select('direction',['asc' => 'ASC', 'desc' => 'DESC'],'desc',['class'=>'form-control', 'id'=>'direction']) }}
                                                    </div>
                                                    
                										
                									<div class="filters-toolbar__item mb-3 col-md-4">
                                                      <label for="Quantity">Quantity</label>
                                                      {{ Form::select('qty', [10=>10, 25=>25, 50=>50, 100=>100, 500=>500, 1000=>1000], 10,['class'=>'form-control', 'id'=>'qty']) }}
                                                    </div>
                                                    <input type='hidden' id="vrc_attendece_main_id" value='{{ $vrc_attendece_main_id }}'>
                
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
        var sorting = $("#sorting").val();
        var direction = $("#direction").val();
        var vrc_attendece_main_id = $("#vrc_attendece_main_id").val();
        var qty = $("#qty").val();
        //var b_reference_number = $("#b_reference_number").val();
        
        
        var ayis_page = currentpage ?? 1;

       $.ajax({
          type: 'POST',
          data:{action:action,vrc_attendece_main_id:vrc_attendece_main_id, sorting:sorting, direction:direction, qty:qty, ayis_page:ayis_page, _token: '{{csrf_token()}}'},
          url: "{{ route('attendance_datalist_fetch_data') }}",
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
	
    $("#b_reference_number, #beneficiary_name, #cnic").on('keyup keydown', function() {
		filter_data();
    });
    
    

    $('body').on('change', '#sorting, #direction, #qty', function(e){
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
</script>
@endpush