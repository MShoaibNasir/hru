<tr>
	<td>{{ Carbon\Carbon::parse($data->created_at)->format('d-m-Y g:i A') }}</td>
	<td>{{ $data->assigned_by->name ?? 'User' }}</td>
	<td>{{ $data->assigned_to->name ?? '' }}</td>
	<td>{{ $data->remarks }}</td>
</tr>