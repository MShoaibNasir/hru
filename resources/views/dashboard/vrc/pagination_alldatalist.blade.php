<div class="col-md-12 my-3 text-end">
    {!! Form::open(array('route' => 'export_vrc_formation','method'=>'POST')) !!}                 
    	{!! Form::hidden('environment_data', $jsondata) !!}
    	{!! Form::submit('Export VRC Formation', array('name' => 'export', 'class' => 'btn btn-danger')); !!}
    {!! Form::close() !!}
</div>  
            </div> 

<div class="row">
<div class="col-md-12">
<div class="table-responsive">
<table class="table table-striped table-bordered">
 <thead>
 <tr>
    <th scope="col">Committee</th>
    <th scope="col">Event</th>
    <th scope="col">Name Of VRC</th>
    <th scope="col">Lot</th>
    <th scope="col">District</th>
    <th scope="col">Tehsil</th>
    <th scope="col">UC</th>
    <th scope="col">No Of Village</th>
    <th scope="col">Total Beneficiries</th>
    <th scope="col">VRC Members</th>
    <th scope="col">Capture Image TOP</th>
    <th scope="col">Capture Image Notification</th>
    <th scope="col">Created At</th>
 </tr>
 </thead>
 
 <tbody>
 
@foreach($data->chunk(3) as $chunks)
@foreach($chunks as $item)


                            <tr>
                                <td><a href='{{ route("vrc_filter",[$item->id]) }}' target="_blank" class='btn btn-success'>View ID:{{ $item->id }}</a></td>
                                <td><a href='{{ route("vrc_event_list",[$item->id]) }}' target="_blank" class='btn btn-success'>View ID:{{ $item->id }}</a></td>
                                <td>{{$item->name_of_vrc ?? ''}}</td>
                                <td>{{$item->lot ?? ''}}</td>
                                <td>{{$item->district ?? ''}}</td>
                                <td>{{$item->tehsil ?? ''}}</td>
                                <td>{{$item->uc ?? ''}}</td>
                                <td>{{$item->no_of_village ?? ''}}</td>
                                <td>{{$item->total_beneficiaries ?? ''}}</td>
                                <td>{{$item->vrc_members ?? ''}}</td>
                                <td>{!! isset($item->capture_image_top) ? '<img  src="'.asset('storage/vrc_attendance/'.$item->capture_image_top).'" width="100" alt="" class="myImg" />' : 'No Image' !!}</td>
                                <td>{!! isset($item->capture_image_notification) ? '<img src="'.asset('storage/vrc_attendance/'.$item->capture_image_notification).'" width="100" alt="" class="myImg" />' : 'No Image' !!}</td>

                                <td>{{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                
                                	  
  </tr>
@endforeach
@endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
<div class="col-md-12">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries</div>
</div>