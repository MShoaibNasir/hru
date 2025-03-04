<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('MIS Weekly Summary Report') }}</title>
    {{ Html::favicon( 'images/favicon.jpeg') }}
    
<link media="all" type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

<style>

</style>
    </head>
    <body>
<div class="container-fluid">
            <center>
            <img src="{{ asset('images/ifrap_logo.png') }}" width="200" alt="Housing Reconstruction Unit" class="logo-ayis" />
            </center>


<div class="row">
<div class="col-md-12 text-center">
<hr><h3 class="page-title">Weekly Summary Report</h3><hr>
<a href="#" class="btn btn-primary mb-3" id="ReportPrint">Print Weekly Summary Report</a>
</div>    
<div class="col-md-12">
        
@if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
         {{ $message }}
        </div>
       @elseif ($message = Session::get('error'))
        <div class="alert alert-danger">
         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
         {{ $message }}
         </div>
	    @endif                
<div class="table-responsive">
<table class="table table-sm table-bordered table-striped table-hover text-center" style="font-size:12px;">
    <thead>
      <tr class="table-primary">
        <th rowspan="2" scope="col" valign="middle">Lot</th>
        <th rowspan="2" scope="col" valign="middle">District</th>
        <th rowspan="2" scope="col" valign="middle">Total Target</th>
        <th rowspan="2" scope="col" valign="middle">Data Collect</th>
        <th rowspan="2" scope="col">Field Supervisor Approved</th>
        <th rowspan="2" scope="col">IP Head Office Approved</th>
        <th rowspan="2" scope="col">Quality Assurance Approved</th>
        <th rowspan="2" scope="col">Selection Committee Approved</th>
        <th rowspan="2" scope="col">CEO Approved</th>
        
        <th colspan="3" scope="col">Gender household Lead</th>
        <th colspan="3" scope="col">Disability household Led</th>
        <th colspan="3" scope="col">Female Led catagories </th>
        <th colspan="4" scope="col">Male Led Households with vulnerables women</th>
        
        <th colspan="3" scope="col">Low Income 0 TO 20,000</th>
        <th colspan="3" scope="col">Low Income 20,001 TO 40,000</th>
        <th colspan="3" scope="col">Low Income 40,001 and above</th>
        
        <th colspan="10" scope="col">Finance</th>
      </tr>
      <tr>
          
          
          
          
        <!-- Gender household Lead -->
        <th scope="col" class="table-info">Male</th>
        <th scope="col" class="table-danger">Female</th>
        <th scope="col" class="table-success">Other</th>
        
        <!-- Disability household Led -->
        <th scope="col" class="table-info">Male</th>
        <th scope="col" class="table-danger">Female</th>
        <th scope="col" class="table-success">Other</th>
        
        <!-- Female Led catagories -->
        <th scope="col" class="table-info">Women Headed Household</th>
        <th scope="col" class="table-danger">With male relatives who have disabilities</th>
        <th scope="col" class="table-success">Single woman not residing with the male relatives</th>
        
        <!-- Male Led Households with vulnerables women -->
        <th scope="col" class="table-info">Widow with no male child above 15 yrs of age</th>
        <th scope="col" class="table-danger">woman with disbilities (physical / Mental)</th>
        <th scope="col" class="table-success">Household with BISP beneficicary </th>
        <th scope="col" class="table-warning">Total unique count</th>
        
        <!-- Income 1 -->
        <th scope="col" class="table-info">Male</th>
        <th scope="col" class="table-danger">Female</th>
        <th scope="col" class="table-success">Other</th>
        
        <!-- Income 2 -->
        <th scope="col" class="table-info">Male</th>
        <th scope="col" class="table-danger">Female</th>
        <th scope="col" class="table-success">Other</th>
        
        <!-- Income 3 -->
        <th scope="col" class="table-info">Male</th>
        <th scope="col" class="table-danger">Female</th>
        <th scope="col" class="table-success">Other</th>
        
        
        
        <!-- Finance Columns -->
        {{--
        <th scope="col" class="table-success">With Accounts</th>
        <th scope="col" class="table-success">Without Accounts</th>
        --}}
        <th scope="col" class="table-primary">Accounts Open</th>
        <th scope="col" class="table-primary">Biometrics verified</th>
        {{--<th scope="col" class="table-primary">Ready For Disbursment</th>--}}
        <th scope="col" class="table-primary">1st Tranche</th>
        <th scope="col" class="table-danger">Plinth Certified</th>
        <th scope="col" class="table-primary">2nd Tranche</th>
        <th scope="col" class="table-info">Lintel Certified</th>
        <th scope="col" class="table-primary">3rd Tranche</th>
        <th scope="col" class="table-warning">Roof Certified</th>
        <th scope="col" class="table-primary">4th Tranche</th>
        <th scope="col" class="table-success">Finishing Completed  (QA Certified)</th>
        
      </tr>
    </thead>
    <tbody>
      @foreach($districts as $district)
      <tr>
        <td>{{ $district->lot->name }}</td>
        <td>{{ $district->name }}{{-- $district->id --}}</td>
        <td>{{ report_district_count($district->id, 'target')->count() ?? 0 }}</td>
        <td>{{ report_district_count($district->id, 'collect')->count() ?? 0 }}</td>
        <!-- Field Supervisor Data -->
        <td class="table-success">{{ report_department_status_count('A', 'field supervisor',$district->id)->count() ?? 0 }}</td> 
        <!-- IP Head Office Data -->
        <td class="table-info">{{ report_department_status_count('A', 'IP',$district->id)->count() ?? 0 }}</td>
        <!-- Quality Assurance Data -->
        <td class="table-warning">{{ report_department_status_count('A', 'HRU',$district->id)->count() ?? 0 }}</td>
        <!-- Selection Committee Data -->
        <td class="table-danger">{{ report_department_status_count('A', 'HRU_MAIN',$district->id)->count() ?? 0 }}</td>
        <!-- CEO Data -->
        <td class="table-info">{{ report_department_status_count('A', 'CEO',$district->id)->count() ?? 0 }}</td>
        
        <!--1-->
        <td>{{ report_genderhousehold_count($district->id, 'Male')->count() ?? 0 }}</td>
        <td>{{ report_genderhousehold_count($district->id, 'Female')->count() ?? 0 }}</td>
        <td>{{ report_genderhousehold_count($district->id, 'Transgender')->count() ?? 0 }}</td>
        
        <!--2-->
        <td>{{ report_disabilityhousehold_count($district->id, 'Male')->count() ?? 0 }}</td>
        <td>{{ report_disabilityhousehold_count($district->id, 'Female')->count() ?? 0 }}</td>
        <td>{{ report_disabilityhousehold_count($district->id, 'Transgender')->count() ?? 0 }}</td>
        
        <!--3-->
        <td>{{ report_genderhousehold_count($district->id, 'Female')->count() ?? 0 }}{{-- report_women_headed_household_count($district->id, 'Female')->count() ?? 0 --}}</td>
        <td>{{ report_male_relatives_disabilities_count($district->id, 'Female')->count() ?? 0 }}</td>
        <td>{{ report_q_2243_optionwise_count($district->id, 'Female', 2936)->count() ?? 0 }}</td>
        
        <!--4-->
        <td>{{ report_q_2243_optionwise_count($district->id, 'Male', 2927)->count() ?? 0 }}</td>
        <td>{{ report_q_2243_optionwise_count($district->id, 'Male', 2930)->count() ?? 0 }}</td>
        <td>0</td>
        <td>{{ report_disabilityhousehold_count($district->id, 'Male')->count() ?? 0 }}</td>
        
        <!--Income 1-->
        <td>{{ report_monthly_income_count($district->id, 'Male', 0, 20000)->count() ?? 0 }}</td>
        <td>{{ report_monthly_income_count($district->id, 'Female', 0, 20000)->count() ?? 0 }}</td>
        <td>{{ report_monthly_income_count($district->id, 'Transgender', 0, 20000)->count() ?? 0 }}</td>
        
        <!--Income 1-->
        <td>{{ report_monthly_income_count($district->id, 'Male', 20001, 40000)->count() ?? 0 }}</td>
        <td>{{ report_monthly_income_count($district->id, 'Female', 20001, 40000)->count() ?? 0 }}</td>
        <td>{{ report_monthly_income_count($district->id, 'Transgender', 20001, 40000)->count() ?? 0 }}</td>
        
        <!--Income 1-->
        <td>{{ report_monthly_income_count($district->id, 'Male', 40001, 900000)->count() ?? 0 }}</td>
        <td>{{ report_monthly_income_count($district->id, 'Female', 40001, 900000)->count() ?? 0 }}</td>
        <td>{{ report_monthly_income_count($district->id, 'Transgender', 40001, 900000)->count() ?? 0 }}</td>
        
        
        
        
        
        
        <!-- Finance Assurance Data -->
        {{--
        <td class="table-success">{{ report_finance_column_wise_count('A', 'CEO', $district->id, 'bank_ac_wise', 'Yes')->count() ?? 0 }}</td>
        <td class="table-success">{{ report_finance_column_wise_count('A', 'CEO', $district->id, 'bank_ac_wise', 'No')->count() ?? 0 }}</td>
        --}}
        
        <td>{{ report_finance_activity_count($district->id,'upload_accounts')->count() ?? 0 }}</td>
        <td>{{ report_finance_activity_count($district->id,'bio metric verification of beneficiaries')->count() ?? 0 }}</td>
        {{--<td class="table-success">{{ report_verify_beneficairy_count($district->id,0)->count() ?? 0 }}</td>--}}
        <td>{{ report_tranche_count($district->id,1)->count() ?? 0 }}</td>
        <td class="table-danger">{{ report_plint_stage1_count($district->id)->count() ?? 0 }}</td>
        <td>{{ report_tranche_count($district->id,2)->count() ?? 0 }}</td>
        <td class="table-info">{{ report_lintel_stage2_count($district->id)->count() ?? 0 }}</td>
        <td>{{ report_tranche_count($district->id,3)->count() ?? 0 }}</td>
        <td class="table-warning">{{ report_roof_stage3_count($district->id)->count() ?? 0 }}</td>
        <td>{{ report_tranche_count($district->id,4)->count() ?? 0 }}</td>
        <td class="table-success">N/A</td>
        
        
        
        
      </tr>
      @endforeach
    </tbody>
</table>

</div>


</div>
</div>





</div>
<script>
$(document ).ready(function() {
    $('#ReportPrint').click(function(){
     alert();
     window.print();
     });
});
</script>
    </body>
</html>
