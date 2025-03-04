<!-- Start Complain Followup History-->
{{--<div class="card border-danger">
  <div class="card-header bg-danger text-white">Complaint Followup History</div>
  <div class="card-body">--}}
   <div class="record_view">
    {{--<h3 class="form-section">Complaint Followup History</h3>--}}
    <div class="row">
        @if(count($followups) > 0)
            <div class="qa-message-list" id="wallmessages">
            @foreach($followups as $followup)												
              <x-backend.grm.complaint.followup :followup="$followup"  />
    		@endforeach
            </div>
        @else
            <div class="flex justify-center">Not Found</div> 
        @endif
   </div></div>   
{{--</div></div></div>--}}
<!-- END Complain Followup History-->