@extends('dashboard.layout.master')
@section('content')


<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
     @include('dashboard.layout.navbar')
     @php
        $current_user = Auth::user();
        if ($current_user) {
                  $allow_access = DB::table('users')
                    ->join('roles', 'users.role', '=', 'roles.id')
                    ->where('users.id', '=', $current_user->id)
                    ->first();
        }
        $form_management = json_decode($allow_access->form_management);
        array_unshift($form_management, 0);
      
    @endphp

    <!-- Navbar End -->


    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Hold Survey Form List</h6>
            </div>
           
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">Id</th>
                            <th scope="col">Beneficiary Ref no</th>
                            <th scope="col">Form Name</th>
                            <th scope="col">Person Name</th>
                            <th scope="col">Person Role</th>
                            @if(Auth::user()->role==51)
                             <th scope="col">M&E Comment</th>
                            @endif
                          
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    
                    
                    <tbody>
                       
                        @foreach($survey_data as $item)
                        @php
                        $beneficairy_details=json_decode($item->beneficiary_details);
                        @endphp
                            <tr  style='background-color: {{$item->priority==1? '#19875433' : 'transparent'}}';>
                                <td>{{$item->generated_id ?? 'not available'}}</td>
                                <td>{{$beneficairy_details->b_reference_number}}</td>
                                <td>{{$item->form_name}}</td>
                                <td>{{$item->validator_name}}</td>
                                <td>{{$item->role_name}}</td>
                                @if(Auth::user()->role==51)
                                  @if(!isset($item->m_and_e_comment))
                                <td><a class='btn btn-danger' data-bs-toggle="modal" data-bs-target="#exampleModal2" onclick='add_comment({{$item->form_status_id}})'> Add Comment</a></td>
                                @else
                                <td><a class='btn btn-success'  onclick='showComment("{{ $item->m_and_e_comment ?? 'not available' }}")'>Show Comment</a></td>
                                @endif
                                @endif
                                <td>
                                <a class="btn btn-sm btn-success" href='{{route("beneficiaryProfile",[$item->survey_form_id,TRUE])}}'>View Form</a>
                                </td>
                          
                            </tr>
                        @endforeach    
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
  <script>
        function add_comment(id){
            $('#form_status_id').val(id);
        }
    </script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="{{asset('dashboard\js\survey_list.js')}}"></script>
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