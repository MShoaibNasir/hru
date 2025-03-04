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
        $form_management = json_decode($allow_access->form_management);
        array_unshift($form_management, 0);
    @endphp

    <!-- Navbar End -->


    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3>Options</h3>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">S no</th>
                            <th scope="col">Options Name</th>
                            <th scope="col">Parent Question</th>
                            @if($form_management[14] == 44 || $form_management[15] == 45)
                            <th scope="col">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>



                        @foreach($options as $item)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$item->name}}</td>
                                @if($item->question_id)
                                    <td><a class="btn button-60" onclick="showQuestion({{$item->question_id}})"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal">View Question</a></td>
                                @else
                                    <td><a class="btn button-60">Not available</a></td>
                                @endif
                                @if($form_management[14] == 44 || $form_management[15] == 45)
                                <td>
                                @if($form_management[14] == 44)
                                    <a class="btn btn-sm btn-success"
                                        href="{{route('options.edit', [$item->id, $title_id])}}">Edit</a>
                                @endif
                                @if($form_management[15] == 45)        
                                    <a class="btn btn-sm btn-danger" href="{{route('options.delete', [$item->id])}}"
                                        onclick="return confirm('Are you sure you want to delete?');">Delete</a>
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


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Question Name</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea id="question_name" readonly class="form-control"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <script src="{{asset('dashboard\js\question.js')}}"></script>
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