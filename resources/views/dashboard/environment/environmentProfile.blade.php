<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link media="all" type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    
    <link href="{{asset('dashboard/css/style.css')}}" rel="stylesheet">
     <meta name="csrf-token" content="{{ csrf_token() }}">
        
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
     <link href="https://mis.hru.org.pk/public/dashboard/css/dataReview.css" rel="stylesheet">
     <style>
    div#review_parent {
        display: flex;
        justify-content: end;
        width: 100%;
        align-items: end;
    }
    .surveyquestion_rejectforms_btn {
    display: none;
    }
    .edit_button_div {
    display: flex;
    width: 100%;
    justify-content: space-between;
    align-items: center;
    }
    form {
    display: flex;
    flex-direction: column;
    gap: 17px;
    justify-content: center;
    align-items: start;
}
.my-3.alert.alert-primary.alert-dismissible.fade.show {
    display: none;
}
a.btn.btn-sm.btn-success.rejection_revert {
    display: none;
}
     </style>
  
</head>
<body>               
<div id="hrupreloader">
        <div id="loader" class="loader">
            <div class="loader-container">
                <div class="loader-icon"><img src="https://mis.hru.org.pk/admin/assets/img/logo.jpeg" alt="Preloader"></div>
            </div>
        </div>
    </div>

    <!---------------------Modal Window for comment Start------------------------>
    <div class="modal fade" id="commentWindow" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Enter Comment About Your Decisions</h5>
                </div>
                <p id="errorMsgForComment" style="color:red;font-size:12px;padding-left:23px;
                padding-top:10px;margin:0px"></p>
                 <form method="POST" id="commentDecisionForm">
                    <div class="modal-body">
                        <input type="hidden" id="commentFormStatus">
                           <textArea id="commentDecision" maxlength="250" class="form-control" 
                           name="commentDecision" placeholder="Please Enter Comment"></textArea>
                    </div>
                     <div class="modal-footer">
                      <button type="submit"  class="btn btn-primary">Submit</button>
                      <button type="button" class="btn btn-danger"  data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
              </div>
            </div>
    </div>
    <!---------------------Modal Window for comment END------------------------>
    @php 

        $allow_to_update_form=allow_to_update_form();
        $certification_status=certification($id,'HRU'); 
        $ineligible=false;
        $m_and_e_show=false;
      
        $change_status=false;
        $hold=false;
        $role = session('role');
        
        if((Auth::user()->role==30 || $allow_to_update_form->allow_to_update_form=='field_supervisor') || (Auth::user()->role==51 && isset($role) && $role=='field supervisor')){
        $change_status=true;
        $updated_by='field supervisor';
        if(Auth::check() && Auth::user()->role==51){
            $m_and_e_show=true;
            
        }
        
       }
        if(Auth::user()->role==48){
        $change_status=true;
        $updated_by='finance';
        $hold=true;
       }
       if((Auth::user()->role==34 || $allow_to_update_form->allow_to_update_form=='IP') || (Auth::user()->role==51 && isset($role) && $role=='IP')){
        $change_status=true;
        $updated_by='IP';
            if(Auth::check() && Auth::user()->role==51){
                $m_and_e_show=true;
            }
       }
       if((Auth::user()->role==26 || $allow_to_update_form->allow_to_update_form=='HRU') || (Auth::user()->role==51 && isset($role) && $role=='HRU')){
        $change_status=true;
        $updated_by='HRU';
        if(Auth::check() && Auth::user()->role==51){
            $m_and_e_show=true;
        }
       }
       if(Auth::user()->role==37 || $allow_to_update_form->allow_to_update_form=='PSIA'){
        $change_status=true;
        $updated_by='PSIA';
       }
       if(Auth::user()->role==38 || $allow_to_update_form->allow_to_update_form=='HRU_Main'){
        $change_status=true;
        $updated_by='HRU_Main';
        $hold=true;
        $ineligible=true;
        
       }
       if(Auth::user()->role==39 || $allow_to_update_form->allow_to_update_form=='COO'){
        $change_status=true;
        $updated_by='COO';
        $hold=true;
       }
       if(Auth::user()->role==40 || $allow_to_update_form->allow_to_update_form=='CEO'){
        $change_status=true;
        $updated_by='CEO';
        $hold=true;
       }
       $is_ineligible=DB::table('survey_form')->where('id',$id)->select('is_ineligible','review_by_mne')->first();
    @endphp
 
<div id="loader_data" style="display:none;">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

    


    <input type='hidden' value='{{$allow_to_update_form->allow_to_update_form ?? null}}' id='team_member_status'>
    <input type='hidden' value='{{$id}}' id='survey_form_id'>
    <input type='hidden' value='{{$updated_by ?? null }}' id='updated_by'>
    @if(Auth::check() && Auth::user()->role==51 && isset($m_and_e_show))
    <input type='hidden' value='is_m_and_e' id='is_m_and_e'>
    @else
    <input type='hidden' value='' id='is_m_and_e'>
    @endif

<div class="container">
<div class="row">
<div class="col-md-10 offset-md-1"> 
<div class="boxcontainer my-5">
@if(Auth::user()->role==51)
<div  id='review_parent'>    
<input type='checkbox' id='review' {{ $is_ineligible->review_by_mne==1 ? 'checked' : ''}}>
</div>
@endif
<x-frontend.survey.beneficiaryprofileimage :surveyformid="$id"/>
<div class="message"></div>
<x-frontend.survey.comparetable :surveyformid="$id"/>

@foreach($question_cat as $section)
@if($loop->iteration == 2)@endif
<x-frontend.environment.section :surveyformid="$id" :section="$section"/>
@endforeach

<div id="mmyModal" class="mmodal">
  <span class="cclose">&times;</span>
  <img class="mmodal-content" id="img01">
  <div id="ccaption"></div>
</div>

 
</div>
</div>
</div>
</div>


@if($form_status)

    @if($form_status->status == 'P')
    @if(Auth::user()->role == $form_status->role_id)
        @if(in_array($form_status->lot_id, json_decode(Auth::user()->lot_id)))
        @if(in_array($form_status->district_id, json_decode(Auth::user()->district_id)))
        @if(in_array($form_status->tehsil_id, json_decode(Auth::user()->tehsil_id)))
        @if(in_array($form_status->uc_id, json_decode(Auth::user()->uc_id)))
          @if($form_status->role_id=='62')  
            <div class="row construction_take_action_btn">
            <div class="col-md-12 my-3">
                <div class="d-flex justify-content-center align-items-center gap-3">
                    <Button class="btn btn-danger take_action" action="Case Registered" construction_id="{{ $form_status->id }}">Case Registered</Button>
                    <Button class="btn btn-success take_action" action="Case Close" construction_id="{{ $form_status->id }}">Case Close</Button>
                </div>
            </div>    
            </div>
            @else
            <div class="row construction_take_action_btn">
            <div class="col-md-12 my-3">
                <div class="d-flex justify-content-center align-items-center gap-3">
                    <Button class="btn btn-success take_action" action="approve" construction_id="{{ $form_status->id }}">Approve</Button>
                    @if(Auth::user()->role!=34)
                    <Button class="btn btn-danger take_action" action="reject" construction_id="{{ $form_status->id }}">Reject</Button>
                    @endif
                </div>
            </div>    
            </div>
   
        @endif @endif @endif @endif @endif
    @endif
    @endif
@endif
<x-backend.environment.construction_modal />
<x-frontend.environment.editQuestion />



<input type='hidden' value='{{Auth::user()->role}}' id='current_user'>
 
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/js/brands.min.js" integrity="sha512-DVS2SDsNMrQQMlbdcnmPtMdtOeqas4WRl06II/v/iMBRda50NUHHiI8z5Kv3WFu6OCCZYyObCXa9oSHcR4bz4g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('dashboard\js\survey_list.js')}}"></script>




  @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                toast: true,         
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000        
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





    <script>

    $('body').on('click', '.edit_question_btn', function(e){
        e.preventDefault();
       
        var question_id = $(this).attr('question_id');
        var survey_id = $(this).attr('survey_id');
        $('#reporttrail_modal').modal('show');
        
        $.ajax({
              url: "{{ route('environment_option_edit') }}",
              type: 'GET',
              data:{_token: '{{csrf_token()}}', question_id:question_id,survey_id:survey_id},
                  beforeSend: function(){$('#reporttrail_modal #modaldata').html('Processing...');},
                  success: function (response) {
                      console.log(response);
                      $('#reporttrail_modal #modaldata').empty();
                      $('#reporttrail_modal #modaldata').html(response); 
                      
                  },
                   error: function (response){
                       $('#reporttrail_modal #modaldata').empty();
                       $('#reporttrail_modal #modaldata').html('Error 401');
                   }
        });
	
	
});
  
   
window.onload = function(){
        //hide the preloader
        document.querySelector("#hrupreloader").style.display = "none";
    }    
    // Get the modal
var mmodal = document.getElementById("mmyModal");

// Get the modal image and caption
var mmodalImg = document.getElementById("img01");
var ccaptionText = document.getElementById("ccaption");

// Get all images with class "myImg"
var imgs = document.getElementsByClassName("myImg");

// Loop through the images and add click event listeners
for (var i = 0; i < imgs.length; i++) {
  imgs[i].onclick = function(){
    mmodal.style.display = "block";
    mmodalImg.src = this.src;
    ccaptionText.innerHTML = this.alt;
  }
}

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("cclose")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
  mmodal.style.display = "none";
}

 
 
 
 
   
    
$(function() {

$('body').on('click', '.take_action', function(e){
        e.preventDefault();
        var construction_id = $(this).attr('construction_id');
        var decision = $(this).attr('action');
        $('#construction_modal').modal('show');
        if (confirm('Are you sure you want to action this item?')) {
        $.ajax({
              url: "{{ route('environment_action_form') }}",
              type: 'POST',
              data:{_token: '{{csrf_token()}}', construction_id:construction_id, decision:decision},
              //dataType: 'JSON',
                  beforeSend: function(){$('#construction_modal #modaldata').html('Processing...');},
                  success: function (response) {
                     
                      $('#construction_modal #modaldata').empty();
                      $('#construction_modal #modaldata').html(response); 
                      
                  },
                   error: function (response){
                       $('#construction_modal #modaldata').empty();
                       $('#construction_modal #modaldata').html('Error 401');
                   }
        });
        }
	
	
});
$('body').on('submit', '#construction_action_form_submit', function(f) {
         f.preventDefault();
		 var formData = new FormData(this);

              $.ajax({
              url: "{{ route('environment_action_form_submit') }}", 
              type: 'POST',
              data:formData,
		      contentType: false,
              processData:false,
			  
                  beforeSend: function(){$('#construction_modal #modaldata').html('Processing...');},
                  success: function (response) {
                      console.log(response);
                      $("#construction_modal #modaldata").trigger("reset");
                      $('#construction_modal #modaldata').empty();
                      $('#construction_modal #modaldata').html(response);
                      $('.construction_take_action_btn').empty();
                      setTimeout(function(){
                         $('.btn-close').click();
                         window.location.reload();
                      }, 2000);
                      
                  },
                   
                   error: function (response){
                       $('#construction_modal #modaldata').empty();
                       $('#construction_modal #modaldata').html('Error 401');
                       
                   }
              });
              

        });        
        
        
    
    });
    
    

    
        function goBack() {
            window.history.back();
        }
        
      const rotatingImages = document.querySelectorAll(".rotating-image");
      const rotateButtons = document.querySelectorAll(".button_rotate");

      rotateButtons.forEach((button, index) => {
        let currentRotation = 0; 

        button.addEventListener("click", () => {
          currentRotation += 90; 

          if (currentRotation === 360) {
            currentRotation = 0; 
          }

         
          rotatingImages[index].style.transform = `rotate(${currentRotation}deg)`;
        });
      });
      
      






$(function() {
$('body').on('click', '.upload_mission_documents', function(e){
        e.preventDefault();
        var comment_id = $(this).attr('comment_id');
        var survey_id = $(this).attr('survey_id');
        var decision = $(this).attr('action');
        $('#upload_missing_document_modal').modal('show');
        $.ajax({
              url: "{{ route('upload_missing_document_form') }}",
              type: 'POST',
              data:{_token: '{{csrf_token()}}', comment_id:comment_id, survey_id:survey_id, decision:decision},
              //dataType: 'JSON',
                  beforeSend: function(){$('#upload_missing_document_modal #modaldata').html('Processing...');},
                  success: function (response) {
                      $('#upload_missing_document_modal #modaldata').empty();
                      $('#upload_missing_document_modal #modaldata').html(response); 
                      
                  },
                   error: function (response){
                       $('#upload_missing_document_modal #modaldata').empty();
                       $('#upload_missing_document_modal #modaldata').html('Error 401');
                   }
        });
	
	
});

$('body').on('submit', '#upload_missing_document_form_submit', function(f) {
         f.preventDefault();
         //var formData = $(this).serialize();
		 var formData = new FormData(this);
		 //var ques_id = formData.get('ques_id');
		 //console.log(ques_id);
		 
              $.ajax({
              url: "{{ route('upload_missing_document_form_submit') }}", 
              type: 'POST',
              data:formData,
		      contentType: false,
              processData:false,
			  
                  beforeSend: function(){
                      //$('#upload_missing_document_modal #modaldata #uploadform_loader').html('Processing...');
                      $('#upload_missing_document_modal #modaldata #uploadform_loader').html('<center><img src="{{ asset('images/preloader.gif') }}" width="100" alt="Loader" /></center>');
                  },
                  
                  success: function (response) {
                      console.log(response);
                      $('#upload_missing_document_modal #modaldata #uploadform_loader').empty();
                      $("#upload_missing_document_modal #modaldata").trigger("reset");
                      $('#upload_missing_document_modal #modaldata').empty();
                      $('#upload_missing_document_modal #modaldata').html(response);
                       setTimeout(function(){
                         $('.btn-close').click();
                         window.location.reload();
                       }, 5000);
                      
                  },
                   error: function(response){
				    $('#upload_missing_document_modal #modaldata #uploadform_loader').empty();
				    //$('#upload_missing_document_modal #modaldata').html('Error 401');
					//$('#upload_missing_document_modal #modaldata').html(response.responseJSON.errors);
    					
                        $('body #upload_missing_document_modal').find(".print-error-msg").find("ul").html('');
                        $('body #upload_missing_document_modal').find(".print-error-msg").css('display','block');
                        $.each( response.responseJSON.errors, function( key, value ) {
                            $('body #upload_missing_document_modal').find(".print-error-msg").find("ul").append('<li>'+value+'</li>');
                        });
                        
                }
                   
                   
                   
                   
              });
              

        });
});


    $("#commentDecisionForm").submit(function(e) {
    e.preventDefault();
    var comment = $("#commentDecision").val();
    var form_status = $("#commentFormStatus").val();
    var team_member_status = $('#team_member_status').val();
    var is_m_and_e = $('#is_m_and_e').val();
   
   
    var survey_form_id = $('#survey_form_id').val();
    var update_by = $('#updated_by').val();
  
  
    var token = $('meta[name="csrf-token"]').attr("content");
    var commentSend = true;
    if(!comment && form_status == 'R' )
    {
       document.getElementById("errorMsgForComment").innerHTML = "Please Add Comment";
       commentSend =false;
    }
     if (commentSend ==true) 
        {
            document.getElementById("errorMsgForComment").innerHTML = "";
            $.ajax({
                type: "GET",

                url: `${base_url}update/form/status`,
                data: {
                    form_status: form_status,
                    survey_form_id: survey_form_id,
                    team_member_status: team_member_status,
                    comment: comment,
                    _token: token,
                    update_by: update_by,
                    is_m_and_e: is_m_and_e,
                },
                success: function (response) {
                    console.log(response);
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
                        title: "You update form status successfully!"
                    });
                    setTimeout(redirectLocation, 3000)
                },
                error: function (request, status, error) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error,
                        toast: true,         // This enables the toast mode
                        position: 'top-end', // Position of the toast
                        showConfirmButton: false, // Hides the confirm button
                        timer: 3000          // Time to show the toast in milliseconds
                    });


                    // alert("Couldn't retrieve lots. Please try again later.");
                },
            });

      }
    });
    
    $('#review').click(function(){
        var survey_form_id=$('#survey_form_id').val();
        $.ajax({
                type: "GET",
                url: `{{route("update_review_survey")}}`,
                data: {
                   survey_form_id:survey_form_id
                },
                success: function (response) {
                    console.log(response);

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
                        title: "You update form status successfully!"
                    });
                   
                },
                error: function (request, status, error) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error,
                        toast: true,         // This enables the toast mode
                        position: 'top-end', // Position of the toast
                        showConfirmButton: false, // Hides the confirm button
                        timer: 3000          // Time to show the toast in milliseconds
                    });


                    // alert("Couldn't retrieve lots. Please try again later.");
                },
            });
    })

    </script>
