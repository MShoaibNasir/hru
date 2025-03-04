@extends('dashboard.layout.master')
@section('content')


<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
    @include('dashboard.layout.navbar')
    <!-- Navbar End -->
    @php
        $current_user = Auth::user();
        if ($current_user) {
                 $allow_access = DB::table('users')
                    ->join('roles', 'users.role', '=', 'roles.id')
                    ->where('users.id', '=', $current_user->id)
                    ->first();
        }
        $role_management = json_decode($allow_access->role_management);
        array_unshift($role_management, 0);
    @endphp

    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Role List</h6>
                <a href="{{route('role.create')}}" class="create_button">Create Role</a>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">S no</th>
                            <th scope="col">Role Name</th>
                            @if($role_management[3] == 27 || $role_management[4] == 28)
                            <th scope="col">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($role as $item)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$item->name}}</td>
                                @if($role_management[3] == 27 || $role_management[4] == 28)
                                <td>@if($role_management[3] == 27)<a class="btn btn-sm btn-success" href="{{route('role.edit',[$item->id])}}">Edit</a>@endif
                                @if($role_management[4] == 28)
                                <a class="btn btn-sm btn-danger" href="{{route('role.status', [$item->id])}}">{{$item->status=='0' ? 'Active' : 'Inactive'}} </a></td>              
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