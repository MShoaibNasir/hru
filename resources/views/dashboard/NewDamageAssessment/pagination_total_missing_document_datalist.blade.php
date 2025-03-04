<div class="row">
<div class="col-md-12">
<div class="table-responsive">
<table class="table table-striped table-bordered">
 <thead>
 <tr>
                            <th scope="col">Survey Id</th>
                            <th scope="col">Date</th>
                            <th scope="col">Ref No</th>
                            <th scope="col">Lot</th>
                            <th scope="col">District</th>
                            <th scope="col">Tehsil</th>
                            <th scope="col">UC</th>
                            <th scope="col">Evidence Type</th>
                            <th scope="col">Action</th>
 </tr>
 </thead>
 
 <tbody>
@foreach($data->chunk(3) as $chunks)
@foreach($chunks as $item)

                            <tr>
                                <td><a href='{{ route("beneficiaryProfile",[$item->id]) }}' class='btn btn-success' target="_blank">View SID:{{ $item->id }}</a></td>
                                <td>{{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                <td>{{ $item->ref_no }}</td>
                                <td>{{$item->getlot->name ?? ''}}</td>
                                <td>{{$item->getdistrict->name ?? ''}}</td>
                                <td>{{$item->gettehsil->name ?? ''}}</td>
                                <td>{{$item->getuc->name ?? ''}}</td>
                                <td>{{ $item->evidence_type }}</td>
                                <td><a class="btn btn-sm btn-danger upload_mission_documents" comment_id="0" survey_id="{{ $item->id }}" action="upload" href="javascript:void(0)">Upload Missing Document</a></td>
                            </tr>
@endforeach
@endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
<div class="col-md-12">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries</div>
</div>