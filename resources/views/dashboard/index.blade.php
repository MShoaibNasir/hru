@extends('dashboard.layout.master')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
<style>
p.mb-2 {
    /*white-space: nowrap;*/
}
</style>

<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
  
    @include('dashboard.layout.navbar')
    <!-- Navbar End -->

       @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible" role="alert" id='success-alert'>
         {{ $message }}
        </div>
       @elseif ($message = Session::get('error'))
        <div class="alert alert-danger" id='alert-danger'>
         {{ $message }}
         </div>
	    @endif

    @if(Auth::user()->role !== 56 && Auth::user()->role !== 57)
    <!-- Sale & Revenue Start -->
    <div class="container pt-4 px-4" >
        <div class="row g-4">
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Users</p>
                        <h6 class="mb-0">{{$user}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-bar fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Lots</p>
                        <h6 class="mb-0">{{$lot}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-area fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total District</p>
                        <h6 class="mb-0">{{$district}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
           
            </div>
        </div>
        <div class='row g-4 my-4'>
            
             <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-pie fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Tehsil</p>
                        <h6 class="mb-0">{{$tehsil}}</h6>
                    </div>
                </div>
            </div>
             <div class="col-sm-6 col-xl-4">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Survey Form Submitted</p>
                        <h6 class="mb-0">{{$survey_form}}</h6>
                    </div>
                </div>
            </div>
             <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Beneficiary</p>
                        <h6 class="mb-0">{{$total_ndma_data}}</h6>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <!-- Sale & Revenue End -->
    
    
    {{-- @include('dashboard.charts&reports.countReports') --}}
   {{-- @include('dashboard.charts&reports.PDNAChart')  
    @include('dashboard.charts&reports.pdnaReportLotWise') --}}
   {{--  @include('dashboard.chart.main_symmery')  --}}
    @endif


    <!-- Recent Sales Start -->
    <div class="container pt-4 px-4">
    </div>
    <!-- Recent Sales End -->

    
    @if(Auth::user()->role == 56 || Auth::user()->role == 57 || Auth::user()->role == 39 || Auth::user()->role == 1)
    <x-backend.grm.complaint.counters />
    @endif
    
     <script>
        
     
    // Automatically close the alert after 5 seconds
    setTimeout(function() {
        $('#success-alert').fadeOut('slow');
        $('#alert-danger').fadeOut('slow');
    }, 2000); // 5000ms = 5 seconds

        
    </script>
    
    @endsection