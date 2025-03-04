<?php
$lotlabels = [];
$totalbeneficiaries = [];
$validatedbeneficiaries = [];

foreach ($lot_wise as $lotwise) {
 $lotlabels[] = $lotwise->lotName;
 $totalbeneficiaries[] = $lotwise->total_beneficiary;
 $validatedbeneficiaries[] = $lotwise->validated_beneficiary;
}

$lot_labels = json_encode($lotlabels);
$total_beneficiaries = json_encode($totalbeneficiaries);
$validated_beneficiaries = json_encode($validatedbeneficiaries);
?>

<h1 style="text-align:center; font-family: Arial, sans-serif; margin-top:100px;">Report Lot Wise</h1>

<!-- Chart.js Canvas -->
<canvas id="lotwiseChart" width="300" height="150"></canvas>
<script>
  const lotwise = document.getElementById('lotwiseChart');
  new Chart(lotwise, {
    type: 'line',
    data: { 
      labels: {!! $lot_labels !!},
      datasets: [{
        label: 'Total Beneficiaries',
        data: {!! $total_beneficiaries !!},
		fill: true,
		backgroundColor: 'rgba(54, 162, 235, 0.6)', // Blue color
        borderColor: 'rgba(54, 162, 235, 1)',
		borderWidth: 1,
		pointStyle: 'circle',
        pointRadius: 5,
        pointHoverRadius: 10,
        tension: 0.5
        
      },
      {
        label: 'Validated Beneficiaries',
        data: {!! $validated_beneficiaries !!},
		fill: true,
		//backgroundColor: 'rgba(75, 192, 192, 0.6)', // Green color
        //borderColor: 'rgba(75, 192, 192, 1)',
		backgroundColor: 'rgba(255, 122, 127, 0.6)', // Red color
        borderColor: 'rgba(255, 122, 127, 1)',
		borderWidth: 1,
		pointStyle: 'circle',
        pointRadius: 5,
        pointHoverRadius: 10,
        tension: 0.5
        
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
