                              
                            @foreach($survey_data as $item)
                            @php
                            $beneficairy_details=json_decode($item->beneficiary_details);
                            $form_status=form_status($item->survey_form_id,'HRU_Main');
                            $seniorpost=form_status($item->survey_form_id,'COO');
                            $junior=form_status($item->survey_form_id,'field supervisor');
                            $missing_document_highlight=missing_document_highlight($item->survey_form_id);
                         
                            $show_data=true;
                            if($seniorpost){
                                if($seniorpost->form_status=='R'){
                                    $show_data=false;
                                    
                                }else if(isset($junior) && $junior->form_status=='A'){
                                    $show_data=true;
                                }
                            }
                            
                             
                            @endphp
                            @if(($item->user_status=='37' || $item->user_status=='51' || $item->team_member_status=='PSIA') && ($form_status==null || $form_status->form_status=='P'))
                            <tr style='background-color: {{$missing_document_highlight==true? '#19875433' : 'transparent'}}';>
                                
                                <td>{{$item->ref_no}}</td>
                                <td>{{$item->beneficiary_name}}</td>
                                <td>{{$item->cnic}}</td>
                                <td>{{$item->father_name}}</td>
                                <td>{{$item->form_name}}</td>
                                <td>{{$item->lot_name}}</td>
                                <td>{{$item->district_name}}</td>
                                <td>{{$item->tehsil_name}}</td>
                                <td>{{$item->uc_name}}</td>
                                
                                <td>
                                    <span class='badge text-bg-danger'>{{ isset($seniorpost) ? ($seniorpost->form_status == 'A' ? 'approved' : ($seniorpost->form_status == 'R' ? 'rejected' : 'pending')): 'not availbale' }}</span></td>
                                <td>
                                <button class='btn btn-info' onclick='showComment("{{ $seniorpost->comment ?? 'not available' }}")'>Show Comment</button>
                                </td>
                                <input type='hidden' value='{{$item->survey_form_id}}' class='survey_form_id'>
                                </td>
                                <td>{{$item->generated_id}}</td>
                                <td>{{$item->submission_date}}</td>
                                <td><a href='{{route("beneficiaryProfile",[$item->survey_form_id,TRUE])}}' class='btn btn-success'>View Detail</a></td>
                            </tr>
                            @endif
                        @endforeach