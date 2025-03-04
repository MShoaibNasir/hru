<div class="table-responsive">
<table class="table table-striped table-hover table-bordered">
	<thead>
		<tr>
		    <th> ID </th>
		    <th> Date </th>
		  
	
		 
			<th> Department </th>
			<th> Action By </th>
			<th> Status </th>
			<th> Comment </th>
		</tr>
	</thead>
	<tbody>
	
	    @if($construction->getstatustrail->count() > 0)
		@foreach($construction->getstatustrail as $item)		
		<tr>
		    <td> {{ $item->id }} </td>
		    <td> {{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y g:i A') }} </td>
			<td> {{ $item->role_name }} </td>
			<td> {{ $item->created_by->name ?? '' }} </td>
						
			@php
			if($item->status=='CR')
			$item->status='Case Register';
			else if($item->status=='C')
			$item->status='Case Close';
			@endphp

			<td> {{ $item->status }} </td>
			<td> {{ $item->comment ?? 'Not Available' }} </td>
		</tr>
		@endforeach
		@else
		<tr>
		    <td colspan="9" align="center">Trail Not Available</td>
		<tr />
		@endif
	</tbody>
</table>
</div>