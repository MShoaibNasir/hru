@extends('dashboard.layout.master')
@section('content')
<style>
input.form-control.btn.btn-success.my-4 {
    margin-left: 14px;
}
.hover-btn:hover{
    color:white !important;
}
td {
    text-align: left !important;
}
th {
    text-align: left;
}
</style>


<div class="content">
    <!-- Navbar Start -->
     @include('dashboard.layout.navbar')
    <!-- Navbar End -->
    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">All Module Report</h6>
            </div>
            
            <div class="row">

            <div class="col-md-12">
                <div class="pdmadatalist">
                                  <!--Toolbar-->
                                    	<div class="toolbar">
                                        	<div class="filters-toolbar-wrapper">
                                        	    
                                        <div class="row">


                                        <div class="filters-toolbar__item mb-3 col-md-4">
                                                      <label for="form">Search by Form Name</label>
                                                      {{ Form::select('form',[ 'Damage Assessment Form' => 'Damage Assessment Form','MNE Form'=>'MNE Form', 'Construction Form' => 'Construction Form', 'Environment Form' => 'Environment Form','Gender From'=>'Gender Form','Social Form'=>'Social Form','VRC Form'=>'VRC Form'],'id',['class'=>'form-control', 'id'=>'form']) }}
                                        </div>




                                        <div class="filters-toolbar__item mb-3 col-md-4">
                                                <label class="form-label">{{ __('Search By Role') }}</label>
                                                {!! Form::select('role', $roles, null, array('placeholder' => 'Select Role', 'class' => 'role form-control select2', 'id'=>'role')) !!}
                                        </div>
                                        <div class="filters-toolbar__item mb-3 col-md-4">
                                                <label class="form-label">{{ __('Search By User') }}</label>
                                                {!! Form::select('user', [], null, array('placeholder' => 'Select User', 'class' => 'user form-control select2', 'id'=>'user')) !!}
                                        </div>

                                        <div class="filters-toolbar__item mb-3 col-md-4">
                                                <label class="form-label">{{ __('Search By District') }}</label>
                                                {!! Form::select('district', $districts, null, array('placeholder' => 'Select District', 'class' => 'district form-control select2', 'id'=>'district')) !!}
                                        </div>
                                        <div class="filters-toolbar__item mb-3 col-md-4">
                                                <label class="form-label">{{ __('Search By Tehsil') }}</label>
                                                <div class="tehsil_list">{!! Form::select('tehsil', [], null, ['placeholder' => 'Select Tehsil', 'class' => 'form-control select2', 'id'=>'tehsil_id']) !!}</div>
                                        </div>
                                            <div class="filters-toolbar__item mb-3 col-md-4">
                                                <label class="form-label">{{ __('Search By UC') }}</label>
                                                <div class="uc_list">{!! Form::select('uc', [], null, ['placeholder' => 'Select UC', 'class' => 'form-control select2', 'id'=>'uc_id']) !!}</div>
                                            </div>
                                        </div>
                                        	    
                                            	
                                            
                
                <div class="row">										
                										
                									<div class="filters-toolbar__item mb-3 col-md-4">
                                                      <label for="Sorting">Sort By</label>
                                                      {{ Form::select('sorting',[ 'id' => 'ID'],'id',['class'=>'form-control', 'id'=>'sorting']) }}
                                                    </div>
                                                    
                									<div class="filters-toolbar__item mb-3 col-md-4">
                                                      <label for="Direction">Direction</label>
                                                      {{ Form::select('direction',['asc' => 'ASC', 'desc' => 'DESC'],'desc',['class'=>'form-control', 'id'=>'direction']) }}
                                                    </div>
                                                    
                										
                									<div class="filters-toolbar__item mb-3 col-md-4">
                                                      <label for="Quantity">Quantity</label>
                                                      {{ Form::select('qty', [10=>10, 25=>25, 50=>50, 100=>100, 500=>500, 1000=>1000], 10,['class'=>'form-control', 'id'=>'qty']) }}
                                                    </div>
                                                    
                
                                                </div>
                                            </div>
                                        </div>
                            
                                  
                                  
                                 
                                    <!--form end-->
                                    <div class="filter_data"></div>
                                </div>
            </div> 
      
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

 function toArray(csvString) {
    return csvString.split(',').map(item => item.trim());
    }

$(document).ready(function() {
$('.select2').select2();    
    
	$('body').on('change', '.role', function(e){
     e.preventDefault();
		var role_id = $(e.target).find("option:selected").val();
		$.ajax({
              url: "{{ route('get_users_according_to_role') }}",
              type: 'POST',
              data: {role_id: role_id, _token: '{{csrf_token()}}'},
              beforeSend: function(){
                  $('.user').html('User list Processing...');
              },
              success: function (response) {
                console.log(response);
                
				 $('.user').empty();				 
				 $('.user').html(response);
				 $('.select2').select2();
				 filter_data();
			  },
              error: function (response){$('.tehsil_list').empty(); $('.uc_list').empty(); }
              });
    });
	$('body').on('change', '.district', function(e){
     e.preventDefault();
		var district_id = $(e.target).find("option:selected").val();
		$.ajax({
              url: "{{ route('complaints.fetch_tehsil_list') }}",
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
              url: "{{ route('complaints.fetch_uc_list') }}",
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
        var form = $("#form").val();
        var direction = $("#direction").val();
        var qty = $("#qty").val();
        var trench = $("#trench").val();
   
        
        var district = $("#district").val();
        var role = $("#role").val();
        var user = $("#user").val();
        var tehsil_id = $("#tehsil_id").val();
        var uc_id = $("#uc_id").val();
        
        var beneficiary_name = $("#beneficiary_name").val();
        var bank_name = $("#bank_name").val();
        var b_reference_number = $("#b_reference_number").val();
		var cnic = $("#cnic").val();
		
        //var colors = get_filter('color');
        
        
        var ayis_page = currentpage ?? 1;

       $.ajax({
          type: 'POST',
          data:{action:action, district:district,role:role,user:user, tehsil_id:tehsil_id, uc_id:uc_id,trench:trench, b_reference_number:b_reference_number,bank_name:bank_name,beneficiary_name:beneficiary_name, cnic:cnic, sorting:sorting,form:form,direction:direction, qty:qty, ayis_page:ayis_page, _token: '{{csrf_token()}}'},
          url: "{{ route('overall_fetch_report_data') }}",
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
	
    $("#b_reference_number, #beneficiary_name, #cnic, #bank_name").on('keyup keydown', function() {
		filter_data();
    });
    
    

    $('body').on('change', '#sorting, #direction, #qty, #district, #tehsil_id,#role,#user, #uc_id, #trench, #form', function(e){
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