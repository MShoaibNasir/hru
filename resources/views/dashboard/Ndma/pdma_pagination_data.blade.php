{{--
@php
$current_user = Auth::user();
if ($current_user) {
$allow_access = DB::table('users')
->join('roles', 'users.id', '=', 'users.role')
->where('users.id', '=', $current_user->id)
->first();
}
$settlement_management = json_decode($allow_access->user_management);
array_unshift($settlement_management, 0);
@endphp
@if($settlement_management[1] == 1)
@endif
--}}
    
<div class="row">
<div class="col-md-12 my-3 text-end">
{!! Form::open(array('route' => 'pdmadatalist_export','method'=>'POST')) !!}                 
	{!! Form::hidden('pdma_export', $jsondata) !!}
	{!! Form::submit('Export PDNA Data', array('name' => 'export', 'class' => 'btn btn-danger')); !!}
{!! Form::close() !!}
</div>
<div class="col-md-12">
<div class="table-responsive">
<table class="table table-striped table-bordered">
 <thead>
 <tr>
    <th scope="col">S no</th>
    <th scope="col">Reference Number</th>       
    <th scope="col">Province</th>         
    <th scope="col">CNIC</th>         
    <th scope="col">Survey Date</th>         
    <th scope="col">Address</th>         
    <th scope="col">District</th>         
    <th scope="col">Tehsil</th>         
    <th scope="col">Uc</th>         
    <th scope="col">Beneficiary Name</th>         
    <th scope="col">Father/Husbent Name</th>         
    <th scope="col">Contact number</th>         
    <th scope="col">Gender</th>         
    <th scope="col">Age</th>         
    <th scope="col">Name of next kin</th>         
    <th scope="col">Cnic of kin</th>         
    <th scope="col">Damaged Rooms</th>         
    <th scope="col">Damaged Type</th>         
    <th scope="col">Damaged Category</th>         
    <th scope="col">Auto Gender</th>         
    <th scope="col">IS CNIC</th>         
    <th scope="col">IS Contact</th>
    <th scope="col">IS Complete</th>
    <th scope="col">IS Potential</th>
    @if(Auth::user()->role==40)
    <th scope="col">Action</th>
    @endif
   
 </tr>
 </thead>
 
 <tbody>
@foreach($data->chunk(3) as $chunks)
@foreach($chunks as $item)
  <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->b_reference_number}}</td>
                                <td>{{$item->province}}</td>
                                <td>{{$item->cnic}}</td>
                                <td>{{$item->survey_date}}</td>
                                <td>{{$item->address}}</td>
                                <td>{{$item->getdistrict->name ?? ''}}</td>
                                <td>{{$item->gettehsil->name ?? ''}}</td>
                                <td>{{$item->getuc->name ?? ''}}</td>
                                <td>{{$item->beneficiary_name}}</td>
                                <td>{{$item->father_name}}</td>
                                <td>{{$item->contact_number}}</td>
                                <td>{{$item->gender}}</td>
                                <td>{{$item->age}}</td>
                                <td>{{$item->name_next_of_kin}}</td>
                                <td>{{$item->cnic_of_kin}}</td>
                                <td>{{$item->damaged_rooms}}</td>
                                <td>{{$item->damaged_type}}</td>
                                <td>{{$item->damaged_category}}</td>
                                <td>{{$item->auto_gender}}</td>
                                <td>{{$item->is_cnic}}</td>
                                <td>{{$item->is_contact}}</td>
                                <td>{{$item->is_complete}}</td>
                                <td>{{$item->is_potential}}</td>
                                @if(Auth::user()->role==40)
                                <td><a class='btn btn-success' href='{{route("edit.pdma",[$item->id])}}'>Edit</a></td>
                                @endif
                                
                                
  </tr>
@endforeach
@endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
<div class="col-md-12">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries</div>
</div>