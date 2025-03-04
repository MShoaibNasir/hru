{{--<div class="card border-warning">
  <div class="card-header bg-warning text-white">Complaint Assign History</div>
  <div class="card-body">--}}
   <div class="record_view">
    {{--<h3 class="form-section">Complaint Assign History</h3>--}}
    @if(count($assignlist) > 0)
    <div class="row">
<div class="table-responsive">
<table class="table table-striped table-hover table-bordered">
	<thead>
		<tr>
			<th> Last Assignment Date </th>
			<th> Last Assignment By </th>
			<th> Last Assignment To </th>
			<th> Assignment Remarks </th>
		</tr>
	</thead>
	<tbody>
		@foreach($assignlist as $data)		
		<x-backend.grm.complaint.assign :data="$data" />
		@endforeach
	</tbody>
</table>
</div>
   </div>
   @else
<div class="flex justify-center">Not Found</div> 
@endif
</div>
{{--</div></div></div>--}}
<!-- END Complain Followup History-->