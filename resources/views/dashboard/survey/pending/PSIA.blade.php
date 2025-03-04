                        
                           @foreach($survey_data as $item)
                            @php
                            $beneficairy_details=json_decode($item->beneficiary_details);
                            $authenticate_user_uc=json_decode(Auth::user()->uc_id);
                            $form_status=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','PSIA')->first();
                            $seniorpost=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','HRU_Main')->first();
                            $junior=form_status($item->survey_form_id,'HRU');
                                $show_data=true;
                            if($seniorpost){
                            if($seniorpost->form_status=='R'){
                                $show_data=false;
                            }
                            if(isset($junior) && $junior->form_status=='A' ){
                              $show_data=true;
                            }
                            }
                           
                            
                            @endphp
                           
                            @if( ($show_data) && ($item->user_status=='26' || $item->user_status=='51' || $item->team_member_status=='HRU') && ($form_status==null || $form_status->form_status=='P'))
                            <tr style='background-color: {{$item->priority==1? '#19875433' : 'transparent'}}'; class='pending_list'>
                             
                                <td>{{$item->ref_no}}</td>
                                <td>{{$item->beneficiary_name}}</td>
                                <td>{{$item->cnic}}</td>
                                <td>{{$item->father_name}}</td>
                                <td>{{$item->form_name}}</td>
                                <td>{{$item->lot_name ?? 'not available'}}</td>
                                <td>{{$item->district_name ?? 'not available'}}</td>
                                <td>{{$item->tehsil_name ?? 'not available'}}</td>
                                <td>{{$item->uc_name ?? 'not available'}}</td>
                                <input type='hidden' value='{{$item->survey_form_id}}' class='survey_form_id'>
                                </td>
                                <td><span class='badge text-bg-danger'>{{$seniorpost ? ($seniorpost->form_status == 'A' ? 'approved' : ($seniorpost->form_status == 'R' ? 'rejected' : ($seniorpost->form_status == 'H' ? 'currently hold': 'pending' ) )):'not available' }}</span></td>
                                <td>
                                <button class='btn btn-info' onclick='showComment("{{ $seniorpost->comment ?? 'not available' }}")'>Show Comment</button>
                                </td>
                                <td>{{$item->generated_id}}</td>
                                <td>{{$item->submission_date}}</td>
                                <td><a href='{{route("beneficiaryProfile",[$item->survey_form_id,TRUE])}}' class='btn btn-success'>View Detail</a></td>
                            </tr>
                           
                            @endif
                        @endforeach
                        
                        
                        
                        
                        <!------ certified list ------->
                        
                     {{--   
                       @foreach($certification as $item)
                            @php
                            $beneficairy_details=json_decode($item->beneficiary_details);
                            $authenticate_user_uc=json_decode(Auth::user()->uc_id);
                            $form_status=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','PSIA')->first();
                            $seniorpost=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','HRU_Main')->first();
                            @endphp
                           
                            @if(in_array($item->uc_id,$authenticate_user_uc) && ($item->user_status=='26' || $item->user_status=='51' || $item->team_member_status=='HRU') && ($form_status==null || $form_status->form_status=='P'))
                            <tr style='background-color: {{$item->priority==1? '#19875433' : 'transparent'}}; display:none;' class='certified_list'>
                                <td>{{$beneficairy_details->b_reference_number}}</td>
                                <td>{{$beneficairy_details->beneficiary_name}}</td>
                                <td>{{$beneficairy_details->cnic}}</td>
                                <td>{{$beneficairy_details->father_name}}</td>
                                <td>{{$item->form_name}}</td>
                                <td>{{$item->lot_name ?? 'not available'}}</td>
                                <td>{{$item->district_name ?? 'not available'}}</td>
                                <td>{{$item->tehsil_name ?? 'not available'}}</td>
                                <td>{{$item->uc_name ?? 'not available'}}</td>
                                 <td><span class='badge text-bg-danger'>{{$seniorpost ? ($seniorpost->form_status == 'A' ? 'approved' : ($seniorpost->form_status == 'R' ? 'rejected' : ($seniorpost->form_status == 'H' ? 'currently hold': 'pending' ) )):'not available' }}</span></td>
                                <td>
                                <button class='btn btn-info' onclick='showComment("{{ $seniorpost->comment ?? 'not available' }}")'>Show Comment</button>
                                </td>
                                <input type='hidden' value='{{$item->survey_form_id}}' class='survey_form_id'>
                                <td>{{$item->generated_id}}</td>
                                <td>{{$item->submission_date}}</td>
                                <td><a href='{{route("beneficiaryProfile",[$item->survey_form_id,TRUE])}}' class='btn btn-success'>View Detail</a></td>
                                <td class='pending_list'></td>
                            </tr>
                            @endif
                        @endforeach
                        
                        --}}
                     
                     
                     {{--   
                        <!------ Uncertified list ------->
                       @foreach($non_certification as $item)
                            @php
                            $beneficairy_details=json_decode($item->beneficiary_details);
                            $authenticate_user_uc=json_decode(Auth::user()->uc_id);
                            $form_status=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','PSIA')->first();
                            $seniorpost=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','HRU_Main')->first();
                            @endphp
                           
                            @if(in_array($item->uc_id,$authenticate_user_uc) && ($item->user_status=='26' || $item->user_status=='51' || $item->team_member_status=='HRU') && ($form_status==null || $form_status->form_status=='P'))
                            <tr style='background-color: {{$item->priority==1? '#19875433' : 'transparent'}}; display:none;' class='un_certified_list'>
                                <td>{{$beneficairy_details->b_reference_number}}</td>
                                <td>{{$beneficairy_details->beneficiary_name}}</td>
                                <td>{{$beneficairy_details->cnic}}</td>
                                <td>{{$beneficairy_details->father_name}}</td>
                                <td>{{$item->form_name}}</td>
                                <td>{{$item->lot_name ?? 'not available'}}</td>
                                <td>{{$item->district_name ?? 'not available'}}</td>
                                <td>{{$item->tehsil_name ?? 'not available'}}</td>
                                <td>{{$item->uc_name ?? 'not available'}}</td>
                                <td><span class='badge text-bg-danger'>{{$seniorpost ? ($seniorpost->form_status == 'A' ? 'approved' : ($seniorpost->form_status == 'R' ? 'rejected' : ($seniorpost->form_status == 'H' ? 'currently hold': 'pending' ) )):'not available' }}</span></td>
                                <td>
                                <button class='btn btn-info' onclick='showComment("{{ $seniorpost->comment ?? 'not available' }}")'>Show Comment</button>
                                <input type='hidden' value='{{$item->survey_form_id}}' class='survey_form_id'>
                                </td>
                                <td>{{$item->generated_id}}</td>
                                <td>{{$item->submission_date}}</td>
                                <td><a href='{{route("beneficiaryProfile",[$item->survey_form_id,TRUE])}}' class='btn btn-success'>View Detail</a></td>
                                <td class='pending_list'></td>
                                
                            </tr>
                            @endif
                        @endforeach
                        --}}