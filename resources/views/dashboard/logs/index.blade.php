@extends('dashboard.layout.master')
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.min.css">


<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
     @include('dashboard.layout.navbar')

    <!-- Navbar End -->


    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <!-- <h6 class="mb-0">Recent Salse</h6> -->
                <!-- <a href="{{route('district.create')}}">Create District</a> -->
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">S no</th>
                            <th scope="col">Action</th>
                            <th scope="col">Section</th>
                            <th scope="col">Activity</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                      
                        </tr>
                    </thead>
                    <tbody>
                  
                        @foreach($logs as $item)
                        @php
                        $formattedDate = date('d-m-Y', strtotime($item->created_at));
                        $formattedTime = date('H:i:s', strtotime($item->created_at));
                        @endphp
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$item->action ?? 'not available'}}</td>
                                <td>{{$item->section ?? 'not available'}}</td>
                                <td>{{$item->name}} {{$item->activity}}</td>
                                <td>{{$formattedDate}}</td>
                                <td>{{$formattedTime}}</td>
                           
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.min.js"></script>
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