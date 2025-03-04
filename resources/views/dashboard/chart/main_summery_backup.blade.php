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
                        <p class="mb-2">Total no of form filled</p>
                        <h6 class="mb-0">{{$total_no_of_form}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-bar fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total No of Beneficiary Validated</p>
                        <h6 class="mb-0">{{$total_no_of_form}}</h6>
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
                <h4 class="text-center">Land Ownership Status</h4>
                <canvas id="myChart1"></canvas>
            </div>
            <div class="col-md-4">
                <h4 class="text-center">Beneficiary By Gender</h4>
                <canvas id="myChart2"></canvas>
            </div>
            <div class="col-md-4">
                <h4 class="text-center">Beneficiary With Bank Account</h4>
                <canvas id="myChart3"></canvas>
            </div>
        </div>
       
      
 <script>
        // Land Ownership Status
const data1 = {
    labels: [
        'tenant',
        'leased',
        'owner',
        'living_with_relatives',
        'living_with_not_relatives',
    ],
    datasets: [{
        label: 'Land Ownership',
        data: [{{$tenant}}, {{$leased}}, {{$owner}}, {{$living_with_relatives}}, {{$living_with_not_relatives}}],
        backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)',
            'rgb(252, 73, 3)',
            'rgb(75, 192, 192)',
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


       // Beneficiary Gender
const data2 = {
    labels: [
        'Male',
        'Female',
        'Transgender'
    ],
    datasets: [{
        label: 'Gender',
        data: [{{$male}}, {{$female}}, {{$transgender}}],
        backgroundColor: [
            'rgb(255, 159, 64)',
            'rgb(153, 102, 255)',
            'rgb(255, 205, 86)'
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

        
       // Beneficiary With Bank Account
const data3 = {
    labels: [
        'Yes',
        'No',
    ],
    datasets: [{
        label: 'Beneficiary With Bank Account',
        data: [{{$bank_account_exits}}, 500],
        backgroundColor: [
            'rgb(255, 159, 64)',
            'rgb(153, 102, 255)',
        ],
        hoverOffset: 4
    }]
};

const config3 = {
    type: 'polar',
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