
<div class="row">
<div class="col-md-12 my-3 text-end">
  
{!! Form::open(array('route' => 'withoutAccount_export','method'=>'POST')) !!}                 
	{!! Form::hidden('pdma_export', $jsondata) !!}
	{!! Form::submit('Export WithOut Account List Data', array('name' => 'export', 'class' => 'btn btn-danger')); !!}
{!! Form::close() !!}   
</div>
<div class="col-md-12">
<div class="table-responsive">
<table class="table table-striped table-bordered">
 <thead>
<tr class="text-dark">
                          
                           
                            <th scope="col">Action</th>
                            <th scope="col">Select Beneficairy</th>
                            <th scope="col">REFERENCE NO</th>
                            <th scope="col">BENEFICIARY FULL NAME</th>
                            <th scope="col">BENEFICIARY FATHER'S/HUSBAND NAME</th>
                            <th scope="col">Proposed Beneficiary</th>
                            <th scope="col">CNIC/ID NUMBER</th>
                            <th scope="col">Phone Number</th>
                            <th scope="col">MARITAL STATUS</th>
                            <th scope="col">DATE OF ISSUANCE OF CNIC</th>
                            <th scope="col">MOTHER MAIDEN NAME</th>
                            <th scope="col">CITY OF BIRTH</th>
                            <th scope="col">CNIC EXPIRY STATUS</th>
                            <th scope="col">CNIC EXPIRY DATE</th>
                            <th scope="col">DATE OF BIRTH</th>
                            <th scope="col">VILLAGE/SETTLEMENT NAME</th>
                            <th scope="col">DISTRICT</th>
                            <th scope="col">TEHSIL</th>
                            <th scope="col">UC</th>
                            <th scope="col">NEXT OF KIN NAME</th>
                            <th scope="col">NEXT OF KIN CNIC</th>
                            <th scope="col">RELATIONSHIP WITH NEXT OF KIN</th>
                            <th scope="col">CONTACT NO OF NEXT OF KIN</th>
                            <th scope="col">PREFERED BANK</th>
                            <th scope="col">BENEFICIARY FRONT CNIC</th>
                            <th scope="col">BENEFICIARY BACK CNIC</th>
                            <th scope="col">Date</th>
                         
                        
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
                        @endphp

                             <tr>
                                 
                                
                             
                                @if(Auth::user()->role==48)
                                <td><a class='btn btn-success' href='{{route("beneficiaryProfile",[$item->survey_id,1])}}' style='margin-left:10px;'>View SID: {{$item->survey_id}} </a></td>
                                @else
                                <td><a class='btn btn-success' href='{{route("beneficiaryProfile",[$item->survey_id,1])}}' style='margin-left:10px;'>View SID: {{$item->survey_id}} </a></td>
                                @endif
                                @if(in_array($item->ref_no,$selectedRefNo))
                                <td><input type="checkbox" class="row-checkbox get_id" checked value='{{$item->ref_no}}'></td>
                                @else
                                <td><input type="checkbox" class="row-checkbox get_id" value='{{$item->ref_no}}'></td>
                                @endif
                                <td>{{$item->ref_no ?? 'not available'}}</td>
                                <td>{{$item->beneficiary_name ?? 'not available'}}</td>
                                <td>{{$beneficiary->father_name ?? 'not available'}}</td>
                                <td>{{$item->proposed_beneficiary ?? 'not available'}}</td>
                                <td>{{$item->beneficiary_cnic ?? 'not available'}}</td>
                                <td>{{$item->phone_number ?? 'not available'}}</td>
                                <td>{{$item->marital_status ?? 'not available'}}</td>
                                <td>{{$item->date_of_insurence_of_cnic ?? 'not available'}}</td>
                                <td>{{$item->mother_maiden_name ?? 'not available'}}</td>
                                <td>{{$item->city_of_birth ?? 'not available'}}</td>
                                <td>{{$item->cnic_expiry_status ?? 'not available'}}</td>
                                <td>{{$item->expiry_date ?? 'not available'}}</td>
                                <td>{{$item->date_of_birth ?? 'not available'}}</td>
                                <td>{{$item->village_name ?? 'not available'}}</td>
                                <td>{{$item->district_name ?? 'not available'}}</td>
                                <td>{{$item->tehsil_name ?? 'not available'}}</td>
                                <td>{{$item->uc_name ?? 'not available'}}</td>
                                <td>{{$item->next_kin_name ?? 'not available'}}</td>
                                <td>{{$item->cnic_of_kin ?? 'not available'}}</td>
                                <td>{{$item->relation_cnic_of_kin ?? 'not available'}}</td>
                                <td>{{$item->conatact_of_next_kin ?? 'not available'}}</td>
                                <td>{{$item->preferred_bank ?? 'not available'}}</td>
                                <td>{{$item->b_f_cnic ?? 'not available'}}</td>
                                <td>{{$item->b_b_cnic ?? 'not available'}}</td>
                                <td>{{$item->created_at ?? 'not available'}}</td>
                            </tr>
                        @endforeach
                        @endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
<div class="col-md-12">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries</div>
</div>