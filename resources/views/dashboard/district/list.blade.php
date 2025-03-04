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
        $district_management = json_decode($allow_access->district_management);
        array_unshift($district_management, 0);
    @endphp

    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">District List</h6>
                @if($district_management[1] == 9)
                <a href="{{route('district.create')}}" class="create_button">Create District</a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">Database id</th>
                            <th scope="col">District Name</th>
                            <th scope="col">Lot Name</th>
                       
                            @if($district_management[3] == 11 || $district_management[4] == 12)
                                <th scope="col">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($district as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->lot_name}}</td>
                          
                                @if($district_management[3] == 11 || $district_management[4] == 12)
                                        <td>@if($district_management[3] == 11)
                                            <a class="btn btn-sm btn-success"
                                                href="{{route('district.edit', [$item->id])}}">Edit</a>
                                        @endif 
                                            @if($district_management[4] == 12)
                                                <a class="btn btn-sm btn-danger" href="{{route('district.status', [$item->id])}}"
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