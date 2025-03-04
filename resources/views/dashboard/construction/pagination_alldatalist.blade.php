<div class="row">
    
<div class="col-md-8 my-3 text-start">
Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}}/35100 entries 
</div>    
<div class="col-md-4 my-3 text-end"></div>

<div class="col-md-12">
<div class="table-responsive">
<table class="table table-striped table-bordered">
 <thead>
 <tr>
    <th scope="col">Actions</th>
    <th scope="col">Date</th>
    <th scope="col">Create By</th>
    <th scope="col">Ref No</th>
    <th scope="col">Stage</th>
    <th scope="col">Department</th>
    <th scope="col">Get Action</th>
    <th scope="col">Status</th>
    <th scope="col">Report Trail</th>
    
    <th scope="col">Last Action Perform</th>
    <th scope="col">Last Status</th>
    <th scope="col">Last Action By</th>
    <th scope="col">Last Department</th>
    
    <th scope="col">Lot</th>
    <th scope="col">District</th>
    <th scope="col">Tehsil</th>
    <th scope="col">UC</th>
 </tr>
 </thead>
 
 <tbody>
@foreach($data->chunk(3) as $chunks)
@foreach($chunks as $item)
  @php
  $beneficairy_details=json_decode($item->beneficiary_details);
$aging = 0;

if($item->role_id == 48 && $item->status == 'P'){  
if($item->action_date){
$aging = Carbon\Carbon::parse($item->action_date)->diffInDays(Carbon\Carbon::now());
}
}
  
  
  
  @endphp
        <tr @class(['table-danger' => $aging > 14])>
                                <td><a href='{{ route("construction.view",[$item->id]) }}' target="_blank" class='btn btn-success'>View ID:{{ $item->id }}</a></td>
                                <td>{{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                <td>{{$item->getuser->name ?? ''}}</td>
                                <td>{{$item->ref_no ?? ''}}</td>
                                <td>{{$item->stage ?? '' }}</td>
                                <td>{{$item->role_name ?? ''}}</td>
                                <td>{{ $item->action_condition == 3 ? 'Yes' : ($item->action_condition < 3 ? 'No' : '')}}</td>
                                <td>{{$item->status ?? '' }}</td>
                                <td>
                                @if($item->getstatustrail->count() > 0)
                                <a class="btn btn-sm btn-danger report_trail_btn" style="height:30px;" construction_id="{{ $item->id }}" href="javascript:void(0)">Report Trail</a>
                                @else
                                No Trail
                                @endif
                                </td>
                                
                                <td>{{ isset($item->action_date) ? Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->action_date)->diffForHumans() : '' }}</td>
                                <td>{{ $item->last_action ?? '' }}</td>
                                <td>{{ $item->get_last_action_user->name ?? '' }}</td>
                                <td>{{ get_role_name($item->last_action_role_id) ?? '' }}</td>
                                
                                <td>{{$item->getlot->name ?? ''}}</td>
                                <td>{{$item->getdistrict->name ?? ''}}</td>
                                <td>{{$item->gettehsil->name ?? ''}}</td>
                                <td>{{$item->getuc->name ?? ''}}</td>
                                
                                	  
  </tr>
@endforeach
@endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
<div class="col-md-12">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}}/35100 entries</div>
</div>