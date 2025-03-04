<div class="table-responsive">
<table class="table table-striped table-hover table-bordered">
	<thead>
		<tr>
		    <th> ID </th>
		    <th> Date </th>
		    <th> Survey ID </th>
		    <th> Referance No </th>
			<th> Department </th>
			<th> Action By </th>
			<th> Status </th>
			<th> Comment </th>
		</tr>
	</thead>
	<tbody>
		@foreach($surveydata->getformstatusold as $item)		
		<tr>
		    <td> {{ $item->id }} </td>
		    <td> {{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y g:i A') }} </td>
		    <td> {{ $item->form_id }} </td>
		    <td> {{ $surveydata->ref_no }} </td>
			<td> {{ $item->update_by }} </td>
			<td> {{ $item->created_by->name ?? '' }} </td>
			<td> {{ $item->form_status }} </td>
			<td>
@if($item->user_status == 30 || $item->user_status == 34)			       
{{ $item->comment ?? 'Not Available' }} 
@endif

@if(Auth::user()->role != 30 && Auth::user()->role != 34)			       
{{ $item->comment ?? 'Not Available' }} 
@endif
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
</div>