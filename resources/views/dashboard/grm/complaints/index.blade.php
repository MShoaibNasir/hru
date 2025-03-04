@extends('dashboard.layout.master')
@section('content')


<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
     @include('dashboard.layout.navbar')
   
    <!-- Navbar End -->


    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">

@if(Route::is('complaints.pending'))
<h6 class="mb-0">Manage Pending Complaints</h6>
@elseif(Route::is('complaints.inprocess'))
<h6 class="mb-0">Manage In Process Complaints</h6>
@elseif(Route::is('complaints.closed'))
<h6 class="mb-0">Manage Closure Complaints</h6>
@elseif(Route::is('complaints.returned'))
<h6 class="mb-0">Manage Returned Complaints</h6>
@elseif(Route::is('complaints.today_total'))
<h6 class="mb-0">Manage Overall Today Complaints</h6>
@elseif(Route::is('complaints.exclusioncases_complaint'))
<h6 class="mb-0">Manage Overall Exclusion Cases Complaints</h6>
@elseif(Route::is('complaints.exclusioncases_today'))
<h6 class="mb-0">Manage Today Exclusion Cases Complaints</h6>
@else
<h6 class="mb-0">Manage Overall Complaints</h6>
@endif


               
                <a href="{{route('complaints.create')}}" class="create_button">Create Complaint</a>
               
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="complaintTable">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">Complaint No</th>
                            <th scope="col">Grievance Type</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Father Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">CNIC</th>
                            <th scope="col">HRU Beneficiary ID</th>
                            <th scope="col">District</th>
                            <th scope="col">Tehsil</th>
                            <th scope="col">UC</th>
                            @if(Route::is('complaints.exclusioncases_today'))
                            @elseif(Route::is('complaints.exclusioncases_complaint'))
                            @else
                            <th scope="col">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                 
                        @foreach($complaints as $item)
                            <tr>
                                <td>{{$item->ticket_no}}</td>
                                <td>{{$item->getgrievancetype->name ?? ''}}</td>
                                <td>{{$item->full_name}}</td>
                                <td>{{$item->father_name}}</td>
                                <td>{{$item->email}}</td>
                                <td>{{$item->cnic}}</td>
                                <td>{{$item->hru_beneficiary_id}}</td>
                                <td>{{$item->getdistrict->name ?? ''}}</td>
                                <td>{{$item->gettehsil->name ?? ''}}</td>
                                <td>{{$item->getuc->name ?? ''}}</td>
                                @if(Route::is('complaints.exclusioncases_today'))
                                @elseif(Route::is('complaints.exclusioncases_complaint'))
                                @else
                                <td>
                                @if(Auth::user()->role == 56)
                                <a class="btn btn-sm btn-primary" href="{{route('complaints.edit', [encrypt($item->id)])}}">Edit</a>
                                @endif
                                <a class="btn btn-sm btn-success" href="{{route('complaints.show', [encrypt($item->id)])}}">View</a>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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