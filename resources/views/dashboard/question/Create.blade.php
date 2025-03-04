@extends('dashboard.layout.master')
@section('content')
<link href="{{asset('dashboard/css/breadcrumbs.css')}}" rel="stylesheet">
<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
    @include('dashboard.layout.navbar')
    <!-- Navbar End -->
    <div class="container-fluid pt-4 px-4 form_width">

         <nav role="navigation" aria-label="Breadcrumb">
            <ol itemscope itemtype="">
                <li itemprop="itemListElement" itemscope itemtype="#">
                    <a href="{{route('form.list')}}" itemprop="item" >
                        <span itemprop="name">{{$form_name->name}}</span>
                    </a>
                    <meta itemprop="position" content="1" />
                </li>
                <li itemprop="itemListElement" itemscope itemtype="#">
                    <a href="{{route('form.view',[$section->form_id])}}" itemprop="item">
                        <span itemprop="name">{{$section->name}}</span>
                    </a>
                    <meta itemprop="position" content="2" />
            </ol>
        </nav>
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Question Create</h6>
                    <form method="post" action="{{route('question.store', [$title_id])}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label">Name <span class='text-danger'>*</span></label>
                                <input type="text" class="form-control" name="name">
                                
                            </div>
                            <input type="hidden" name="section_id" value="{{$title_id}}">
                            <div class="mb-3 col-6">
                                <label class="form-label">Type <span class='text-danger'>*</span></label>
                                <select name="type" class="form-control" id='question_type'>
                                    <option value="">Select Type</option>
                                    <option value="radio">Radio Button</option>
                                    <option value="text">Text</option>
                                    <option value="date">Date</option>
                                    <option value="map">Map</option>
                                    <option value="number">Normal Number</option>
                                    <option value="image">Image</option>
                                    <option value="file">File</option>
                                    <option value="counter">Counter</option>
                                    <option value="checkbox">Checkbox</option>
                                    <option value="searchable">Searchable</option>
                                    <option value="cnic_number">Cnic Number</option>
                                    <option value="mobile_number">Mobile Number</option>
                                </select>
                            </div>
                             <div class="mb-3 col-6">
                                <label class="form-label">Is this question is for location(if yes then select any option)</label>
                                <select  name='location_condition' class="form-control" >
                                    <option value="">Select Type</option>
                                    <option value="lot">Lot</option>
                                    <option value="district">District</option>
                                    <option value="tehsil">Tehsil</option>
                                    <option value="uc">UC</option>
                                </select>
                            </div>
                            <div class="mb-3 col-12">
                                <label class="form-label">Placeholder</label>
                                <textarea name="placeholder" class="form-control" rows="4"></textarea>
                            </div>
                            
                            <div class="mb-3 col-6" style='display:none;' id='range_limit'>
                                <label class="form-label">Do you want to set range for this number?</label>
                                <input type='number' class='form-control' name='range_number'>
                            </div>
                            
                            
                            
                            
                            
                            
                            
                            <div class="mb-3 col-6">
                                <label class="form-label">Is this field is mandatory?</label>
                                <input type='checkbox' name='is_mandatory'>
                            </div>

                            <div class="mb-3 col-6">
                                <label class="form-label">Is this field is Editable?</label>
                                <input type='checkbox' name='is_editable'>
                            </div>
                           
                            <div class="mb-3 col-6 checkbox">
                                <label class="form-label">Parent Options</label>
                                <select name="option_id" class="form-control" id="option_id" onchange="show_related_question()">
                                    <option value="">Select Option</option>
                                    @foreach($Option as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-6 checkbox">
                                <label class="form-label">Question Name</label>
                               <input type="text"  class="form-control" readonly id="question_name_for_show">
                            </div>
                            <div class="row">
                            <div class="mb-3 col-12"  style='gap: 13px; align-items: center;'>
                                <label class="form-label">Is this question related to searchable question (if yes check it) <small>(This question is for benefeciary)</small></label>
                                <input type="checkbox" id='check_of_question_related_type'>
                            </div>
                            
                        </div>
                        
                        
                            <div class='row' style='display:none;' id='related_question'>
                                <div class='mb-3 col-6' >
                                    <lable>From Which Related Question</label>
                                <select class='form-control' name='related_question'>
                                    <option value=''>Select Question</option>
                                    @foreach($related_question as $item)
                                    <option value='{{$item->id}}'>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            </div>
                            <div class="row">
                            <div class="mb-3 col-6" id='question_value_check' style='display:none;'>
                                <label class="form-label">Select Value Type</label>
                                <select class='form-control' name='variable_type'>
                                    <option value=''>Select</option>
                                    <option value='b_reference_number'>Beneficiary Number</option>
                                    <option value='province'>Province</option>
                                    <option value='cnic'>Cnic</option>
                                    <option value='survey_date'>Survey Date</option>
                                    <option value='address'>Address</option>
                                    <option value='district_name'>District</option>
                                    <option value='tehsil_name'>Tehsil</option>
                                    <option value='uc_name'>uc</option>
                                    <option value='beneficiary_name'>Beneficiary Name</option>
                                    <option value='father_name'>Father Name</option>
                                    <option value='contact_number'>Contact No</option>
                                    <option value='gender'>Gender</option>
                                    <option value='age'>Age</option>
                                    <option value='name_next_of_kin'>Name next Of kin</option>
                                    <option value='cnic_of_kin'>Cnic of kin</option>
                                    <option value='cnic_of_kin'>Cnic of kin</option>
                                    <option value='damaged_rooms'>Damaged rooms</option>
                                    <option value='damaged_type'>Damaged Type</option>
                                    <option value='damaged_category'>Damaged category</option>
                                    <option value='auto_gender'>Auto Gender</option>
                                    <option value='IsCNIC'>Is Cnic</option>
                                    <option value='IsContact'>Is Contact</option>
                                </select>
                            </div>
                        </div>
                            




                        </div>
                        <button type="submit" class="btn btn-primary" style='margin-top:2px;'>Save</button>
                        <a onclick="history.back()" class="btn back_button" style='margin-top:2px;'>Go Back</a>
                    </form>

                </div>
            </div>
        </div>
        <hr>
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="mb-4">Options Create</h6>
                        </div>

                    </div>
                    <form method="post" action="{{route('options.store', [$title_id])}}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="col-12 d-flex justify-content-end">
                            <a class="btn btn-danger" id="add_options_0" onclick="add_options(0)">Add Option</a>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-6" id='option_name'>
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control"  name="name[]" >
                            </div>
                            <input type="hidden" name="section_id" value="{{$title_id}}">
                            <div class="mb-3 col-6">
                                <label class="form-label">Type</label>
                                <select name="type[]" id='option_type' class="form-control" required>
                                    <option value="">Select Type</option>
                                    <option value="radio">Radio Button</option>
                                    <option value="text">Text</option>
                                    <option value="number">Normal Number</option>
                                    <option value="image">Image</option>
                                    <option value="file">File</option>
                                    <option value="date">Date</option>
                                    <option value="counter">Counter</option>
                                    <option value="checkbox">Checkbox</option>
                                    <option value="cnic_number">Cnic Number</option>
                                    <option value="mobile_number">Mobile Number</option>
                                </select>
                            </div>
                           
                            
                        
                            <div class="col-6 checkbox">
                                <label class="form-label">Parent Question</label>
                                <select name="question_id" class="form-control" id="question_id" required>
                                    <option value="">Select Question</option>
                                    @foreach($question as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                             <div class="mb-3 col-6">
                                <label class="form-label">Is this option is for location(if yes then select one of them)</label>
                                <select  id='select_location_type' name='location_type'  class="form-control">
                                    <option value="">Select Type</option>
                                    <option value="lot">Lot</option>
                                    <option value="district">District</option>
                                    <option value="tehsil">Tehsil</option>
                                    <option value="uc">UC</option>
                                    <option value="name">Don't want to use that any location</option>
                                </select>
                            </div>
                             <div class="mb-3 col-6" style='display:none;' id='lots_list'>
                                <label class="form-label">Lots</label>
                                @php
                                $lots=lots();
                                @endphp
                                <select  name="name[]" class="form-control" disabled>
                                    @foreach($lots as $item)
                                    <option value='{{$item->name}}'>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="mb-3 col-6"  style='display:none;'  id="district_list">
                                <label class="form-label">District</label>
                                @php
                                $district=district();
                                @endphp
                                <select  name="name[]" class="form-control"  disabled>
                                    @foreach($district as $item)
                                    <option value='{{$item->name}}'>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="mb-3 col-6" style='display:none;'  id="tehsil_list">
                                <label class="form-label">Tehsil</label>
                                @php
                                $tehsil=tehsil();
                                @endphp
                                <select  name="name[]" class="form-control" disabled>
                                    @foreach($tehsil as $item)
                                    <option value='{{$item->name}}'>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="mb-3 col-6" style='display:none;'  id="uc_list">
                                <label class="form-label">uc</label>
                                @php
                                $uc=uc();
                                @endphp
                                <select  name="name[]" class="form-control" disabled>
                                    @foreach($uc as $item)
                                    <option value='{{$item->name}}'>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            
                            <div class="mb-3 col-6" style='display:none;' id='range_limit_for_option'>
                                <label class="form-label">Do you want to set range for this number?</label>
                                <input type='number' class='form-control' name='range_number[]'>
                            </div>
                            
                            <div class="row">
                            <div class="mb-3 col-6" id='map_option_check' style='display:none; gap: 13px; align-items: center;'>
                                <label class="form-label">Is this Map Option (if yes so check it)</label>
                                <input type="checkbox" id='check_of_value_type' name='is_subsection'>
                            </div>
                        </div>
                            <div class="row">
                            <div class="mb-3 col-6" id='map_value_check' style='display:none;'>
                                <label class="form-label">Select Value Type</label>
                                <select class='form-control' name='variable_type[]' >
                                    <option value=''>Select</option>
                                    <option value='latitude'>Latitude</option>
                                    <option value='longitude'>Longitude</option>
                                    <option value='altitude'>Altitude</option>
                                    <option value='accuracy'>Accuracy</option>
                                </select>
                            </div>
                        </div>
                            <div id="add_more_option_0"></div>
                        </div>
                        <button type="submit" class="btn btn-primary" style='margin-top:6px;'>Save</button>
                        <a onclick="history.back()" class="btn back_button" style='margin-top:6px;'>Go Back</a>
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