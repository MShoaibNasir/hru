{{--
@dump($reportdata->getformstatus_trail)
@dump($reportdata->getformstatus_firsttrail)
--}}

<div class="row">
<div class="col-md-12">
<div class="row"> 
<div class="col-md-6"><h5 class="mt-4 mb-2 text-start">Master Report Parent</h5></div>
</div>
<div class="table-responsive">
<table class="table table-striped table-hover table-bordered">
	<thead>
		<tr>
		    <th> Action </th>
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
			
		<tr>
		    <td> 
		    <a class="btn btn-primary masterreport_trail_btn" report_id="{{ $reportdata->getformstatus->id }}" survey_id="{{ $reportdata->getformstatus->survey_id }}" href="javascript:void(0)"><i class="fa fa-edit"></i></a>
		    </td>
		    <td> {{ Carbon\Carbon::parse($reportdata->getformstatus->created_at)->format('d-m-Y g:i A') }} </td>
		    <td> {{ $reportdata->getformstatus->id }} </td>
		    <td> {{ $reportdata->id }} </td>
		    <td> {{ $reportdata->ref_no }} </td>
			<td> {{ $reportdata->getformstatus->role }} </td>
			<td> {{ $reportdata->getformstatus->created_by->name ?? '' }} ({{ $reportdata->getformstatus->created_by->id ?? '' }})</td>
			<td> {{ $reportdata->getformstatus->new_status }} </td>
			<td> {{ $reportdata->getformstatus->last_status }} </td>
		</tr>

	</tbody>
</table>
</div>
</div>    
    
    
    
    
    
    
    
    
    
    
<div class="col-md-12">
<div class="row"> 
<div class="col-md-6"><h5 class="mt-4 mb-2 text-start">Master Report Trail ({{ $reportdata->getformstatus->report_history->count() }})</h5></div>
<div class="col-md-6"><a class="btn btn-success float-end mb-2 mt-4 report_trail_btn" report_id="{{ $reportdata->getformstatus->id }}" report_type="master" action="add" survey_id="{{ $reportdata->id }}" href="javascript:void(0)" >Add <i class="fa fa-plus"></i></a></div>
</div>
<div class="table-responsive">
<table class="table table-striped table-hover table-bordered">
	<thead>
		<tr>
		    <th> Action </th>
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
		@foreach($reportdata->getformstatus->report_history as $item)		
		<tr>
		    <td> 
		    <a class="btn btn-primary report_trail_btn" report_id="{{ $item->id }}" report_type="master" action="edit" survey_id="{{ $reportdata->id }}" href="javascript:void(0)"><i class="fa fa-edit"></i></a>
		    <a class="btn btn-danger report_trail_del_btn" href="{{ route('report_trail_delete', $item->id) }}" onclick="javascript:return confirm('Are you sure you want to delete this record?')"><i class="fa fa-trash"></i></a>
		    </td>
		    <td> {{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y g:i A') }} </td>
		    <td> {{ $item->maaster_report_id }} </td>
		    <td> {{ $item->survey_id }} </td>
		    <td> {{ $reportdata->ref_no }} </td>
			<td> {{ $item->role }} </td>
			<td> {{ $item->created_by->name ?? '' }} ({{ $item->created_by->id ?? '' }})</td>
			<td> {{ $item->new_status }} </td>
			<td> {{ $item->last_status }} </td>
		</tr>
		@endforeach
	</tbody>
</table>
</div>
</div>



<div class="col-md-12">
<div class="row"> 
<div class="col-md-6"><h5 class="mt-4 mb-2 text-start">Form Status Trail ({{ $reportdata->getformstatus_firsttrail->count() }})</h5></div>
<div class="col-md-6"><a class="btn btn-success float-end mb-2 mt-4 report_trail_btn" report_id="0" report_type="formstatus" action="add" survey_id="{{ $reportdata->id }}" href="javascript:void(0)" >Add <i class="fa fa-plus"></i></a></div>
</div>
<div class="table-responsive">
<table class="table table-striped table-hover table-bordered">
	<thead>
		<tr>
		    <th> Action </th>
		    <th> Date </th>
		    <th> Survey ID </th>
		    <th> Referance No </th>
			<th> Department </th>
			<th> Action By </th>
			<th> Form Status </th>
		</tr>
	</thead>
	<tbody>
		@foreach($reportdata->getformstatus_firsttrail as $item)		
		<tr>
		    <td> 
		    <a class="btn btn-primary report_trail_btn" report_id="{{ $item->id }}" report_type="formstatus" action="edit" survey_id="{{ $reportdata->id }}" href="javascript:void(0)"><i class="fa fa-edit"></i></a>
		    <a class="btn btn-danger report_trail_del_btn" href="{{ route('formstatus_trail_delete', $item->id) }}" onclick="javascript:return confirm('Are you sure you want to delete this record?')"><i class="fa fa-trash"></i></a>
		    </td>
		    <td> {{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y g:i A') }} </td>
		    <td> {{ $item->form_id }} </td>
		    <td> {{ $reportdata->ref_no }} </td>
			<td> {{ $item->update_by }} </td>
			<td> {{ $item->created_by->name ?? '' }} ( {{ $item->created_by->id ?? '' }} )</td>
			<td> {{ $item->form_status }} </td>
		</tr>
		@endforeach
	</tbody>
</table>
</div>
</div>

</div>