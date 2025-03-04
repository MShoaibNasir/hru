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
                <h6 class="mb-0">Survey Form List</h6>

          
            </div>
        
                      
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0" id=  {{Auth::user()->role!=37 ? 'myTable' : ''}} >
                    <thead>
                        <!--for admin role-->
                        @if(Auth::user()->role==1 || Auth::user()->role==51)
                        <tr class="text-dark">
                            <th scope="col">Ref No</th>
                            <th scope="col">Beneficiary Name</th>
                            <th scope="col">CNIC</th>
                            <th scope="col">Father Name</th>
                            @if(Auth::user()->role==1)
                            <th scope="col">User Name</th>
                            @endif
                            <th scope="col">Form Name</th>
                            <th scope="col">Lot</th>
                            <th scope="col">District</th>
                            <th scope="col">Tehsil</th>
                            <th scope="col">UC</th>
                            @if(Auth::user()->role==51)
                            <th scope="col">Role Name</th>
                            <th scope="col">Select Status</th>
                            <th scope="col">Status</th>
                            <th scope="col">Comments</th>
                            @endif
                            <th scope="col">id</th>
                             <th scope="col">Date</th>
                            <th scope="col">Actions</th>
                            
                        </tr>
                        @else
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
                           
                           
                            @if(Auth::user()->role==39 || $allow_to_update_form->allow_to_update_form=='COO')
                            <th scope="col">CEO Status</th>
                            <th scope="col">CEO Comment</th>
                            
                            @endif
                             @if(Auth::user()->role==37 || $allow_to_update_form->allow_to_update_form=='PSIA')
                            <th scope="col">HRU Main Status</th>
                            <th scope="col">HRU Main Comment</th>
                            @endif
                          
                            @if(Auth::user()->role==38 || $allow_to_update_form->allow_to_update_form=='HRU_Main')
                            <th scope="col">COO Status</th>
                            <th scope="col">COO Comment</th>
                            @endif
                            @if(Auth::user()->role==26 || $allow_to_update_form->allow_to_update_form=='HRU')
                            <th scope="col">QA Status</th>
                            <th scope="col">QA Comment</th>
                            @endif
                            @if(Auth::user()->role==34 || $allow_to_update_form->allow_to_update_form=='IP')
                            <th scope="col">HRU Status</th>
                            <th scope="col">HRU Comment</th>
                            @endif
                            @if(Auth::user()->role==30 || $allow_to_update_form->allow_to_update_form=='field_supervisor')
                            <th scope="col">IP Status</th>
                            <th scope="col">IP Comment</th>
                            @endif
                            <th scope="col">Id</th>
                            <th scope="col">Date</th>
                            <th scope="col">Actions</th>
                        </tr>
                        @endif
                    </thead>
                    <tbody>
                        @if(Auth::user()->role==1)
                      
                        
                        @foreach($survey_data as $item)
                        @php
                        $beneficairy_details=json_decode($item->beneficiary_details);
                        @endphp
                            <tr style='background-color: {{$item->priority==1 ? '#19875433' : 'transparent'}}';>
                                
                                <td>{{$beneficairy_details->b_reference_number}}</td>
                                <td>{{$beneficairy_details->beneficiary_name}}</td>
                                <td>{{$beneficairy_details->cnic}}</td>
                                <td>{{$beneficairy_details->father_name}}</td>
                                <td>{{$item->user_name}}</td>
                                <td>{{$item->form_name}}</td>
                                @if(Auth::user()->role==51)
                                @php
                                $role_name=\DB::table('users')->join('roles','users.role','=','roles.id')->select('roles.name')
                                ->where('users.id',$item->user_id)->first();
                                @endphp
                                <td>{{$role_name->name ?? 'N/A'}}</td>
                                <td>
                                <input type='hidden' value='{{$allow_to_update_form->allow_to_update_form}}' id='team_member_status'>    
                                 <Select class='form-control form_status'>
                                @if($item->form_status=='A' || $item->form_status=='R')
                                <option selected> {{$item->form_status=='A' ? 'Approved' : 'Reject'}}  </option>
                                @else
                                <option value=''>Select Status</option>
                                <option value='A'>Approved</option>
                                <option value='R'>Reject</option>
                                <option value='P'>Pending</option>
                                @endif
                                </select>
                               <input type='hidden' value='{{$item->survey_form_id}}' class='survey_form_id'>
                                </td>
                                @endif
                                <td>{{$item->lot_name}}</td>
                                <td>{{$item->district_name}}</td>
                                <td>{{$item->tehsil_name}}</td>
                                <td>{{$item->uc_name}}</td>
                                <td>{{$item->generated_id}}</td>
                                <td>{{$item->submission_date}}</td>
                                <td><a href='{{route("beneficiaryProfile",[$item->survey_form_id])}}' class='btn btn-success'>View Detail</a></td>
                            </tr>
                        @endforeach
                            
                            
                        @elseif(Auth::user()->role==51)
                           @include('dashboard.survey.pending.m&e')
                        @elseif(Auth::user()->role==30 || $allow_to_update_form->allow_to_update_form=='field_supervisor')
                           @include('dashboard.survey.pending.field_supervisor')
                        @elseif(Auth::user()->role==34 || $allow_to_update_form->allow_to_update_form=='IP') 
                           @include('dashboard.survey.pending.IP')
                        @elseif(Auth::user()->role==26 || $allow_to_update_form->allow_to_update_form=='HRU')
                           @include('dashboard.survey.pending.HRU')
                        @elseif(Auth::user()->role==37 || $allow_to_update_form->allow_to_update_form=='PSIA')
                           @include('dashboard.survey.pending.PSIA')
                        @elseif(Auth::user()->role==38 || $allow_to_update_form->allow_to_update_form=='HRU_Main')
                           @include('dashboard.survey.pending.HRUMAIN')
                        @elseif(Auth::user()->role==39 || $allow_to_update_form->allow_to_update_form=='COO')
                           @include('dashboard.survey.pending.COO')
                        @elseif(Auth::user()->role==40 || $allow_to_update_form->allow_to_update_form=='CEO')
                           @include('dashboard.survey.pending.CEO')
                        @endif
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