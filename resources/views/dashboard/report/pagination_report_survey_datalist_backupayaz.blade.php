<?php
$bulk_survey_id_list = explode(",", $bulk_survey_id);
?>
<div class="row">
<div class="col-md-8 my-3 text-start">
Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries
</div>    
<div class="col-md-4 my-3 text-end">
@if($jsondata != '[]')
{!! Form::open(array('route' => 'report_survey_datalist_export','method'=>'POST')) !!}                 
	{!! Form::hidden('survey_datalist_export', $jsondata) !!}
	{!! Form::submit('Export Survey Report', array('name' => 'export', 'class' => 'btn btn-danger')); !!}
{!! Form::close() !!}
@endif
</div>
<div class="col-md-12">
<div class="table-responsive">
<table class="table table-striped table-bordered text-start">
 <thead>
 <tr>
    <th scope="col">Actions</th>
    <th scope="col">Bulk Action</th>
    <th scope="col">Date</th>
    <th scope="col">Ref No</th>
    <th scope="col">Priority Level</th>
    <th scope="col">Pending Days</th>
    <th scope="col">Status</th>
    <th scope="col">Department</th>
    <th scope="col">Report Trail</th>
    <th scope="col">Beneficiary Name</th>
    <th scope="col">Proposed Beneficiary</th>
    <th scope="col">CNIC</th>
    <th scope="col">Father Name</th>
    <th scope="col">User Name</th>
    <th scope="col">Form Name</th>
    <th scope="col">Lot</th>
    <th scope="col">District</th>
    <th scope="col">Tehsil</th>
    <th scope="col">UC</th>
    <th scope="col">id</th>
 </tr>
 </thead>
 
 <tbody>
@foreach($data->chunk(3) as $chunks)
@foreach($chunks as $item)
  @php
  $beneficairy_details=json_decode($item->beneficiary_details);
  $formstatus = $item->getformstatusold()->where('form_status', $status)->where('update_by', $department)->first();
  
  if($item->getformstatusold()->count() > 0){
  $days = $item->getformstatusold()->where('form_status', 'P')->orderBy('id','DESC')->first();
  if($days){
  $aging = Carbon\Carbon::parse($days->created_at)->diffInDays(Carbon\Carbon::now());
  }else{$aging = '';}
  }else{
  $aging = Carbon\Carbon::parse($item->created_at)->diffInDays(Carbon\Carbon::now());
  }
  @endphp
                            {{--<tr style='background-color: {{$item->priority==1 ? '#19875433' : 'transparent'}}';>--}}
                             <tr
                             @if($aging > 15)
                                        class="table-danger"
                                    @elseif($aging > 10)
                                        class="table-warning"
                                    @elseif($aging > 5)
                                        class="table-success"
                                    @endif
                             > 
                            @if(isset($formstatus->update_by) && isset($formstatus->form_status))
                                
                                @if((Auth::user()->role==40 || Auth::user()->role==51) && $formstatus->update_by == 'CEO' && $formstatus->form_status == 'P')   
                                <td><a href='{{route("beneficiaryProfile",[$item->id,TRUE])}}' class='btn btn-success' target="_blank">View SID:{{ $item->id }}</a></td>
                                @elseif((Auth::user()->role==38 || Auth::user()->role==51) && $formstatus->update_by == 'HRU_Main' && $formstatus->form_status == 'P')
                                <td><a href='{{route("beneficiaryProfile",[$item->id,TRUE])}}' class='btn btn-success' target="_blank">View SID:{{ $item->id }}</a></td>
                                @elseif((Auth::user()->role==37 || Auth::user()->role==51) && $formstatus->update_by == 'PSIA' && $formstatus->form_status == 'P')
                                <td><a href='{{route("beneficiaryProfile",[$item->id,TRUE])}}' class='btn btn-success' target="_blank">View SID:{{ $item->id }}</a></td>
                                @elseif((Auth::user()->role==36 || Auth::user()->role==51) && $formstatus->update_by == 'HRU' && $formstatus->form_status == 'P')
                                <td><a href='{{route("beneficiaryProfile",[$item->id,TRUE,$department])}}' class='btn btn-success' target="_blank">View SID:{{ $item->id }}</a></td>
                                @elseif((Auth::user()->role==34 || Auth::user()->role==51) && $formstatus->update_by == 'IP' && $formstatus->form_status == 'P')
                                <td><a href='{{route("beneficiaryProfile",[$item->id,TRUE,$department])}}' class='btn btn-success' target="_blank">View SID:{{ $item->id }}</a></td>
                                @elseif((Auth::user()->role==30 || Auth::user()->role==51) && $formstatus->update_by == 'field supervisor' && $formstatus->form_status == 'P')
                                <td><a href='{{route("beneficiaryProfile",[$item->id,TRUE,$department])}}' class='btn btn-success' target="_blank">View SID:{{ $item->id }}</a></td>
                                @else
                                <td><a href='{{route("beneficiaryProfile",[$item->id])}}' class='btn btn-success' target="_blank">View SID:{{ $item->id }}</a></td>
                                @endif
                                
                            @else
                            
                            @if(isset($not_action))
                                @if($not_action == 'reject_by_ceo')
                                <td><a href='{{route("beneficiaryProfile",[$item->id,TRUE])}}' class='btn btn-success' target="_blank">View SID:{{ $item->id }}</a></td>
                                @elseif($not_action == 'reject_by_hru_main')
                                <td><a href='{{route("beneficiaryProfile",[$item->id,TRUE])}}' class='btn btn-success' target="_blank">View SID:{{ $item->id }}</a></td>
                                @elseif($not_action == 'reject_by_psia')
                                <td><a href='{{route("beneficiaryProfile",[$item->id,TRUE])}}' class='btn btn-success' target="_blank">View SID:{{ $item->id }}</a></td>
                                @elseif($not_action == 'reject_by_hru')
                                <td><a href='{{route("beneficiaryProfile",[$item->id,TRUE])}}' class='btn btn-success' target="_blank">View SID:{{ $item->id }}</a></td>
                                @elseif($not_action == 'reject_by_ip')
                                <td><a href='{{route("beneficiaryProfile",[$item->id,TRUE])}}' class='btn btn-success' target="_blank">View SID:{{ $item->id }}</a></td>
                                @elseif($not_action == 'reject_by_fs')
                                <td><a href='{{route("beneficiaryProfile",[$item->id,TRUE])}}' class='btn btn-success' target="_blank">View SID:{{ $item->id }}</a></td>
                                @elseif($not_action == 'not_action_fs' && Auth::user()->role==30)
                                <td><a href='{{route("beneficiaryProfile",[$item->id,TRUE])}}' class='btn btn-success' target="_blank">View SID:{{ $item->id }}</a></td>
                                @else
                                <td><a href='{{route("beneficiaryProfile",[$item->id])}}' class='btn btn-success' target="_blank">View SID:{{ $item->id }}</a></td>
                                @endif
                            @else
                            <td><a href='{{route("beneficiaryProfile",[$item->id])}}' class='btn btn-success' target="_blank">View SID:{{ $item->id }}</a></td>
                            @endif
                            
                            
                            
                            
                            @endif
                            
                            
                            
                            @if($formstatus)
                            @if((Auth::user()->role == 36 && $formstatus->update_by == 'HRU' && $formstatus->form_status == 'P') || (Auth::user()->role == 40 && $formstatus->update_by == 'CEO' && $formstatus->form_status == 'P'))
                                <td>
                                    @if(in_array($item->id, $bulk_survey_id_list))
                                    <input type="checkbox" value="{{$item->id}}" class="bulk_survey_id" id="bulk_survey_id" checked />
                                    @else
                                    <input type="checkbox" value="{{$item->id}}" class="bulk_survey_id" id="bulk_survey_id" />
                                    @endif
                                </td>
                            @else
                            <td></td>
                            @endif
                            @else
                            <td></td>
                            @endif
                            
                                
                                <td>{{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                <td>{{$item->ref_no ?? ''}}</td>
                                <td>{{$item->total_scores ?? ''}} Score</td>
                                <td>{{ $aging ? $aging.' Days' : '' }}</td>
                                @if(isset($not_action))
                                
                                @if($not_action == 'reject_by_ceo')
                                <td>R</td>
                                <td>CEO</td>
                                @elseif($not_action == 'reject_by_hru_main')
                                <td>R</td>
                                <td>HRU_MAIN</td>
                                @elseif($not_action == 'reject_by_psia')
                                <td>R</td>
                                <td>PSIA</td>
                                @elseif($not_action == 'reject_by_hru')
                                <td>R</td>
                                <td>HRU</td>
                                @elseif($not_action == 'reject_by_ip')
                                <td>R</td>
                                <td>IP</td>
                                @elseif($not_action == 'reject_by_fs')
                                <td>R</td>
                                <td>Field Supervisor</td>
                                @else
                                <td>{{ $item->m_status ?? '' }}</td>
                                <td>{{ get_role_name($item->m_role_id) ?? '' }}</td>
                                @endif
                                
                                @else
                                @if($formstatus)
                                <td>{{ $formstatus->form_status ?? '' }}</td>
                                <td>{{ $formstatus->update_by ?? '' }} ( {{ $formstatus->created_by->name ?? '' }} )</td>
                                @else
                                <td>{{ $item->m_status ?? '' }}</td>
                                <td>{{ get_role_name($item->m_role_id) ?? '' }}</td>
                                @endif
                                @endif

                                {{--
                                <td>{{$item->getformstatus->new_status ?? ''}}</td>
                                <td>{{$item->getformstatus->role ?? ''}} ( {{ $item->getformstatus->created_by->name ?? '' }} )</td>
                                --}}
                                <td><a class="btn btn-sm btn-danger report_trail_btn" style="height:30px;" survey_id="{{ $item->id }}" href="javascript:void(0)">Report Trail</a>
                                
           <?php 
           /*
          $data = $item->getformstatus->report_history;
          
          dump($data);
          
          if(isset($data)){
          $selected_data = $data->map(function ($itemm) {
              
              return $itemm;
              
            //return [
                //'date' => Carbon\Carbon::parse($itemm->created_at)->format('d-m-Y'),
                //'sid' => $itemm->role

            //];
        });
          }
          */
        ?>
         </td>                       
                                
                                
                                
                                
                                <td>{{$beneficairy_details->beneficiary_name ?? '' }}</td>
                                <td>{{$item->beneficiary_name ?? '' }}</td>
                                <td>{{$item->cnic ?? ''}}</td>
                                <td>{{$beneficairy_details->father_name ?? '' }}</td>
                                <td>{{$item->getuser->name ?? ''}}</td>
                                <td>{{$item->getform->name ?? ''}}</td>
                                <td>{{$item->getlot->name ?? ''}}</td>
                                <td>{{$item->getdistrict->name ?? ''}}</td>
                                <td>{{$item->gettehsil->name ?? ''}}</td>
                                <td>{{$item->getuc->name ?? ''}}</td>
                                <td>{{$item->generated_id ?? '' }}</td>
                                
                                	  
  </tr>
@endforeach
@endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
</div>