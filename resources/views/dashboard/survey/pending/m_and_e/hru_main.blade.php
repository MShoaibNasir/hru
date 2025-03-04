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
                            <th scope="col">Status</th>
                            <th scope="col">Rejected Comment</th>
                            <th scope="col">M&E Comment</th>
                            <th scope="col">Id</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <h5>HRU MAIN Pending List</h5>
                        @foreach($survey_data as $item)
                            @php
                            $authenticate_user_uc=json_decode(Auth::user()->uc_id);
                            $beneficairy_details=json_decode($item->beneficiary_details);
                            $form_status=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','HRU_Main')->first();
                            // checking that if any form status of that form is priotize or not 
                            @endphp
                            @if(($form_status==null || $form_status->form_status=='P'))
                            @php
                            $seniorpost=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','COO')->first();
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
                                <td>HRU MAIN</td>
                                  <td><span class='badge text-bg-danger'>{{$seniorpost ? ($seniorpost->form_status == 'A' ? 'approved' : ($seniorpost->form_status == 'R' ? 'rejected' : 'pending')) : 'Not Available' }}</span></td>
                                <td>
                                <button class='btn btn-info' onclick='showComment("{{ $seniorpost->comment ?? 'not available' }}")'>Show Comment</button>
                                </td>
                                @if($item->m_and_e_comment==null)
                                <td><a class='btn btn-danger' data-bs-toggle="modal" data-bs-target="#exampleModal2" onclick='add_comment({{$item->form_status_id}})'> Add Comment</a></td>
                                @else
                                <td><a class='btn btn-success'  onclick='showComment("{{ $item->m_and_e_comment ?? 'not available' }}")'>Show Comment</a></td>
                                @endif
                                <input type='hidden' value='{{$item->survey_form_id}}' class='survey_form_id'>
                                <input type='hidden' value='HRU_Main' class='update_by'>
                                <input type='hidden' value='is_m_and_e' id='is_m_and_e'>
                                <td>{{$item->generated_id}}</td>
                                </td>
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
<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Beneficiary Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form method='post' action='{{route("add_comment")}}'>
          @csrf
      <div class="modal-body">
      <textarea class='form-control' name='comment' rows='4'></textarea>
      <input type='hidden' id='form_status_id' name='form_status_id'>
      <input type='submit' class='btn btn-danger my-4'>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

    
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="{{asset('dashboard\js\survey_list.js')}}"></script>
    <script>
        function add_comment(id){
            $('#form_status_id').val(id);
        }
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