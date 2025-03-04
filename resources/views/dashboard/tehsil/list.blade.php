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
        $tehsil_management = json_decode($allow_access->tehsil_management);
        array_unshift($tehsil_management, 0);
    @endphp
    <!-- Navbar End -->
    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Tehsil List</h6>
                @if($tehsil_management[1] == 13)
                <a href="{{route('tehsil.create')}}" class="create_button">Create Tehsil</a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">Database Id</th>
                            <th scope="col">Tehsil Name</th>
                            
                            @if($tehsil_management[3] == 15 || $tehsil_management[4] == 16)
                            <th scope="col">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                 
                        @foreach($tehsil as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->name}}</td>
                                
                                @if($tehsil_management[3] == 15 || $tehsil_management[4] == 16)
                                <td>
                                @if($tehsil_management[3] == 15)
                                    <a class="btn btn-sm btn-success" href="{{route('tehsil.edit', [$item->id])}}">Edit</a>
                                @endif
                                @if($tehsil_management[4] == 16)   
                                <a class="btn btn-sm btn-danger" href="{{route('tehsil.status', [$item->id])}}"
                                >{{$item->status=='0' ? 'active' : 'inactive'}}</a> 
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