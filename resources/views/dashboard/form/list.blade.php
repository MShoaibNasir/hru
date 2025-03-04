@extends('dashboard.layout.master')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />


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
                <h6 class="mb-0">Form List</h6>
                @if($form_management[1] == 31)
                <a href="{{route('form.create')}}" class="create_button">Create Form</a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">S no</th>
                            <th scope="col">Manage Sequence</th>
                            <th scope="col">Form Name</th>
                            @if($form_management[3] == 33 || $form_management[5] == 35)
                            <th scope="col">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                  
                        @foreach($form as $item)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td><a class='btn btn-success btn-sm' style='margin-right:4px;' href='{{route('form_up',[$item->id])}}'><i class="fa-solid fa-arrow-up"></i></a><a href='{{route('form_down',[$item->id])}}' class='btn btn-danger btn-sm'><i class="fa-solid fa-arrow-down"></i></a></td>
                                <td>{{$item->name}}</td>
                                @if($form_management[3] == 33 || $form_management[5] == 35)
                                <td>
                                @if($form_management[3] == 33)
                                <a class="btn btn-sm btn-success" href="{{route('form.edit', [$item->id])}}">Edit</a>
                                @endif
                                
                                <a class="btn btn-sm btn-danger" href="{{route('form.status', [$item->id])}}">  {{$item->status ? 'Unpublish' : 'Publish' }}</a>
                                @if($form_management[5] == 35)
                                <a class="btn btn-sm btn-secondary" href="{{route('form.view', [$item->id])}}">View</a>
                                @endif
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