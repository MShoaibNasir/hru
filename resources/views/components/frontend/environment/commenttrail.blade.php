<div id="section_comments" class="order_comments data-view">
    <div class="accordion-header" onclick="toggleAccordion(this)">Comments Trail</div>
    <div class="accordion-content">
<div class="card mb-2">
  <h5 class="card-header">Comments</h5>
  <div class="card-body">
@if($surveydata)
@if($surveydata->getformstatusold->count() > 0)
<div class="table-responsive">
<table class="table table-striped table-hover table-bordered">
	<thead>
		<tr>
		    <th>Date</th>
			<th>Department</th>
			<th>Action By</th>
			<th>Status</th>
			<th>Comment</th>
		</tr>
	</thead>
	<tbody>
		@foreach($surveydata->getformstatusold()->latest()->get() as $item)		
		<tr>
		    <td> {{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y g:i A') }} </td>
			<td> {{ $item->update_by }} </td>
			<td> {{ $item->created_by->name ?? '' }} </td>
			<td> {{ $item->form_status }} </td>
			<td> {{ $item->comment ?? 'Not Available' }} </td>
		</tr>
		@endforeach
	</tbody>
</table>
</div>
@else
<p class="card-text">Comment Trail Not Available</p>
@endif
@endif
  </div>
</div> 
    </div>
</div>

