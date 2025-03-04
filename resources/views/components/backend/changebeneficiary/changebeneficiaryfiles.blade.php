<div class="table-responsive">
<table class="table table-striped table-hover table-bordered">
	<thead>
		<tr>
		    <th> Created Date </th>
			<th> Created By </th>
			<th> Question</th>
			<th> Extension </th>
			<th> Size </th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	    @if($changebeneficiary->getfiles->count() > 0)
		@foreach($changebeneficiary->getfiles as $item)	
        <tr>
            <td>{{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y g:i A') }}</td>
            <td>{{ $item->user_by->name ?? 'User' }}</td>
            <td>@if($item->question_id){{ getquestionlabel($item->question_id) ?? '' }} @else Evidence @endif</td>
            <td>{{ $item->extension}}</td>
            <td>{{ $item->size}}</td>
            <td><a href="{{asset('uploads/surveyform_files')}}/{{ $item->filename }}" target="_blank" class="btn btn-sm btn-default"><i class="fa fa-file"></i> View </a></td>
        </tr>
		@endforeach
		@else
		<tr>
		    <td colspan="9" align="center">Files Not Available</td>
		<tr />
		@endif
	</tbody>
</table>
</div>			