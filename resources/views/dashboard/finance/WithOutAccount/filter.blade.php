@extends('dashboard.layout.master')
@section('content')


<style>
input.form-control.btn.btn-success.my-4 {
    margin-left: 14px;
}
.hover-btn:hover{
    color:white !important;
}
input#generate_sheet {
    width: 146px;
    margin-bottom: 18px;
}


.download_btn {
    width: 100%;
    display: flex;
    justify-content: end;
}
    
.csv_button {
text-align: justify;
}
a.btn.btn-success {
    width: 143px;
    height: auto;
    margin-top: 25px;
}
#confirm_list {
    width: 200px;
    margin-left: 25px;
}
.bank_heading{
    display:flex;
}
form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.search_btn{
    width:100px;
}
.buttons {
    display: flex;
    gap:4px;
}
input.btn.btn-danger {
    width: 280px;
}
</style>


<div class="content">
    <!-- Navbar Start -->
     @include('dashboard.layout.navbar')
    <!-- Navbar End -->
    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Approved Beneficries WithOut Account List</h6>
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
                                                      <label for="Search">Search By Prefered Bank</label>
                                                       <select class='form-control js-example-basic-multiple' id="bank_name" multiple="multiple">
                                                           <option value='null'>Select Bank</option>
                                                           @foreach($bank_names as $data)
                                                           <option value='{{$data}}'>{{$data}}</option>
                                                           @endforeach
                                                       </select>
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
                                                      {{ Form::select('qty', [10=>10, 25=>25, 50=>50, 100=>100, 500=>500, 1000=>1000,10000=>10000], 10,['class'=>'form-control', 'id'=>'qty']) }}
                                                    </div>
                                                    	<div class="filters-toolbar__item mb-3 col-md-4">
                										<label for="Search">From</label>
                										<input type='date' name='from' id='from' class='form-control'>
                										</div>
                                                    	<div class="filters-toolbar__item mb-3 col-md-4">
                										<label for="Search">To</label>
                										<input type='date' name='to' id='to' class='form-control'>
                										</div>
                                                    
                
                                                </div>
                                            </div>
                                        </div>

                                    <form method='post' action='{{route("get_export_without_account_data")}}'>
                                        @csrf
                                    <div class='col-6 my-4'>
                                            <h5 class='bank_heading'>Select Bank</h5>
                                            <select class='form-control' id='select_bank_name' name='select_bank_name'>
                                                <option value=''>Select Bank</option>
                                                @foreach($bank as  $item)
                                                    <option value='{{ $item->id }}'>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    <input type='hidden' id='ref_no_data' name='ref_no_data'>
                                    <input type='submit' value='Generate Sheet' id="generate_sheet" class='btn btn-success'>
                                    </form>
                                    </div>
    
                                    <div class='select_all_button' style="text-align:justify;">
                                    <button id="checkAllButton" class='btn btn-danger'>Select all</button>
                                    <button id="deCheckAllButton" class='btn btn-danger'>Deselect all</button>
                                   </div>
                               
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>

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
 $('.js-example-basic-multiple').select2();
    
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
        var to = $("#to").val();
        var from = $("#from").val();
        var qty = $("#qty").val();
   
        
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
          data:{action:action, district:district, tehsil_id:tehsil_id, uc_id:uc_id, b_reference_number:b_reference_number,bank_name:bank_name,beneficiary_name:beneficiary_name, cnic:cnic, sorting:sorting, direction:direction,to:to,from:from, qty:qty, ayis_page:ayis_page, _token: '{{csrf_token()}}'},
          url: "{{ route('WithOutAccountFetchData') }}",
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
    
    

    $('body').on('change', '#sorting, #direction, #to,#qty,#from, #district, #tehsil_id, #uc_id,#bank_name', function(e){
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
 function toArray(csvString) {
    return csvString.split(',').map(item => item.trim());
    }

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
    
        });

           
      
 
       

</script>
@endpush