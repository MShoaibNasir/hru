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
</style>

<!-- Content Start -->
<div class="content">
   
   
    <!-- Navbar Start -->
    @include('dashboard.layout.navbar')
    <!-- Navbar End -->
 
    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Ineligible List</h6>

           
            </div>
                     
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0" id=  {{Auth::user()->role!=37 ? 'myTable' : ''}} >
                    <thead>
                      
                        <tr class="text-dark">
                            <th scope="col">Ref no</th>
                            <th scope="col">Beneficiary Name</th>
                            <th scope="col">CNIC</th>
                            <th scope="col">Father Name</th>
                            <th scope="col">Form Name</th>
                            <th scope="col">Lot</th>
                            <th scope="col">District</th>
                            <th scope="col">Tehsil</th>
                            <th scope="col">UC</th>
                            @if(Auth::user()->role==38 || $allow_to_update_form->allow_to_update_form=='HRU_Main')
                            <th scope="col">COO Status</th>
                            <th scope="col">COO Comment</th>
                            @endif
                            <th scope="col">Id</th>
                            <th scope="col">Date</th>
                            <th scope="col">Actions</th>
                        </tr>
                      
                    </thead>
                    <tbody>
                     
                      
                        
                        @foreach($survey_data as $item)
                            @php
                            $beneficairy_details=json_decode($item->beneficiary_details);
                            $form_status=form_status($item->survey_form_id,'HRU_Main');
                            $seniorpost=form_status($item->survey_form_id,'COO');
                            $junior=form_status($item->survey_form_id,'field supervisor');
                            $show_data=true;
                            if($seniorpost){
                                if($seniorpost->form_status=='R'){
                                    $show_data=false;
                                }else if(isset($junior) && $junior->form_status=='A'){
                                    $show_data=true;
                                }
                            }
                             
                            @endphp
                    <tr style='background-color: {{$item->priority==1? '#19875433' : 'transparent'}}';>
                                
                                <td>{{$beneficairy_details->b_reference_number}}</td>
                                <td>{{$beneficairy_details->beneficiary_name}}</td>
                                <td>{{$beneficairy_details->cnic}}</td>
                                <td>{{$beneficairy_details->father_name}}</td>
                                <td>{{$item->form_name}}</td>
                                <td>{{$item->lot_name}}</td>
                                <td>{{$item->district_name}}</td>
                                <td>{{$item->tehsil_name}}</td>
                                <td>{{$item->uc_name}}</td>
                                
                                <td>
                                    <span class='badge text-bg-danger'>{{ isset($seniorpost) ? ($seniorpost->form_status == 'A' ? 'approved' : ($seniorpost->form_status == 'R' ? 'rejected' : 'pending')): 'not availbale' }}</span></td>
                                <td>
                                <button class='btn btn-info' onclick='showComment("{{ $seniorpost->comment ?? 'not available' }}")'>Show Comment</button>
                                </td>
                                <input type='hidden' value='{{$item->survey_form_id}}' class='survey_form_id'>
                                </td>
                                <td>{{$item->generated_id}}</td>
                                <td>{{$item->submission_date}}</td>
                                <td><a href='{{route("beneficiaryProfile",[$item->survey_form_id,TRUE])}}' class='btn btn-success'>View Detail</a></td>
                            </tr>
                            @endforeach
                            
                            
                    
                    </tbody>
                </table>
               
            </div>
        </div>
    </div>
    
    <!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Beneficiary Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <label>Beneficiary Name:-</label>
      <input  id='beneficiary_name' type='text' readonly class='form-control'>
      <label>Beneficiary Address:-</label>
      <input  id='beneficiary_address' type='text' readonly class='form-control'>
      <label>Beneficiary CNIC:-</label>
      <input  id='beneficiary_cnic' readonly type='text' class='form-control'>
      <label>Beneficiary refrence number :-</label>
      <input  id='beneficiary_refrence_number' type='text' readonly class='form-control'>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
     
      </div>
    </div>
  </div>
</div>

    
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="{{asset('dashboard\js\survey_list.js')}}"></script>
    <script src="{{asset('dashboard\js\ip_create.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
    <script>
        $(document).ready(function(){
             $('.js-example-basic-multiple').select2();
            $('#certified_list_btn').click(function(){
                $(this).css({backgroundColor: 'red',color:'white'});
                $("#pending_list_btn").css({ 'backgroundColor' : '', 'color' : '' });
                $("#non_certified_btn").css({ 'backgroundColor' : '', 'color' : '' });
                $(".pending_list").css({ 'display' : 'none' });
                $(".certified_list").css({ 'display' : '' });
                $(".un_certified_list").css({ 'display' : 'none' });
            })
            $('#pending_list_btn').click(function(){
                
                $(this).css({backgroundColor: 'red',color:'white'});
                $("#certified_list_btn").css({ 'backgroundColor' : '', 'color' : '' });
                $("#non_certified_btn").css({ 'backgroundColor' : '', 'color' : '' });
                $(".pending_list").css({ 'display' : '' });
                $(".certified_list").css({ 'display' : 'none' });
                $(".un_certified_list").css({ 'display' : 'none' });
            })
            $('#non_certified_btn').click(function(){
                $(this).css({backgroundColor: 'red',color:'white'});
                $("#pending_list_btn").css({ 'backgroundColor' : '', 'color' : '' });
                $("#certified_list_btn").css({ 'backgroundColor' : '', 'color' : '' });
                $(".pending_list").css({ 'display' : 'none' });
                $(".certified_list").css({ 'display' : 'none' });
                $(".un_certified_list").css({ 'display' : '' });
            })
        })
        
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