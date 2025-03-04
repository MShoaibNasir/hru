                       <h5>Field Supervisor Pending List</h5>
                        @foreach($field_super_visor_survey_final as $item)
                            @php
                            $authenticate_user_uc=json_decode(Auth::user()->uc_id);
                            $beneficairy_details=json_decode($item->beneficiary_details);
                            $form_status=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','field supervisor')->first();
                            // checking that if any form status of that form is priotize or not 
                            @endphp
                            @if(($form_status==null || $form_status->form_status=='P'))
                            @php
                            
                            $seniorpost=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','IP')->first();
                            if($item->update_by==null){
                               $update_by='field_supervisor';
                            }
                            @endphp
                            <tr style='background-color: {{$item->priority==1? '#19875433' : 'transparent'}}';>
                                
                                <td>{{$beneficairy_details->b_reference_number}}</td>
                                <td>{{$beneficairy_details->beneficiary_name}}</td>
                                <td>{{$beneficairy_details->cnic}}</td>
                                <td>{{$beneficairy_details->father_name}}</td>
                                <td>{{$item->form_name}}</td>
                                <td>{{$item->lot_name}}</td>
                                <td>{{$item->district_name}}</td>
                                <td>{{$item->tehsil_name}}</td>
                                <td>{{$item->uc_name}}</td>
                                <td>{{  $update_by }}</td>
                                  <td><span class='badge text-bg-danger'>{{$seniorpost ? ($seniorpost->form_status == 'A' ? 'approved' : ($seniorpost->form_status == 'R' ? 'rejected' : 'pending')) : 'Not Available' }}</span></td>
                                <td>
                                <button class='btn btn-info' onclick='showComment("{{ $seniorpost->comment ?? 'not available' }}")'>Show Comment</button>
                                </td>
                                <input type='hidden' value='{{$item->survey_form_id}}' class='survey_form_id'>
                                <input type='hidden' value='{{$update_by}}' class='update_by'>
                                <input type='hidden' value='is_m_and_e' id='is_m_and_e'>
                                </td>
                                <td>{{$item->generated_id}}</td>
                                <td><a href='{{route("beneficiaryProfile",[$item->survey_form_id,TRUE])}}' class='btn btn-success'>View Detail</a></td>
                            </tr>
                            @endif
                        @endforeach
                        
                        
                   
                        