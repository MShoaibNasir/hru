<h1 style="text-align:center; font-family: Arial, sans-serif; margin-top:100px;">Report District Wise</h1>

<!-- Chart.js Canvas -->
<canvas id="beneficiaryChart" width="300" height="150"></canvas>
<script>
    // Preparing the data for the chart
    var result = @json($result);

    // Extract data for the chart
    var districts = result.map(function(item) {
        return item.district;
    });
    var totalBeneficiaries = result.map(function(item) {
        return item.total_beneficiary;
    });
    var validatedBeneficiaries = result.map(function(item) {
        return item.validated_beneficiary;
    });

    // Create the chart
    var ctx = document.getElementById('beneficiaryChart').getContext('2d');
    var beneficiaryChart = new Chart(ctx, {
        type: 'bar', // Bar chart type
        data: {
            labels: districts, // X-axis labels (district names)
            datasets: [{
                label: 'Total Beneficiaries',
                data: totalBeneficiaries, // Y-axis data (total beneficiaries)
                backgroundColor: 'rgba(54, 162, 235, 0.6)', // Lighter color
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 10, // Rounded corners
                barThickness: 20 // Thinner bars
            }, {
                label: 'Validated Beneficiaries',
                data: validatedBeneficiaries, // Y-axis data (validated beneficiaries)
                backgroundColor: 'rgba(75, 192, 192, 0.6)', // Lighter color
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                borderRadius: 10, // Rounded corners
                barThickness: 20 // Thinner bars
            }]
        },
        options: {
            responsive: true, // Make the chart responsive to screen size
            plugins: {
                legend: {
                    position: 'top', // Place legend at the top
                    labels: {
                        font: {
                            family: 'Arial, sans-serif', // Font style for legend
                            size: 14,
                            weight: 'bold',
                            color: '#333'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: '#333', // Dark background for tooltips
                    titleColor: '#fff', // White title color for tooltips
                    bodyColor: '#fff', // White body text for tooltips
                    borderRadius: 5, // Rounded corners for tooltips
                    padding: 10 // Padding inside tooltips
                }
            },
            scales: {
                y: {
                    beginAtZero: true, // Start the Y-axis at zero
                    ticks: {
                        font: {
                            family: 'Arial, sans-serif', // Font style for Y-axis ticks
                            size: 12,
                            color: '#333'
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            family: 'Arial, sans-serif', // Font style for X-axis ticks
                            size: 12,
                            color: '#333'
                        },
                        rotation: 45, // Rotate X-axis labels for better readability
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });
</script>
