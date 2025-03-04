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
                    <h6 class="mb-4">Update Account</h6>
                    <form method="post" action="{{route('updateAccount',[$survey_form_id])}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label">Account Number</label>
                                <input type="text" class="form-control"  name="account_number" value='{{$account_numner->answer}}'> 
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Bank Name</label>
                                <input type="text" class="form-control"  name="bank_name" value='{{$bank_name->answer}}'>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Branch Name</label>
                                <input type="text" class="form-control"  name="branch_name" value='{{$branch_name->answer}}'>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Bank Address</label>
                                <input type="text" class="form-control"  name="bank_address" value='{{$bank_address->answer}}'>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Beneficiary Name</label>
                                <input type="text" class="form-control"  name="beneficiary_name" value='{{$beneficiary_name->beneficiary_name}}'>
                            </div>
                       
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a onclick="history.back()" class="btn back_button">Go Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

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