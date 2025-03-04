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
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total No of Rooms</p>
                        <h6 class="mb-0">{{$no_of_rooms}}</h6>
                        
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-bar fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total No of Damaged Rooms</p>
                        <h6 class="mb-0">{{$damaged_room}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-area fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Household Member</p>
                        <h6 class="mb-0">500</h6>
                    </div>
                </div>
            </div>
          
     
        </div>
        <div class="row g-4 my-4">
            <div class="col-md-4">
                <h4 class="text-center">Foundation</h4>
                
                <canvas id="myChart1"></canvas>
            </div>
            <div class="col-md-4">
                <h4 class="text-center">Type of Construction</h4>
                <canvas id="myChart2"></canvas>
            </div>
            <div class="col-md-4">
                <h4 class="text-center">Type of Roof</h4>
                <canvas id="myChart3"></canvas>
            </div>
        </div>

      
 <script>
        // foundation status
const data1 = {
    labels: [
        'collapsed',
        'deflected',
        'infact',
    ],
    datasets: [{
        label: 'Foundation Status',
        data: [{{$collapsed}}, {{$deflected}}, {{$infact}}],
        backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)',
        ],
        hoverOffset: 4
    }]
};

const config1 = {
    type: 'pie',
    data: data1,
    options: {
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        const total = tooltipItem.dataset.data.reduce((a, b) => a + b, 0);
                        const value = tooltipItem.raw;
                        const percentage = ((value / total) * 100).toFixed(2);
                        return `${tooltipItem.label}: ${value} (${percentage}%)`;
                    }
                }
            }
        }
    }
};

const myChart1 = new Chart(
    document.getElementById('myChart1'),
    config1
);


       // type of construction
const data2 = {
    labels: [
        'kacha',
        'pakka',
        'stone_masonry',
        'hybrid',
        'confied_mansory',
        'others'
    ],
    datasets: [{
        label: 'Type of Construction',
        data: [{{$kacha}}, {{$pakka}}, {{$stone_masonry}}, {{$hybrid}}, {{$confied_mansory}}, {{$others}}],
        backgroundColor: [
            'rgb(255, 159, 64)',
            'rgb(153, 102, 255)',
            'rgb(255, 205, 86)',
            'rgb(40, 179, 184)',
            'rgb(134, 40, 184)',
            'rgb(184, 40, 122)',
        ],
        hoverOffset: 4
    }]
};

const config2 = {
    type: 'pie',
    data: data2,
    options: {
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        const total = tooltipItem.dataset.data.reduce((a, b) => a + b, 0);
                        const value = tooltipItem.raw;
                        const percentage = ((value / total) * 100).toFixed(2);
                        return `${tooltipItem.label}: ${value} (${percentage}%)`;
                    }
                }
            }
        }
    }
};

const myChart2 = new Chart(
    document.getElementById('myChart2'),
    config2
);

        
        // type of roof 
const data3 = {
    labels: [
        'RCC',
        'T.iron and Girder',
        'Bamboo and Girder',
        'Others'
    ],
    datasets: [{
        label: 'Type of Roof',
        data: [{{$rcc}}, {{$t_iron}}, {{$bamboo}}, {{$others_roof}}],
        backgroundColor: [
            'rgb(255, 159, 64)',
            'rgb(153, 102, 255)',
            'rgb(255, 205, 86)',
            'rgb(40, 179, 184)',
        ],
        hoverOffset: 4
    }]
};

const config3 = {
    type: 'pie',
    data: data3,
    options: {
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        const total = tooltipItem.dataset.data.reduce((a, b) => a + b, 0);
                        const value = tooltipItem.raw;
                        const percentage = ((value / total) * 100).toFixed(2);
                        return `${tooltipItem.label}: ${value} (${percentage}%)`;
                    }
                }
            }
        }
    }
};

const myChart3 = new Chart(
    document.getElementById('myChart3'),
    config3
);

        
        
        
        
        
        
        
        
      
    
    
    
    </script>
        
        
        
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    @endsection