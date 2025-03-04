@extends('dashboard.layout.master')
@section('content')


<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
    @include('dashboard.layout.navbar')
    <!-- Navbar End -->

@php
$form_name=$survey_data->form_name;
$survey_data=json_decode($survey_data->survey_form_data);
$sub_section=$survey_data->sub_sections;

$array_sectiion = (array) $survey_data->sections;
@endphp
    <div class="container-fluid pt-4 px-4 form_width">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h2 class="mb-4">Survey Form</h2>
                    <h5 class="mb-4">{{$form_name}}:</h5>
                    @foreach($array_sectiion as $key=>$section)
                    @php
                    $questions= $section->questions;
                    @endphp
                     <h6>{{$key}}</h6>
                    @foreach($questions as $ques)
                    @php
                    $question_name=$ques->question->name;
                    $answer=$ques->question->answer;
                    @endphp
                    <p>Question:- {{$question_name}}</p>
                    @if($ques->question->type=='checkbox')
                    @php
                    $options=$ques->options;
                    @endphp
                    <ul>
                    @foreach($options as $option)
                    @if($option->answer=='selected')
                    <li>{{$option->name}}</li>
                    @endif
                    @endforeach
                    </ul> 
                    @else
                 
                    <p>Answer:-{{$answer ?? 'not available'}}</p>
                    
                @if($ques->options !=null)
                @php
                    $options_id=[];
                    foreach($ques->options as $option_id){
                    $options_id[]=$option_id->option_id;
                    }
                @endphp
                @foreach($sub_section as $index=>$item)
                @if(in_array($index,$options_id))
                @foreach($item as $sub_section_data)
                <h6>{{$sub_section_data->name}} </h6>
                @endforeach
                @endif
                @endforeach
                @endif
                    @endif
                    @endforeach
                    @endforeach
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