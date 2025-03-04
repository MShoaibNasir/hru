@extends('dashboard.layout.master')
@section('content')


<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
    @include('dashboard.layout.navbar')
    <!-- Navbar End -->
    @php

    $form = \DB::table('options')
        ->join('questions', 'options.question_id', '=', 'questions.id')
        ->join('question_title', 'questions.section_id', '=', 'question_title.id')
        ->join('form', 'question_title.form_id', '=', 'form.id')
        ->where('options.id', $question_title->option_id)
        ->select('questions.name as question_name','question_title.name as title_name','options.name as option_name','form.name as form_name') 
        ->first();
      
        

@endphp


    <div class="container-fluid pt-4 px-4 form_width">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Edit Section</h6>
                   
                    <form method="post" action="{{route('question.title.update',[$question_title->id])}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" value="{{$question_title->name}}" name="name">
                                <input type="hidden" name="form_id" value="{{$question_title->form_id}}">
                            </div>
                            <div class="mb-3 col-12">
                                <label class="form-label">Sub Heading</label>
                                <textarea class="form-control" rows="5" cols="15" name="sub_heading">{{$question_title->sub_heading ?? 'Not Available'}}</textarea>
                            </div>
                            <hr>
                            <h4>If you want to consider this section as sub section then use that option</h4>
                        
                         
                            <div class="row" id='show_subsection'>
                            <div class="mb-3 col-6">
                                <label class="form-label">Under Which option?</label>
                                <select name="option_id"  class="form-control" id='select_option' required>
                                    <option selected>Select Option</option>
                                    @foreach($options as $opt)
                                        <option value="{{$opt->id}}"  {{$opt->id==$question_title->option_id ? 'selected' : ''}}>{{$opt->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                           
                            <div class="mb-3 col-6">
                                <label class="form-label">Question of that option</label>
                                <input type="text" readonly class="form-control" value='{{(isset($form) && $form->question_name) ? $form->question_name : ''}}' id='filter_question'>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Section of that option</label>
                                <input type="text" readonly class="form-control" value='{{ ( isset($form) && $form->title_name) ? $form->title_name : '' }}' id="filter_title">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Form of that option</label>
                                <input type="text" readonly class="form-control" id="filter_form" value='{{ (isset($form) && $form->option_name) ? $form->option_name :  ''}}'>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Is this Subsection is replicable (if yes so check it)</label>
                                <input type="checkbox" name='is_replicable' {{($replicable_check && $replicable_check->is_replicable==1) ? 'checked' : ''}}>
                            </div>
                        </div>
                         

                        </div>
                        
                        <button type="submit" class="btn btn-primary" style='margin-top:2px;'>Update</button>
                        <a onclick="history.back()" class="btn back_button" style='margin-top:2px;'>Go Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="{{asset('dashboard\js\section.js')}}"></script>
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