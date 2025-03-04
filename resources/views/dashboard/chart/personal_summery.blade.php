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
                <h4 class="text-center">Is Beneficiary Same As Identified in Post 2022 Flood</h4>
                <canvas id="myChart1"></canvas>
            </div>
            <div class="col-md-4">
                <h4 class="text-center">Beneficiary By Gender</h4>
                <canvas id="myChart2"></canvas>
            </div>
            
            <div class="col-md-4">
                <h4 class="text-center">Is CNIC Issued</h4>
                <canvas id="myChart3"></canvas>
            </div>
            <div class="col-md-4">
                <h4 class="text-center">Beneficiary breakdown</h4>
                <canvas id="myChart4"></canvas>
            </div>
            <div class="col-md-4">
                <h4 class="text-center">Source of income</h4>
                <canvas id="myChart5"></canvas>
            </div>
        </div>
       
      
 <script>
        // Land Ownership Status
        const data1 = {
            labels: [
                'Yes',
                'No'
            ],
            datasets: [{
                label: 'Same Beneficiary',
                data: [{{$same_beneficiary}},{{$not_same_beneficiary}}],
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

       // Beneficiary Gender
        const data2 = {
            labels: [
                'Male',
                'Female',
                'Transgender'
               
            ],
            datasets: [{
                label: 'Gender',
                data: [{{$male}}, {{$female}},{{$transgender}}],
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
        };

        const myChart2 = new Chart(
            document.getElementById('myChart2'),
            config2
        );
        
       // is cnic issued
        const data3 = {
            labels: [
                'Yes',
                'No',
               
            ],
            datasets: [{
                label: 'CNIC Issued',
                data: [{{$is_cnic_issued}}, {{$not_cnic_issued}}],
                backgroundColor: [
                    'rgb(255, 159, 64)',
                    'rgb(153, 102, 255)',
              
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
        
        
        
        
        
        
        
        
            // Beneficiary breakdown
        const data4 = {
            labels: [
                'Adult Male',
                'Adult Female',
                'House Hold Members Other Gender',
                'Male Child',
                'Female Child',
                
                
               
            ],
            datasets: [{
                label: 'Beneficiary breakdown',
                data: [{{$adult_male}}, {{$adult_female}},{{$house_hold_member_other_gender}},{{$male_child}},{{$female_child}}],
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
        
        
        
        
        // source of income
        
        
        
  const data5 = {
        labels: ['Govt Employee', 'Private Employee','Retired from job','Farmer (Tenant)','Farmer (Landlord)','Mason','Other'],
        datasets: [{
            label: 'Source of income',
            data: [{{$govt_employe}}, {{$private_employe}},{{$retried_job}},{{$tenant}},{{$lanlord}},{{$labour}},{{$manson}},{{$other}}],
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(252, 186, 3)',
                'rgb(3, 215, 252)',
                'rgb(42, 35, 120)',
                'rgb(245, 66, 123)',
                'rgb(230, 66, 245)',
            ],
            hoverOffset: 4
        }]
    };

    const config5 = {
        type: 'bar', // Use 'bar' for a horizontal bar chart
        data: data5,
        options: {
            indexAxis: 'y', // Set indexAxis to 'y' for horizontal orientation
            scales: {
                x: {
                    beginAtZero: true // Ensure the x-axis starts at zero
                }
            }
        }
    };

    const myChart5 = new Chart(
        document.getElementById('myChart5'),
        config5
    );

        
        
        
        
    
    
    
    </script>
        
        
        
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    @endsection