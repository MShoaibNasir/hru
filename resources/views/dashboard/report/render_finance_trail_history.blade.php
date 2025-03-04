<div class="table-responsive">
<table class="table table-striped table-hover table-bordered">
	<thead>
		<tr>
		    <th> ID </th>
		    <th> Date </th>
		    <th> Survey ID </th>
		    <th> Referance No </th>
			<th> Action </th>
			<th> Action By </th>

		</tr>
	</thead>
	<tbody>
	    @if($surveydata->getfinancetrail->count() > 0)
		@foreach($surveydata->getfinancetrail as $item)		
		<tr>
		    <td> {{ $item->id }} </td>
		    <td> {{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y g:i A') }} </td>
		    <td> {{ $surveydata->id }} </td>
		    <td> {{ $item->ref_no }} </td>
		    <td> 
		    {{ $item->action ? match ($item->action) {
    'already_account_verify' => 'Already Account Verify',
    'bio metric verification of beneficiaries' => 'Biometric Verification of Beneficiaries',
    'create_batch' => 'Create Batch',
    'move_to_first_trench' => 'Move to First Trench',
    'move_to_second_trench' => 'Move to Second Trench',
    'move_to_third_trench' => 'Move to Third Trench',
    'upload_accounts' => 'Upload Accounts',
    default => 'N/A'
} : 'N/A' }}
		    </td>
			<td> {{ $item->created_by->name }} </td>
		</tr>
		@endforeach
		@else
		<tr>
		    <td colspan="6" align="center">No Activity Found</td>
		</tr>
		@endif
	</tbody>
</table>
</div>