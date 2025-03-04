<!-- Start Complain Followup History-->
{{--<div class="card border-info">
  <div class="card-header bg-info text-white">Complaint File History</div>
  <div class="card-body">--}}
   <div class="record_view">
    {{--<h3 class="form-section">Complaint File History</h3>--}} 
    @if(count($filelist) > 0)
    <div class="row">
<div class="table-responsive">
<table class="table table-striped table-hover table-bordered">
	<thead>
		<tr>
			<th> Created Date </th>
			<th> Created By </th>
			<th> Extension </th>
			<th> Size </th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
		@foreach($filelist as $data)		
		<x-backend.grm.complaint.file :data="$data" />
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