
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
                            <th scope="col">S NO</th>
                            <th scope="col">View Form</th>
                            <th scope="col">Select Beneficairy</th>
                            <th scope="col">BENEFICIARY FULL NAME</th>
                            <th scope="col">Ref no</th>
                            <th scope="col">BENEFICIARY FATHER'S/HUSBAND NAME</th>
                            <th scope="col">Proposed Beneficiary</th>
                            <th scope="col">CNIC/ID NUMBER</th>
                            <th scope="col">MARITAL STATUS</th>
                            <th scope="col">ACCOUNT NUMBER</th>
                            <th scope="col">BANK NAME</th>
                            <th scope="col">BRANCH NAME</th>
                            <th scope="col">BANK ADDRESS</th>
                        </tr>
 </thead>
 
 <tbody>
     
                    @foreach($data->chunk(3) as $chunks)
                    @foreach($chunks as $item)
                        @php
                         $beneficiary=json_decode($item->beneficiary_details);
                         $selectedRefNo = session('selectedRefNo');
                         if(!isset($selectedRefNo)){
                          $selectedRefNo=[];
                         }
                        @endphp
                             <tr>
                                <td>{{$loop->index+1}}</td>
                                <td><a class='btn btn-success' href='{{route("beneficiaryProfile",[$item->survey_id])}}' style='margin-left:10px;'>View SID: {{$item->survey_id}} </a></td>
                             
                                 
                                @if(in_array($item->ref_no,$selectedRefNo))
                                <td><input type="checkbox" class="row-checkbox get_id" checked value='{{$item->ref_no}}'></td>
                                @else
                                <td><input type="checkbox" class="row-checkbox get_id" value='{{$item->ref_no}}'></td>
                                @endif
                                 
                                <td>{{$item->beneficiary_name ?? 'not available'}}</td>
                                <td>{{$item->ref_no ?? 'not available'}}</td>
                                <td>{{$beneficiary->father_name ?? 'not available'}}</td>
                                <td>{{$item->proposed_beneficiary ?? 'not available'}}</td>
                                <td>{{$item->beneficiary_cnic ?? 'not available'}}</td>
                                <td>{{$item->marital_status ?? 'not available'}}</td>
                                <td>{{$item->account_number ?? 'not available'}}</td>
                                <td>{{$item->bank_name ?? 'not available'}}</td>
                                <td>{{$item->branch_name ?? 'not available'}}</td>
                                <td>{{$item->bank_address ?? 'not available'}}</td>
                                
                            </tr>
                        @endforeach
                        @endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
<div class="col-md-12">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries</div>
</div>