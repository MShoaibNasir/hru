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
                        <tr class="text-dark">
                            <th scope="col">Ref No</th>
                            <th scope="col">Beneficiary Name</th>
                            <th scope="col">CNIC</th>
                            <th scope="col">Father Name</th>
                            <th scope="col">Form Name</th>
                            <th scope="col">Lot</th>
                            <th scope="col">District</th>
                            <th scope="col">Tehsil</th>
                            <th scope="col">Uc</th>
                            <th scope="col">Role Name</th>
                            <th scope="col">Select Status</th>
                            <th scope="col">Status</th>
                            <th scope="col">Comments</th>
                            <th scope="col">Id</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <h5>HRU Pending List</h5>
                        @foreach($survey_data as $item)
                            @php
                            $authenticate_user_uc=json_decode(Auth::user()->uc_id);
                            $beneficairy_details=json_decode($item->beneficiary_details);
                            $form_status=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','HRU')->first();
                            // checking that if any form status of that form is priotize or not 
                            @endphp
                            @if(($form_status==null || $form_status->form_status=='P'))
                            @php
                            $seniorpost=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','PSIA')->first();
                            
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
                                <td>HRU</td>
                                <td>
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
                                  <td><span class='badge text-bg-danger'>{{$seniorpost ? ($seniorpost->form_status == 'A' ? 'approved' : ($seniorpost->form_status == 'R' ? 'rejected' : 'pending')) : 'Not Available' }}</span></td>
                                <td>
                                <button class='btn btn-info' onclick='showComment("{{ $seniorpost->comment ?? 'not available' }}")'>Show Comment</button>
                                </td>
                                <input type='hidden' value='{{$item->survey_form_id}}' class='survey_form_id'>
                                <input type='hidden' value='HRU' class='update_by'>
                                <input type='hidden' value='is_m_and_e' id='is_m_and_e'>
                                </td>
                                <td>{{$item->generated_id}}</td>
                                <td><a href='{{route("beneficiaryProfile",[$item->survey_form_id,TRUE])}}' class='btn btn-success'>View Detail</a></td>
                            </tr>
                            @endif
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