@extends('dashboard.layout.master')
@section('content')


<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
    @include('dashboard.layout.navbar')
    <!-- Navbar End -->

    <style>
        a:hover {
            color: #007dcc;
        }
    </style>

    <div class="container-fluid pt-4 px-4 form_width">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Edit Role</h6>
                <form method="post" action="{{route('role.update', [$role->id])}}" enctype="multipart/form-data"
                        id="form">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" value="{{$role->name}}" name="name">
                            </div>


                        </div>
                        <div class="row">
                            <div class="accordion my-4" id="accordionExample1">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingtwo">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#side_bar1" aria-expanded="false"
                                            aria-controls="collapseOne">
                                            User Managament
                                        </button>
                                    </h2>
                                    <div id="side_bar1" class="accordion-collapse collapse "
                                        aria-labelledby="headingtwo" data-bs-parent="#accordionExample1">
                                        <div class="accordion-body ">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <nav aria-label="breadcrumb" class="text-right">
                                                        <ol class="breadcrumb text-right">
                                                            <li><a href="#"
                                                                    onclick='seleccheckboxName("class_management", event)'>Select
                                                                    all</a>&nbsp;</li>
                                                            <li> <span> | </span>&nbsp;<a href="#"
                                                                    onclick='unselectAll("class_management", event)'>Deselect
                                                                    all</a>&nbsp;</li>
                                                        </ol>
                                                    </nav>
                                                </div>
                                            </div>
                                            <div class="row  p-2">
                                                @foreach($user_managemnt as $item)
                                                                                                @php
                                                                                                    $user = json_decode($role->user_management);
                                                                                                @endphp
                                                                                                @if($user && in_array($item->id, $user))
                                                                                                    <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input class_management" checked
                                                                                                            type="checkbox" value='{{$item->id}}' name='user_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @else
                                                                                                    <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input class_management" type="checkbox"
                                                                                                            value='{{$item->id}}' name='user_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @endif                                                  @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="accordion my-4" id="accordionExample1">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingtwo">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#side_bar2" aria-expanded="false"
                                            aria-controls="collapseOne">
                                            Lots Managament
                                        </button>
                                    </h2>
                                    <div id="side_bar2" class="accordion-collapse collapse "
                                        aria-labelledby="headingtwo" data-bs-parent="#accordionExample1">
                                        <div class="accordion-body ">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <nav aria-label="breadcrumb" class="text-right">
                                                        <ol class="breadcrumb text-right">
                                                            <li><a href="#"
                                                                    onclick='seleccheckboxName("lots_management", event)'>Select
                                                                    all</a>&nbsp;</li>
                                                            <li> <span> | </span>&nbsp;<a href="#"
                                                                    onclick='unselectAll("lots_management", event)'>Deselect
                                                                    all</a>&nbsp;</li>
                                                        </ol>
                                                    </nav>
                                                </div>
                                            </div>
                                            <div class="row  p-2">
                                                @foreach($lots_managemnt as $item)
                                                                                                @php
                                                                                                    $lots = json_decode($role->lots_management);
                                                                                                @endphp
                                                                                                @if($lots && in_array($item->id, $lots))
                                                                                                    <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input lots_management" type="checkbox"
                                                                                                            value='{{$item->id}}' name='lots_management[]' checked>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @else
                                                                                                    <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input lots_management" type="checkbox"
                                                                                                            value='{{$item->id}}' name='lots_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="accordion my-4" id="accordionExample1">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingtwo">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#side_bar3" aria-expanded="false"
                                            aria-controls="collapseOne">
                                            District Managament
                                        </button>
                                    </h2>
                                    <div id="side_bar3" class="accordion-collapse collapse "
                                        aria-labelledby="headingtwo" data-bs-parent="#accordionExample1">
                                        <div class="accordion-body ">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <nav aria-label="breadcrumb" class="text-right">
                                                        <ol class="breadcrumb text-right">
                                                            <li><a href="#"
                                                                    onclick='seleccheckboxName("distict_management", event)'>Select
                                                                    all</a>&nbsp;</li>
                                                            <li> <span> | </span>&nbsp;<a href="#"
                                                                    onclick='unselectAll("distict_management", event)'>Deselect
                                                                    all</a>&nbsp;</li>
                                                        </ol>
                                                    </nav>
                                                </div>
                                            </div>
                                            <div class="row  p-2">
                                                @foreach($district_managemnt as $item)
                                                                                                @php
                                                                                                    $district = json_decode($role->district_management);
                                                                                                @endphp
                                                                                                @if($district && in_array($item->id, $district))
                                                                                                    <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input distict_management" type="checkbox"
                                                                                                            value='{{$item->id}}' name='district_management[]' checked>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @else
                                                                                                    <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input distict_management" type="checkbox"
                                                                                                            value='{{$item->id}}' name='district_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @endif                                                  @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="accordion my-4" id="accordionExample1">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingtwo">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#side_bar4" aria-expanded="false"
                                            aria-controls="collapseOne">
                                            Tehsil Managament
                                        </button>
                                    </h2>
                                    <div id="side_bar4" class="accordion-collapse collapse "
                                        aria-labelledby="headingtwo" data-bs-parent="#accordionExample1">
                                        <div class="accordion-body ">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <nav aria-label="breadcrumb" class="text-right">
                                                        <ol class="breadcrumb text-right">
                                                            <li><a href="#"
                                                                    onclick='seleccheckboxName("tehsil_managament", event)'>Select
                                                                    all</a>&nbsp;</li>
                                                            <li> <span> | </span>&nbsp;<a href="#"
                                                                    onclick='unselectAll("tehsil_managament", event)'>Deselect
                                                                    all</a>&nbsp;</li>
                                                        </ol>
                                                    </nav>
                                                </div>
                                            </div>
                                            <div class="row  p-2">
                                                @foreach($tehsil_managemnt as $item)
                                                                                                @php
                                                                                                    $tehsil = json_decode($role->tehsil_management);
                                                                                                @endphp
                                                                                                @if($tehsil && in_array($item->id, $tehsil))
                                                                                                    <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input tehsil_managament" type="checkbox"
                                                                                                            value='{{$item->id}}' name='tehsil_management[]' checked>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @else
                                                                                                    <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input tehsil_managament" type="checkbox"
                                                                                                            value='{{$item->id}}' name='tehsil_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @endif                                                  @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="accordion my-4" id="accordionExample1">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingtwo">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#side_bar5" aria-expanded="false"
                                            aria-controls="collapseOne">
                                            UC Managament
                                        </button>
                                    </h2>
                                    <div id="side_bar5" class="accordion-collapse collapse "
                                        aria-labelledby="headingtwo" data-bs-parent="#accordionExample1">
                                        <div class="accordion-body ">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <nav aria-label="breadcrumb" class="text-right">
                                                        <ol class="breadcrumb text-right">
                                                            <li><a href="#"
                                                                    onclick='seleccheckboxName("uc_management", event)'>Select
                                                                    all</a>&nbsp;</li>
                                                            <li> <span> | </span>&nbsp;<a href="#"
                                                                    onclick='unselectAll("uc_management", event)'>Deselect
                                                                    all</a>&nbsp;</li>
                                                        </ol>
                                                    </nav>
                                                </div>
                                            </div>
                                            <div class="row  p-2">
                                                @foreach($uc as $item)
                                                                                                @php
                                                                                                    $uc = json_decode($role->uc_management);
                                                                                                @endphp
                                                                                                @if($uc && in_array($item->id, $uc))
                                                                                                    <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input uc_management" type="checkbox"
                                                                                                            value='{{$item->id}}' name='uc_management[]' checked>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @else
                                                                                                    <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input uc_management" type="checkbox"
                                                                                                            value='{{$item->id}}' name='uc_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @endif                                                  @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="accordion my-4" id="accordionExample1">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingtwo">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#side_bar6" aria-expanded="false"
                                            aria-controls="collapseOne">
                                            Role Managament
                                        </button>
                                    </h2>
                                    <div id="side_bar6" class="accordion-collapse collapse "
                                        aria-labelledby="headingtwo" data-bs-parent="#accordionExample1">
                                        <div class="accordion-body ">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <nav aria-label="breadcrumb" class="text-right">
                                                        <ol class="breadcrumb text-right">
                                                            <li><a href="#"
                                                                    onclick='seleccheckboxName("role_management", event)'>Select
                                                                    all</a>&nbsp;</li>
                                                            <li> <span> | </span>&nbsp;<a href="#"
                                                                    onclick='unselectAll("role_management", event)'>Deselect
                                                                    all</a>&nbsp;</li>
                                                        </ol>
                                                    </nav>
                                                </div>
                                            </div>
                                            <div class="row  p-2">
                                                @foreach($Role as $item)
                                                                                                @php
                                                                                                    $roles_data = json_decode($role->role_management);
                                                                                                @endphp
                                                                                                @if($roles_data && in_array($item->id, $roles_data))
                                                                                                    <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input role_management" type="checkbox"
                                                                                                            value='{{$item->id}}' checked name='role_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @else
                                                                                                    <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input role_management" type="checkbox"
                                                                                                            value='{{$item->id}}' name='role_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @endif                                                  @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="accordion my-4" id="accordionExample1">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingtwo">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#side_bar8" aria-expanded="false"
                                            aria-controls="collapseOne">
                                            Logs Managament
                                        </button>
                                    </h2>
                                    <div id="side_bar8" class="accordion-collapse collapse "
                                        aria-labelledby="headingtwo" data-bs-parent="#accordionExample1">
                                        <div class="accordion-body ">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <nav aria-label="breadcrumb" class="text-right">
                                                        <ol class="breadcrumb text-right">
                                                            <li><a href="#"
                                                                    onclick='seleccheckboxName("logs_management", event)'>Select
                                                                    all</a>&nbsp;</li>
                                                            <li> <span> | </span>&nbsp;<a href="#"
                                                                    onclick='unselectAll("logs_management", event)'>Deselect
                                                                    all</a>&nbsp;</li>
                                                        </ol>
                                                    </nav>
                                                </div>
                                            </div>
                                            <div class="row  p-2">
                                                @foreach($logs as $item)
                                                                                                @php
                                                                                                    $logs_data = json_decode($role->logs_management);
                                                                                                @endphp
                                                                                                @if($logs_data && in_array($item->id, $logs_data))
                                                                                                    <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input logs_management" type="checkbox"
                                                                                                            value='{{$item->id}}' checked name='logs_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @else                                                   <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input logs_management" type="checkbox"
                                                                                                            value='{{$item->id}}' name='logs_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="accordion my-4" id="accordionExample1">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingthree">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#side_bar9" aria-expanded="false"
                                            aria-controls="collapseOne">
                                            Form Managament
                                        </button>
                                    </h2>
                                    <div id="side_bar9" class="accordion-collapse collapse "
                                        aria-labelledby="headingthree" data-bs-parent="#accordionExample1">
                                        <div class="accordion-body ">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <nav aria-label="breadcrumb" class="text-right">
                                                        <ol class="breadcrumb text-right">
                                                            <li><a href="#"
                                                                    onclick='seleccheckboxName("form_management", event)'>select
                                                                    all</a>&nbsp;</li>
                                                            <li> <span> | </span>&nbsp;<a href="#"
                                                                    onclick='unselectAll("form_management", event)'>Deselect
                                                                    all</a>&nbsp;</li>
                                                        </ol>
                                                    </nav>
                                                </div>
                                            </div>
                                            <div class="row  p-2">
                                                @foreach($form as $item)
                                                                                                @php
                                                                                                    $form_data = json_decode($role->form_management);
                                                                                                @endphp
                                                                                                @if($form_data && in_array($item->id, $form_data))
                                                                                                    <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input form_management" type="checkbox"
                                                                                                            value='{{$item->id}}' checked name='form_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @else                                                   <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input form_management" type="checkbox"
                                                                                                            value='{{$item->id}}' name='form_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="accordion my-4" id="accordionExample1">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingthree">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#side_bar10" aria-expanded="false"
                                            aria-controls="collapseOne">
                                            PDMA Managament
                                        </button>
                                    </h2>
                                   <div id="side_bar10" class="accordion-collapse collapse "
                                        aria-labelledby="headingthree" data-bs-parent="#accordionExample1">
                                        <div class="accordion-body ">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <nav aria-label="breadcrumb" class="text-right">
                                                        <ol class="breadcrumb text-right">
                                                            <li><a href="#"
                                                                    onclick='seleccheckboxName("pdma_management", event)'>select
                                                                    all</a>&nbsp;</li>
                                                            <li> <span> | </span>&nbsp;<a href="#"
                                                                    onclick='unselectAll("pdma_management", event)'>Deselect
                                                                    all</a>&nbsp;</li>
                                                        </ol>
                                                    </nav>
                                                </div>
                                            </div>
                                            <div class="row  p-2">
                                                @foreach($pdma as $item)
                                                                                                @php
                                                                                                    $pdma_data = json_decode($role->pdma_management);
                                                                                                @endphp
                                                                                                @if($pdma_data && in_array($item->id, $pdma_data))
                                                                                                    <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input pdma_management" type="checkbox"
                                                                                                            value='{{$item->id}}' checked name='pdma_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @else  
                                                                                                <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input pdma_management" type="checkbox"
                                                                                                            value='{{$item->id}}' name='pdma_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                          
                                </div>
                                
                        </div>    
                        </div>
                        <div class="row">
                            <div class="accordion my-4" id="accordionExample1">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingthree">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#side_bar11" aria-expanded="false"
                                            aria-controls="collapseOne">
                                            Zone Managament
                                        </button>
                                    </h2>
                                   <div id="side_bar11" class="accordion-collapse collapse "
                                        aria-labelledby="headingthree" data-bs-parent="#accordionExample1">
                                        <div class="accordion-body ">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <nav aria-label="breadcrumb" class="text-right">
                                                        <ol class="breadcrumb text-right">
                                                            <li><a href="#"
                                                                    onclick='seleccheckboxName("zone_management", event)'>Select
                                                                    all</a>&nbsp;</li>
                                                            <li> <span> | </span>&nbsp;<a href="#"
                                                                    onclick='unselectAll("zone_management", event)'>Deselect
                                                                    all</a>&nbsp;</li>
                                                        </ol>
                                                    </nav>
                                                </div>
                                            </div>
                                            <div class="row  p-2">
                                                @foreach($zone as $item)
                                                                                                @php
                                                                                                    $zone_data = json_decode($role->zone_management);
                                                                                                @endphp
                                                                                                @if($zone_data && in_array($item->id, $zone_data))
                                                                                                    <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input zone_management" type="checkbox"
                                                                                                            value='{{$item->id}}' checked name='zone_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @else  
                                                                                                <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input zone_management" type="checkbox"
                                                                                                            value='{{$item->id}}' name='zone_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                          
                                </div>
                                
                        </div>    
                        </div>
                        <div class="row">
                            <div class="accordion my-4" id="accordionExample1">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="finance_heading">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#side_bar15" aria-expanded="false"
                                            aria-controls="collapseOne">
                                            Finance Managament
                                        </button>
                                    </h2>
                                   <div id="side_bar15" class="accordion-collapse collapse "
                                        aria-labelledby="finance_heading" data-bs-parent="#accordionExample1">
                                        <div class="accordion-body ">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <nav aria-label="breadcrumb" class="text-right">
                                                        <ol class="breadcrumb text-right">
                                                            <li><a href="#"
                                                                    onclick='seleccheckboxName("finance_management", event)'>Select
                                                                    all</a>&nbsp;</li>
                                                            <li> <span> | </span>&nbsp;<a href="#"
                                                                    onclick='unselectAll("finance_management", event)'>Deselect
                                                                    all</a>&nbsp;</li>
                                                        </ol>
                                                    </nav>
                                                </div>
                                            </div>
                                            <div class="row  p-2">
                                                @foreach($finance as $item)
                                                                                                @php
                                                                                                    $finance_data = json_decode($role->finance_management);
                                                                                                @endphp
                                                                                                @if($finance_data && in_array($item->id, $finance_data))
                                                                                                    <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input finance_management" type="checkbox"
                                                                                                            value='{{$item->id}}' checked name='finance_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @else  
                                                                                                <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input finance_management" type="checkbox"
                                                                                                            value='{{$item->id}}' name='finance_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                          
                                </div>
                                
                        </div>    
                        </div>
                        <div class="row">
                            <div class="accordion my-4" id="accordionExample1">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="report_heading">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#side_bar16" aria-expanded="false"
                                            aria-controls="collapseOne">
                                            Report Managament
                                        </button>
                                    </h2>
                                   <div id="side_bar16" class="accordion-collapse collapse "
                                        aria-labelledby="report_heading" data-bs-parent="#accordionExample1">
                                        <div class="accordion-body ">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <nav aria-label="breadcrumb" class="text-right">
                                                        <ol class="breadcrumb text-right">
                                                            <li><a href="#"
                                                                    onclick='seleccheckboxName("report_management", event)'>Select
                                                                    all</a>&nbsp;</li>
                                                            <li> <span> | </span>&nbsp;<a href="#"
                                                                    onclick='unselectAll("report_management", event)'>Deselect
                                                                    all</a>&nbsp;</li>
                                                        </ol>
                                                    </nav>
                                                </div>
                                            </div>
                                            <div class="row  p-2">
                                                @foreach($report as $item)
                                                                                                @php
                                                                                                    $report_data = json_decode($role->report_management);
                                                                                                @endphp
                                                                                                @if($report_data && in_array($item->id, $report_data))
                                                                                                    <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input report_management" type="checkbox"
                                                                                                            value='{{$item->id}}' checked name='report_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @else  
                                                                                                <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input report_management" type="checkbox"
                                                                                                            value='{{$item->id}}' name='report_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                          
                                </div>
                                
                        </div>    
                        </div>
                        <div class="row">
                            <div class="accordion my-4" id="accordionExample1">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="report_heading">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#side_bar17" aria-expanded="false"
                                            aria-controls="collapseOne">
                                            Bank Managament
                                        </button>
                                    </h2>
                                   <div id="side_bar17" class="accordion-collapse collapse "
                                        aria-labelledby="report_heading" data-bs-parent="#accordionExample1">
                                        <div class="accordion-body ">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <nav aria-label="breadcrumb" class="text-right">
                                                        <ol class="breadcrumb text-right">
                                                            <li><a href="#"
                                                                    onclick='seleccheckboxName("bank_management", event)'>Select
                                                                    all</a>&nbsp;</li>
                                                            <li> <span> | </span>&nbsp;<a href="#"
                                                                    onclick='unselectAll("bank_management", event)'>Deselect
                                                                    all</a>&nbsp;</li>
                                                        </ol>
                                                    </nav>
                                                </div>
                                            </div>
                                          
                                            <div class="row  p-2">
                                                @foreach($bank as $item)
                                                
                                                                                                @php
                                                                                                    $bank_data = json_decode($role->bank_management);
                                                                                                    
                                                                                                @endphp
                                                                                                @if($bank_data && in_array($item->id, $bank_data))
                                                                                                    <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input bank_management" type="checkbox"
                                                                                                            value='{{$item->id}}' checked name='bank_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @else  
                                                                                                <div class="form-check col-md-3">
                                                                                                        <input class="form-check-input bank_management" type="checkbox"
                                                                                                            value='{{$item->id}}' name='bank_management[]'>
                                                                                                        <label class="form-check-label">
                                                                                                            {{$item->name}}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                          
                                </div>
                                
                        </div>    
                        </div>
                                
                            @php
                                $types = [
                                'field_supervisor' => 'In the team of Field Supervisor', 
                                'IP' => 'In the team of IP', 
                                'HRU' => 'In the team of HRU','PSIA'=>'In the team of PSIA',
                                'HRU_Main'=>'In the team of HRU Main',
                                'COO'=>'In the team of COO',
                                'CEO'=>'In the team of CEO'
                                ];
                            @endphp
                            <div class='row' style='margin-top:4px;'>
                                    <lable>Allow to update the status of survey form</label>
                                    <select name='allow_to_update_form' class='form-control'>
                                            <option>Select Option</option>
                                                @foreach ($types as $Key =>$type)
                                                <option value="{{$Key}}"  {{$Key == $role->allow_to_update_form ? 'selected' : ''}} >{{$type}}</option>
                                                @endforeach
                                            </select>
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

    <script>
        function seleccheckboxName(checkboxClass, event) {
            event.preventDefault();
            console.log("ok click");

            $(`.${checkboxClass}`).prop('checked', true);
        }

        // Function to handle the unselect all for checkboxes
        function unselectAll(checkboxClass, event) {
            event.preventDefault();
            $(`.${checkboxClass}`).prop('checked', false);

        }
        $("#form").on('submit', function () {
            // for each unchecked checkbox
            $(this).find('input[type=checkbox]:not(:checked)').each(function () {
                // set value 0 and check it
                $(this).prop('checked', true).val(0);
            });
        });
    </script>
    @endsection