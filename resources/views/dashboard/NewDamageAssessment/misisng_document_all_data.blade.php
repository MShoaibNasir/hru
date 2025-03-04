<div class="row">
<div class="col-md-12">
<div class="table-responsive">
<table class="table table-striped table-bordered">
 <thead>
 <tr>
                           <th scope="col">Survey Id</th>
                            <th scope="col">Date</th>
                            <th scope="col">Ref No</th>
                            <th scope="col">Status</th>
                            <th scope="col">Lot</th>
                            <th scope="col">Action</th>
                            <th scope="col">Department</th>
                            <th scope="col">Comment By</th>
                            <th scope="col">Comment</th>
 </tr>
 </thead>
 
 <tbody>
@foreach($data->chunk(3) as $chunks)
@foreach($chunks as $item)

                            <tr>
                                <td><a href='{{ route("beneficiaryProfile",[$item->survey_id]) }}' class='btn btn-success' target="_blank">View SID:{{ $item->survey_id }}</a></td>
                                <td>{{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                <td>{{ get_ref_no($item->survey_id) }}</td>
                                <td>{{ $item->status }}</td>
                                <td>Lot {{ $item->lot_id }}</td>
                                @if($item->status == 'P')
                                <td><a class="btn btn-sm btn-danger upload_mission_documents" comment_id="{{ $item->id }}" survey_id="{{ $item->survey_id }}" action="upload" href="javascript:void(0)">Upload Missing Document</a></td>
                                @else
                                <td>Document Uploaded</td>
                                @endif
                                <td>{{ $item->created_role }}</td>
                                <td>{{ get_user_name($item->created_by) }}</td>
                                <td>{{$item->comment}}</td>
                            </tr>
@endforeach
@endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
<div class="col-md-12">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries</div>
</div>