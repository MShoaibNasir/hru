@extends('dashboard.layout.master')
@section('content')
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 <style>
        canvas {
            max-width: 700px;
            margin: auto;
        }
    </style>

<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
    @include('dashboard.layout.navbar')
    <!-- Navbar End -->



    <div class="container-fluid pt-4 px-4 form_width">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">HRU Damage Assessment and Verification Dashboard</h6>
                </div>
            </div>
        </div>
    
        <div class="row g-4 my-4">
            <div class="col-md-4">
                <h4 class="text-center">Resconstruction Started</h4>
                <canvas id="myChart1"></canvas>
            </div>
            <div class="col-md-4">
                <h4 class="text-center">Construction Done By</h4>
                <canvas id="myChart2"></canvas>
            </div>
            
            <div class="col-md-4">
                <h4 class="text-center">Construction Type</h4>
                <canvas id="myChart3"></canvas>
            </div>
            <div class="col-md-4">
                <h4 class="text-center">Construction Stage</h4>
                <canvas id="myChart4"></canvas>
            </div>
           
        </div>
       
      
 <script>
        // reconstruction started
        const data1 = {
            labels: [
                'Yes',
                'No'
            ],
            datasets: [{
                label: 'Resconstruction Started',
                data: [{{$reconstruction_started}},{{$reconstruction_not_started}}],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                ],
                hoverOffset: 4
            }]
        };

        const config1 = {
            type: 'pie',
            data: data1,
        };

        const myChart1 = new Chart(
            document.getElementById('myChart1'),
            config1
        );

       // Construction Done By
        const data2 = {
            labels: [
                'Govt',
                'Loan',
                'Owner Himself',
                'Other Organization (Please Specify)'
               
            ],
            datasets: [{
                label: 'Construction Done By',
                data: [{{$govt}}, {{$loan}},{{$owner_himself}},{{$other_organization}}],
                backgroundColor: [
                    'rgb(255, 159, 64)',
                    'rgb(153, 102, 255)',
                    'rgb(255, 205, 86)',
                    'rgb(252, 36, 3)',
              
                ],
                hoverOffset: 4
            }]
        };

        const config2 = {
            type: 'pie',
            data: data2,
        };

        const myChart2 = new Chart(
            document.getElementById('myChart2'),
            config2
        );
        
       // Construction Type
        const data3 = {
            labels: [
                'katcha',
                'Pakka',
                'Hybrid',
               
            ],
            datasets: [{
                label: 'Construction Type',
                data: [{{$katcha}}, {{$pakka}},{{$Hybrid}}],
                backgroundColor: [
                    'rgb(255, 159, 64)',
                    'rgb(153, 102, 255)',
                    'rgb(40, 77, 73)'
              
                ],
                hoverOffset: 4
            }]
        };

        const config3 = {
            type: 'pie',
            data: data3,
        };

        const myChart3 = new Chart(
            document.getElementById('myChart3'),
            config3
        );
        
        
        
        
        
        
        
        
            //  Construction Stage
        const data4 = {
            labels: [
                'PrePlinth',
                'Plinth',
                'Lentle Level',
                'Roof Level',
                'Completed',
                
                
               
            ],
            datasets: [{
                label: 'Beneficiary breakdown',
                data: [{{$PrePlinth}}, {{$plinth}},{{$lentle_level}},{{$roof_level}},{{$completed}}],
                backgroundColor: [
                    'rgb(255, 159, 64)',
                    'rgb(252, 186, 3)',
                    'rgb(153, 102, 255)',
                    'rgb(3, 252, 177)',
                    'rgb(148, 3, 25)',
              
                ],
                hoverOffset: 4
            }]
        };

        const config4 = {
            type: 'pie',
            data: data4,
        };

        const myChart4 = new Chart(
            document.getElementById('myChart4'),
            config4
        );
        
        
        
        
      
        
        
        
        
    
    
    
    </script>
        
        
        
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    @endsection