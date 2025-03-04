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

</div>
<div class="col-md-12">
<div class="table-responsive">
<table class="table table-striped table-bordered">
 <thead>
  <tr class="text-dark">
                            <th scope="col">Ref No</th>
                            <th scope="col">Beneficiary Name</th>
                            <th scope="col">CNIC</th>
                            <th scope="col">Father Name</th>
                            <th scope="col">User Name</th>
                            <th scope="col">Form Name</th>
                            <th scope="col">Lot</th>
                            <th scope="col">District</th>
                            <th scope="col">Tehsil</th>
                            <th scope="col">UC</th>
                            <th scope="col">id</th>
                            <th scope="col">Latitude</th>
                            <th scope="col">Longitude</th>
                            <th scope="col">Altitude</th>
                            <th scope="col">Date</th>
                            <th scope="col">Actions</th>
                        </tr> 
 </thead>
 
 <tbody>
@foreach($data->chunk(3) as $chunks)
@foreach($chunks as $item)
                        @php
                        $beneficairy_details=json_decode($item->beneficiary_details);
                        if(isset($item->coordinates)){
                        $coordinates=json_decode($item->coordinates);
                        $latitude=null;
                        $longitutde=null;
                        $altitude=null;
                      
                        foreach($coordinates as $co){
                          if($co->label=='Latitude'){
                             $latitude=$co->answer;
                          
                          }
                          if($co->label=='Longitude'){
                             $longitutde=$co->answer;
                          }
                          if($co->label=='Altitude'){
                             $altitude=$co->answer;
                          }
                        }
                     
                        }
                   
                        @endphp
  <tr style='background-color: {{$item->priority==1 ? '#19875433' : 'transparent'}}';>
                                
                                <td>{{$item->ref_no}}</td>
                                <td>{{$item->beneficiary_name}}</td>
                                <td>{{$item->cnic}}</td>
                                <td>{{$item->father_name}}</td>
                                <td>{{$item->user_name}}</td>
                                <td>{{$item->form_name}}</td>
                                <td>{{$item->lot_name}}</td>
                                <td>{{$item->district_name}}</td>
                                <td>{{$item->tehsil_name}}</td>
                                <td>{{$item->uc_name}}</td>
                                <td>{{$item->generated_id}}</td>
                                <td>{{$longitutde ?? 'Not available'}}</td>
                                <td>{{$latitude ?? 'Not available'}}</td>
                                <td>{{$altitude ?? 'Not available'}}</td>
                                <td>{{$item->submission_date}}</td>
                                <td><a href='{{route("beneficiaryProfile",[$item->survey_form_id])}}' class='btn btn-success'>View Detail</a></td>
                            </tr>
@endforeach
@endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
<div class="col-md-12">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries</div>
</div>