<table class="table table-bordered table-striped">
                <tr>
                    <th>Sno</th>
                    {{--<th>Detail</th>--}}
                    <th>CNIC</th>
                    <th>Ticket No</th>
                    <th>Mobile No</th>
                    <th>Reported Date</th>
                    <th>Status</th>
                    {{--<th>Feedback</th>--}}
                    <th></th>
                </tr>
			@if($complaints->count() > 0)
            @foreach ($complaints as $key => $complaint)   
       
			<tr>
                    <td>{{ ++$key }}</td>
                    {{--<td>{{ $complaint->detail }}</td>--}}
                    <td>{{ $complaint->cnic }}</td>
                    <td>{{ $complaint->ticket_no }}</td>
                    <td>{{ $complaint->mobile }}</td>
                    <td>{{ Carbon\Carbon::parse($complaint->created_at)->format('d-m-Y') }}</td>
                    <td>{{ $complaint->status }}</td>
                    
                    {{--
                    <td>
                        @if($complaint->status=="Closed") 
                            @if($complaint->closing_status=="1")
                            Partially Resolved
                            @elseif($complaint->closing_status=="2")
                            Temporary Closed
                            @elseif($complaint->closing_status=="3")
                            Fully Resolved
                            @endif
                        @else 
                        {{$complaint->status}} 
                        @endif
                    </td>
                    --}}
                    
                    <td>
						{{--<button type="button" class="btn btn-primary mb-2" id="complaintdetailbtn" data-id="{{ $complaint->id }}" data-bs-toggle="modal" data-bs-target="#complaintdetailmodal">View Detail</button>--}}
						
						{{--
						@if($complaint->status == 'Closed')
						@if($complaint->feedback->count() > 0)
						Already Feedback	
						@else	
						<button type="button" class="btn btn-primary mb-2" id="feedbackbtn" data-id="{{ $complaint->id }}" data-bs-toggle="modal" data-bs-target="#feedbackmodal">Send Feedback</button>
						@endif
					@elseif($complaint->status == 'Requirement')
					<button type="button" class="btn btn-primary mb-2" id="feedbackbtn" data-id="{{ $complaint->id }}" data-bs-toggle="modal" data-bs-target="#feedbackmodal">Send Requirement</button>
					@endif
					--}}
					
					</td>
                </tr>
			@endforeach	
				@else
				<tr><td colspan="9" class="text-center">Error! Record not Found.</td></tr>	
				@endif
            </table>

<x-frontend.complaint.complaintdetailmodal />
<x-frontend.complaint.feedbackmodal />		