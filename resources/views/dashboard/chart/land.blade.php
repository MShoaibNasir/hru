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
                <h4 class="text-center">Land Ownership-Evidence Type</h4>
                <canvas id="myChart1"></canvas>
            </div>
            <div class="col-md-4">
                <h4 class="text-center">Land Ownership</h4>
                <canvas id="myChart2"></canvas>
            </div>
           
        </div>
       
      
 <script>
        // Land Ownership-Evidence Type

        const data1 = {
            labels: [
                'Property Deed (Fard)',
                'Stamp Paper',
                'No Evidence Available',
                'Any other',
            ],
            datasets: [{
                label: 'Land Ownership-Evidence Type',
                data: [{{$property_dead}},{{$stamp_paper}},{{$no_evidence}},{{$any_other}}],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(66, 135, 245)',
                    'rgb(50, 168, 82)',
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

       // Land Ownership

        const data2 = {
            labels: [
                'Owner',
                'Leased',
                'Tenant',
                'Living with relative',
                'living With Non relative'
               
            ],
            datasets: [{
                label: 'Land Ownership',
                data: [{{$leased}}, {{$tenant}},{{$living_with_relative}},{{$living_with_non_relative}}],
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
        
       
        
        
        
        
        
        
        
         
        
        
        
    
    
    
    </script>
        
        
        
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    @endsection