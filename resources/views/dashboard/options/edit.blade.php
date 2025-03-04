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
                    <h6 class="mb-4">Edit Option</h6>
                    <form method="post" action="{{route('options.update', [$options->id, $title_id])}}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-6" id='option_name' style="display:{{isset($options->location_type) ? 'none' : ''}};">
                                <label class="form-label">Name</label>
                                
                                <input type="text" class="form-control" value="{{$options->name}}" {{ isset($options->location_type) ? 'disabled' : '' }}  name="name">
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Is this option is for location(if yes then select one of them)</label>
                                <select id="select_location_type" class="form-control" name='location_type'>
                                    <option value="">Select Type</option>
                                    <option value="lot" {{$options->location_type=='lot' ? "selected" : ""}} >Lot</option>
                                    <option value="district" {{$options->location_type=='district' ? "selected" : ""}}>District</option>
                                    <option value="tehsil" {{$options->location_type=='tehsil' ? "selected" : ""}}>Tehsil</option>
                                    <option value="uc" {{$options->location_type=='uc' ? "selected" : ""}} >UC</option>
                                    <option value="zone" {{$options->location_type=='zone' ? "selected" : ""}} >Zone</option>
                                    <option value="name">Don't want to use that any location</option>
                                </select>
                                
                            </div>
                             <div class="mb-3 col-6" style='display:{{$options->location_type=="lot" ? "block" : "none"}};' id='lots_list'>
                                <label class="form-label">Lots</label>
                                @php
                                $lots=lots();
                                @endphp
                                <select  name="name" class="form-control" {{ $options->location_type == "lot" ? '' : 'disabled' }}>
                                    @foreach($lots as $item)
                                    <option value='{{$item->name}}'   {{$options->name==$item->name ? "selected" : ""}} >{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="mb-3 col-6"  style='display:{{$options->location_type=="district" ? "block" : "none"}};' id="district_list">
                                <label class="form-label">District</label>
                                @php
                                $district=district();
                                @endphp
                                <select name="name" class="form-control" {{ $options->location_type == "district" ? '' : 'disabled' }}>
                                    @foreach($district as $item)
                                    <option value='{{$item->name}}'  {{$options->name==$item->name ? "selected" : ""}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="mb-3 col-6"  style='display:{{$options->location_type=="tehsil" ? "block" : "none"}};' id="tehsil_list">
                                <label class="form-label">Tehsil</label>
                                @php
                                $tehsil=tehsil();
                                @endphp
                                <select  name="name"  class="form-control" {{ $options->location_type == "tehsil" ? '' : 'disabled' }}>
                                    @foreach($tehsil as $item)
                                    <option value='{{$item->name}}' {{$options->name==$item->name ? "selected" : ""}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="mb-3 col-6"  style='display:{{$options->location_type=="uc" ? "block" : "none"}};' id="uc_list">
                                <label class="form-label">uc</label>
                                @php
                                $uc=uc();
                                @endphp
                                <select  name="name"  class="form-control" {{ $options->location_type == "uc" ? '' : 'disabled' }} >
                                    @foreach($uc as $item)
                                    <option value='{{$item->name}}' {{$options->name==$item->name ? "selected" : ""}} >{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="mb-3 col-6"  style='display:{{$options->location_type=="zone" ? "block" : "none"}};' id="zone_list">
                                <label class="form-label">zone</label>
                                @php
                                $zone=zone();
                                @endphp
                                <select  name="name"  class="form-control" {{ $options->location_type == "zone" ? '' : 'disabled' }} >
                                    @foreach($zone as $item)
                                    <option value='{{$item->name}}' {{$options->name==$item->name ? "selected" : ""}} >{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            
                            
                            
                            @php
                                $types = ['radio' => 'Radio Button', 'text' => 'Text', 'date' => 'Date','number'=>'Number','image'=>'Image','file'=>'File','counter'=>'Counter','checkbox'=>'Checkbox'];
                            @endphp
                            <div class="mb-3 col-6">
                                <label class="form-label">Type</label>
                                <select name="type" id='option_type' class="form-control">
                                    <option value="">Select Type</option>
                                    @foreach($types as $Key => $type)
                                        <option value="{{$Key}}" {{$Key == $options->type ? 'selected' : ''}}>{{$type}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-6 checkbox">
                                <label class="form-label">Parent Question</label>
                                <select name="question_id" class="form-control" id="question_id">
                                    <option value="">Select Question</option>
                                    @foreach($question as $item)
                                        <option value="{{$item->id}}"  {{$item->id==$options->question_id ? 'selected' : ''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="row">
                            <div class="mb-3 col-6" id='map_value_check'>
                                <label class="form-label">Select Value Type (select if you are using for map option)</label>
                                <select class='form-control' name='variable_type'>
                                    <option value=''>Select</option>
                                    <option value='latitude'>Latitude</option>
                                    <option value='longitude'>Longitude</option>
                                    <option value='altitude'>Altitude</option>
                                    <option value='accuracy'>Accuracy</option>
                                </select>
                            </div>
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
    <script>
        $(document).ready(function (){
            var option_type= $('#option_type').val();
            if(option_type=='number'){
                $('#map_value_check').show();
            }else{
                $('#map_value_check').hide();
            }
            $('#option_type').change(function(){
                var type_value=$(this).val();
                if(type_value=='number'){
                $('#map_value_check').show();
                }else{
                $('#map_value_check').hide();
                }
            })
        })
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