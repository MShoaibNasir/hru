@extends('dashboard.layout.master')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
    @include('dashboard.layout.navbar')
    <!-- Navbar End -->



    <div class="container-fluid pt-4 px-4 form_width">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Create User</h6>
                    <form method="post" action="{{route('ip_signup')}}" id='select_two' enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label">Name <span class="text text-danger">*</span></label>
                                <input type="text" class="form-control" name="name">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Email <span class="text text-danger">*</span></label>
                                <input type="email" class="form-control" name="email">
                               
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Contact No <span class="text text-danger">*</span></label>
                                <input type="number" class="form-control" name="number">

                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Organization<span class="text text-danger">*</span></label>
                                <input type="organization" class="form-control" name="organization">
                               
                            </div>

                           
                            <div class="mb-3 col-6">
                                <label class="form-label">Section  <span class="text text-danger">*</span></label>
                                <select   class="form-control" name="section">
                                    <option>Select sections</option>
                                    @foreach($sections as $section)
                                    <option value='{{$section->id}}'>{{$section->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Designation <span class="text text-danger">*</span></label>
                                <select class="form-control" name="designation">
                                    <option>Select Designation</option>
                                    @foreach($designation as $item)
                                    <option value='{{$item->id}}'>{{$item->name}}</option>
                                    @endforeach
                               
                                </select>

                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Supervisor Name<span class="text text-danger">*</span></label>
                                <input type="text" class="form-control" name="supervisor_name">
                            </div>
                            
                              <div class="mb-3 col-6">
                                <label class="form-label">Profile Image </label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Password<span class="text text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password">
                                <a class="btn btn-danger my-2" id="generate_password">Generate Password</a>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Roles <span class="text text-danger">*</span></label>
                                <select name="role"  class="form-control">
                                    <option value='' selected>Select Role</option>
                                    @foreach($roles as $role)
                                    <option value='{{$role->id}}'>{{$role->name}}</option>
                                    @endforeach
                                    
                                </select>

                            </div>
                            
                            
                            <div class="mb-3 col-6">
                                <label class="form-label">Lot <span class="text text-danger">*</span></label>
                                <select   class="js-example-basic-multiple form-control" name="lot_id[]" multiple="multiple"  id="lot">
                                     <option value="all">Select All</option>
                                    @foreach($lots as $lot)
                                    <option value='{{$lot->id}}' >{{$lot->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">District <span class="text text-danger">*</span></label>
                                <select name="district_id[]" multiple="multiple"  id="district" class="js-example-basic-multiple form-control">
                                    <option value='' >Select District</option>
                                </select>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Tehsil <span class="text text-danger">*</span></label>
                                <select name="tehsil_id[]" id="tehsil" multiple="multiple" class="form-control js-example-basic-multiple">
                                    <option value='' >Select Tehsil</option>
                                </select>

                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Uc<span class="text text-danger">*</span></label>
                                <select name="uc_id[]" multiple="multiple" id="uc" class="form-control js-example-basic-multiple">
                                    <option value='' >Select Uc</option>
                                </select>

                            </div>
                            
                          

                        </div>


                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="{{asset('dashboard\js\ip_create.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>

    <script>
    $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});





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