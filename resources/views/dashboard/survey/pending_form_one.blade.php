@extends('dashboard.layout.master')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
select.form-control.form_status {
    width: 126px;
}
.badge {
    display: inline-block;
    padding: .35em .65em;
    font-size: .75em;
    font-weight: 700;
    line-height: 1;
    color: #fffdfd;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 5px;
    background: red;
}
.customBtn {
    width: 150px;
    border: 2px solid black;
    margin-left: 10px;
    white-space: nowrap;
}

.label {
    /*display: flex;*/
    font-weight: bolder;
}
.button_list {
    margin-left: 49px;
    margin-top: 16px;
    }
    .label {
    display: flex;
    }
</style>

<!-- Content Start -->
<div class="content">
   
   
    <!-- Navbar Start -->
    @include('dashboard.layout.navbar')
    <!-- Navbar End -->
 
    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Survey Form List</h6>

          
            </div>
        
                      
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0"  >
                    <thead>
                       
                        <tr class="text-dark">
                            <th scope="col">Ref No</th>
                           
                        </tr>
                     
                    </thead>
                    <tbody>
                       
                     
                        {{-- fs --}}
                            
                   
                     {{--
                                                  
                        @foreach($survey_data as $item)
                            @php
                                $form_status=form_status($item->survey_form_id,'field supervisor');
                                $seniorpost=form_status($item->survey_form_id,'IP');
                            @endphp
                            @if( ($form_status==null || $form_status->form_status=='P'))
                            {{$item->survey_form_id}},
                            @endif
                        @endforeach
                    
                    --}} 
                    
                    
                                 {{--  ip  --}}
                        {{--         
                            @foreach($survey_data as $item)
                            @php
                         
                            $form_status=form_status($item->survey_form_id,'IP');
                           
                            $seniorpost=form_status($item->survey_form_id,'HRU');
                            $junior=form_status($item->survey_form_id,'field supervisor');
                            $show_data=true;
                           
                          
                            
                            @endphp
                            @if(($show_data)  && ($item->user_status=='30'  || $item->user_status=='51' || $item->team_member_status=='field_supervisor') && ($form_status==null || $form_status->form_status=='P'))
                            {{$item->survey_form_id}},
                            @endif
                        @endforeach
                        -- }}
                        
                          {{--  hru  --}}
                        
                    {{--
                        
                        @foreach($survey_data as $item)
                            
                            @php
                            
                         
                            $form_status=form_status($item->survey_form_id,'HRU');
                            $seniorpost=form_status($item->survey_form_id,'PSIA');
                            $show_data=true;
                            $junior=form_status($item->survey_form_id,'IP');
                            
                            @endphp
                            @if( @isset($show_data) &&  ($item->user_status=='34' || $item->user_status=='51' || $item->team_member_status=='IP' || $item->user_status=='51') && ($form_status==null || $form_status->form_status=='P'))
                              {{$item->survey_form_id}},
                            @endif
                        @endforeach
                        --}}
                        
                        {{--  qa  --}}
                        
                   {{--
                         @foreach($survey_data as $item)
                            @php
                            $form_status=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','PSIA')->first();
                            $seniorpost=\DB::table('form_status')->where('form_id',$item->survey_form_id)->where('update_by','HRU_Main')->first();
                            $junior=form_status($item->survey_form_id,'HRU');
                            @endphp
                           
                            @if(($item->user_status=='26' || $item->user_status=='51' || $item->team_member_status=='HRU') && ($form_status==null || $form_status->form_status=='P'))
                             {{$item->survey_form_id}},
                            @endif
                        @endforeach  
                        --}}
                        
                        {{--  HRU MAIN --}}
                        
                         {{--                             
                            @foreach($survey_data as $item)
                            @php
                            $form_status=form_status($item->survey_form_id,'HRU_Main');
                            $seniorpost=form_status($item->survey_form_id,'COO');
                            $junior=form_status($item->survey_form_id,'field supervisor');
                            $show_data=true;
                            @endphp
                            @if(($item->user_status=='37' || $item->user_status=='51' || $item->team_member_status=='PSIA') && ($form_status==null || $form_status->form_status=='P'))
                             {{$item->survey_form_id}},
                            @endif
                        @endforeach
                        --}}
                        
                        
                        {{--  CEO --}}
                        
                        
                            @foreach($survey_data as $item)
                            @php
                            $form_status=form_status($item->survey_form_id,'CEO');
                            $hru_form_status=form_status($item->survey_form_id,'HRU_Main');
                            @endphp
                            @if(($item->user_status=='39' || $item->user_status=='38' || $item->user_status=='51' || $item->team_member_status=='COO' || $item->team_member_status=='HRU_Main') && (($form_status==null || $form_status->form_status=='P') || ($hru_form_status==null || $hru_form_status->form_status=='P') && $hru_form_status->form_status!=='H')  )
                              @dd($hru_form_status->form_status)
                                 {{$item->survey_form_id}},
                            @endif
                        @endforeach
                                                
                          
                    
                    </tbody>
                </table>
               
            </div>
        </div>
    </div>
    
    <!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Beneficiary Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <label>Beneficiary Name:-</label>
      <input  id='beneficiary_name' type='text' readonly class='form-control'>
      <label>Beneficiary Address:-</label>
      <input  id='beneficiary_address' type='text' readonly class='form-control'>
      <label>Beneficiary CNIC:-</label>
      <input  id='beneficiary_cnic' readonly type='text' class='form-control'>
      <label>Beneficiary refrence number :-</label>
      <input  id='beneficiary_refrence_number' type='text' readonly class='form-control'>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
     
      </div>
    </div>
  </div>
</div>

    
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="{{asset('dashboard\js\survey_list.js')}}"></script>
    <script src="{{asset('dashboard\js\ip_create.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
    <script>
        $(document).ready(function(){
             $('.js-example-basic-multiple').select2();
            $('#certified_list_btn').click(function(){
                $(this).css({backgroundColor: 'red',color:'white'});
                $("#pending_list_btn").css({ 'backgroundColor' : '', 'color' : '' });
                $("#non_certified_btn").css({ 'backgroundColor' : '', 'color' : '' });
                $(".pending_list").css({ 'display' : 'none' });
                $(".certified_list").css({ 'display' : '' });
                $(".un_certified_list").css({ 'display' : 'none' });
            })
            $('#pending_list_btn').click(function(){
                
                $(this).css({backgroundColor: 'red',color:'white'});
                $("#certified_list_btn").css({ 'backgroundColor' : '', 'color' : '' });
                $("#non_certified_btn").css({ 'backgroundColor' : '', 'color' : '' });
                $(".pending_list").css({ 'display' : '' });
                $(".certified_list").css({ 'display' : 'none' });
                $(".un_certified_list").css({ 'display' : 'none' });
            })
            $('#non_certified_btn').click(function(){
                $(this).css({backgroundColor: 'red',color:'white'});
                $("#pending_list_btn").css({ 'backgroundColor' : '', 'color' : '' });
                $("#certified_list_btn").css({ 'backgroundColor' : '', 'color' : '' });
                $(".pending_list").css({ 'display' : 'none' });
                $(".certified_list").css({ 'display' : 'none' });
                $(".un_certified_list").css({ 'display' : '' });
            })
        })
        
    </script>
  
    
    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                toast: true,         // This enables the toast mode
                position: 'top-end', // Position of the toast
                showConfirmButton: false, // Hides the confirm button
                timer: 3000          // Time to show the toast in milliseconds
            });
        </script>
    @endif
    @if(session('success'))
        <script>

            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: "{{ session('success') }}"
            });
        </script>
    @endif

    @endsection