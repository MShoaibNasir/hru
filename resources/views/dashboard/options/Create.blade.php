@extends('dashboard.layout.master')
@section('content')

<style>
    .mb-3.col-6.checkbox {
        padding-top: 40px;
    }
</style>
<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
    @include('dashboard.layout.navbar')
    <!-- Navbar End -->



    <div class="container-fluid pt-4 px-4 form_width">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Question Create</h6>
                    <form method="post" action="{{route('question.store', [$title_id])}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name">
                            </div>
                            <input type="hidden" name="question_title_id" value="{{$title_id}}">
                          
                            <div class="mb-3 col-6">
                                <label class="form-label">Placeholder</label>
                                <input type="text" class="form-control" name="placeholder">
                            </div>
                            <div class="mb-3 col-6 checkbox">
                                <label class="form-label">Is this question have options?</label>
                                <input type="checkbox" id="first_option_checkbox">
                            </div>
                        </div>




                        <button type="submit" class="btn btn-primary">Save</button>
                        <a onclick="history.back()" class="btn back_button">Go Back</a>
                    </form>
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