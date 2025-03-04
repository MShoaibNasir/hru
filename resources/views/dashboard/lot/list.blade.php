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
        $lots_management = json_decode($allow_access->lots_management);
        array_unshift($lots_management, 0);
    @endphp
    <!-- Navbar End -->
    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Lot list</h6>
                @if($lots_management[1] == 5)
                <a href="{{route('lot.create')}}" class="create_button">Create Lots</a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                    <thead>
                        <tr class="text-dark">
                          
                            <th scope="col">Database Id</th>
                            <th scope="col">Lot Name</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                 
                        @foreach($lots as $item)
                            <tr>
                                
                                <td>{{$item->id}}</td>
                                <td>{{$item->name}}</td>
                           

                                <td>
                                @if($lots_management[3] == 7)
                                    <a class="btn btn-sm btn-success" href="{{route('lot.edit', [$item->id])}}">Edit</a>
                                @endif
                                @if($lots_management[4] == 8)
                                <a class="btn btn-sm btn-danger" href="{{route('lot.status', [$item->id])}}">{{$item->status=='0' ? 'Active' : 'Inactive'}} </a></td>              
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