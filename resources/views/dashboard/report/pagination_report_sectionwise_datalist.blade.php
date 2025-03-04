<?php
$bulk_survey_id_list = explode(",", $bulk_survey_id);
?>
<div class="row">
<div class="col-md-8 my-3 text-start">
Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries
</div>

<div class="col-md-4 my-3 text-end">
@if($jsondata != '[]')
{!! Form::open(array('route' => 'report_sectionwise_datalist_export','method'=>'POST')) !!}                 
	{!! Form::hidden('survey_datalist_export', $jsondata) !!}
	{!! Form::submit('Export Sectionwise Data Report', array('name' => 'export', 'class' => 'btn btn-danger')); !!}
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
    
    <th scope="col">Lot</th>
    <th scope="col">District</th>
    <th scope="col">Tehsil</th>
    <th scope="col">UC</th>
    <th scope="col">Gender</th>
    <th scope="col">Section 86 652 Gender</th>
    <th scope="col">Is Vulnerable</th>
    <th scope="col">Vulnerability</th>
 </tr>
 </thead>
 
 <tbody>
@foreach($data->chunk(3) as $chunks)
@foreach($chunks as $item)
                             <tr> 
                            <td><a href='{{route("beneficiaryProfile",[$item->id])}}' class='btn btn-success' target="_blank">View SID:{{ $item->id }}</a></td>
                            
                            
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
                                
                                
                                <td>{{$item->getlot->name ?? ''}}</td>
                                <td>{{$item->getdistrict->name ?? ''}}</td>
                                <td>{{$item->gettehsil->name ?? ''}}</td>
                                <td>{{$item->getuc->name ?? ''}}</td>
                                <td>{{$item->gender ?? ''}}</td>
                                <td>{{$item->getsection86->q_652 ?? ''}}</td>
                                
                                <td>{{$item->getsection117->q_2242 ?? ''}}</td>
                                <td>
                               
                                <?php 
                                if(isset($item->getsection117->q_2243)){
                                $checkbox = json_decode($item->getsection117->q_2243); ?>
                                    @if($checkbox)
                                        @foreach($checkbox as $item)
                                          <span class="badge bg-primary">{{ getoptionlabel($item->option_id) }}</span>
                                        @endforeach
                                    @endif
                                    <?php } ?>
                                </td>
                                
                                
                                
                                	 
  </tr>
@endforeach
@endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
</div>