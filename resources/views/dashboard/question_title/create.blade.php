@extends('dashboard.layout.master')
@section('content')
<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
    @include('dashboard.layout.navbar')
    <!-- Navbar End -->
    <div class="container-fluid pt-4 px-4 form_width">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Create Sections</h6>
                    <form method="post" action="{{route('question.title.store', [$form_id])}}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Sub Heading</label>
                                <input type="text" class="form-control" name="sub_heading">
                            </div>

                        </div>
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label">Is this Subsection (if yes so check it)</label>
                                <input type="checkbox" id='check_to_show_sub_section' name='is_subsection'>
                            </div>
                        </div>
                        <div class="row" id='show_subsection' style='display: none;'>
                            <div class="mb-3 col-6">
                                <label class="form-label">Under Which option?</label>
                                <select name="option_id"  class="form-control" id='select_option' >
                                    <option selected value=''>Select option</option>
                                    @foreach($options as $opt)
                                        <option value="{{$opt->id}}">{{$opt->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Question of that option</label>
                                <input type="text" readonly class="form-control" id='filter_question'>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Section of that option</label>
                                <input type="text" readonly class="form-control" id="filter_title">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Is this Subsection is replicable (if yes so check it)</label>
                                <input type="checkbox" name='is_replicable'>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Form of that option</label>
                                <input type="text" readonly class="form-control" id="filter_form">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style='margin-top:2px;'>create</button>
                        <a onclick="history.back()" class="btn back_button" style='margin-top:2px;'>Go Back</a>

                </div>



                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="{{asset('dashboard\js\section.js')}}"></script>
<script>

</script>
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