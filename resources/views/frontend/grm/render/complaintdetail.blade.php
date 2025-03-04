<style type="text/css">
table{font-size:14px;}
.blueheading{background:#004fb6!important; color:#fff!important;}
.greenheading{background:green!important; color:#fff!important;}
</style>

<div class="d-flex justify-content-between">
    <div>
        <p class="my-1">Ticket No - <span>TN#{{ $complaint->ticket_no }}</span></p>
        <p class="my-1">Ticket Date - <span>{{ Carbon\Carbon::parse($complaint->created_at)->format('M d, Y h:i:s A') }}</span></p>
		<p class="my-1">Current Status - <span class="badge bg-primary">{{ $complaint->status }}</span></p>
    </div>
    <div>
        <img style="width:160px; float:right;" src="https://sld.devstaging.a2zcreatorz.com/ifrap2/public/images/ifrap_logo.png" />   
    </div>
</div>
	
<div class="mt-3">
    <table class="table table-bordered table-striped">
		@if($message)
		<tr>
            <th colspan="2" class="greenheading">{{ $message }}</th>
        </tr>
		@endif
        <tr>
            <th colspan="2" class="blueheading">Complainant Information</th>
        </tr>
        <tr>
            <th>Source/Channel:</th>
			<th>PIU:</th>
        </tr>
		<tr>
            <td>{{ $complaint->getsourcechannel->name ?? '' }}</td>
			<td>{{ $complaint->getpiu->name ?? '' }}</td>
        </tr>
    </table>
</div>
	
<div class="mt-3">
    <table class="table table-bordered table-striped">
        <tr>
            <th colspan="3" class="blueheading">Personal Details</th>
        </tr>
        <tr>
            <th>Full Name:</th>
            <th>Father Name:</th>
			<th>CNIC:</th>
        </tr>
		<tr>
            <td>{{ $complaint->full_name }}</td>
            <td>{{ $complaint->father_name }}</td>
			<td>{{ $complaint->cnic }}</td>
        </tr>
        <tr>
            <th>HRU Beneficiary ID:</th>
            <th>Mobile No:</th>
			<th>Email ID:</th>
        </tr>
		<tr>
            <td>{{ $complaint->hru_beneficiary_id }}</td>
            <td>{{ $complaint->mobile }}</td>
			<td>{{ $complaint->email }}</td>
        </tr>
		<tr>
            <th>District:</th>
            <th>Tehsil:</th>
			<th>UC:</th>
        </tr>
		<tr>
            <td>{{ $complaint->getdistrict->name ?? '' }}</td>
            <td>{{ $complaint->gettehsil->name ?? '' }}</td>
			<td>{{ $complaint->getuc->name ?? '' }}</td>
        </tr>
		<tr>
            <th colspan="3">Postal Address:</th>
        </tr>
		<tr>
            <td colspan="3">{{ $complaint->postal_address }}</td>
        </tr>
    </table>
</div>	

<div class="mt-3">
    <table class="table table-bordered table-striped">
        <tr>
            <th colspan="2" class="blueheading">Grievance Registration</th>
        </tr>
        <tr>
            <th>Grievance Type:</th>
			<th>Subject:</th>
        </tr>
		<tr>
            <td>{{ $complaint->getgrievancetype->name }}</td>
			<td>{{ $complaint->subject }}</td>
        </tr>
		<tr>
            <th colspan="2">Description:</th>
        </tr>
		<tr>
            <td colspan="2">{{ $complaint->description }}</td>
        </tr>
    </table>
</div>
{{-- <x-backend.grm.complaint.complaintview :complaint="$complaint" /> --}}
@if(count($followups) > 0)
<div class="mt-3">
    <table class="table table-bordered table-striped">
        <tr>
            <th colspan="4" class="blueheading">Complain Followup History</th>
        </tr>
        <tr>
            <th>Followup Date:</th>
			<th>Last Status:</th>
			<th>Current Status:</th>
			<th>Followup Remarks:</th>
        </tr>
		@foreach($followups as $followup)
		<tr>
            <td>{{ Carbon\Carbon::parse($followup->created_at)->format('M d, Y h:i:s A') }}</td>
			<td>{{ $followup->status }}</td>
			<td>{{ $followup->currentstatus }}</td>
			<td>{{ $followup->remark }}</td>
        </tr>
		@endforeach

    </table>
</div>
@endif
{{--<?php /*$complaint->feedback */ ?>--}}