<div class="col-md-12 my-3 text-end">
{!! Form::open(array('route' => 'environment_datalist_mitigation_export','method'=>'POST')) !!}                 
	{!! Form::hidden('environment_data', $jsondata) !!}
	{!! Form::submit('Export Environment Mitigation Data', array('name' => 'export', 'class' => 'btn btn-danger')); !!}
{!! Form::close() !!}
</div>    


<div class="row">
<div class="col-md-12">
<div class="table-responsive">
<table class="table table-striped table-bordered">
 <thead>
 <tr>
    <th scope="col">Actions</th>
    <th scope="col">Checklist Name</th>
    <th scope="col">Date</th>
    <th scope="col">Create By</th>
    <th scope="col">Ref No</th>
    <th scope="col">Department</th>
    <th scope="col">Get Action</th>
    <th scope="col">Status</th>
    <th scope="col">Report Trail</th>
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
  @endphp
                            <tr style='background-color: {{$item->priority==1 ? '#19875433' : 'transparent'}}';>
                                <td><a href='{{ route("environment_case.view",[$item->id]) }}' target="_blank" class='btn btn-success'>View ID:{{ $item->id }}</a></td>
                                <td>{{ $item->getFormName?->name }}</td>
                                <td>{{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                <td>{{$item->getuser->name ?? ''}}</td>
                                <td>{{$item->ref_no ?? ''}}</td>
                   
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
<div class="col-md-12">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries</div>
</div>