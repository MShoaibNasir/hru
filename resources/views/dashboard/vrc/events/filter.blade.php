@extends('dashboard.layout.master')
@section('content')

<style>
    body {
    font-family: 'Roboto', sans-serif;
    background-color: #eef2f3;
       }
    
.boxcontainer{
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    padding:30px;
    text-align: center;
}
        .profile-header {
            margin-bottom: 20px;
        }

        .profile-img {
            width: 200px;
            height: 200px;
            border-radius:5px; 
            border: 3px solid #009CFF;
            margin-bottom: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        

        .email {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .data-view {
            text-align: left;
           
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }

        .accordion-header {
            background-color: #009CFF;
            color: white;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 10px;
        }

        .accordion-header:hover {
            background-color: #007bb5;
        }
        

        .accordion-content {
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            margin-top: 5px;
        }

        .back-button {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #218838;
        }

        .custom_button {
            background-image: linear-gradient(#0dccea, #0d70ea);
            border: 0;
            border-radius: 4px;
            box-shadow: rgba(0, 0, 0, .3) 0 5px 15px;
            box-sizing: border-box;
            color: #fff;
            cursor: pointer;
            font-family: Montserrat, sans-serif;
            font-size: .9em;
            margin: 5px;
            padding: 10px 15px;
            text-align: center;
            user-select: none;
            margin-top: 16px;
        }
        
        
        
        
        
#myImg {
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
  margin: 5px; /* Added margin for spacing */
}

#myImg:hover {opacity: 0.7;}

/* The Modal (background) */
.mmodal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
.mmodal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

/* Caption of Modal Image */
#ccaption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation */
.mmodal-content, #caption {  
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
}

/* The Close Button */
.cclose {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.cclose:hover,
.cclose:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .mmodal-content {
    width: 100%;
  }
}
        
   .button_rotate {
      margin-top: 20px;
      font-size: 16px;
      cursor: pointer;
      background: black;
      width: 100px;
      color: #fff;
    }
 
    
     .rotating-image {
      transition: transform 0.5s ease-in-out; /* Smooth rotation */

   
    } 
    #loader_data {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
}

    

    
    </style>

<div class="content">
    <!-- Navbar Start -->
     @include('dashboard.layout.navbar')
    <!-- Navbar End -->
    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">VRC Event List</h6>
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
                                                    <input type='hidden' id="vrc_formation_id" value='{{ $vrc_formation_id }}'>
                
                                                </div>
                                            </div>
                                        </div>
                                        <!--End Toolbar-->
                                    <div class="filter_data"></div>
                                </div>
            </div> 
      
            </div>
            
            
<div id="mmyModal" class="mmodal">
    <span class="cclose">&times;</span>
    <img class="mmodal-content" id="img01">
    <div id="ccaption"></div>
</div>
        </div>
    </div>

  
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
	$('body').on('change', '.lot', function(e){
     e.preventDefault();
		var lot_id = $(e.target).find("option:selected").val();
		$.ajax({
              url: "{{ route('report.fetch_district_list_ac_name') }}",
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
              url: "{{ route('report.fetch_tehsil_list_ac_name') }}",
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
              url: "{{ route('report.fetch_uc_list_ac_name') }}",
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
        var lot_id = $("#lot_id").val();
        var district_id = $("#district_id").val();
        var tehsil_id = $("#tehsil_id").val();
        var uc_id = $("#uc_id").val();
        var vrc_formation_id = $("#vrc_formation_id").val();
        var qty = $("#qty").val();
        //var b_reference_number = $("#b_reference_number").val();
        
        
        var ayis_page = currentpage ?? 1;

       $.ajax({
          type: 'POST',
          data:{action:action,vrc_formation_id:vrc_formation_id,lot_id:lot_id,district_id:district_id,tehsil_id:tehsil_id,uc_id:uc_id, sorting:sorting, direction:direction, qty:qty, ayis_page:ayis_page, _token: '{{csrf_token()}}'},
          url: "{{ route('events_datalist_fetch_data') }}",
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
    
    

    $('body').on('change', '#sorting, #direction, #qty ,#lot_id,#district_id,#tehsil_id,#uc_id', function(e){
            e.preventDefault();
            filter_data();
    });
      
    $('body').on('click','.pagination a',function(f){
        f.preventDefault();
        var url = $(this).attr('href');
        var currentpage = url.split('page=')[1];
        filter_data(currentpage);
    });
    
    
  $(document).ready(function(){
      var mmodal = document.getElementById("mmyModal");

// Get the modal image and caption
var mmodalImg = document.getElementById("img01");
var ccaptionText = document.getElementById("ccaption");

// Get all images with class "myImg"
var imgs = document.getElementsByClassName("myImg");
console.log(imgs.length);

// Loop through the images and add click event listeners
for (var i = 0; i < imgs.length; i++) {
  imgs[i].onclick = function(){
      alert("check");
    mmodal.style.display = "block";
    mmodalImg.src = this.src;
    ccaptionText.innerHTML = this.alt;
  }
}

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("cclose")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
  mmodal.style.display = "none";
}
      
      
  }) 
    
    
    

});
</script>
@endpush