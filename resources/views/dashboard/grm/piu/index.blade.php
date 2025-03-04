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
                <h6 class="mb-0">PIU List</h6>
               
                <a href="{{route('piu.create')}}" class="create_button">Create PIU</a>
               
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">S no</th>
                            <th scope="col">PIU Name</th>
                            <th scope="col">User</th>
                            <th scope="col">Actions</th>
                          
                        </tr>
                    </thead>
                    <tbody>
                 
                        @foreach($pius as $item)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{ $item->user->name ?? ''}}</td>
                                <td>
                                <a class="btn btn-sm btn-success" href="{{route('piu.edit', [$item->id])}}">Edit</a>
                                <a class="btn btn-sm btn-danger" href="{{route('piu.status', [$item->id])}}"
                                >{{$item->status=='0' ? 'active' : 'inactive'}}</a> 
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