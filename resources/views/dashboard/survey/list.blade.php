@extends('dashboard.layout.master')
@section('content')
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
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="">
                    <thead>
                        <!--for admin role-->
                        @if(Auth::user()->role==1)
                        <tr class="text-dark">
                            <th scope="col">S no</th>
                            <th scope="col">User Name</th>
                            <th scope="col">Form Name</th>
                            <th scope="col">View beneficiary details</th>
                            <th scope="col">Actions</th>
                        </tr>
                        @else
                        <tr class="text-dark">
                            <th scope="col">S no</th>
                            <th scope="col">User Name</th>
                            <th scope="col">Form Name</th>
                            <th scope="col">View beneficiary details</th>
                            <th scope="col">Status</th>
                            @if(Auth::user()->role==37 || $allow_to_update_form->allow_to_update_form=='PSIA')
                              <th scope="col">HRU Main Status</th>
                            <th scope="col">HRU Main Comment</th>
                            <th scope="col">Certification Status</th>
                            @endif
                            @if(Auth::user()->role==39 || $allow_to_update_form->allow_to_update_form=='COO')
                            <th scope="col">CEO Status</th>
                            <th scope="col">CEO Comment</th>
                            <th scope="col">Click for proirity</th>
                            @endif
                            @if(Auth::user()->role==38 || $allow_to_update_form->allow_to_update_form=='HRU_Main')
                            <th scope="col">COO Status</th>
                            <th scope="col">COO Comment</th>
                            @endif
                            @if(Auth::user()->role==26 || $allow_to_update_form->allow_to_update_form=='HRU')
                            <th scope="col">PSIA Status</th>
                            <th scope="col">PSIA Comment</th>
                            @endif
                            @if(Auth::user()->role==34 || $allow_to_update_form->allow_to_update_form=='IP')
                            <th scope="col">HRU Status</th>
                            <th scope="col">HRU Comment</th>
                            @endif
                            @if(Auth::user()->role==30 || $allow_to_update_form->allow_to_update_form=='field_supervisor')
                            <th scope="col">IP Status</th>
                            <th scope="col">IP Comment</th>
                            @endif
                            <th scope="col">Actions</th>
                        </tr>
                        @endif
                    </thead>
                    <tbody>
                       
                        
                        @if(Auth::user()->role==1)
                        @foreach($survey_data as $item)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$item->user_name}}</td>
                                <td>{{$item->form_name}}</td>
                                <td><button class='btn btn-secondary' data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="view_beneficiary({{$item->survey_form_id}})">View Details</button></td>
                                <td><a href='{{route("survey.view",[$item->survey_form_id])}}' class='btn btn-success'>View Detail</a></td>
                            </tr>
                        @endforeach
                        {{--for Field Supervisor role --}}
                        @elseif(Auth::user()->role==30 || $allow_to_update_form->allow_to_update_form=='field_supervisor')
                             
                            @foreach($survey_data as $item)
                            @php
                            $authenticate_user_uc=json_decode(Auth::user()->uc_id);
                            $form_status=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','field supervisor')->first();
                            $senior_form_status=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','IP')->first();
                            @endphp
                            @if(in_array($item->uc_id,$authenticate_user_uc))
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$item->user_name}}</td>
                                <td>{{$item->form_name}}</td>
                                <td><button class='btn btn-secondary' data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="view_beneficiary({{$item->survey_form_id}})">View Details</button></td>
                                <td>
                                <input type='hidden' value='{{$allow_to_update_form->allow_to_update_form}}' id='team_member_status'>    
                                <Select class='form-control form_status'>
                                <option value=''>Select Status</option>  
                                @if(@isset($form_status) && ($form_status->user_status=='30' || $form_status->team_member_status=='field_supervisor'))
                                <option value='A' {{$form_status->form_status=='A' ? 'selected' : ''}}>Approved</option>
                                @if(!($form_status->form_status=='A'))
                                <option value='R' {{$form_status->form_status=='R' ? 'selected' : ''}}>Reject</option>
                                <option value='P' {{$form_status->form_status=='P' ? 'selected' : ''}}>Pending</option>
                                @endif
                                @else
                                <option value='A'>Approved</option>
                                <option value='R'>Reject</option>
                                <option value='P'>Pending</option>
                                @endif
                                </select>
                                
                                  <td><span class='badge text-bg-danger'>{{$senior_form_status ? ($form_status_COO->form_status == 'A' ? 'approved' : ($form_status_COO->form_status == 'R' ? 'rejected' : 'pending')) : 'Not Available' }}</span></td>
                                <td>
                                <button class='btn btn-info' onclick='showComment("{{ $form_status_COO->comment ?? 'not available' }}")'>Show Comment</button>
                                </td>
                                <input type='hidden' value='{{$item->survey_form_id}}' class='survey_form_id'>
                                </td>
                                <td><a href='{{route("survey.view",[$item->survey_form_id])}}' class='btn btn-success'>View Detail</a></td>
                            </tr>
                            @endif
                        @endforeach
                        
                        
                        @elseif(Auth::user()->role==34 || $allow_to_update_form->allow_to_update_form=='IP')
                          
                            @foreach($survey_data as $item)
                            @php
                            $authenticate_user_uc=json_decode(Auth::user()->uc_id);
                            $form_status=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','IP')->first();
                            $form_status_COO=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','HRU')->first();
                            @endphp
                            @if(in_array($item->uc_id,$authenticate_user_uc) && ($item->user_status=='30' || $item->team_member_status=='field_supervisor'))
                            <tr>
                                
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$item->user_name}}</td>
                                <td>{{$item->form_name}}</td>
                                <input type='hidden' value='{{$item->survey_form_id}}'>
                                <td><button class='btn btn-secondary' data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="view_beneficiary({{$item->survey_form_id}})">View Details</button></td>
                                <td>
                                <input type='hidden' value='{{$allow_to_update_form->allow_to_update_form}}' id='team_member_status'>    
                                <Select class='form-control form_status'>
                                <option value=''>Select Status</option>  
                                @if(@isset($form_status) && ($form_status->user_status=='34' || $form_status->team_member_status=='IP'))
                                <option value='A' {{$form_status->form_status=='A' ? 'selected' : ''}}>Approved</option>
                                @if(!($form_status->form_status=='A'))
                                <option value='R' {{$form_status->form_status=='R' ? 'selected' : ''}}>Reject</option>
                                <option value='P' {{$form_status->form_status=='P' ? 'selected' : ''}}>Pending</option>
                                @endif
                                @else
                                <option value='A'>Approved</option>
                                <option value='R'>Reject</option>
                                <option value='P'>Pending</option>
                                @endif
                                </select>
                                 <td><span class='badge text-bg-danger'>{{ isset($form_status_COO) ? ($form_status_COO->form_status == 'A' ? 'approved' : ($form_status_COO->form_status == 'R' ? 'rejected' : 'pending')): 'Not Available' }} </span></td>
                                <td>
                                <button class='btn btn-info' onclick='showComment("{{ $form_status_COO->comment ?? 'not available' }}")'>Show Comment</button>
                                </td>
                                <input type='hidden' value='{{$item->survey_form_id}}' class='survey_form_id'>
                                </td>
                                <td><a href='{{route("survey.view",[$item->survey_form_id])}}' class='btn btn-success'>View Detail</a></td>
                            </tr>
                            @endif
                        @endforeach
                        
                        
                        @elseif(Auth::user()->role==26 || $allow_to_update_form->allow_to_update_form=='HRU')
                          
                            @foreach($survey_data as $item)
                            @php
                            $authenticate_user_uc=json_decode(Auth::user()->uc_id);
                            $form_status=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','HRU')->first();
                            $form_status_COO=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','PSIA')->first();
                            @endphp
                            @if(in_array($item->uc_id,$authenticate_user_uc) && ($item->user_status=='34' || $item->team_member_status=='IP'))
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$item->user_name}}</td>
                                <td>{{$item->form_name}}</td>
                                 <td><button class='btn btn-secondary' data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="view_beneficiary({{$item->survey_form_id}})">View Details</button></td>

                                <td>
                                <input type='hidden' value='{{$allow_to_update_form->allow_to_update_form}}' id='team_member_status'>    
                                <Select class='form-control form_status'>
                                <option value=''>Select Status</option>  
                                @if(@isset($form_status) && ($form_status->user_status=='26' || $form_status->team_member_status=='HRU'))
                                <option value='A' {{$form_status->form_status=='A' ? 'selected' : ''}}>Approved</option>
                                @if(!($form_status->form_status=='A'))
                                <option value='R' {{$form_status->form_status=='R' ? 'selected' : ''}}>Reject</option>
                                <option value='P' {{$form_status->form_status=='P' ? 'selected' : ''}}>Pending</option>
                                @endif
                                @else
                                <option value='A'>Approved</option>
                                <option value='R'>Reject</option>
                                <option value='P'>Pending</option>
                                @endif
                                </select>
                                 <td><span class='badge text-bg-danger'>{{$form_status_COO ? ($form_status_COO->form_status == 'A' ? 'approved' : ($form_status_COO->form_status == 'R' ? 'rejected' : 'pending')):'Not Availbale' }}</span></td>
                                <td>
                                <button class='btn btn-info' onclick='showComment("{{ $form_status_COO->comment ?? 'not available' }}")'>Show Comment</button>
                                </td>
                                <input type='hidden' value='{{$item->survey_form_id}}' class='survey_form_id'>
                                </td>
                                <td><a href='{{route("survey.view",[$item->survey_form_id])}}' class='btn btn-success'>View Detail</a></td>
                            </tr>
                            @endif
                        @endforeach
                        @elseif(Auth::user()->role==37 || $allow_to_update_form->allow_to_update_form=='PSIA')
                           
                            @foreach($survey_data as $item)
                           
                            @php
                            $authenticate_user_uc=json_decode(Auth::user()->uc_id);
                            $form_status=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','PSIA')->first();
                            $form_status_COO=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','HRU_Main')->first();
                            
                            dd($form_status_COO);
                            @endphp
                           
                            @if(in_array($item->uc_id,$authenticate_user_uc) && ($item->user_status=='26' || $item->team_member_status=='HRU'))
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$item->user_name}}</td>
                                <td>{{$item->form_name}}</td>
                                <td><button class='btn btn-secondary' data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="view_beneficiary({{$item->survey_form_id}})">View Details</button></td>
                                <td>
                                <input type='hidden' value='{{$allow_to_update_form->allow_to_update_form}}' id='team_member_status'>    
                                <Select class='form-control form_status'>
                                <option value=''>Select Status</option>  
                                @if(@isset($form_status) && ($form_status->user_status=='37' || $form_status->team_member_status=='PSIA'))
                                <option value='A' {{$form_status->form_status=='A' ? 'selected' : ''}}>Approved</option>
                                @if(!($form_status->form_status=='A'))
                                <option value='R' {{$form_status->form_status=='R' ? 'selected' : ''}}>Reject</option>
                                <option value='P' {{$form_status->form_status=='P' ? 'selected' : ''}}>Pending</option>
                                @endif
                                @else
                                <option value='A'>Approved</option>
                                <option value='R'>Reject</option>
                                <option value='P'>Pending</option>
                                @endif
                                </select>
                                
                                 <td><span class='badge text-bg-danger'>{{ isset($form_status_COO) ? ($form_status_COO->form_status == 'A' ? 'approved' : ($form_status_COO->form_status == 'R' ? 'rejected' : ($form_status_CEO->form_status == 'H' ? 'holding' : 'pending'))): 'not availbale' }}</span></td>                                
                                 <td>
                                <button class='btn btn-info' onclick='showComment("{{ $form_status_COO->comment ?? 'not available' }}")'>Show Comment</button>
                                </td>
                                <input type='hidden' value='{{$item->survey_form_id}}' class='survey_form_id'>
                                </td>
                                @if($item->certification==1)
                                <td style='text-align:center;'><a class='btn btn-success' href='{{route("update_certified",[$item->form_status_id])}}' style='width:100%;'>Certified</a></td>
                                @else
                                <td style='text-align:center;'><a class='btn btn-danger' href='{{route("update_certified",[$item->form_status_id])}}' style='width:100%;'>Non Certified</a></td>
                                @endif
                                <td><a href='{{route("survey.view",[$item->survey_form_id])}}' class='btn btn-success'>View Detail</a></td>
                            </tr>
                            @endif
                        @endforeach
                        
                        @elseif(Auth::user()->role==38 || $allow_to_update_form->allow_to_update_form=='HRU_Main')
                            @foreach($survey_data as $item)
                            @php
                           
                            $authenticate_user_uc=json_decode(Auth::user()->uc_id);
                            $form_status=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','HRU_Main')->first();
                            $form_status_COO=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','COO')->first();
                            @endphp
                            @if(in_array($item->uc_id,$authenticate_user_uc) && ($item->user_status=='37' || $item->team_member_status=='PSIA'))
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$item->user_name}}</td>
                                <td>{{$item->form_name}}</td>
                                <td><button class='btn btn-secondary' data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="view_beneficiary({{$item->survey_form_id}})">View Details</button></td>
                                <td>
                                <input type='hidden' value='{{$allow_to_update_form->allow_to_update_form}}' id='team_member_status'>    
                                <Select class='form-control form_status'>
                                <option value=''>Select Status</option>  
                                <!--if user role verify  that user update the status of that form in the past and this role  has the right to change the status -->
                                @if(@isset($form_status) && ($form_status->user_status=='38' || $form_status->team_member_status=='HRU_Main'))
                                <option value='A' {{$form_status->form_status=='A' ? 'selected' : ''}}>Approved</option>
                                @if(!($form_status->form_status=='A'))
                                <option value='R' {{$form_status->form_status=='R' ? 'selected' : ''}}>Reject</option>
                                <option value='P' {{$form_status->form_status=='P' ? 'selected' : ''}}>Pending</option>
                                @endif
                                @else
                                <option value='A'>Approved</option>
                                <option value='R'>Reject</option>
                                <option value='P'>Pending</option>
                                @endif
                                </select>
                                <td>
                                    <span class='badge text-bg-danger'>{{ isset($form_status_COO) ? ($form_status_COO->form_status == 'A' ? 'approved' : ($form_status_COO->form_status == 'R' ? 'rejected' : ($form_status_CEO->form_status == 'H' ? 'holding' : 'pending'))): 'not availbale' }}</span></td>
                                <td>
                                <button class='btn btn-info' onclick='showComment("{{ $form_status_COO->comment ?? 'not available' }}")'>Show Comment</button>
                                </td>
                                <input type='hidden' value='{{$item->survey_form_id}}' class='survey_form_id'>
                                </td>
                                <td><a href='{{route("survey.view",[$item->survey_form_id])}}' class='btn btn-success'>View Detail</a></td>
                            </tr>
                            @endif
                        @endforeach
                        @elseif(Auth::user()->role==39 || $allow_to_update_form->allow_to_update_form=='COO')
                       
                            @foreach($survey_data as $item)
                            @php
                            
                            $authenticate_user_uc=json_decode(Auth::user()->uc_id);
                            $form_status=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','COO')->first();

                            $form_status_CEO=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','CEO')->first();
                            @endphp
                            @if($item->user_status=='38' || $item->team_member_status=='HRU_Main')
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <input type='hidden' value='{{$item->survey_form_id}}'>
                                <td>{{$item->user_name}}</td>
                                <td>{{$item->form_name}}</td>
                                <td><button class='btn btn-secondary' data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="view_beneficiary({{$item->survey_form_id}})">View Details</button></td>
                                <td>
                                <input type='hidden' value='{{$allow_to_update_form->allow_to_update_form}}' id='team_member_status'>    
                                <Select class='form-control form_status'>
                                <option value=''>Select Status</option>  
                                @if(@isset($form_status) && ($form_status->user_status=='39' || $form_status->team_member_status=='COO'))                          
                                <option value='A' {{$form_status->form_status=='A' ? 'selected' : ''}}>Approved</option>
                                @if(!($form_status->form_status=='A'))
                                <option value='R' {{$form_status->form_status=='R' ? 'selected' : ''}}>Reject</option>
                                <option value='P' {{$form_status->form_status=='P' ? 'selected' : ''}}>Pending</option>
                                @endif
                                @else
                                <option value='A'>Approved</option>
                                <option value='R'>Reject</option>
                                <option value='P'>Pending</option>
                                @endif
                                </select>
                               
                                <td><span class='badge text-bg-danger'>{{$form_status_CEO ? ($form_status_CEO->form_status == 'A' ? 'approved' : ($form_status_CEO->form_status == 'R' ? 'rejected' :($form_status_CEO->form_status == 'H' ? 'holding') :'pending')):'not available' }}</span></td>
                                <td>
                                <button class='btn btn-info' onclick='showComment("{{ $form_status_CEO->comment ?? 'not available' }}")'>Show Comment</button>
                                </td>
                                <td>
                                <button class='btn btn-warning' onclick='Add_to_priority({{$item->survey_form_id}})'>Add to Priority</button>
                                
                                </td>
                                <input type='hidden' value='{{$item->survey_form_id}}' class='survey_form_id'>
                                </td>
                             
                                <td><a href='{{route("survey.view",[$item->survey_form_id])}}' class='btn btn-success'>View Detail</a></td>
                            </tr>
                            @endif
                        @endforeach
                        @elseif(Auth::user()->role==40 || $allow_to_update_form->allow_to_update_form=='CEO')
                            @foreach($survey_data as $item)
                            @php
                            $authenticate_user_uc=json_decode(Auth::user()->uc_id);
                            $form_status=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','CEO')->first();
                            @endphp
                            @if($item->user_status=='39' || $item->team_member_status=='COO')
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <input type='hidden' value='{{$item->survey_form_id}}'>
                                <td>{{$item->user_name}}</td>
                                <td>{{$item->form_name}}</td>
                                <td><button class='btn btn-secondary' data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="view_beneficiary({{$item->survey_form_id}})">View Details</button></td>
                                <td>
                                <input type='hidden' value='{{$allow_to_update_form->allow_to_update_form}}' id='team_member_status'>    
                                <Select class='form-control form_status'>
                                <option value=''>Select Status</option>  
                                @if(@isset($form_status) && ($form_status->user_status=='40' || $form_status->team_member_status=='CEO'))
                                <option value='A' {{$form_status->form_status=='A' ? 'selected' : ''}}>Approved</option>
                                <option value='R' {{$form_status->form_status=='R' ? 'selected' : ''}}>Reject</option>
                                <option value='P' {{$form_status->form_status=='P' ? 'selected' : ''}}>Pending</option>
                                @else
                                <option value='A'>Approved</option>
                                <option value='R'>Reject</option>
                                <option value='P'>Pending</option>
                                @endif
                                </select>
                                <input type='hidden' value='{{$item->survey_form_id}}' class='survey_form_id'>
                                </td>
                             
                                <td><a href='{{route("survey.view",[$item->survey_form_id])}}' class='btn btn-success'>View Detail</a></td>
                            </tr>
                            @endif
                        @endforeach
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