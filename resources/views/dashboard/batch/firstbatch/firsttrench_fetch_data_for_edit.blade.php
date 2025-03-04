
<div class="row">
<div class="col-md-12 my-3 text-end">
    
{!! Form::open(array('route' => 'edit_batch_list_export','method'=>'POST')) !!}                 
	{!! Form::hidden('json_data', $jsondata) !!} 
	{!! Form::submit('Export Batch Data', array('name' => 'export', 'class' => 'btn btn-danger')); !!}
{!! Form::close() !!}   
</div>
<div class="col-md-12">
<div class="table-responsive">
<table class="table table-striped table-bordered">
 <thead>
 <tr>
                           <th scope="col">S NO</th>
                            <th scope="col">View</th>
                            <th scope="col">Ref No</th>
                            <th scope="col">Tranche No</th>
                            <th scope="col">Select Beneficairy</th>
                            <th scope="col">BENEFICIARY FULL NAME</th>
                            <th scope="col">BENEFICIARY FATHER'S/HUSBAND NAME</th>
                            <th scope="col">District</th>
                            <th scope="col">Tehsil</th>
                            <th scope="col">Uc</th>
                            <th scope="col">CNIC/ID NUMBER</th>
                            <th scope="col">MARITAL STATUS</th>
                            <th scope="col">ACCOUNT NUMBER</th>
                            <th scope="col">BANK NAME</th>
                            <th scope="col">BRANCH NAME</th>
                            <th scope="col">BANK ADDRESS</th>
                            <th scope="col">Amount</th>
 </tr>
 </thead>
 
 <tbody>
     
                    @foreach($data->chunk(3) as $chunks)
                    @foreach($chunks as $item)

                         @php
                         $selectedRefNo = session('selectedRefNo');
                      
                         if(!isset($selectedRefNo)){
                         $selectedRefNo=[];
                      
                         }
              
                      
                         $beneficiary=json_decode($item->beneficiary_details);
                         $account_number= $item->type=='biometric' ?  $item->account_number : $item->beneficiary_account_number;
                         $bank_name= $item->type=='biometric' ?  $item->bank_name :$item->beneficiary_bank_name;
                         $branch_name= $item->type=='biometric' ?  $item->branch_name :$item->beneficiary_branch_name;
                         $bank_address=$item->type=='biometric' ?  $item->bank_address :$item->beneficiary_bank_address;
                        @endphp
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td> <a class='btn btn-success' href='{{route("beneficiaryProfile",[$item->survey_form_id])}}' style='margin-left:10px;'>View SID: {{$item->survey_id}} </a></td>
                                <td> {{$item->ref_no}}</td>
                                <td> {{$item->trench_no}}</td>
                                @if(in_array($item->ref_no,$selectedRefNo) || in_array($item->ref_no,$edit_ref_no))
                                <td><input type="checkbox" class="row-checkbox get_id change_id_status" checked value='{{$item->ref_no}}'></td>
                                @else
                                <td><input type="checkbox" class="row-checkbox get_id" value='{{$item->ref_no}}'></td>
                                @endif
                                <td>{{$item->beneficiary_name ?? 'not available'}}</td>
                                <td>{{$beneficiary->father_name ?? 'not available'}}</td>
                                <td>{{$item->district_name ?? 'not available'}}</td>
                                <td>{{$item->tehsil_name ?? 'not available'}}</td>
                                <td>{{$item->uc_name ?? 'not available'}}</td>
                                <td>{{$item->beneficiary_cnic  ?? 'not available'}}</td>
                                <td>{{$item->marital_status ?? 'not available'}}</td>
                                <td>{{$account_number ?? 'not available'}}</td>
                                <td>{{$bank_name ?? 'not available'}}</td>
                                <td>{{$branch_name ?? 'not available'}}</td>
                                <td>{{$bank_address ?? 'not available'}}</td>
                                <td>100000</td>
                            </tr>
                        @endforeach
                        @endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
<div class="col-md-12">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries</div>
</div>