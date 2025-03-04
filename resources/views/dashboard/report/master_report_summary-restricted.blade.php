<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('MIS Master Report Summary') }}</title>
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
<hr><h3 class="page-title">Master Report Summary</h3><hr>
<a href="#" class="btn btn-primary mb-3" id="ReportPrint">Print Master Report</a>
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
        @if(Auth::user()->role==1 || Auth::user()->role==30 || Auth::user()->role==36 || Auth::user()->role==37 || Auth::user()->role==38 || Auth::user()->role==40 || Auth::user()->role==51)
        <th colspan="3" scope="col">Field Supervisor</th>
        @endif
        @if(Auth::user()->role==1 || Auth::user()->role==34 || Auth::user()->role==36 || Auth::user()->role==37 || Auth::user()->role==38 || Auth::user()->role==40 || Auth::user()->role==51)
        <th colspan="3" scope="col">IP Head Office</th>
        @endif
        
        @if(Auth::user()->role==1 || Auth::user()->role==36 || Auth::user()->role==37 || Auth::user()->role==38 || Auth::user()->role==40 || Auth::user()->role==51)
        <th colspan="2" scope="col">Quality Assurance</th>
        <th colspan="5" scope="col">Selection Committee</th>
        <th colspan="3" scope="col">CEO</th>
        <th colspan="13" scope="col">Finance</th>
        @endif
        
      </tr>
      <tr>
        @if(Auth::user()->role==1 || Auth::user()->role==30 || Auth::user()->role==36 || Auth::user()->role==37 || Auth::user()->role==38 || Auth::user()->role==40 || Auth::user()->role==51)
        <!-- Field Supervisor Columns -->
        <th scope="col" class="table-success">Net Approved</th>
        <th scope="col" class="table-success">Currently Pending</th>
        {{--<th scope="col" class="table-success">Overall Approved</th>--}}
        <th scope="col" class="table-success">Overall Rejected</th>
        @endif
        
        @if(Auth::user()->role==1 || Auth::user()->role==34 || Auth::user()->role==36 || Auth::user()->role==37 || Auth::user()->role==38 || Auth::user()->role==40 || Auth::user()->role==51)
        <!-- IP Head Office Columns -->
        <th scope="col" class="table-info">Net Approved</th>
        <th scope="col" class="table-info">Currently Pending</th>
        {{--<th scope="col" class="table-info">Overall Approved</th>--}}
        <th scope="col" class="table-info">Overall Rejected</th>
        @endif
        
        @if(Auth::user()->role==1 || Auth::user()->role==36 || Auth::user()->role==37 || Auth::user()->role==38 || Auth::user()->role==40 || Auth::user()->role==51)
        <!-- Quality Assurance Columns -->
        <th scope="col" class="table-warning">Currently Certified</th>
        {{--<th scope="col" class="table-warning">Overall Certified</th>--}}
        <th scope="col" class="table-warning">Overall Rejected</th>
        <!-- Selection Committee Columns -->
        <th scope="col" class="table-danger">Approved</th>
        <th scope="col" class="table-danger">Hold</th>
        <th scope="col" class="table-danger">Incomplete Document</th>
        <th scope="col" class="table-danger">Ineligible</th>
        <th scope="col" class="table-danger">Rejected</th>
        <!-- CEO Columns -->
        <th scope="col" class="table-info">Approved</th>
        <th scope="col" class="table-info">Rejected</th>
        <th scope="col" class="table-info">Hold</th>
        <!-- Finance Columns -->
        <th scope="col" class="table-success">With Accounts</th>
        <th scope="col" class="table-success">Without Accounts</th>
        <th scope="col" class="table-success">Accounts Open</th>
        <th scope="col" class="table-success">Biometrics verified</th>
        <th scope="col" class="table-success">Ready For Disbursment</th>
        <th scope="col" class="table-success">1st Tranch</th>
        <th scope="col" class="table-success">Plinth Certified</th>
        <th scope="col" class="table-success">2nd Tranch</th>
        <th scope="col" class="table-success">Lintel Certified</th>
        <th scope="col" class="table-success">3rd Tranch</th>
        <th scope="col" class="table-success">Roof Certified</th>
        <th scope="col" class="table-success">4th Tranch</th>
        <th scope="col" class="table-success">Finishing</th>
        @endif
      </tr>
    </thead>
    <tbody>
      @foreach($districts as $district)
      <tr>
        <td>{{ $district->lot->name }}</td>
        <td id="{{ $district->id }}">{{ $district->name }}</td>
        <td>{{ report_district_count($district->id, 'target')->count() ?? 0 }}</td>
        <td>{{ report_district_count($district->id, 'collect')->count() ?? 0 }}</td>
        
        @if(Auth::user()->role==1 || Auth::user()->role==30 || Auth::user()->role==36 || Auth::user()->role==37 || Auth::user()->role==38 || Auth::user()->role==40 || Auth::user()->role==51)
        <!-- Field Supervisor Data -->
        <td class="table-success">{{ report_department_status_count('A', 'field supervisor',$district->id)->count() ?? 0 }}</td> 
        <td class="table-success">{{ report_department_pending_count('P', '30',$district->id)->count() ?? 0 }}</td>
        {{--<td class="table-success">N/A</td>--}}
        <td class="table-success">{{ report_department_rejected_count('R', '30',$district->id)->count() ?? 0 }}</td>
        @endif
        
        @if(Auth::user()->role==1 || Auth::user()->role==34 || Auth::user()->role==36 || Auth::user()->role==37 || Auth::user()->role==38 || Auth::user()->role==40 || Auth::user()->role==51)
        <!-- IP Head Office Data -->
        <td class="table-info">{{ report_department_status_count('A', 'IP',$district->id)->count() ?? 0 }}</td>
        <td class="table-info">{{ report_department_pending_count('P', '34',$district->id)->count() ?? 0 }}</td>
        {{--<td class="table-info">N/A</td>--}}
        <td class="table-info">{{ report_department_rejected_count('R', '34',$district->id)->count() ?? 0 }}</td>
        @endif
        
        @if(Auth::user()->role==1 || Auth::user()->role==36 || Auth::user()->role==37 || Auth::user()->role==38 || Auth::user()->role==40 || Auth::user()->role==51)
        <!-- Quality Assurance Data -->
        <td class="table-warning">{{ report_department_cirtified_count('HRU',$district->id)->count() ?? 0 }}</td>
        {{--<td class="table-warning">N/A</td>--}}
        <td class="table-warning">{{ report_department_rejected_count('R', '37',$district->id)->count() ?? 0 }}</td>
        <!-- Selection Committee Data -->
        <td class="table-danger">{{ report_department_status_count('A', 'HRU_MAIN',$district->id)->count() ?? 0 }}</td>
        <td class="table-danger">{{ report_department_pending_count('H', '38',$district->id)->count() ?? 0 }}</td>
        <td class="table-danger">
        {{-- report_finance_column_wise_count('', 'HRU_MAIN', $district->id, 'evidence_type', 'No Evidence Available')->count() ?? 0 --}}
        {{ report_finance_missing_document_column_wise_count('HRU_MAIN', $district->id, 'evidence_type', 'No Evidence Available')->count() ?? 0 }}
        </td>
        <td class="table-danger">{{ report_finance_column_wise_count('', 'HRU_MAIN', $district->id, 'is_ineligible', 1)->count() ?? 0 }}</td>
        <td class="table-danger">{{ report_department_rejected_count('R', '38',$district->id)->count() ?? 0 }}</td>
        <!-- CEO Data -->
        <td class="table-info">{{ report_department_status_count('A', 'CEO',$district->id)->count() ?? 0 }}</td>
        <td class="table-info">{{ report_department_rejected_count('R', '40',$district->id)->count() ?? 0 }}</td>
        <td class="table-info">{{ report_department_pending_count('H', '40',$district->id)->count() ?? 0 }}</td>
        <!-- Finance Assurance Data -->
        <td class="table-success">{{ report_finance_column_wise_count('A', 'CEO', $district->id, 'bank_ac_wise', 'Yes')->count() ?? 0 }}</td>
        <td class="table-success">{{ report_finance_column_wise_count('A', 'CEO', $district->id, 'bank_ac_wise', 'No')->count() ?? 0 }}</td>
        <td class="table-success">{{ report_finance_activity_count($district->id,'upload_accounts')->count() ?? 0 }}</td>
        <td class="table-success">{{ report_finance_activity_count($district->id,'bio metric verification of beneficiaries')->count() ?? 0 }}</td>
        <td class="table-success">{{ report_verify_beneficairy_count($district->id,0)->count() ?? 0 }}</td>
        <td class="table-success">{{ report_verify_beneficairy_count($district->id,1)->count() ?? 0 }}</td>
        <td class="table-success">N/A</td>
        <td class="table-success">N/A</td>
        <td class="table-success">N/A</td>
        <td class="table-success">N/A</td>
        <td class="table-success">N/A</td>
        <td class="table-success">N/A</td>
        <td class="table-success">N/A</td>
        @endif
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
