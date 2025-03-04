@foreach($survey_data as $item)
                            @php
                            $beneficairy_details=json_decode($item->beneficiary_details);
                            $authenticate_user_uc=json_decode(Auth::user()->uc_id);
                            $form_status=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','COO')->first();
                            @endphp
                            @if(($item->user_status=='38' || $item->team_member_status=='HRU_Main') && ($form_status==null || $form_status->form_status=='P'))
                            @php
                            $form_status_CEO=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','CEO')->first();
                            @endphp
                            <tr style='background-color: {{$item->priority==1? '#19875433' : 'transparent'}}';>
                                <input type='hidden' value='{{$item->survey_form_id}}'>
                                <td>{{$beneficairy_details->b_reference_number}}</td>
                                <td>{{$beneficairy_details->beneficiary_name}}</td>
                                <td>{{$beneficairy_details->cnic}}</td>
                                <td>{{$beneficairy_details->father_name}}</td>
                                <td>{{$item->form_name}}</td>
                                <td>{{$item->lot_name}}</td>
                                <td>{{$item->district_name}}</td>
                                <td>{{$item->tehsil_name}}</td>
                                <td>{{$item->uc_name}}</td>
                               
                                
                                <td><span class='badge text-bg-danger'>{{$form_status_CEO ? ($form_status_CEO->form_status == 'A' ? 'approved' : ($form_status_CEO->form_status == 'R' ? 'rejected' : 'pending')):'not available' }}</span></td>
                                <td>
                                <button class='btn btn-info' onclick='showComment("{{ $form_status_CEO->comment ?? 'not available' }}")'>Show Comment</button>
                                </td>
                              
                                <input type='hidden' value='{{$item->survey_form_id}}' class='survey_form_id'>
                                </td>
                             
                                 <td>{{$item->generated_id}}</td>
                                 <td>{{$item->submission_date}}</td>
                                <td><a href='{{route("beneficiaryProfile",[$item->survey_form_id,TRUE])}}' class='btn btn-success'>View Detail</a></td>
                            </tr>
                            @endif
                        @endforeach