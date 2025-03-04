<div class="table-responsive">
<table class="table table-striped table-hover table-bordered">
	<thead>
		<tr>
		    <th> ID </th>
		    <th> Date </th>
		    <th> Master ID </th>
		    <th> Survey ID </th>
		    <th> Referance No </th>
			<th> Department </th>
			<th> Action By </th>
			<th> Current Status </th>
			<th> Last Status </th>
		</tr>
	</thead>
	<tbody>
		@foreach($surveydata->getformstatus->report_history as $item)		
		<tr>
		    <td> {{ $item->id }} </td>
		    <td> {{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y g:i A') }} </td>
		    <td> {{ $item->maaster_report_id }} </td>
		    <td> {{ $item->survey_id }} </td>
		    <td> {{ $surveydata->ref_no }} </td>
			<td> {{ $item->role }} </td>
			<td> {{ $item->created_by->name ?? '' }} </td>
			<td> {{ $item->new_status }} </td>
			<td> {{ $item->last_status }} </td>
		</tr>
		@endforeach
	</tbody>
</table>
</div>