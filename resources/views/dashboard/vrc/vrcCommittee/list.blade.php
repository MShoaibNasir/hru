<div class="col-md-12 my-3 text-end">
{!! Form::open(array('route' => 'export_vrc_committee','method'=>'POST')) !!}                 
	{!! Form::hidden('environment_data', $jsondata) !!}
	{!! Form::submit('Export VRC Committee', array('name' => 'export', 'class' => 'btn btn-danger')); !!}
{!! Form::close() !!}
</div>   


<div class="row">
<div class="col-md-12">
<div class="table-responsive">
<table class="table table-striped table-bordered">
 <thead>
 <tr>

    <th scope="col">Ref No</th>
    <th scope="col">Name</th>
    <th scope="col">Father Name</th>
    <th scope="col">Gender</th>
    <th scope="col">Disability</th>
    <th scope="col">CNIC</th>
    <th scope="col">Mobile No</th>
    <th scope="col">vrc Designation</th>
    <th scope="col">Created At</th>
 </tr>
 </thead>
 
 <tbody>
 
@foreach($data->chunk(3) as $chunks)
@foreach($chunks as $item)


                            <tr>
                                <td>{{$item->beneficiary_id ?? ''}}</td>
                                <td>{{$item->name ?? ''}}</td>
                                <td>{{$item->father_name ?? ''}}</td>
                                <td>{{$item->gender ?? ''}}</td>
                                <td>{{$item->disability ?? ''}}</td>
                                <td>{{$item->cnic ?? ''}}</td>
                                <td>{{$item->mobile_no ?? ''}}</td>
                              
                                <td>{{$item->vrc_designation ?? ''}}</td>
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