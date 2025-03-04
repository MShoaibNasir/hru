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
                    <h6 class="mb-4">Edit User</h6>
                    <form method="post" action="{{route('user.update',[$user->id])}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" required value="{{$user->name}}" name="name">

                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" readonly name="email" value="{{$user->email}}">
                                
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Contact No <span class="text text-danger">*</span></label>
                                <input type="number" class="form-control" required name="number" value='{{$user->number}}'>

                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Organization<span class="text text-danger">*</span></label>
                                <input type="organization" class="form-control" required name="organization" value='{{$user->organization}}'>
                               
                            </div>
                             <div class="mb-3 col-6">
                                <label class="form-label">Section  <span class="text text-danger">*</span></label>
                                <select   class="form-control" name="section" required>
                                    <option>Select sections</option>
                                   @foreach($sections as $section)
                                    <option value='{{$section->id}}' {{$section->id==$user->section ? 'selected' : '' }}>{{$section->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Designation <span class="text text-danger">*</span></label>
                                <select class="form-control" name="designation" required>
                                    <option>Select designation</option>
                                    @foreach($designation as $item)
                                    <option value='{{$item->id}}' {{$item->id==$user->designation ? 'selected' : '' }}>{{$item->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Supervisor Name<span class="text text-danger">*</span></label>
                                <input type="text" class="form-control" required name="supervisor_name" value='{{$user->supervisor_name}}'>
                            </div>

                                       @php
                                        
                                        $lot_id=json_decode($user->lot_id);
                                        $district_id=json_decode($user->district_id);
                                        $tehsil_id=json_decode($user->tehsil_id);
                                        $uc_id=json_decode($user->uc_id);
                                    
                                        @endphp
                              <div class="mb-3 col-6">
                                <label class="form-label">Profile Image</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <div class="mb-3 col-6">
                                <img src="{{ asset('admin/assets/img/' . $user->image) }}" alt="User Image" style="width:200px; height:200px;">

                            </div>
                           
                            <div class="mb-3 col-6">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <a class="btn btn-danger my-2" id="generate_password">Generate Password</a>
                            </div>

                            <div class="mb-3 col-6">
                                <label class="form-label">Roles</label>
                                <select name="role"  class="form-control">
                                    <option value='' selected>Select Role</option>
                                    @foreach($roles as $role)
                                    <option value='{{$role->id}}' {{$role->id == $user->role ? 'selected': ''}}>{{$role->name}}</option>
                                    @endforeach
                                    
                                </select>

                            </div>            
                                       
                            <div class="mb-3 col-6">
                                <label class="form-label">Lot</label>
                                    <select class="js-example-basic-multiple form-control" name="lot_id[]" multiple="multiple" id="lot">
                                        <option value="all">Select All</option>
                                        @foreach($lots as $lot)
                                            <option value="{{ $lot->id }}" {{ in_array($lot->id, $lot_id) ? 'selected' : '' }}>
                                                {{ $lot->name }}
                                            </option>
                                        @endforeach
                                    </select>

                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">District</label>
                                <select name="district_id[]" multiple="multiple"  id="district" class="js-example-basic-multiple form-control">
                                    <option value="all">Select All</option>
                                   
                                    @foreach($district as $item)
                                    <option value='{{$item->id}}' {{ in_array($item->id, $district_id) ? 'selected' : '' }}>{{$item->name}}</option>
                                    @endforeach
                                </select>

                            </div>  
                            <div class="mb-3 col-6">
                                <label class="form-label">Tehsil</label>
                                <select name="tehsil_id[]" id="tehsil" multiple="multiple" class="form-control js-example-basic-multiple">
                                    <option value="all">Select All</option>
                                    @foreach($tehsil as $item)
                                    @if(isset($tehsil_id))
                                    <option value='{{$item->id}}' {{(in_array($item->id, $tehsil_id)) ? 'selected' : '' }}>{{$item->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                                
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Uc</label>
                                <select name="uc_id[]" multiple="multiple" id="uc" class="form-control js-example-basic-multiple">
                                   <option value="all">Select All</option>
                                    @foreach($uc as $item)
                                    <option value='{{$item->id}}'  {{ in_array($item->id, $uc_id) ? 'selected' : '' }}>{{$item->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                            
                          
                          
                        </div>


                        <button type="submit" class="btn btn-primary">Update</button>
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