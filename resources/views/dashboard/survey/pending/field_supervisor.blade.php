                       
                        @foreach($survey_data as $item)
                      
                            @php
                                $authenticate_user_uc=json_decode(Auth::user()->uc_id);
                                $form_status=form_status($item->survey_form_id,'field supervisor');
                                $seniorpost=form_status($item->survey_form_id,'IP');
                                $show_data=true;
                            if($seniorpost){
                                if($seniorpost->form_status=='R'){
                                    $show_data=false;
                                } 
                            if(isset($form_status) && $form_status->form_status=='P'){
                                $show_data=true;
                            }
                            }
                            @endphp
                            @if( ($show_data) && in_array($item->uc_id,$authenticate_user_uc) &&   ($form_status==null || $form_status->form_status=='P'))
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
                                  <td><span class='badge text-bg-danger'>{{$seniorpost ? ($seniorpost->form_status == 'A' ? 'approved' : ($seniorpost->form_status == 'R' ? 'rejected' : 'pending')) : 'Not Available' }}</span></td>
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
                       