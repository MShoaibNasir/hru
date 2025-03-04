<div class="row">

<div class="col-md-12 my-3 text-end">
{!! Form::open(array('route' => 'environment_datalist_export','method'=>'POST')) !!}                 
	{!! Form::hidden('environment_data', $jsondata) !!}
	{!! Form::submit('Export Environment Data', array('name' => 'export', 'class' => 'btn btn-danger')); !!}
{!! Form::close() !!}
</div>    
<div class="col-md-12">
<div class="table-responsive">
<table class="table table-striped table-bordered">
 <thead>
 <tr>
    <th scope="col">Actions</th>
    <th scope="col">Ref No</th>
    <th scope="col">Department</th>
    <th scope="col">Status</th>
    <th scope="col">Report Trail</th>
    <th scope="col">Lot</th>
    <th scope="col">District</th>
    <th scope="col">Tehsil</th>
    <th scope="col">Uc</th>
 </tr>
 </thead>
 
 <tbody>

@foreach($data->chunk(3) as $chunks)
@foreach($chunks as $item)

@php
$trail=\DB::table('environment_status_histories')->where('environment_id',$item->primary_id)->first() ?? null;
$status='';
if($item->status=='P'){
$status='Pending';
}
if($item->status=='C'){
$status='Case Close';
}
if($item->status=='CR'){
$status='Case Register';
}
@endphp




                            <tr>     
                            <td> <a href='{{route("environment.profile",[$item->survey_form_id])}}' class='btn btn-success'>SID:{{$item->survey_form_id}}</a>  </td>
                            <td>{{$item->survey_form_ref_no}}</td>    	  
                            <td>{{$item->role_name}}</td>    	  
                            <td>{{$status}}</td>
                               <td>
                                @if($trail)
                                <a class="btn btn-sm btn-danger report_trail_btn" style="height:30px;" construction_id="{{ $item->primary_id }}" href="javascript:void(0)">Report Trail</a>
                                @else
                                No Trail
                                @endif
                                </td>
                            <td>{{$item->lot_name}}</td>    	  
                            <td>{{$item->district_name}}</td>    	  
                            <td>{{$item->tehsil_name}}</td>    	  
                            <td>{{$item->uc_name}}</td>    	  
                           </tr>
@endforeach
@endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
<div class="col-md-12">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries</div>
</div>