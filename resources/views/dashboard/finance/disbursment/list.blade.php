
<div class="row">
<div class="col-md-12 my-3 text-end">
{{--    
{!! Form::open(array('route' => 'pdmadatalist_export','method'=>'POST')) !!}                 
	{!! Form::hidden('pdma_export', $jsondata) !!}
	{!! Form::submit('Export PDNA Data', array('name' => 'export', 'class' => 'btn btn-danger')); !!}
{!! Form::close() !!}   --}}
</div>
<div class="col-md-12">
<div class="table-responsive">
<table class="table table-striped table-bordered">
 <thead>
   <tr class="text-dark">
                           
                            <th scope="col">View</th>
                            <th scope="col">Select Beneficairy</th>
                            <th scope="col">Ref no</th>
                            <th scope="col">BENEFICIARY FULL NAME</th>
                            <th scope="col">BENEFICIARY FATHER'S/HUSBAND NAME</th>
                            <th scope="col">CNIC/ID NUMBER</th>
                            <th scope="col">MARITAL STATUS</th>
                            <th scope="col">ACCOUNT NUMBER</th>
                            <th scope="col">BANK NAME</th>
                            <th scope="col">BRANCH NAME</th>
                            <th scope="col">BANK ADDRESS</th>
                            <th scope="col">Action</th>
                        </tr>
 </thead>
 
 <tbody>
                  
     
                    @foreach($data->chunk(3) as $chunks)
                    @foreach($chunks as $item)
                         @php
                         $beneficiary=json_decode($item->beneficiary_details);
                         $ref_no=$beneficiary->b_reference_number;
                         $selectedRefNo = session('selectedRefNo');
                         if(!isset($selectedRefNo)){
                          $selectedRefNo=[];
                         }
                         $beneficiary=json_decode($item->beneficiary_details);
                         $marital_status=get_answer(656,$item->survey_id);
                         $account_number= $item->type=='biometric' ?  $item->account_number : get_answer(250,$item->survey_id)->answer;
                         $bank_name= $item->type=='biometric' ?  $item->bank_name :get_answer(251,$item->survey_id)->answer;
                         $branch_name= $item->type=='biometric' ?  $item->branch_name :get_answer(252,$item->survey_id)->answer;
                         $bank_address=$item->type=='biometric' ?  $item->bank_address :get_answer(253,$item->survey_id)->answer;
                        @endphp

                        <tr>
                            
                         
                            <td> <a class='btn btn-success' href='{{route("beneficiaryProfile",[$item->survey_form_id,1])}}' style='margin-left:10px;'>View SID: {{$item->survey_id}} </a></td>
                            @if(in_array($item->ref_no,$selectedRefNo))
                            <td><input type="checkbox" class="row-checkbox get_id" checked value='{{$item->ref_no}}'></td>
                            @else
                            <td><input type="checkbox" class="row-checkbox get_id" value='{{$item->ref_no}}'></td>
                            @endif
                            <td>{{$item->ref_no}}</td>
                            <td>{{$item->beneficiary_name ?? 'not available'}}</td>
                            <td>{{$beneficiary->father_name ?? 'not available'}}</td>
                            <td>{{$item->beneficiary_cnic ?? 'not available'}}</td>
                            <td>{{$marital_status->answer ?? 'not available'}}</td>
                            <td>{{$account_number ?? 'not available'}}</td>
                            <td>{{$bank_name ?? 'not available'}}</td>
                            <td>{{$branch_name ?? 'not available'}}</td>
                            <td>{{$bank_address ?? 'not available'}}</td>
                            <td><a class='btn btn-success' href='{{route("moveToFirstTrench",[$item->ref_no,$item->id])}}'>Move To Trench</a></td>
                            
                        </tr>
                        @endforeach
                        @endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
<div class="col-md-12">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries</div>
</div>