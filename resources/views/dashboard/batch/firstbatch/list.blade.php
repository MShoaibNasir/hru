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
                <h6 class="mb-0">Batch List</h6>
               
                <a href="{{route('firstTrechDatalist')}}" class="create_button">Create Batch</a>
              
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">S no</th>
                            <th scope="col">Batch No</th>
                            <th scope="col">Tranche No</th>
                            <th scope="col">Cheque No</th>
                            <th scope="col">Actions</th>
                         
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data as $item)
@php
if($item->trench_no==1){
$item->trench_no='First Tranche';
}
if($item->trench_no==2){
$item->trench_no='Second Tranche';
}
if($item->trench_no==3){
$item->trench_no='Third Tranche';
}
if($item->trench_no==4){
$item->trench_no='Fourth Tranche';
}
@endphp
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$item->batch_no}}</td>
                                <td>{{$item->trench_no}}</td>
                                <td>{{$item->cheque_no}}</td>
                                <td>
                                    <a class="btn btn-sm btn-danger" href="{{route('batch_detail', [$item->id])}}">View</a>                        
                                    @if($item->is_complete==1)
                                    <a class="btn btn-sm btn-primary">Completed</a>
                                    @else
                                    <a class="btn btn-sm btn-success" href="{{route('firstbatch.edit', [$item->id])}}">Edit</a>                        
                                    <a class="btn btn-sm btn-primary" href="{{route('firstbatch.complete', [$item->id])}}">Complete</a>                
                                    @endif
                                </td>
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