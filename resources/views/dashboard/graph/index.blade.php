<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/4.3.0/apexcharts.min.css" integrity="sha512-P/8zp3yWsYKLYgykcnVdWono7iWa9VXcoNLFnUhC82oBjt/6z5BIHXTQsMKBgYJjp6K+JAkt4yrID1cxfoUq+g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            color: white;
            font-family: 'Montserrat';
        }

        h1, h2, h3, h4, h5, h6, strong {
            font-weight: 600;
        }

        .shadow {
            box-shadow: 0px 1px 15px 1px rgba(69, 65, 78, 0.08);
        }
        .apexcharts-menu {
            background-color: #333 !important; /* Dark background */
            color: #fff !important;            /* White text */
        }

        /* Style each menu item */
        .apexcharts-menu .apexcharts-menu-item {
            background-color: #333 !important;
            color: #fff !important;
            border: none;
        }

        /* On hover, slightly change the background */
        .apexcharts-menu .apexcharts-menu-item:hover {
            background-color: #444 !important;
            color: #fff !important;
        }

        /* If there are SVG icons inside the menu, force their fill color to white */
        .apexcharts-menu svg {
            fill: #fff !important;
        }

        .apexcharts-legend {
            display: flex !important;
            flex-wrap: nowrap !important;
            justify-content: center; /* center the items */
        }
        .apexcharts-legend-series {
            margin-right: 10px !important;
        }
    </style>
</head>
<body>
    <!--Gender Wise Chart-->
    <div class="container-fluid row">
        <div class="col-md-12">
            <div class="box shadow mt-4">
                <div id="line-adwords" class=""></div>
            </div>
        </div>
    </div>

    <!--Bank Details Chart-->
    <div class="container-fluid row mt-5">
        <div class="col-md-12 mt-4">
            <div class="box shadow mt-4">
                <div id="bankChart" class=""></div>
            </div>
        </div>
    </div>

    <!--Tenant Chart-->
    <div class="container-fluid row mt-5">
        <div class="col-md-12 mt-4">
            <div class="box shadow mt-4">
                <div id="tenantChart" class=""></div>
            </div>
        </div>
    </div>

    <!--Type Of Construction Chart-->
    <div class="container-fluid row mt-5">
        <div class="col-md-12 mt-4">
            <div class="box shadow mt-4">
                <div id="typeOfConstruction" class=""></div>
            </div>
        </div>
    </div>

    <!--House Visible Chart-->
    <div class="container-fluid row mt-5">
        <div class="col-md-12 mt-4">
            <div class="box shadow mt-4">
                <div id="houseVisible" class=""></div>
            </div>
        </div>
    </div>

    <!--Slary Wise Chart-->
    <div class="container-fluid row mt-5">
        <div class="col-md-12 mt-4">
            <div class="box shadow mt-4">
                <div id="salaryWise" class=""></div>
            </div>
        </div>
    </div>

    <!--Survey 86 Chart-->
    <div class="container-fluid row mt-5">
        <div class="col-md-12 mt-4">
            <div class="box shadow mt-4">
                <div id="survey86" class=""></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/4.3.0/apexcharts.min.js" integrity="sha512-QgLS4OmTNBq9TujITTSh0jrZxZ55CFjs4wjK8NXsBoZb04UYl8wWQJNaS8jRiLq6/c60bEfOj3cPsxadHICNfw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>

    //Gender Chart
    var optionsLine = {
        chart: {
            height: 328,
            type: 'line',
            fontFamily: 'Montserrat',
            zoom: {
                enabled: false
            },
            dropShadow: {
                enabled: true,
                top: 3,
                left: 2,
                blur: 4,
                opacity: 1,
            }
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        series: [{
            name: "Total Beneficiary",
            data: [
                @foreach($genderWiseData as $data)
                    {{$data->total_beneficiary}},
                @endforeach
            ]
        },
            {
                name: "Validated Beneficiary",
                data: [
                    @foreach($genderWiseData as $data)
                        {{$data->validated_beneficiary}},
                    @endforeach
                ]
            },
            {
                name: "Male",
                data: [
                    @foreach($genderWiseData as $data)
                        {{$data->male_count}},
                    @endforeach
                ]
            },
            {
                name: "Female",
                data: [
                    @foreach($genderWiseData as $data)
                        {{$data->female_count}},
                    @endforeach
                ]
            },
            {
                name: "Transgender",
                data: [
                    @foreach($genderWiseData as $data)
                        {{$data->transgender_count}},
                    @endforeach
                ]
            }
        ],
        title: {
            text: 'Gender Wise Data',
            align: 'left',
            offsetY: 25,
            offsetX: 20
        },
        markers: {
            size: 6,
            strokeWidth: 0,
            hover: {
                size: 9
            }
        },
        grid: {
            show: true,
            padding: {
                bottom: 0
            }
        },
        labels: [
            @foreach($genderWiseData as $data)
                "{{ $data->lot_name }}",
            @endforeach],
        xaxis: {
            tooltip: {
                enabled: false
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            offsetY: -20
        },
        tooltip: {
            theme: 'dark', // Use a light theme to get a white background with dark text
            style: {
                fontFamily: 'Montserrat, sans-serif', // Apply Montserrat font
                fontSize: '12px',
                color: '#000000' // Ensure the text is black
            }
        }
    }

    var chartLine = new ApexCharts(document.querySelector('#line-adwords'), optionsLine);
    chartLine.render();

    //Bank Chart
    var options = {
        series: [
            {
                name: 'Total Beneficiary',
                type:"area",

                data: [
                    @foreach($bankWiseData as $data)
                        {{$data->total_beneficiary}},
                    @endforeach
                ]
            },
            {
                name: 'Validated Beneficiary',
                type:"line",

                data: [
                    @foreach($bankWiseData as $data)
                        {{$data->validated_beneficiary}},
                    @endforeach
                ]
            },
            {
                name: 'Bank Account Exists',
                type:"area",

                data: [
                    @foreach($bankWiseData as $data)
                        {{$data->bank_account_exists}},
                    @endforeach
                ]
            },
            {
                name: 'Bank Account Not Exists',
                type:"line",
                data: [
                    @foreach($bankWiseData as $data)
                        {{$data->bank_account_not_exists}},
                    @endforeach
                ]
            }
            ],
        chart: {
            height: 350,
            fontFamily: 'Montserrat',
            type: 'line',
        },
        stroke: {
            curve: 'smooth'
        },
        fill: {
            type:'solid',
            opacity: [0.35, 1],
        },
        labels: [
            @foreach($genderWiseData as $data)
                "{{ $data->lot_name }}",
            @endforeach],
        markers: {
            size: 0
        },
        yaxis: [
            {
                title: {
                    text: 'Series A',
                },
            },
            {
                opposite: true,
                title: {
                    text: 'Series B',
                },
            },
        ],
        tooltip: {
            theme: 'dark', // Use a light theme to get a white background with dark text
            style: {
                fontFamily: 'Montserrat, sans-serif', // Apply Montserrat font
                fontSize: '12px',
                color: '#000000' // Ensure the text is black
            }
        },
        legend: {
            position: 'bottom',           // Position the legend at the top
            horizontalAlign: 'center', // Center the legend horizontally
            itemMargin: {
                horizontal: 2,        // Adjust horizontal spacing between legend items
                vertical: 0            // Remove vertical spacing to keep them on one line
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#bankChart"), options);
    chart.render();

    //Tenant Chart
    var options = {
        series: [{
            name: 'Owned',
            data: [
                @foreach($tenantWiseData as $data)
                    "{{ $data->owner }}",
                @endforeach
            ]
        }, {
            name: 'Leased',
            data: [
                @foreach($tenantWiseData as $data)
                    "{{ $data->leased }}",
                @endforeach
            ]
        }, {
            name: 'Tenant',
            data: [
                @foreach($tenantWiseData as $data)
                    "{{ $data->tenant }}",
                @endforeach
            ]
        },
            {
            name: 'Living With Relatives',
            data: [
                @foreach($tenantWiseData as $data)
                    "{{ $data->living_with_relatives }}",
                @endforeach
            ]
        },{
            name: 'Living With Non Relatives',
            data: [
                @foreach($tenantWiseData as $data)
                    "{{ $data->living_with_non_relatives }}",
                @endforeach
            ]
        }

        ],
        chart: {
            type: 'bar',
            fontFamily: 'Montserrat',
            height: 350
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                borderRadius: 5,
                borderRadiusApplication: 'end'
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: [
                @foreach($tenantWiseData as $data)
                    "{{ $data->lot_name }}",
                @endforeach
            ],
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            theme: 'dark', // Use a light theme to get a white background with dark text
            style: {
                fontFamily: 'Montserrat, sans-serif', // Apply Montserrat font
                fontSize: '12px',
                color: '#000000' // Ensure the text is black
            }
        },
        legend: {
            position: 'bottom',           // Position the legend at the top
            horizontalAlign: 'center', // Center the legend horizontally
            itemMargin: {
                horizontal: 2,        // Adjust horizontal spacing between legend items
                vertical: 0            // Remove vertical spacing to keep them on one line
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#tenantChart"), options);
    chart.render();

    //Type Of Construction
    var options = {
        series: [
            {
                name: 'Katcha',
                data: [
                    @foreach($typeOfConstructionData as $data)
                        "{{ $data->katcha }}",
                    @endforeach
                ]
            },
            {
                name: 'Pakka',
                data: [
                    @foreach($typeOfConstructionData as $data)
                        "{{ $data->pakka }}",
                    @endforeach
                ]
            },
            {
                name: 'Stone Masonry',
                data: [
                    @foreach($typeOfConstructionData as $data)
                        "{{ $data->Stone_masonry }}",
                    @endforeach
                ]
            },
            {
                name: 'Hybrid',
                data: [
                    @foreach($typeOfConstructionData as $data)
                        "{{ $data->Hybrid }}",
                    @endforeach
                ]
            },
            {
                name: 'Confined Masonry',
                data: [
                    @foreach($typeOfConstructionData as $data)
                        "{{ $data->confined_masonry }}",
                    @endforeach
                ]
            },
            {
                name: 'Other',
                data: [
                    @foreach($typeOfConstructionData as $data)
                        "{{ $data->other }}",
                    @endforeach
                ]
            },

        ],
        chart: {
            height: 350,
            type: 'line',
        },
        forecastDataPoints: {
            count: 7
        },
        stroke: {
            width: 5,
            curve: 'smooth'
        },
        xaxis: {
            categories: [
                @foreach($typeOfConstructionData as $data)
                    "{{ $data->lot_name }}",
                @endforeach
            ],
        },
        title: {
            text: 'Forecast',
            align: 'left',
            style: {
                fontSize: "16px",
                color: '#666'
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                gradientToColors: [ '#FDD835'],
                shadeIntensity: 1,
                type: 'horizontal',
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100, 100, 100]
            },
        },
        tooltip: {
            theme: 'dark', // Use a light theme to get a white background with dark text
            style: {
                fontFamily: 'Montserrat, sans-serif', // Apply Montserrat font
                fontSize: '12px',
                color: '#000000' // Ensure the text is black
            }
        },
        legend: {
            position: 'bottom',           // Position the legend at the top
            horizontalAlign: 'center', // Center the legend horizontally
            itemMargin: {
                horizontal: 2,        // Adjust horizontal spacing between legend items
                vertical: 0            // Remove vertical spacing to keep them on one line
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#typeOfConstruction"), options);
    chart.render();

    //House Visible
    var options = {
        series: [
            {
                data: [
                    @foreach($houseVisibleData as $data)
                        "{{ $data->house_visible }}",
                    @endforeach
                ]
            },
            {
                data: [
                    @foreach($houseVisibleData as $data)
                        "{{ $data->house_not_visible }}",
                    @endforeach
                ]
            },
        ],
        chart: {
            type: 'line',
            height: 350
        },
        stroke: {
            curve: 'stepline',
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: [
                @foreach($houseVisibleData as $data)
                    "{{ $data->lot_name }}",
                @endforeach
            ],
        },
        title: {
            text: 'Stepline Chart',
            align: 'left'
        },
        markers: {
            hover: {
                sizeOffset: 4
            }
        },
        tooltip: {
            theme: 'dark', // Use a light theme to get a white background with dark text
            style: {
                fontFamily: 'Montserrat, sans-serif', // Apply Montserrat font
                fontSize: '12px',
                color: '#000000' // Ensure the text is black
            },
            custom: function({ series, seriesIndex, dataPointIndex, w }) {
                // Define your custom labels here. The order should match your series.
                var customLabels = ['House Visible', 'House Not Visible'];
                // Get the value for the hovered point.
                var value = series[seriesIndex][dataPointIndex];
                // Build and return the HTML content of your tooltip.
                return '<div class="arrow_box" style="padding:10px; background:#333; color:#fff; border-radius:4px;">' +
                    '<div style="font-weight:bold;">' + customLabels[seriesIndex] + '</div>' +
                    '<div>' + value + '</div>' +
                    '</div>';
            }
        },
        legend: {
            position: 'bottom',           // Position the legend at the top
            horizontalAlign: 'center', // Center the legend horizontally
            itemMargin: {
                horizontal: 2,        // Adjust horizontal spacing between legend items
                vertical: 0            // Remove vertical spacing to keep them on one line
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#houseVisible"), options);
    chart.render();

    //Salary Wise Chart
    var options = {
        series: [
            {
                name: "Range 0-1000",
                data: [
                    @foreach($salaryWiseData as $data)
                        "{{ $data->range_0_1000 }}",
                    @endforeach
                ]
            },
            {
                name: "Range 1001-5000",
                data: [
                    @foreach($salaryWiseData as $data)
                        "{{ $data->range_1001_5000 }}",
                    @endforeach
                ]
            },
            {
                name: 'Range 5001-10000',
                data: [
                    @foreach($salaryWiseData as $data)
                        "{{ $data->range_5001_10000 }}",
                    @endforeach
                ]
            },
            {
                name: "Range 10001-20000",
                data: [
                @foreach($salaryWiseData as $data)
                    "{{ $data->range_10001_20000 }}",
                @endforeach
            ]
            },
            {
                name: "Range 200001-50000",
                data: [
                @foreach($salaryWiseData as $data)
                    "{{ $data->range_20001_25000 }}",
                @endforeach
            ]
            },
            {
                name: "Range 250001-40000",
                data: [
                @foreach($salaryWiseData as $data)
                    "{{ $data->range_25001_40000}}",
                @endforeach
            ]
            },
            {
                name: "Range 40001-Above",
                data: [
                @foreach($salaryWiseData as $data)
                    "{{ $data->range_40001_Above }}",
                @endforeach
            ]
            },
        ],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: [5, 7, 5],
            curve: 'straight',
            dashArray: [0, 8, 5]
        },
        title: {
            text: 'Page Statistics',
            align: 'left'
        },
        legend: {
            position: 'bottom',           // Position the legend at the top
            horizontalAlign: 'center', // Center the legend horizontally
            itemMargin: {
                horizontal: 2,        // Adjust horizontal spacing between legend items
                vertical: 0            // Remove vertical spacing to keep them on one line
            }
        },
        markers: {
            size: 0,
            hover: {
                sizeOffset: 6
            }
        },
        xaxis: {
            categories: [
                @foreach($salaryWiseData as $data)
                    "{{ $data->lot_name }}",
                @endforeach
            ],
        },
        tooltip: {
            theme: 'dark', // Use a light theme to get a white background with dark text
            style: {
                fontFamily: 'Montserrat, sans-serif', // Apply Montserrat font
                fontSize: '12px',
                color: '#000000' // Ensure the text is black
            }
        },
        grid: {
            borderColor: '#f1f1f1',
        },

    };

    var chart = new ApexCharts(document.querySelector("#salaryWise"), options);
    chart.render();

    //Survey 86 Chart
    var optionsLine = {
        chart: {
            height: 328,
            type: 'line',
            fontFamily: 'Montserrat',
            zoom: {
                enabled: false
            },
            dropShadow: {
                enabled: true,
                top: 3,
                left: 2,
                blur: 4,
                opacity: 1,
            }
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        series: [{
            name: "Govt Employee",
            data: [
                @foreach($surveys as $data)
                    {{$data->govt_employee}},
                @endforeach
            ]
        },
            {
                name: "Private Employee",
                data: [
                    @foreach($surveys as $data)
                        {{$data->private_employee}},
                    @endforeach
                ]
            },
            {
                name: "Retired",
                data: [
                    @foreach($surveys as $data)
                        {{$data->retired}},
                    @endforeach
                ]
            },
            {
                name: "Farmer Tenant",
                data: [
                    @foreach($surveys as $data)
                        {{$data->Farmer_Tenant}},
                    @endforeach
                ]
            },
            {
                name: "Farmer Landlord",
                data: [
                    @foreach($surveys as $data)
                        {{$data->Farmer_landlord}},
                    @endforeach
                ]
            },
            {
                name: "Labourer",
                data: [
                    @foreach($surveys as $data)
                        {{$data->labourer}},
                    @endforeach
                ]
            },
            {
                name: "Mason",
                data: [
                    @foreach($surveys as $data)
                        {{$data->mason}},
                    @endforeach
                ]
            },
            {
                name: "Other",
                data: [
                    @foreach($surveys as $data)
                        {{$data->other}},
                    @endforeach
                ]
            }
        ],
        title: {
            text: 'Survey Report Section 86',
            align: 'left',
            offsetY: 25,
            offsetX: 20
        },
        markers: {
            size: 6,
            strokeWidth: 0,
            hover: {
                size: 9
            }
        },
        grid: {
            show: true,
            padding: {
                bottom: 0
            }
        },
        labels: [
            @foreach($surveys as $data)
                "{{ $data->lot_name }}",
            @endforeach],
        xaxis: {
            tooltip: {
                enabled: false
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            offsetY: -20
        },
        tooltip: {
            theme: 'dark', // Use a light theme to get a white background with dark text
            style: {
                fontFamily: 'Montserrat, sans-serif', // Apply Montserrat font
                fontSize: '12px',
                color: '#000000' // Ensure the text is black
            }
        }
    }

    var chartLine = new ApexCharts(document.querySelector('#survey86'), optionsLine);
    chartLine.render();
</script>
</body>
</html>