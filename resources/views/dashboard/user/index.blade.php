@extends('dashboard.layout.master')
@section('content')

<style>
    td {
        white-space: nowrap;
    }

    th {
        white-space: nowrap;
    }
</style>
<!-- Content Start -->
<div class="content">


    @php
        $current_user = Auth::user();
        if ($current_user) {
              $allow_access = DB::table('users')
                    ->join('roles', 'users.role', '=', 'roles.id')
                    ->where('users.id', '=', $current_user->id)
                    ->first();
        }
     
        $user_managment = json_decode($allow_access->user_management);
        array_unshift($user_managment, 0);
   
    @endphp
    <!-- Navbar Start -->
    @include('dashboard.layout.navbar')
    <!-- Navbar End -->


    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">User List</h6>
                @if($user_managment[1] == 1)
                <a href="{{route('ip.create')}}" class="create_button">Create User</a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">S no</th>
                            <th scope="col">Name</th>
                            <th scope="col">email</th>
                            <th scope="col">Date of register</th>
                            @if(@isset($user_managment) && ($user_managment['3'] == 3 || $user_managment['4'] == 4))
                                <th scope="col">Actions</th>
                            @endif
                            <!-- <th scope="col">Status</th>
                            <th scope="col">Delete</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $item)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->email}}</td>
                                <td>{{$item->created_at}}</td>

                                @if($user_managment['3'] == 3 || $user_managment['4'] == 4 )
                                    <td>
                                        @if($user_managment['3'] == 3)
                                            <a class="btn btn-sm btn-success" href="{{route('user.edit', [$item->id])}}">Edit</a>

                                        @endif
                                        @if($user_managment['4'] == 4)
                                            <a class="btn btn-sm btn-secondary" href="{{route('ip.block', [$item->id])}}">
                                                {{$item->status == 1 ? "Block" : 'Unblock'}} </a>
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