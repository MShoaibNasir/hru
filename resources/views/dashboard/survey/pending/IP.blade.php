
                           
                            @foreach($survey_data as $item)
                          
                            @php
                            $beneficairy_details=json_decode($item->beneficiary_details);
                            //  current status of
                            $form_status=form_status($item->survey_form_id,'IP');
                            //  senior status to find about his rejection commnet  
                            $seniorpost=form_status($item->survey_form_id,'HRU');
                            $junior=form_status($item->survey_form_id,'field supervisor');
                            $show_data=true;
                            if(isset($seniorpost)){
                                if($seniorpost->form_status=='R'){
                                    $show_data=false;
                                }else if(isset($junior) && $junior->form_status=='A'){
                                    $show_data=true;
                                }
                            }  
                            @endphp
                             {{-- here three condition must be filfil 1- form uc must be include in user account 2-junior user role verify according to the form step 3 form must be in pending condition  --}}
                            @if(($show_data)  && ($item->user_status=='30'  || $item->user_status=='51' || $item->team_member_status=='field_supervisor') && ($form_status==null || $form_status->form_status=='P'))
                           
                          
                            <tr style='background-color: {{$item->priority==1? '#19875433' : 'transparent'}}';>
                                <td>{{$item->ref_no}}</td>
                                 <td>{{$item->beneficiary_name}}</td>
                                <td>{{$item->cnic}}</td>
                                <td>{{$item->father_name}}</td>
                                <td>{{$item->form_name}}</td>
                                <td>{{$item->lot_name}}</td>
                                <td>{{$item->district_name}}</td>
                                <td>{{$item->tehsil_name}}</td>
                                <td>{{$item->uc_name}}</td>
                                
                                
                                 <td><span class='badge text-bg-danger'>{{ isset($seniorpost) ? ($seniorpost->form_status == 'A' ? 'approved' : ($seniorpost->form_status == 'R' ? 'rejected' : 'pending')): 'Not Available' }} </span></td>
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