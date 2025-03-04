<?php
$bulk_survey_id_list = explode(",", $bulk_survey_id);
?>
<div class="row">
<div class="col-md-8 my-3 text-start">
Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries
</div>

<div class="col-md-4 my-3 text-end">
@if($jsondata != '[]')
{!! Form::open(array('route' => 'report_survey_customdatalist_export','method'=>'POST')) !!}                 
	{!! Form::hidden('survey_datalist_export', $jsondata) !!}
	{!! Form::submit('Export Survey Data Report', array('name' => 'export', 'class' => 'btn btn-danger')); !!}
{!! Form::close() !!}
@endif
</div>
<div class="col-md-12">
<div class="table-responsive">
<table class="table table-striped table-bordered text-start">
 <thead>
 <tr>
    <th scope="col">Actions</th>
    @if(Auth::user()->role == 1)
    {{--<th scope="col">Bulk Action</th>--}}
    @endif
    <th scope="col">Date</th>
    <th scope="col">Add By</th>
    <th scope="col">Ref No</th>
    <th scope="col">CNIC</th>
    <th scope="col">Beneficiary Name</th>
    <th scope="col">Proposed Beneficiary</th>
    <th scope="col">Beneficiary Gender</th>
    <th scope="col">Father Name</th>
    <th scope="col">IS Registered in BISP</th>
    <th scope="col">Visually Challanged</th>
    <th scope="col">Amputation Case</th>
    <th scope="col">Physical Issues</th>
    <th scope="col">Lot</th>
    <th scope="col">District</th>
    <th scope="col">Tehsil</th>
    <th scope="col">UC</th>
    
    <th scope="col">Is Vulnerable</th>
    <th scope="col">Vulnerability</th>
    
    <th scope="col">Disability 2</th>
    <th scope="col">Gender 2</th>
    {{--
    <th scope="col">Disability 3</th>
    <th scope="col">Gender 3</th>
    <th scope="col">Disability 4</th>
    <th scope="col">Gender 4</th>
    <th scope="col">Disability 5</th>
    <th scope="col">Gender 5</th>
    --}}
 </tr>
 </thead>
 
 <tbody>
@foreach($data->chunk(3) as $chunks)
@foreach($chunks as $item)
                             <tr> 
                            <td><a href='{{route("beneficiaryProfile",[$item->survey_id])}}' class='btn btn-success' target="_blank">View SID:{{ $item->survey_id }}</a></td>
                            
                            
                            @if(Auth::user()->role == 1)
                                {{--<td>
                                    @if(in_array($item->id, $bulk_survey_id_list))
                                    <input type="checkbox" value="{{$item->id}}" class="bulk_survey_id" id="bulk_survey_id" checked />
                                    @else
                                    <input type="checkbox" value="{{$item->id}}" class="bulk_survey_id" id="bulk_survey_id" />
                                    @endif
                                </td>--}}
                            @endif    
                                
                                <td>{{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                <td>{{$item->getuser->name ?? ''}}</td>
                                <td>{{$item->ref_no ?? ''}}</td>
                                <td>{{$item->cnic ?? ''}}</td>
                                <td>{{$item->q_482 ?? '' }}</td>
                                <td>{{$item->q_643 ?? '' }}</td>
                                <td>{{$item->q_652 ?? '' }}</td>
                                <td>{{$item->q_483 ?? '' }}</td>
                                <td>{{$item->q_704 ?? '' }}</td>
                                <td>{{$item->q_2001 ?? '' }}</td>
                                <td>{{$item->q_2007 ?? '' }}</td>
                                <td>{{$item->q_2009 ?? '' }}</td>
                                
                                <td>{{$item->getlot->name ?? ''}}</td>
                                <td>{{$item->getdistrict->name ?? ''}}</td>
                                <td>{{$item->gettehsil->name ?? ''}}</td>
                                <td>{{$item->getuc->name ?? ''}}</td>
                                
                                <td>{{$item->q_2242 ?? '' }}</td>
                                <td>
                               
                                <?php $checkbox = json_decode($item->q_2243); ?>
                                    @if($checkbox)
                                        @foreach($checkbox as $item)
                                          <span class="badge bg-primary">{{ getoptionlabel($item->option_id) }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                
                                <td>{{$item->q_968 ?? '' }}</td>
                                <td>{{$item->q_971 ?? '' }}</td>
                                {{--
                                <td>{{$item->q_2071 ?? '' }}</td>
                                <td>{{$item->q_2079 ?? '' }}</td>
                                
                                <td>{{$item->q_2081 ?? '' }}</td>
                                <td>{{$item->q_2085 ?? '' }}</td>
                                
                                <td>{{$item->q_2185 ?? '' }}</td>
                                <td>{{$item->q_2188 ?? '' }}</td>
                                --}}
                                	 
  </tr>
@endforeach
@endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
</div>