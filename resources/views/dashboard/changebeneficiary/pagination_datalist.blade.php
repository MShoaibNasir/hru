<div class="row">
<div class="col-md-12">
<div class="table-responsive">
<table class="table table-striped table-bordered">
 <thead>
 <tr>
    <th scope="col">Actions</th>
    <th scope="col">Date</th>
    <th scope="col">Create By</th>
    <th scope="col">Data Type</th>
    <th scope="col">Ref No</th>
    <th scope="col">Department</th>
    <th scope="col">Status</th>
    <th scope="col">Report Trail</th>
    <th scope="col">Lot</th>
    <th scope="col">District</th>
    <th scope="col">Tehsil</th>
    <th scope="col">UC</th>
    <th scope="col">Update Date</th>
    <th scope="col">Update By</th>
 </tr>
 </thead>
 
 <tbody>
@foreach($data->chunk(3) as $chunks)
@foreach($chunks as $item)
                            <tr>
                                <td>
                                <a href='{{ route("changebeneficiary.show",[encrypt($item->id)]) }}' target="_blank" class='btn btn-success'>View CBID:{{ $item->id }}</a>
                                @if(Auth::user()->role == 30 && $item->type == 'New' && $item->status == 'P' && $item->role_id == 30)
                                <a href='{{ route("changebeneficiary.edit",[encrypt($item->id)]) }}' target="_blank" class='btn btn-primary'>Edit CBID:{{ $item->id }}</a>
                                @endif
                                </td>
                                <td>{{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                <td>{{$item->getuser->name ?? ''}}</td>
                                <td>{{$item->type ?? ''}}</td>
                                <td>{{$item->ref_no ?? ''}}</td>
                                <td>{{$item->role_name ?? ''}}</td>
                                <td>{{$item->status ?? '' }}</td>
                                <td>
                                @if($item->getstatustrail->count() > 0)
                                <a class="btn btn-sm btn-danger report_trail_btn" style="height:30px;" cb_id="{{ $item->id }}" href="javascript:void(0)">Report Trail</a>
                                @else
                                No Trail
                                @endif
                                </td>
                                <td>{{$item->getlot->name ?? ''}}</td>
                                <td>{{$item->getdistrict->name ?? ''}}</td>
                                <td>{{$item->gettehsil->name ?? ''}}</td>
                                <td>{{$item->getuc->name ?? ''}}</td>
                                <td>{{ Carbon\Carbon::parse($item->updated_at)->format('d-m-Y') }}</td>
                                <td>{{$item->getupdateuser->name ?? ''}}</td>
                                
                                	  
  </tr>
@endforeach
@endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
<div class="col-md-12">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries</div>
</div>