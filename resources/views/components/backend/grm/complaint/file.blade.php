<tr>
	<td>{{ Carbon\Carbon::parse($data->created_at)->format('d-m-Y g:i A') }}</td>
	<td>{{ $data->assign_by->name ?? 'User' }}</td>
	<td>{{ $data->extension}}</td>
	<td>{{ $data->size}}</td>
	<td><a href="{{asset('uploads/complaints_files')}}/{{ $data->name }}" target="_blank" class="btn btn-sm btn-default"><i class="fa fa-file"></i> View </a></td>
</tr>