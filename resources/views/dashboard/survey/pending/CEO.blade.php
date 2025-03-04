
                            @foreach($survey_data as $item)
                            @php
                              
                            $beneficairy_details=json_decode($item->beneficiary_details);
                            $form_status=form_status($item->survey_form_id,'CEO');
                            $hru_form_status=form_status($item->survey_form_id,'HRU_Main');
                            @endphp
                            @if(($item->user_status=='39' || $item->user_status=='38' || $item->user_status=='51' || $item->team_member_status=='COO' || $item->team_member_status=='HRU_Main') && (($form_status==null || $form_status->form_status=='P') || ($hru_form_status==null || $hru_form_status->form_status=='P'))  )
                            <tr style='background-color: {{$item->priority==1? '#19875433' : 'transparent'}}';>
                                <input type='hidden' value='{{$item->survey_form_id}}'>
                                <td>{{$item->ref_no}}</td>
                                <td>{{$item->beneficiary_name}}</td>
                                <td>{{$item->cnic}}</td>
                                <td>{{$item->father_name}}</td>
                                <td>{{$item->form_name}}</td>
                                <td>{{$item->lot_name}}</td>
                                <td>{{$item->district_name}}</td>
                                <td>{{$item->tehsil_name}}</td>
                                <td>{{$item->uc_name}}</td>
                                <td>{{$item->generated_id}}</td>
                                <td>{{$item->submission_date}}</td>
                                <td><a href='{{route("beneficiaryProfile",[$item->survey_form_id,TRUE])}}' class='btn btn-success'>View Detail</a></td>
                            </tr>
                            @endif
                        @endforeach