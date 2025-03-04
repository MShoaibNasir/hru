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
                ->join('roles', 'users.id', '=', 'users.role')
                ->where('users.id', '=', $current_user->id)
                ->first();
        }
        $settlement_management = json_decode($allow_access->settlement_management);
        array_unshift($settlement_management, 0);
    @endphp


    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Area Settlement</h6>
                @if($settlement_management[1] == 21)
                <a href="{{route('area.create')}}" class="create_button">Create Settlement</a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">S no</th>
                            <th scope="col">Settlement Name</th>
                            <th scope="col">UC Name</th>
                            @if($settlement_management[3] == 23 || $settlement_management[4] == 24)
                                <th scope="col">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($area as $item)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->uc_name}}</td>
                                @if($settlement_management[3] == 23 || $settlement_management[4] == 24)
                                    <td>
                                        @if($settlement_management[3] == 23)
                                            <a class="btn btn-sm btn-success" href="{{route('area.edit', [$item->id])}}">Edit</a>
                                        @endif
                                        @if($settlement_management[4] == 24)
                                        <a class="btn btn-sm btn-danger" href="{{route('area.status', [$item->id])}}"
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