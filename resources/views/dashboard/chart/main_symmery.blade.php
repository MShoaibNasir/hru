
 <!--<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>-->
 <style>
        .canvas {
            max-width: 700px;
            margin: auto;
        }
    </style>
    
    
    @php
    $total_no_of_form=DB::table('survey_form')->count();
        // Land Ownership Status
    $tenant=answer_count(240,'Tenant');
    
       
        $leased=answer_count(240,'Leased');
        $owner=answer_count(240,'Owner');
        $living_with_relatives=answer_count(240,'Living with Relatives');
        $living_with_not_relatives=answer_count(240,'Living with Non-Relatives');
        
        
        // Beneficiary Gender   
        $male=answer_count(652,'Male');
        $female=answer_count(652,'Female');
        $transgender=answer_count(652,'Transgender');
          
          
           // Beneficiary With Bank Account
        $bank_account_exits=answer_count(248,'Yes');
        $bank_account_not_exits=answer_count(248,'No');  
    @endphp

<!-- Content Start -->
<div class="">
    <!-- Navbar Start -->
    <!-- Navbar End -->



    <div class="container-fluid pt-4 px-4 form_width">
        
     
        <div class="row g-4 my-4" >
            <div class="col-md-6">
                <h4 class="text-center" >Land Ownership Status</h4>
                <canvas id="myChart1" class="canvas"></canvas>
            </div>
            <div class="col-md-6" >
                <h4 class="text-center">Beneficiary By Gender</h4>
                <canvas id="myChart2" class="canvas"></canvas>
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

        


    </script>
        
        
        
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
   