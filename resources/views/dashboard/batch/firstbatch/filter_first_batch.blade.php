@extends('dashboard.layout.master')
@section('content')
<style>
input.form-control.btn.btn-success.my-4 {
    margin-left: 14px;
}
.hover-btn:hover{
    color:white !important;
}
</style>


<div class="content">
    <!-- Navbar Start -->
     @include('dashboard.layout.navbar')
    <!-- Navbar End -->
    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Tranche LIST</h6>
            </div>
            
            <div class="row">

            <div class="col-md-12">
                <div class="pdmadatalist">
                                  <!--Toolbar-->
                                    	<div class="toolbar">
                                        	<div class="filters-toolbar-wrapper">
                                        	    
                                        	    <div class="row">
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
                										<label for="Search">Search By Refrence Number</label>
                										{{ Form::text('b_reference_number',null,['class'=>'form-control', 'id'=>'b_reference_number','placeholder'=>'Refrence Number']) }}
                										</div>
                										
                										<div class="filters-toolbar__item mb-3 col-md-4">
                										<label for="Search">Search By Beneficiary Name</label>
                										{{ Form::text('beneficiary_name',null,['class'=>'form-control', 'id'=>'beneficiary_name','placeholder'=>'Beneficiary Name']) }}
                										</div>
                									
                										<div class="filters-toolbar__item mb-3 col-md-4">
                                                      <label for="Search">Search By CNIC</label>
                                                       {{ Form::text('cnic',null,['class'=>'form-control', 'id'=>'cnic','placeholder'=>'CNIC']) }}
                                                    </div>
                										<div class="filters-toolbar__item mb-3 col-md-4">
                                                      <label for="Search">Search By Bank Name</label>
                                                       {{ Form::text('bank_name',null,['class'=>'form-control', 'id'=>'bank_name','placeholder'=>'Bank Name']) }}
                                                    </div>
                                                    
                                                    
                                                    <div class="filters-toolbar__item mb-3 col-md-4">
                                                      <label for="Direction">Seach By Tranche</label>
                                                      {{ Form::select('trench',['1' => 'First Tranche', '2' => 'Second Tranche' ,'3' => 'Third Tranche'],1,['class'=>'form-control', 'id'=>'trench']) }}
                                                    </div>
                									
                                                    </div>
                
                <div class="row">										
                										
                									<div class="filters-toolbar__item mb-3 col-md-4">
                                                      <label for="Sorting">Sort By</label>
                                                      {{ Form::select('sorting',[ 'id' => 'ID', 'b_reference_number' => 'Refrence Number', 'beneficiary_name' => 'Beneficiary Name'],'id',['class'=>'form-control', 'id'=>'sorting']) }}
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
                                        <!--End Toolbar-->
                                    <!--form start-->
                                    <form method='post' action='{{route("add_first_batch")}}'>
                                        @csrf
                                        <div class='row'>
                                            <h4>Add Batch Data</h4>
                                        </div>
                                        <div class='row'>
                                            <div class='col-6'>
                                                <label>Batch No</label>
                                                <input type='text' name='batch_no' required  class='form-control'>
                                            </div>
                                            <div class='col-6'>
                                                <label>Cheque No</label>
                                                <input type='text' name='cheque_no' class='form-control'>
                                            </div>
                                            <div class='col-6'>
                                                <label>Date</label>
                                                <input type='date' name='batch_created_date' required class='form-control'>
                                            </div>
                                            <div class='col-6'>
                                                <label>Select Bank</label>
                                                <select class='form-control' required name='bank_id'>
                                                    @foreach($bank as $key=>$item)
                                                    <option value='{{$key}}'>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <input type='hidden' name='ref_no' id='ref_no_data' >
                                            <input type='hidden' value='1' id='trench_no' name='trench_no'>
                                            <input type='submit' value='Submit'  class='form-control hover-btn btn btn-success my-4' style='width:200px; color:black;' >
                                        </div>
                                    </form>
                                    <p id='show_selected_count'>Selected Data Count: 0</p>
                                    <div class='select_all_button' style="text-align:justify;">
                                    <button id="checkAllButton" class='btn btn-danger'>Select all</button>
                                    <button id="deCheckAllButton" class='btn btn-danger'>Deselect all</button>
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
        var direction = $("#direction").val();
        var qty = $("#qty").val();
        var trench = $("#trench").val();
   
        
        var district = $("#district").val();
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
          data:{action:action, district:district, tehsil_id:tehsil_id, uc_id:uc_id,trench:trench, b_reference_number:b_reference_number,bank_name:bank_name,beneficiary_name:beneficiary_name, cnic:cnic, sorting:sorting, direction:direction, qty:qty, ayis_page:ayis_page, _token: '{{csrf_token()}}'},
          url: "{{ route('firsttrench_fetch_data') }}",
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
    
    

    $('body').on('change', '#sorting, #direction, #qty, #district, #tehsil_id, #uc_id, #trench', function(e){
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


        $(document).ready(function () {
       
            var selectedValues = [];
            
            
        $(document).on('change', '.get_id', function() {
            var selectedValues=$('#ref_no_data').val();  
            selectedValues= toArray(selectedValues);
            var value = $(this).val();
              
               if ($(this).is(':checked')) {
                    selectedValues.push(value);
                } else {
                    selectedValues = $.grep(selectedValues, function (item) {
                        return item !== value;
                    });
                }
                let count = Math.max(0, selectedValues.length - 1);
                $('#show_selected_count').text(`Selected Data Count: ${count}`);
                $('#ref_no_data').val(selectedValues.join(','));
                var token = $('meta[name="csrf-token"]').attr("content");
                $.ajax({
                        type: "POST",
                        url: `{{ route('add_ref_session') }}`,
                        data: {
                            selectedValues: selectedValues,
                            _token: token,
                        },
                        success: function (response) {
                        
                          
                           
                        },
                        error: function (request, status, error) {
                            console.log(error);
                            alert("Couldn't retrieve lots. Please try again later.");
                        },
                    });
    });
        $(document).on('click', '#checkAllButton', function () {
            var selectedValues=$('#ref_no_data').val();  
            selectedValues= toArray(selectedValues);
        $('.get_id').each(function () {
            $(this).prop('checked', true); // Check all checkboxes
            var value = $(this).val();
            if (!selectedValues.includes(value)) {
                selectedValues.push(value);
            }
        });
        let count = Math.max(0, selectedValues.length - 1);
        $('#show_selected_count').text(`Selected Data Count: ${count}`);
        $('#ref_no_data').val(selectedValues.join(','));
     
        var token = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            type: "POST",
            url: `{{ route('add_ref_session') }}`,
            data: {
                selectedValues: selectedValues,
                _token: token,
            },
            success: function (response) {
                console.log(response);
            },
            error: function (request, status, error) {
                console.error(error);
                alert("Couldn't retrieve lots. Please try again later.");
            },
        });
    });
        $(document).on('click', '#deCheckAllButton', function () {
         
            $('.get_id').each(function () {
                $(this).prop('checked', false); // Check all checkboxes
            });
            let count = 0;
            $('#show_selected_count').text(`Selected Data Count: ${count}`);
            $('#ref_no_data').val('');
            var token = $('meta[name="csrf-token"]').attr("content");
            selectedValues=[];
            $.ajax({
                type: "POST",
                url: `{{ route('add_ref_session') }}`,
                data: {
                    selectedValues: selectedValues,
                    _token: token,
                },
                success: function (response) {
                    console.log(response);
                },
                error: function (request, status, error) {
                    console.error(error);
                    alert("Couldn't retrieve lots. Please try again later.");
                },
            });
             
            
            });
            
            
            $(document).on('change', '#trench', function () {
            var trench=$(this).val();
            $('#trench_no').val(trench);
            $('#ref_no_data').val('');
            });
    
    
    
        });

           
      
 
       

</script>
@endpush