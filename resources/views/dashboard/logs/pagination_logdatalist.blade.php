<div class="row">
<div class="col-md-12">
<div class="table-responsive">
<table class="table table-striped table-bordered" style="text-align:left;">
 <thead>
    <tr class="text-dark">
        <th scope="col">Log ID</th>
        <th scope="col">Date</th>
        <th scope="col">Time</th>
        <th scope="col">Action</th>
        <th scope="col">Section</th>
        <th scope="col">Ref No</th>
        <th scope="col">Activity</th>
    </tr>
 </thead>
 
 <tbody>
@foreach($data->chunk(3) as $chunks)
@foreach($chunks as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                <td>{{ Carbon\Carbon::parse($item->created_at)->format('H:i:s') }}</td>
                                <td>{{$item->action ?? 'not available'}}</td>
                                <td>{{$item->section ?? 'not available'}}</td>
                                <td>{{$item->ref_number ?? ''}}</td>
                                <td>{{$item->name}} - {{$item->activity}}</td>
                                
                            </tr>
@endforeach
@endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
<div class="col-md-12">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries</div>
</div>