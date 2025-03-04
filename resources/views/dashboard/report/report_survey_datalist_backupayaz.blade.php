@extends('dashboard.layout.master')
@section('content')

<div class="content">
    <!-- Navbar Start -->
     @include('dashboard.layout.navbar')
    <!-- Navbar End -->
    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">SURVEY FORM MASTER REPORTING</h6>
            </div>
            
            <div class="row">

            <div class="col-md-12">
                <div class="pdmadatalist">
                                  <!--Toolbar-->
                                    	<div class="toolbar">
                                        	<div class="filters-toolbar-wrapper">
                                        	    
                                        	    
                                        	    <div class="row">
                										<div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Search By Start Date</label>
                										{{ Form::date('start_date',null,['class'=>'form-control', 'id'=>'start_date','placeholder'=>'Start Date']) }}
                										</div>
                										
                										<div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Search By End Date</label>
                										{{ Form::date('end_date',null,['class'=>'form-control', 'id'=>'end_date','placeholder'=>'End Date']) }}
                										</div>
                										
                										<div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Search By Refrence Number</label>
                										{{ Form::text('b_reference_number',null,['class'=>'form-control', 'id'=>'b_reference_number','placeholder'=>'Refrence Number']) }}
                										</div>
                										
                										{{--
                										<div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Search By Status</label>
                										{!! Form::select('status', ['P'=>'Pending', 'R'=>'Reject','A'=>'Approved', 'H'=>'Hold'], null, ['placeholder' => 'Select Status', 'class' => 'status form-control select2', 'id'=>'status']) !!}
                										</div>
                										--}}
                										
                										
                										
                										<div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Search By Status</label>
                										@if(Auth::user()->role == 27)
                										
                										@elseif(Auth::user()->role == 30)
                										{!! Form::select('status', ['P'=>'Pending', 'H'=>'Hold','A'=>'Approved List','R'=>'Rejected List', '34'=>'Rejected By IP'], 'P', ['placeholder' => 'Select Status', 'class' => 'status form-control select2', 'id'=>'status']) !!}
                										@elseif(Auth::user()->role == 34)
                										{!! Form::select('status', ['P'=>'Pending', 'H'=>'Hold','A'=>'Approved List','R'=>'Rejected List', '36'=>'Rejected By HRU'], 'P', ['placeholder' => 'Select Status', 'class' => 'status form-control select2', 'id'=>'status']) !!}
                										@elseif(Auth::user()->role == 36)
                										{!! Form::select('status', ['P'=>'Pending', 'H'=>'Hold','A'=>'Approved List','R'=>'Rejected List', '37'=>'Rejected By PSIA'], 'P', ['placeholder' => 'Select Status', 'class' => 'status form-control select2', 'id'=>'status']) !!}
                										@elseif(Auth::user()->role == 37)
                										{!! Form::select('status', ['P'=>'Pending', 'H'=>'Hold','A'=>'Approved List','R'=>'Rejected List', '38'=>'Rejected By HRU_MAIN'], 'P', ['placeholder' => 'Select Status', 'class' => 'status form-control select2', 'id'=>'status']) !!}
                										@elseif(Auth::user()->role == 38)
                										{!! Form::select('status', ['P'=>'Pending', 'H'=>'Hold','A'=>'Approved List','R'=>'Rejected List', '40'=>'Rejected By CEO'], 'P', ['placeholder' => 'Select Status', 'class' => 'status form-control select2', 'id'=>'status']) !!}
                										@elseif(Auth::user()->role == 40)
                										{!! Form::select('status', ['P'=>'Pending', 'H'=>'Hold','A'=>'Approved List','R'=>'Rejected List'], 'P', ['placeholder' => 'Select Status', 'class' => 'status form-control select2', 'id'=>'status']) !!}
                										@else
                										{!! Form::select('status', ['P'=>'Pending', 'H'=>'Hold'], null, ['placeholder' => 'Select Status', 'class' => 'status form-control select2', 'id'=>'status']) !!}
                										@endif
                										</div>
                										
                										

                									
                                                    </div>

                                        	    
                                        	    <div class="row">
                                        	<div class="filters-toolbar__item mb-3 col-md-3">
                                                <label class="form-label">{{ __('Search By Lot') }}</label>
                                                {!! Form::select('lot', $lots, null, array('placeholder' => 'Select Lot', 'class' => 'lot form-control select2', 'id'=>'lot_id')) !!}
                                            </div>        
                                            <div class="filters-toolbar__item mb-3 col-md-3">
                                                <label class="form-label">{{ __('Search By District') }}</label>
                                                <div class="district_list">{!! Form::select('district', [], null, ['placeholder' => 'Select District', 'class' => 'form-control select2', 'id'=>'district_id']) !!}</div>
                                            </div>
                                            <div class="filters-toolbar__item mb-3 col-md-3">
                                                <label class="form-label">{{ __('Search By Tehsil') }}</label>
                                                <div class="tehsil_list">{!! Form::select('tehsil', [], null, ['placeholder' => 'Select Tehsil', 'class' => 'form-control select2', 'id'=>'tehsil_id']) !!}</div>
                                            </div>
                                            <div class="filters-toolbar__item mb-3 col-md-3">
                                                <label class="form-label">{{ __('Search By UC') }}</label>
                                                <div class="uc_list">{!! Form::select('uc', [], null, ['placeholder' => 'Select UC', 'class' => 'form-control select2', 'id'=>'uc_id']) !!}</div>
                                            </div>
                                        </div>
                                        	    
                                            	{{--
                                            	<div class="row">
                										<div class="filters-toolbar__item mb-3 col-md-4">
                										<label for="Search">Search By Refrence Number</label>
                										{{ Form::text('b_reference_number',null,['class'=>'form-control', 'id'=>'b_reference_number','placeholder'=>'Refrence Number']) }}
                										</div>
                										
                										<div class="filters-toolbar__item mb-3 col-md-4">
                										<label for="Search">Search By Beneficiary Name</label>
                										{{ Form::text('beneficiary_name',null,['class'=>'form-control', 'id'=>'beneficiary_name','placeholder'=>'Beneficiary Name']) }}
                										</div>
                									
                										<div class="filters-toolbar__item mb-3 col-md-4">
                                                      <label for="Search">Search By CNIC</label>
                                                       {{ Form::text('cnic',null,['class'=>'form-control', 'id'=>'cnic','placeholder'=>'CNIC']) }}
                                                    </div>
                									
                                                    </div>
                                                    --}}
                                                    
                                                    <div class="row">
                										<div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Search By Gender</label>
                										{!! Form::select('gender', $gender, null, ['placeholder' => 'Select Gender', 'class' => 'gender form-control select2', 'id'=>'gender']) !!}
                										</div>
                										
                										<div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Search By Disability</label>
                										{!! Form::select('disability', ['No'=>'No', 'Yes'=>'Yes'], null, ['placeholder' => 'Select Disability', 'class' => 'disability form-control select2', 'id'=>'disability']) !!}
                										</div>
                										@if(Auth::user()->role==51)
                										<div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Review By MNE</label>
                										{!! Form::select('Review By MNE', ['No'=>'No', 'Yes'=>'Yes'], null, ['placeholder' => 'Select Status', 'class' => 'review_by_mne form-control select2', 'id'=>'review_by_mne']) !!}
                										</div>
                										@endif
                										<div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Search By Landownership</label>
                										{!! Form::select('landownership', $landownership, null, ['placeholder' => 'Select Landownership', 'class' => 'landownership form-control select2', 'id'=>'landownership']) !!}
                										</div>
                										
                										<div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Search By Bank A/C</label>
                										{!! Form::select('bank_ac_wise', ['No'=>'No', 'Yes'=>'Yes'], null, ['placeholder' => 'Select Bank A/C', 'class' => 'bank_ac_wise form-control select2', 'id'=>'bank_ac_wise']) !!}
                										</div>
                										
 
                									
                                                    </div>
                                                    
                                                    <div class="row">
                										<div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Socio Legal Status</label>
                										{!! Form::select('socio_legal_status', $socio_legal_status, null, ['placeholder' => 'Select Socio Legal Status', 'class' => 'socio_legal_status form-control select2', 'id'=>'socio_legal_status']) !!}
                										</div>
                										
                										<div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Search By Evidence Type</label>
                										{!! Form::select('evidence_type', $evidence_type, null, ['placeholder' => 'Select Evidence Type', 'class' => 'evidence_type form-control select2', 'id'=>'evidence_type']) !!}
                										</div>
                										
                										<div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Search By Status of Land</label>
                										{!! Form::select('status_of_land', $status_of_land, null, ['placeholder' => 'Select Status of Land', 'class' => 'status_of_land form-control select2', 'id'=>'status_of_land']) !!}
                										</div>
                										
                										<div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Proposed Beneficiary</label>
                										{!! Form::select('proposed_beneficiary', ['No'=>'No', 'Yes'=>'Yes'], null, ['placeholder' => 'Select Proposed Beneficiary', 'class' => 'proposed_beneficiary form-control select2', 'id'=>'proposed_beneficiary']) !!}
                										
                										</div>
 
                									
                                                    </div>
                                                    
                                                    <div class="row">
                										<div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Search By Reconstruction</label>
                										{!! Form::select('reconstruction_wise', ['No'=>'No', 'Yes'=>'Yes'], null, ['placeholder' => 'Select Reconstruction', 'class' => 'reconstruction_wise form-control select2', 'id'=>'reconstruction_wise']) !!}
                										</div>
                										
                										<div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Search By Construction Type</label>
                										{!! Form::select('construction_type', $construction_type, null, ['placeholder' => 'Select Construction Type', 'class' => 'construction_type form-control select2', 'id'=>'construction_type']) !!}
                										</div>
                										
                										<div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Search By Damage</label>
                										{!! Form::select('damage_type', ['No'=>'No', 'Yes'=>'Yes'], null, ['placeholder' => 'Select Damage', 'class' => 'damage_type form-control select2', 'id'=>'damage_type']) !!}
                										</div>
                										
                									    {{--
                										<div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Search By Department</label>
                										@if(Auth::user()->role==1 || Auth::user()->role==51)
                										{!! Form::select('form_status', ['validator'=>'Validator', 'field supervisor'=>'Field Supervisor', 'IP'=>'IP', 'HRU'=>'HRU', 'PSIA'=>'PSIA', 'HRU_MAIN'=>'HRU_MAIN', 'CEO'=>'CEO'], null, ['placeholder' => 'Select Department', 'class' => 'form_status form-control select2', 'id'=>'form_status']) !!}
                										@elseif(Auth::user()->role==40)
                										{!! Form::select('form_status', ['validator'=>'Validator', 'field supervisor'=>'Field Supervisor', 'IP'=>'IP', 'HRU'=>'HRU', 'PSIA'=>'PSIA', 'HRU_MAIN'=>'HRU_MAIN', 'CEO'=>'CEO'], 'CEO', ['placeholder' => 'Select Department', 'class' => 'form_status form-control select2', 'id'=>'form_status']) !!}
                										@elseif(Auth::user()->role==38)
                										{!! Form::select('form_status', ['validator'=>'Validator', 'field supervisor'=>'Field Supervisor', 'IP'=>'IP', 'HRU'=>'HRU', 'PSIA'=>'PSIA', 'HRU_MAIN'=>'HRU_MAIN'], 'HRU_MAIN', ['placeholder' => 'Select Department', 'class' => 'form_status form-control select2', 'id'=>'form_status']) !!}
                										@elseif(Auth::user()->role==37)
                										{!! Form::select('form_status', ['validator'=>'Validator', 'field supervisor'=>'Field Supervisor', 'IP'=>'IP', 'HRU'=>'HRU', 'PSIA'=>'PSIA'], 'PSIA', ['placeholder' => 'Select Department', 'class' => 'form_status form-control select2', 'id'=>'form_status']) !!}
                										@elseif(Auth::user()->role==36)
                										{!! Form::select('form_status', ['validator'=>'Validator', 'field supervisor'=>'Field Supervisor', 'IP'=>'IP', 'HRU'=>'HRU'], 'HRU', ['placeholder' => 'Select Department', 'class' => 'form_status form-control select2', 'id'=>'form_status']) !!}
                										@elseif(Auth::user()->role==34)
                										{!! Form::select('form_status', ['validator'=>'Validator', 'field supervisor'=>'Field Supervisor', 'IP'=>'IP'], 'IP', ['placeholder' => 'Select Department', 'class' => 'form_status form-control select2', 'id'=>'form_status']) !!}
                										@elseif(Auth::user()->role==30)
                										{!! Form::select('form_status', ['validator'=>'Validator', 'field supervisor'=>'Field Supervisor'], 'field supervisor', ['placeholder' => 'Select Department', 'class' => 'form_status form-control select2', 'id'=>'form_status']) !!}
                										@elseif(Auth::user()->role==27)
                										{!! Form::select('form_status', ['validator'=>'Validator'], 'validator', ['placeholder' => 'Select Department', 'class' => 'form_status form-control select2', 'id'=>'form_status']) !!}
                										@endif
                										</div>
                										--}}
                										
                										
                										
                							@if(Auth::user()->role==1 || Auth::user()->role==51)			
                							<div class="filters-toolbar__item mb-3 col-md-3">
                								<label for="Search">Search By Department New</label>
                                            {!! Form::select('department', ['27' => 'Validator', '30' => 'FS', '34' => 'IP', '36' => 'HRU', '37' => 'PSIA', '38' => 'HRU MAIN', '40' => 'CEO'], null, array('placeholder' => 'Select Department', 'class' => 'department form-control select2', 'id'=>'department')) !!}   
                                            {{--
                                            @if(Auth::user()->role==1 || Auth::user()->role==51)
    										{!! Form::select('department', ['27' => 'Validator', '30' => 'FS', '34' => 'IP', '36' => 'HRU', '37' => 'PSIA', '38' => 'HRU MAIN', '40' => 'CEO'], null, array('placeholder' => 'Select Department', 'class' => 'department form-control select2', 'id'=>'department')) !!}
    										@elseif(Auth::user()->role==40)
    										{!! Form::select('department', ['27' => 'Validator', '30' => 'FS', '34' => 'IP', '36' => 'HRU', '37' => 'PSIA', '38' => 'HRU MAIN', '40' => 'CEO'], Auth::user()->role, array('placeholder' => 'Select Department', 'class' => 'department form-control select2', 'id'=>'department')) !!}
    										@elseif(Auth::user()->role==38)
    										{!! Form::select('department', ['27' => 'Validator', '30' => 'FS', '34' => 'IP', '36' => 'HRU', '37' => 'PSIA', '38' => 'HRU MAIN'], Auth::user()->role, array('placeholder' => 'Select Department', 'class' => 'department form-control select2', 'id'=>'department')) !!}
    										@elseif(Auth::user()->role==37)
    										{!! Form::select('department', ['27' => 'Validator', '30' => 'FS', '34' => 'IP', '36' => 'HRU', '37' => 'PSIA'], Auth::user()->role, array('placeholder' => 'Select Department', 'class' => 'department form-control select2', 'id'=>'department')) !!}
    										@elseif(Auth::user()->role==36)
    										{!! Form::select('department', ['27' => 'Validator', '30' => 'FS', '34' => 'IP', '36' => 'HRU'], Auth::user()->role, array('placeholder' => 'Select Department', 'class' => 'department form-control select2', 'id'=>'department')) !!}
    										@elseif(Auth::user()->role==34)
    										{!! Form::select('department', ['27' => 'Validator', '30' => 'FS', '34' => 'IP'], Auth::user()->role, array('placeholder' => 'Select Department', 'class' => 'department form-control select2', 'id'=>'department')) !!}
    										@elseif(Auth::user()->role==30)
    										{!! Form::select('department', ['27' => 'Validator', '30' => 'FS'], Auth::user()->role, array('placeholder' => 'Select Department', 'class' => 'department form-control select2', 'id'=>'department')) !!}
    										@elseif(Auth::user()->role==27)
    										{!! Form::select('department', ['27' => 'Validator'], Auth::user()->role, array('placeholder' => 'Select Department', 'class' => 'department form-control select2', 'id'=>'department')) !!}
    										@endif
    										--}}
                                            </div>
                                            @else
                                            {{ Form::hidden('department',Auth::user()->role,['id'=>'department']) }}
                                            @endif
                                            
                                            
                										
                										
 
                									
                                                    </div>



                                                    
                <div class="row">
                                                    
                                                <div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Is Vulnerable</label>
                										{!! Form::select('vulnerable', ['No'=>'No', 'Yes'=>'Yes'], null, ['placeholder' => 'Select Vulnerable', 'class' => 'vulnerable form-control select2', 'id'=>'vulnerable']) !!}
                								</div>
                							    <div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Vulnerability</label>
                										{!! Form::select('vulnerabilities', $vulnerabilities, null, ['placeholder' => 'Select Vulnerability', 'class' => 'vulnerabilities form-control select2', 'id'=>'vulnerabilities']) !!}
                								</div>
                										
                                                <div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Search By Pending Days</label>
                										{!! Form::select('pending_days', ['4'=>'0 To 5 Days', '5'=>'5 Days Up', '10'=>'10 Days Up','15'=>'15 Days Up'], null, ['placeholder' => 'Select Pending Days', 'class' => 'pending_days form-control select2', 'id'=>'pending_days']) !!}
                								</div>
                								
                								
                								{{--		
                								<div class="filters-toolbar__item mb-3 col-md-3">
                										<label for="Search">Search By Action</label>
                										@if(Auth::user()->role==1 || Auth::user()->role==40 || Auth::user()->role==51)
                										{!! Form::select('not_action', ['not_action_fs'=>'No Action Perform', 'not_action_ip' => 'Pending List IP', 'not_action_hru' => 'Pending List HRU', 'not_action_psia' => 'Pending List PSIA', 'not_action_hru_main' => 'Pending List HRU_MAIN', 'not_action_ceo' => 'Pending List CEO'], null, ['placeholder' => 'Select Pending List', 'class' => 'form_status form-control select2', 'id'=>'not_action']) !!}
                										@elseif(Auth::user()->role==38)
                										{!! Form::select('not_action', ['not_action_fs'=>'No Action Perform', 'not_action_ip' => 'Pending List IP', 'not_action_hru' => 'Pending List HRU', 'not_action_psia' => 'Pending List PSIA', 'not_action_hru_main' => 'Pending List HRU_MAIN', 'reject_by_ceo' => 'Rejected By CEO'], null, ['placeholder' => 'Select Pending List', 'class' => 'form_status form-control select2', 'id'=>'not_action']) !!}
                										@elseif(Auth::user()->role==37)
                										{!! Form::select('not_action', ['not_action_fs'=>'No Action Perform', 'not_action_ip' => 'Pending List IP', 'not_action_hru' => 'Pending List HRU', 'not_action_psia' => 'Pending List PSIA', 'reject_by_hru_main' => 'Rejected By HRU_MAIN'], null, ['placeholder' => 'Select Pending List', 'class' => 'form_status form-control select2', 'id'=>'not_action']) !!}
                										@elseif(Auth::user()->role==36)
                										{!! Form::select('not_action', ['not_action_fs'=>'No Action Perform', 'not_action_ip' => 'Pending List IP', 'reject_by_psia' => 'Rejected By PSIA'], null, ['placeholder' => 'Select Pending List', 'class' => 'form_status form-control select2', 'id'=>'not_action']) !!}
                										@elseif(Auth::user()->role==34)
                										{!! Form::select('not_action', ['not_action_fs'=>'No Action Perform', 'reject_by_hru' => 'Rejected By HRU'], null, ['placeholder' => 'Select Pending List', 'class' => 'form_status form-control select2', 'id'=>'not_action']) !!}
                										@elseif(Auth::user()->role==30)
                										{!! Form::select('not_action', ['not_action_fs'=>'No Action Perform', 'reject_by_ip' => 'Rejected By IP'], null, ['placeholder' => 'Select Pending List', 'class' => 'form_status form-control select2', 'id'=>'not_action']) !!}
                										@elseif(Auth::user()->role==27)
                										{!! Form::select('not_action', ['not_action_fs'=>'No Action Perform'], null, ['placeholder' => 'Select Pending List', 'class' => 'form_status form-control select2', 'id'=>'not_action']) !!}
                										@endif
                								</div>
                								--}}
                								
                </div>                                    
                <div class="row">										
                										
                									<div class="filters-toolbar__item mb-3 col-md-4">
                                                      <label for="Sorting">Sort By</label>
                                                      {{ Form::select('sorting',[ 'id' => 'Survey ID', 'ref_no' => 'Refrence Number','total_scores' => 'Priority Level'],'total_scores',['class'=>'form-control', 'id'=>'sorting']) }}
                                                    </div>
                                                    
                									<div class="filters-toolbar__item mb-3 col-md-4">
                                                      <label for="Direction">Direction</label>
                                                      {{ Form::select('direction',['asc' => 'ASC', 'desc' => 'DESC'],'desc',['class'=>'form-control', 'id'=>'direction']) }}
                                                    </div>
                                                    
                										
                									<div class="filters-toolbar__item mb-3 col-md-4">
                                                      <label for="Quantity">Quantity</label>
                                                      {{ Form::select('qty', [5=>5, 10=>10, 25=>25, 50=>50, 100=>100, 500=>500, 1000=>1000, 5000=>5000], 5,['class'=>'form-control', 'id'=>'qty']) }}
                                                    </div>

                                                </div>
                
                @if(Auth::user()->role == 40 || Auth::user()->role == 36)              
                <div class="row"><div class="col-md-12">               
                <form method='post' action='{{route("bulkApprove")}}'>
                @csrf
                <input type='hidden' name='survey_ids' id='survey_ids'>
                <input type='hidden' value='A' name='status' id='status'>
                @if(Auth::user()->role == 40)
                <input type='hidden' value='CEO' name='role' id='role'>
                @elseif(Auth::user()->role == 36)
                <input type='hidden' value='HRU' name='role' id='role'>
                @endif
                <input type='submit' name='action' value='Approved' class='btn btn-success'>
                <input type='submit' name='action' value='Reject' class='btn btn-danger'>
                <input type='submit' name='action' value='Hold' class='btn btn-primary'>
                </form></div></div> 
                @endif
                
                                                
                                                
                                                
                                                
                                                
                                                
                                            </div>
                                        </div>
                                        <!--End Toolbar-->
                                    <div class="filter_data"></div>
                                </div>
            </div> 
      
            </div>
        </div>
    </div>

    
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


<x-frontend.survey.reporttrail_modal />
@endsection
@push('ayiscss')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<style>
.pdmadatalist .form-group{margin-bottom:15px;}
.pdmadatalist label{display:block; text-align:left;} 
.pdmadatalist .select2-container{width:100%!important; text-align:left;}
</style>
@endpush
@push('ayisscript') 
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$(document).ready(function() {
$('.select2').select2();    
    
	$('body').on('change', '.lot', function(e){
     e.preventDefault();
		var lot_id = $(e.target).find("option:selected").val();
		$.ajax({
              url: "{{ route('report.fetch_district_list') }}",
              type: 'POST',
              data: {lot_id: lot_id, _token: '{{csrf_token()}}'},
              beforeSend: function(){
                  $('.district_list').html('District list Processing...');
                  $('.tehsil_list').html('Tehsil list Processing...');
                  $('.uc_list').html('UC list Processing...');
              },
              success: function (response) {
                 $('.district_list').empty();				 
				 $('.district_list').html(response);
				 $('.tehsil_list').empty();				 
				 $('.tehsil_list').html('<select name="tehsil_id" class="form-control select2" id="tehsil_id"><option value="">Select Tehsil</option></select>');
                 $('.uc_list').empty();
                 $('.uc_list').html('<select name="uc_id" class="form-control select2" id="uc_id"><option value="">Select UC</option></select>');				 
				 $('.select2').select2();
				 //filter_data();
			  },
              error: function (response){$('.district_list').empty(); $('.tehsil_list').empty(); $('.uc_list').empty(); }
              });
    });
	
	$('body').on('change', '.district', function(e){
     e.preventDefault();
		var district_id = $(e.target).find("option:selected").val();
		$.ajax({
              url: "{{ route('report.fetch_tehsil_list') }}",
              type: 'POST',
              data: {district_id: district_id, _token: '{{csrf_token()}}'},
              beforeSend: function(){
                  $('.tehsil_list').html('Tehsil list Processing...');
                  $('.uc_list').html('UC list Processing...');
              },
              success: function (response) {
				 $('.tehsil_list').empty();				 
				 $('.tehsil_list').html(response);
                 $('.uc_list').empty();
                 $('.uc_list').html('<select name="uc_id" class="form-control select2" id="uc_id"><option value="">Select UC</option></select>');				 
				 $('.select2').select2();
				 filter_data();
			  },
              error: function (response){$('.tehsil_list').empty(); $('.uc_list').empty(); }
              });
    });
	
	$('body').on('change', '.tehsil', function(e){
     e.preventDefault();
		var tehsil_id = $(e.target).find("option:selected").val();
		$.ajax({
              url: "{{ route('report.fetch_uc_list') }}",
              type: 'POST',
              data: {tehsil_id: tehsil_id, _token: '{{csrf_token()}}'},
              beforeSend: function(){$('.uc_list').html('UC List Processing...');},
              success: function (response) {
				 $('.uc_list').empty(); 
				 $('.uc_list').html(response); 
				 $('.select2').select2();
				 filter_data();
			  },
              error: function (response){$('.uc_list').empty();}
              });
    });
	
	
	
	
});

$(document).ready(function(){	
    filter_data();
    function filter_data(currentpage, ayis_survey_id)
    {
        $('.filter_data').html('<div id="loading"></div>');
        var action = 'fetch_data';
        var sorting = $("#sorting").val();
        var direction = $("#direction").val();
        var qty = $("#qty").val();
        
        var lot_id = $("#lot_id").val();
        var district_id = $("#district_id").val();
        var tehsil_id = $("#tehsil_id").val();
        var uc_id = $("#uc_id").val();
        
        var gender = $("#gender").val();
        var disability = $("#disability").val();
        var vulnerable = $("#vulnerable").val();
        var vulnerabilities = $("#vulnerabilities").val();
        var review_by_mne = $("#review_by_mne").val();
        var landownership = $("#landownership").val();
        var bank_ac_wise = $("#bank_ac_wise").val();
        var reconstruction_wise = $("#reconstruction_wise").val();
        var construction_type = $("#construction_type").val();
        var damage_type = $("#damage_type").val();
        
        var socio_legal_status = $("#socio_legal_status").val();
        var evidence_type = $("#evidence_type").val();
        var status_of_land = $("#status_of_land").val();
        var proposed_beneficiary = $("#proposed_beneficiary").val();
        
        
        
        var department = $("#department").val();
        var form_status = $("#form_status").val();
        
        var status = $("#status").val();
        var not_action = $("#not_action").val();
        var pending_days = $("#pending_days").val();
        
        
        

        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        
        var beneficiary_name = $("#beneficiary_name").val();
        var b_reference_number = $("#b_reference_number").val();
		var cnic = $("#cnic").val();
		
		
		
        //var colors = get_filter('color');
        

        
        var bulk_survey_id =  ayis_survey_id ?? '';
        var ayis_page = currentpage ?? 1;

       $.ajax({
          type: 'POST',
          data:{action:action, bulk_survey_id:bulk_survey_id, start_date:start_date, end_date:end_date, pending_days:pending_days, not_action:not_action, lot_id:lot_id, district_id:district_id, tehsil_id:tehsil_id, uc_id:uc_id, gender:gender, disability:disability, vulnerable:vulnerable, vulnerabilities:vulnerabilities, review_by_mne:review_by_mne, landownership:landownership, bank_ac_wise:bank_ac_wise, reconstruction_wise:reconstruction_wise, construction_type:construction_type, damage_type:damage_type, department:department, form_status:form_status, status:status, socio_legal_status:socio_legal_status, evidence_type:evidence_type, status_of_land:status_of_land, proposed_beneficiary:proposed_beneficiary, b_reference_number:b_reference_number, beneficiary_name:beneficiary_name, cnic:cnic, sorting:sorting, direction:direction, qty:qty, ayis_page:ayis_page, _token: '{{csrf_token()}}'},
          url: "{{ route('report_survey_datalist_fetch_data') }}",
		   beforeSend: function(){$('.filter_data').html('<center><img src="{{ asset('images/loading.gif') }}" width="100" alt="Loader" /></center>');},
           success:function(data){
               
                  $('.filter_data').html(data);
              },
           error: function(data){console.log(data);}
  }); 
        
    }

    function get_filter(class_name)
    {
        var filter = [];
        $('.'+class_name+':checked').each(function(){
            filter.push($(this).val());
        });
        return filter;
    }
    
            /*
            $(".color:checked").each(function(){
                colors.push($(this).val());
                colorName.push(this.name);
                $("#filter-item-color").html('<b>Color: </b>'+colorName);
            });
            */
    
    

    $('.common_selector').click(function(){
        filter_data();
    });
	
    $("#b_reference_number, #beneficiary_name, #cnic").on('keyup keydown', function() {
		filter_data();
    });
    
  

    $('body').on('change', '#sorting, #direction, #qty, #not_action, #lot_id, #district_id, #tehsil_id, #uc_id, #gender, #disability, #vulnerable, #vulnerabilities, #review_by_mne, #landownership, #bank_ac_wise, #reconstruction_wise, #construction_type, #damage_type,  #start_date, #end_date, #form_status, #department, #status, #socio_legal_status, #evidence_type, #status_of_land, #proposed_beneficiary, #pending_days', function(e){
            e.preventDefault();
            filter_data();
    });
      
    $('body').on('click','.pagination a',function(f){
        f.preventDefault();
        var url = $(this).attr('href');
        var currentpage = url.split('page=')[1];
        var ayis_survey_id = $("#survey_ids").val();
        filter_data(currentpage, ayis_survey_id);
    });
    
    
    
    
    
    $('body').on('click', '.report_trail_btn', function(e){
        e.preventDefault();
        var survey_id = $(this).attr('survey_id');
        $('#reporttrail_modal').modal('show');
        
        $.ajax({
              url: "{{ route('report_trail_history') }}",
              type: 'GET',
              data:{_token: '{{csrf_token()}}', survey_id:survey_id},
              //dataType: 'JSON',
                  beforeSend: function(){$('#reporttrail_modal #modaldata').html('Processing...');},
                  success: function (response) {
                      $('#reporttrail_modal #modaldata').empty();
                      $('#reporttrail_modal #modaldata').html(response); 
                      
                  },
                   error: function (response){
                       $('#reporttrail_modal #modaldata').empty();
                       $('#reporttrail_modal #modaldata').html('Error 401');
                   }
        });
	
	
});



       
var selectedValues = [];
$('body').on('change', '.bulk_survey_id', function(e){
  e.preventDefault();
  var value = $(this).val();
  
   if ($(this).is(':checked')) {
        selectedValues.push(value);
    } else {
        selectedValues = $.grep(selectedValues, function (item) {
            return item !== value;
        });
    }
    $('#survey_ids').val(selectedValues.join(','));
});

    
    

});
</script>
@endpush