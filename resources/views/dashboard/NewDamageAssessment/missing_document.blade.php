@extends('dashboard.layout.master')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
select.form-control.form_status {
    width: 126px;
}
.badge {
    display: inline-block;
    padding: .35em .65em;
    font-size: .75em;
    font-weight: 700;
    line-height: 1;
    color: #fffdfd;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 5px;
    background: red;
}
.customBtn {
    width: 150px;
    border: 2px solid black;
    margin-left: 10px;
    white-space: nowrap;
}

.label {
    /*display: flex;*/
    font-weight: bolder;
}
.button_list {
    margin-left: 49px;
    margin-top: 16px;
    }
    .label {
    display: flex;
    }
   form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    text-align: start;
}
    .search_btn{
    width:100px;
    }
 
</style>



<!-- Content Start -->
<div class="content">
    
   
    <!-- Navbar Start -->
    @include('dashboard.layout.navbar')
    <!-- Navbar End -->

   
    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                @if(Route::is('missing_document_list'))
                <h6 class="mb-0">Missing Document List {{-- Auth::user()->role --}}</h6>
                @elseif(Route::is('missing_document_receive_list'))
                <h6 class="mb-0">Missing Document Receive List</h6>
                @endif

          
            </div>
    @php
     $lots= getTableData('lots');
    
    @endphp
            
             <div class='col-6 my-4'>
                 {{--
                <form method='get' action='{{route("missing_document_list")}}'>
                    @csrf
                <h5 class='bank_heading'>Search By Lot</h5>
                <select class='form-control' name='lot_id'>
                    <option value=null>Select Lot</option>
                    @foreach($lots as $item)
                    <option value='{{$item->id}}'>{{$item->name}}</option>
                    @endforeach
                </select>
                 <input type='submit' value='search'  class='btn btn-danger search_btn'>
                </form>
                --}}
            </div>
                     
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0"  >
                    <thead>
                        <!--for admin role-->
                        <tr class="text-dark">
                            <th scope="col">Survey Id</th>
                            <th scope="col">Date</th>
                            <th scope="col">Ref No</th>
                            <th scope="col">Status</th>
                            <th scope="col">Lot</th>
                            <th scope="col">Action</th>
                            <th scope="col">Department</th>
                            <th scope="col">Comment By</th>
                            <th scope="col">Comment</th>
                    </thead>
                    <tbody>
                      
                      
                        
                        @foreach($missing_documents as $item)
                        
                            <tr>
                                <td><a href='{{ route("beneficiaryProfile",[$item->survey_id]) }}' class='btn btn-success' target="_blank">View SID:{{ $item->survey_id }}</a></td>
                                <td>{{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                <td>{{ get_ref_no($item->survey_id) }}</td>
                                <td>{{ $item->status }}</td>
                                <td>Lot {{ $item->lot_id }}</td>
                                @if($item->status == 'P')
                                <td><a class="btn btn-sm btn-danger upload_mission_documents" comment_id="{{ $item->id }}" survey_id="{{ $item->survey_id }}" action="upload" href="javascript:void(0)">Upload Missing Document</a></td>
                                @else
                                <td>Document Uploaded</td>
                                @endif
                                <td>{{ $item->created_role }}</td>
                                <td>{{ get_user_name($item->created_by) }}</td>
                                <td>{{$item->comment}}</td>
                            </tr>
                        @endforeach
                            
                     
                    </tbody>
                </table>
                <div class="my-3">{{ $missing_documents->links() }}</div>
               
            </div>
        </div>
    </div>
    
    <!-- Button trigger modal -->
<x-frontend.survey.upload_missing_document_modal />
    
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="{{asset('dashboard\js\survey_list.js')}}"></script>
    <script src="{{asset('dashboard\js\ip_create.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>

    
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
    @endsection