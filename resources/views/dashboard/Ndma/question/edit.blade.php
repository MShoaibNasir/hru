@extends('dashboard.layout.master')
@section('content')

<style>
    .mb-3.col-6.checkbox {
        padding-top: 40px;
    }

    .plus {
        display: flex;
        gap: 3px;
        align-items: center;
        margin: 0;
    }

    .plus_sign {

        font-size: 22px;
    }
    /* Responsive and accessible HTML 5 breadcrumb */

@import url("https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap");

*,
*::before,
*::after {
  box-sizing: border-box;
}

body {
  color: #191919;
  font-family: "Open Sans", sans-serif;
  line-height: 1.6;
  height: 100vh;
  height: 100svh;
  display: grid;
  place-items: center;
}

a {
  color: #666666;
  text-decoration: none;
  transition: color 0.5s ease;
}

a:hover,
a:focus {
  color: #191919;
}

ol {
  margin: 0;
  padding: 0;
  list-style: none;
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  border: 1px solid #a7a7a7;
  border-radius: 5px;
  background-color: #c7c7c7;
}

li {
  --arrow-size: 1.4em;
  --arrow-width: 1em;
  position: relative;
  padding: 0.4em 1em;
  line-height: 1.8;
  white-space: nowrap;
}

li::before,
li::after {
  content: " ";
  display: block;
  width: 0;
  height: 0;
  border-top: var(--arrow-size) solid transparent;
  border-bottom: var(--arrow-size) solid transparent;
  border-left: var(--arrow-width) solid var(--arrow-color);
  position: absolute;
  top: 50%;
  margin-top: calc(var(--arrow-size) * -1);
  left: 100%;
}

li::before {
  --arrow-color: #c7c7c7;
  z-index: 2;
}

li::after {
  --arrow-color: #a7a7a7;
  margin-left: 1px;
  z-index: 1;
}

li:first-of-type {
  padding-inline-start: 1.4em;
}

li:last-of-type {
  padding-inline-end: 1.4em;
}

li:last-of-type::before,
li:last-of-type::after {
  display: none;
}

.sr-only {
  clip: rect(0 0 0 0);
  clip-path: inset(50%);
  height: 1px;
  overflow: hidden;
  position: absolute;
  white-space: nowrap;
  width: 1px;
}

</style>
<!-- Content Start -->
<div class="content">

    <!-- Navbar Start -->
    @include('dashboard.layout.navbar')
    <!-- Navbar End -->
    <div class="container-fluid pt-4 px-4 form_width">
<?php
$currentUrl = Request::url();
$segments = explode('/', $currentUrl);
$lastSegment = end($segments);
if(isset($question->option_id)){
  $question_name=\DB::table('questions')->where('option_id',$question->option_id)->select('name')->first();
}
?>

  
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Edit Question</h6>
                    <form method="post" action="{{route('question.update', [$question->id])}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label">Name</label>
                                <input type="text" value="{{$question->name}}" class="form-control" name="name">
                                <input type="hidden" name="section_id" value="{{$lastSegment}}">
                              </div>
                            @php
                                $types = ['radio' => 'Radio Button', 'text' => 'Text', 'date' => 'Date','map'=>'Map','number'=>'Normal Number','image'=>'Image','file'=>'File','counter'=>'Counter','checkbox'=>'Checkbox',
                                'cnic_number'=>'Cnic Number',
                                'mobile_number'=>'Mobile Number'
                                ];
                            @endphp
                            <div class="mb-3 col-6">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-control" id='question_type'>
                                    <option value="">Select Type</option>
                                    @foreach ($types as $Key =>$type)
                                    <option value="{{$Key}}" {{$Key == $question->type ? 'selected' : ''}}>{{$type}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-12">
                                <label class="form-label">Placeholder</label>
                                <textarea name="placeholder" class="form-control" rows="4">{{$question->placeholder}}</textarea>
                            </div>
                             <div class="mb-3 col-6" style='display:none;' id='range_limit'>
                                <label class="form-label">Do you want to set range for this number?</label>
                                <input type='number' class='form-control' name='range_number'>
                            </div>
                            <div class="mb-3 col-12">
                                <label class="form-label">Is this field is mandatory?</label>
                                <input type='checkbox' name='is_mandatory' {{$question->is_mandatory==1? 'checked' : ''}}>
                            </div>
                            <div class="mb-3 col-12">
                                <label class="form-label">Is this field is Editable?</label>
                                <input type='checkbox' name='is_editable' {{$question->is_editable==1? 'checked' : ''}}>
                            </div>
                            
                           
                        <div class='row' id='related_question'>
                            <div class='mb-3 col-6' >
                                    <lable>From Which Related Question (if you want to use that question to relate with another question)</label>
                                <select class='form-control' name='related_question'>
                                    <option value=''>Select Question</option>
                                    @foreach($related_question as $item)
                                    <option value='{{$item->id}}'   {{$item->id==$question->related_question ? 'selected' : '' }}  >{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="row">
                            <div class="mb-3 col-6" id='question_value_check'>
                                <label class="form-label">Select Value Type (if you want to use that question to relate with another question)</label>
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
                            
                            


                            <div class="mb-3 col-6 checkbox">
                                <label class="form-label">Parent Options</label>
                                <select name="option_id" class="form-control" id="option_id" onchange="show_related_question()">
                                    <option value="">Select Option</option>
                                    @foreach($Option as $item)
                                        <option value="{{$item->id}}" {{$item->id==$question->option_id ? 'selected' : ''}}  >{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-6 checkbox">
                                <label class="form-label">Question Name</label>
                               <input type="text"  class="form-control" readonly value='{{isset($question_name) ? $question_name->name : ""}}' id="question_name_for_show">
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