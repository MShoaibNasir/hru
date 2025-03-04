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
    <th scope="col">Report Trail</th>
    <th scope="col">Bulk Action</th>
    <th scope="col">Date</th>
    <th scope="col">Ref No</th>
    <th scope="col">Priority Level</th>
    <th scope="col">Pending Days</th>
    <th scope="col">Status</th>
    <th scope="col">Department</th>
    <th scope="col">Last Action Perform</th>
    <th scope="col">Last Status</th>
    <th scope="col">Last Action By</th>
    <th scope="col">Last Department</th>
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
    <th scope="col">Location</th>
    <th scope="col">Longitude</th>
    <th scope="col">Latitude</th>
    <th scope="col">Account</th>
    <th scope="col">Batch No</th>
    <th scope="col">Tranche No</th>
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
  
  
if($item->updated_at){
$aging2 = Carbon\Carbon::parse($item->updated_at)->diffInDays(Carbon\Carbon::now());
}else{
$aging2 = Carbon\Carbon::parse($item->created_at)->diffInDays(Carbon\Carbon::now());
}


$agingnew = 0;
if($item->m_status == 'P'){  
if($item->m_last_action_date){
$agingnew = Carbon\Carbon::parse($item->m_last_action_date)->diffInDays(Carbon\Carbon::now());
}else{
$agingnew = Carbon\Carbon::parse($item->created_at)->diffInDays(Carbon\Carbon::now());
}
}









  @endphp
                            
            <tr @class(['table-danger'  => $agingnew > 15, 'table-warning' => $agingnew > 10 && $agingnew <= 15, 'table-success' => $agingnew > 5 && $agingnew <= 10])>
                             <td>
                             @if(
                                (Auth::user()->role == $item->m_role_id && $item->m_status == 'P') || 
                                (Auth::user()->role == $item->m_role_id && $item->m_status == 'H')
                                )
                            <a href='{{route("beneficiaryProfile",[$item->id,TRUE])}}' class='btn btn-success' target="_blank">View SID:{{ $item->id }}</a>    
                            @else
                            <a href='{{route("beneficiaryProfile",[$item->id])}}' class='btn btn-success' target="_blank">View SID:{{ $item->id }}</a>
@endif 
</td>
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
                            <td>
                                @if(
                                (Auth::user()->role == 36 && Auth::user()->role == $item->m_role_id && $item->m_status == 'P') || 
                                (Auth::user()->role == 40 && $item->m_role_id == Auth::user()->role && $item->m_status == 'P') ||
                                (Auth::user()->role == 36 && Auth::user()->role == $item->m_role_id && $item->m_status == 'H') || 
                                (Auth::user()->role == 40 && $item->m_role_id == Auth::user()->role && $item->m_status == 'H')
                                )
                                    @if(in_array($item->id, $bulk_survey_id_list))
                                    <input type="checkbox" value="{{$item->id}}" class="bulk_survey_id" id="bulk_survey_id" checked />
                                    @else
                                    <input type="checkbox" value="{{$item->id}}" class="bulk_survey_id" id="bulk_survey_id" />
                                    @endif
                                @endif    
                                    
                            </td>
                            <td>{{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                <td>{{$item->ref_no ?? ''}}</td>
                            
                            
                                
                                
                                <td>{{$item->total_scores ?? ''}} Score</td>
                                <td>{{ $agingnew ? $agingnew.' Days' : '' }}</td>
                                
                                
                                
                               
                                <td>{{ $item->m_status ?? '' }}</td>
                                <td>{{ get_role_name($item->m_role_id) ?? '' }}</td>
                                <td>{{ isset($item->m_last_action_date) ? Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->m_last_action_date)->diffForHumans() : '' }}</td>
                                <td>{{ $item->m_last_action ?? '' }}</td>
                                <td>{{ $item->get_last_action_user->name ?? '' }}</td>
                                <td>{{ get_role_name($item->m_last_action_role_id) ?? '' }}</td>
                                  
         
                                
                                
                                
                                
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
                                <td>{{ $item->getsection38->q_2000 ?? '' }}</td>
                                <?php 
                                        $location = $item->getsection38->q_416 ?? null;
                                        $locationData = $location ? json_decode($location, true) : []; 
                                    ?>
                                <td>
                                    @if (!empty($locationData) && isset($locationData[0]['answer']) )
                                        {{ $locationData[0]['answer'] }}
                                    @endif
                                </td>
                                <td>
                                    @if (!empty($locationData) && isset($locationData[1]['answer']) )
                                        {{ $locationData[1]['answer'] }}
                                    @endif
                                </td>
                                <td>{!! $item->bank_ac_wise ? ($item->bank_ac_wise === 'Yes' ? '<span class="badge bg-success">With Account</span>' : '<span class="badge bg-danger">Without Account</span>') : 'N/A' !!}</td>
                                <td> 
@if(isset($item->getfirstbatch) && $item->getfirstbatch->count() > 0)
@foreach($item->getfirstbatch as $item)
{{ $item->getbatch->batch_no ?? '' }} <br />
@endforeach
@else
N/A
@endif
</td>
                                <td>
                                    @if(isset($item->getverifybeneficairytranche) && $item->getverifybeneficairytranche->count() > 0)
                                    @foreach($item->getverifybeneficairytranche as $item)
                                    Tranche: {{ $item->trench_no ?? '' }}<br />
                                    @endforeach
                                    @else
                                    N/A
                                    @endif
                                    
                                </td>
                                <td>{{$item->generated_id ?? '' }}</td>
                                
                                
                                
                                	  
  </tr>
@endforeach
@endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
<div class="col-md-12">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries</div>
</div>