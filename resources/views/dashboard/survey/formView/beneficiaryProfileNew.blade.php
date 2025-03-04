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
<x-frontend.survey.section :surveyformid="$id" :section="$section"/>
@endforeach
<x-frontend.survey.commenttrail :surveyformid="$id" />
<div id="mmyModal" class="mmodal">
  <span class="cclose">&times;</span>
  <img class="mmodal-content" id="img01">
  <div id="ccaption"></div>
</div>

 <div class='row'>
                <div class='col-12'>
             
            @if($change_status && ($session=='1' || $session=='2'))
               @if(Auth::user()->role==37 || $allow_to_update_form->allow_to_update_form=='PSIA')
                <Button class='custom_button' onclick='changeStatus("A")'>Certify</Button>
               @else
                @if(Auth::user()->role!=48)
                <Button class='custom_button' onclick='changeStatus("A")'>Approved</Button>
               @endif
               @endif
               <Button class='custom_button' onclick='changeStatus("R")'>Reject</Button>
              
               @if($hold && $session=='1')
               <Button class='custom_button'  onclick='changeStatus("H")'>Hold</Button>
               @else
               <Button class='custom_button'  onclick='remove_from_hold_list({{$id}})'>Remove From Hold List</Button>
               
               @endif
               @if($ineligible)
               <a class="custom_button" survey_id="{{ $id }}" id='add_to_ineligible'  style="text-decoration:none; padding:12px;"    href="javascript:void(0)">Ineligible</a>
               @if($is_ineligible->is_ineligible==0)
               <a class="custom_button" survey_id="{{ $id }}" id='remove_to_ineligible'  style="text-decoration:none; padding:12px;"    href="javascript:void(0)">Remove From Ineligible</a>
               @endif
               @endif
             @endif
             
             @if(Auth::user()->role==30)
            <a class="btn btn-sm btn-danger upload_mission_documents" comment_id="0" survey_id="{{ $id }}" action="upload" href="javascript:void(0)">Upload Document</a>
             @endif
             
        <?php $show_button_hru_main = is_hru_main(); ?>
        @if($show_button_hru_main == true)
            @if(isset($comment_missing_document))
            <a class="custom_button missing_document_comment_remove_btn" style="text-decoration:none; padding:12px;" comment_id="{{ $comment_missing_document->id }}" ques_id="247"  href="javascript:void(0)">Remove Comment Land Document Missing</a>
            @else
            <a class="custom_button missing_document_btn" style="text-decoration:none; padding:12px;" survey_id="{{ $id }}" ques_id="247" action="missing_document" href="javascript:void(0)">Land Document Missing</a>
            @endif
        @endif
             
             
            </div>    
            </div>  
</div>
</div>
</div>
</div>
<x-frontend.survey.rejectionform_modal />
<x-frontend.survey.missing_document_modal />
<x-frontend.survey.upload_missing_document_modal />

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
    var current_user=$('#current_user').val();
    function redirectLocation(){
        if(current_user==40){
        window.location.href = 'https://mis.hru.org.pk/survey/pending/damage/datalist';
        }
        else if(current_user==48){
            window.location.href = 'https://mis.hru.org.pk/admin/survey/hold';
        }
        else if(current_user==51){
            window.location.href = 'https://mis.hru.org.pk/survey/pending/damage/datalist';
        }
        else{
        window.location.href = 'https://mis.hru.org.pk/survey/pending/damage/datalist';
            
        }
    }
    
    
    // change status code
function changeStatus(formStatus=null,is_m_and_e=null) {
    
    var confirmation = confirm('are you sure!');
    if (confirmation == true) {
        var token = $('meta[name="csrf-token"]').attr("content");
        var form_status = formStatus;
        var team_member_status = $('#team_member_status').val();
        var is_m_and_e = $('#is_m_and_e').val();
        var survey_form_id = $('#survey_form_id').val();
        var update_by = $('#updated_by').val();
        if ( form_status == 'P' || form_status == 'H') {
            $.ajax({
                type: "GET",
                url: `${base_url}update/form/status`,
                data: {
                    form_status: form_status,
                    survey_form_id: survey_form_id,
                    team_member_status: team_member_status,
                    update_by: update_by,
                    is_m_and_e: is_m_and_e,
                    _token: token,
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
        else if (form_status == 'R' || form_status == 'A' ) {
            $("#commentFormStatus").val(form_status);
            $('#commentWindow').modal('show');
            // let comment = prompt("Enter Comment About Your Decision");
            // if (comment) {
            //     $.ajax({
            //         type: "GET",

            //         url: `${base_url}update/form/status`,
            //         data: {
            //             form_status: form_status,
            //             survey_form_id: survey_form_id,
            //             team_member_status: team_member_status,
            //             comment: comment,
            //             _token: token,
            //             update_by: update_by,
            //             is_m_and_e: is_m_and_e,
            //         },
            //         success: function (response) {
            //             console.log(response);
            //             const Toast = Swal.mixin({
            //                 toast: true,
            //                 position: "top-end",
            //                 showConfirmButton: false,
            //                 timer: 3000,
            //                 timerProgressBar: true,
            //                 didOpen: (toast) => {
            //                     toast.onmouseenter = Swal.stopTimer;
            //                     toast.onmouseleave = Swal.resumeTimer;
            //                 }
            //             });
            //             Toast.fire({
            //                 icon: "success",
            //                 title: "You update form status successfully!"
            //             });
            //             setTimeout(redirectLocation, 3000)
            //         },
            //         error: function (request, status, error) {

            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'Oops...',
            //                 text: error,
            //                 toast: true,         // This enables the toast mode
            //                 position: 'top-end', // Position of the toast
            //                 showConfirmButton: false, // Hides the confirm button
            //                 timer: 3000          // Time to show the toast in milliseconds
            //             });


            //             // alert("Couldn't retrieve lots. Please try again later.");
            //         },
            //     });

            // }

        }
        
            
    }
    else {
        window.location.reload();
    }
    
    
    
  
}    
function remove_from_hold_list(id) {
    

             var token = $('meta[name="csrf-token"]').attr("content");
            $.ajax({
                type: "GET",
                url: `{{route("remove_from_hold_list")}}`,
                data: {
                     id:id,
                    _token: token,
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
        $('body').on('click', '.rejection_revert', function(i){
        i.preventDefault(); 
        var survey_id = $(this).attr('survey_id');
        var ques_id = $(this).attr('ques_id');
        var decision = $(this).attr('action');
        var confirmation=confirm("are you sure");
        
        if(confirmation){
        $.ajax({
              url: "{{ route('rejection_revert') }}",
              type: 'POST',
              data:{_token: '{{csrf_token()}}', survey_id:survey_id, ques_id:ques_id, decision:decision},
              //dataType: 'JSON',
                  beforeSend: function(){$('.message').html('Processing...');},
                  success: function (response) {
                      $('.message').empty();
                      $('.message').html(response);
                      $('.revert_'+ques_id).empty();
                      window.location.reload();
                      //alert(response);
                  },
                   error: function (response){
                       $('.message').empty();
                       $('.message').html('Error 401');
                       
                   }
              });
        }
        
        
    });
        
        
        
        
    $('body').on('click', '.surveyquestion_rejectforms_btn', function(e){
        e.preventDefault();
        var survey_id = $(this).attr('survey_id');
        var ques_id = $(this).attr('ques_id');
        var decision = $(this).attr('action');
        $('#rejectionform_modal').modal('show');
        
        $.ajax({
              url: "{{ route('surveyquestion_rejectforms') }}",
              type: 'POST',
              data:{_token: '{{csrf_token()}}', survey_id:survey_id, ques_id:ques_id, decision:decision},
              //dataType: 'JSON',
                  beforeSend: function(){$('#rejectionform_modal #modaldata').html('Processing...');},
                  success: function (response) {
                      $('#rejectionform_modal #modaldata').empty();
                      $('#rejectionform_modal #modaldata').html(response); 
                      
                  },
                   error: function (response){
                       $('#rejectionform_modal #modaldata').empty();
                       $('#rejectionform_modal #modaldata').html('Error 401');
                   }
        });
	
	
});
	

	
$('body').on('submit', '#surveyquestion_rejectform_popup', function(f) {
         f.preventDefault();
         //var formData = $(this).serialize();
		 var formData = new FormData(this);
		 var ques_id = formData.get('ques_id');
		 //console.log(ques_id);
		 
              $.ajax({
              url: "{{ route('surveyquestion_rejectformsubmit') }}", 
              type: 'POST',
              data:formData,
		      contentType: false,
              processData:false,
			  
                  beforeSend: function(){$('#modaldata').html('Processing...');},
                  success: function (response) {
                      console.log(response);
                      $("#modaldata").trigger("reset");
                      $('#modaldata').empty();
                      $('#modaldata').html(response);
                      $('.reject_'+ques_id).empty();
                       setTimeout(function(){
                         $('.btn-close').click();
                         window.location.reload();
                       }, 2000); 
                      
                  },
                   
                   error: function (response){
                       $('#modaldata').empty();
                       $('#modaldata').html('Error 401');
                       
                   }
              });
              

        });
        
        
/********missing_document_btn*********/
$('body').on('click', '.missing_document_btn', function(e){
        e.preventDefault();
        var survey_id = $(this).attr('survey_id');
        var ques_id = $(this).attr('ques_id');
        var decision = $(this).attr('action');
        $('#missing_document_modal').modal('show');
        $.ajax({
              url: "{{ route('missing_documentcomment_form') }}",
              type: 'POST',
              data:{_token: '{{csrf_token()}}', survey_id:survey_id, ques_id:ques_id, decision:decision},
              //dataType: 'JSON',
                  beforeSend: function(){$('#missing_document_modal #modaldata').html('Processing...');},
                  success: function (response) {
                      $('#missing_document_modal #modaldata').empty();
                      $('#missing_document_modal #modaldata').html(response); 
                      
                  },
                   error: function (response){
                       $('#missing_document_modal #modaldata').empty();
                       $('#missing_document_modal #modaldata').html('Error 401');
                   }
        });
	
	
});
$('body').on('submit', '#missing_documentcomment_form_submit', function(f) {
         f.preventDefault();
         //var formData = $(this).serialize();
		 var formData = new FormData(this);
		 var ques_id = formData.get('ques_id');
		 //console.log(ques_id);
		 
              $.ajax({
              url: "{{ route('missing_documentcomment_form_submit') }}", 
              type: 'POST',
              data:formData,
		      contentType: false,
              processData:false,
			  
                  beforeSend: function(){$('#missing_document_modal #modaldata').html('Processing...');},
                  success: function (response) {
                      console.log(response);
                      $("#missing_document_modal #modaldata").trigger("reset");
                      $('#missing_document_modal #modaldata').empty();
                      $('#missing_document_modal #modaldata').html(response);
                      $('.missing_document_'+ques_id).empty();
                      
                      window.location.reload();
                    //   setTimeout(function(){
                    //     $('.btn-close').click();
                    //     window.location.reload();
                    //   }, 5000);
                      
                  },
                   
                   error: function (response){
                       $('#missing_document_modal #modaldata').empty();
                       $('#missing_document_modal #modaldata').html('Error 401');
                       
                   }
              });
              

        });        
        
        
$('body').on('click', '.missing_document_comment_remove_btn', function(i){
        i.preventDefault(); 
        var comment_id = $(this).attr('comment_id');
        var ques_id = $(this).attr('ques_id');
        var confirmation=confirm("are you sure");
        
        if(confirmation){
        $.ajax({
              url: "{{ route('missing_document_comment_remove') }}",
              type: 'POST',
              data:{_token: '{{csrf_token()}}', comment_id:comment_id, ques_id:ques_id},
              //dataType: 'JSON',
                  beforeSend: function(){$('.message').html('Processing...');},
                  success: function (response) {
                      $('.message').empty();
                      $('.message').html(response);
                      $('.missing_document_comment_remove_'+ques_id).empty();
                      window.location.reload();
                      //alert(response);
                  },
                   error: function (response){
                       $('.message').empty();
                       $('.message').html('Error 401');
                       
                   }
              });
        }
        
        
    });        
        
        
        
        
        $('body').on('click', '.comment_revert', function(i){
        i.preventDefault(); 
        var comment_id = $(this).attr('comment_id');
        var ques_id = $(this).attr('ques_id');
        var confirmation=confirm("are you sure");
        
        if(confirmation){
        $.ajax({
              url: "{{ route('comment_revert') }}",
              type: 'POST',
              data:{_token: '{{csrf_token()}}', comment_id:comment_id, ques_id:ques_id},
              //dataType: 'JSON',
                  beforeSend: function(){$('.message').html('Processing...');},
                  success: function (response) {
                      $('.message').empty();
                      $('.message').html(response);
                      $('.revert_'+ques_id).empty();
                      window.location.reload();
                      //alert(response);
                  },
                   error: function (response){
                       $('.message').empty();
                       $('.message').html('Error 401');
                       
                   }
              });
        }
        
        
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
      
      
$(document).ready(function() {
  
  $(".modal_item").on("click", function() {
    console.log($(this).closest('.card').find('.question_data'));
    var nearestLink = $(this).closest('.card').find('.question_data'); 
    console.log(nearestLink);
    
    var rejectDataId = nearestLink.attr('id');
    var surveyId = nearestLink.attr('survey_id');
    var quesId = nearestLink.attr('ques_id');
    var action = nearestLink.attr('action');
    var formData=[];
    formData['surveyId']=surveyId;
    formData['quesId']=quesId;
 
    $('#insert_answer').text(nearestLink.attr('answer'));
    
    $('.save_changes_question').click(function(){
          var new_value=$('#new_value').val();
          var token = $('meta[name="csrf-token"]').attr('content');
           $('#loader_data').show();
           console.log('showing loader');
          $.ajax({
              url: "{{ route('edit_question_answer') }}", 
              type: 'POST',
              data: {
                surveyId: surveyId,
                quesId:quesId,
                new_value:new_value
                },
                headers: {
                'X-CSRF-TOKEN': token  
                },

                  success: function (response) {
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
                        title: "Answer update Successfully!"
                    });
                      $('#loader_data').hide();
                    
                        setTimeout(function() {
                        window.location.reload();
                        }, 2000);

                      
                      
                  },
                   
                  error: function (response){
                        Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Something went wrong answer not updated!",
                        toast: true,         
                        position: 'top-end', 
                        showConfirmButton: false, 
                        timer: 3000         
                        });
                         $('#loader_data').hide();
                        setTimeout(function() {
                        window.location.reload();
                        }, 2000);
                       
                  }
              });
    })
    
  });
   $(".modal_item_comparision").on("click", function() {
    var surveyId = $(this).closest('td').find('#surveyformid_for_edit').val(); 
    var quesId = $(this).closest('td').find('#questionid_for_edit').val(); 
    var answer = $(this).closest('td').find('#answer_for_edit').val(); 
    
    // Now you can log the values correctly
    console.log('Survey ID: ' + surveyId);
    console.log('Question ID: ' + quesId);
    console.log('Answer: ' + answer);
    
    
    var formData=[];
    formData['surveyId']=surveyId;
    formData['quesId']=quesId;
    $('#insert_answer').text(answer);
     $('.save_changes_question').click(function(){
           $('#loader_data').show();
          var new_value=$('#new_value').val();
          var token = $('meta[name="csrf-token"]').attr('content');
          $.ajax({
              url: "{{ route('edit_question_answer') }}", 
              type: 'POST',
              data: {
                surveyId: surveyId,
                quesId:quesId,
                new_value:new_value
                },
                headers: {
                'X-CSRF-TOKEN': token  
                },

                  success: function (response) {
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
                        title: "Answer update Successfully!"
                    });
                     $('#loader_data').hide();
                    
                    setTimeout(function() {
                        window.location.reload();
                        }, 3000);

                      
                      
                  },
                   
                   error: function (response){
                       $('#loader_data').hide();
                        Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Something went wrong answer not updated!",
                        toast: true,         
                        position: 'top-end', 
                        showConfirmButton: false, 
                        timer: 3000         
                        });
                        setTimeout(function() {
                        window.location.reload();
                        }, 3000);
                       
                   }
              });
    })
});
});

// add to Ineligible

$('body').on('click', '#add_to_ineligible', function(e){
        e.preventDefault();
        var survey_id = $(this).attr('survey_id');
      
     
        $.ajax({
              url: "{{ route('add_to_ineligible') }}",
              type: 'POST',
              data:{_token: '{{csrf_token()}}', survey_id:survey_id},
                  success: function (response) {
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
                        title: "Add to Ineligible!"
                    });
                     $('#loader_data').hide();
                    
                    setTimeout(function() {
                        window.location.reload();
                        }, 3000);
                      
                  },
                  error: function (response){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Something went wrong!",
                        toast: true,         
                        position: 'top-end', 
                        showConfirmButton: false, 
                        timer: 3000         
                        });
                    setTimeout(function() {
                        window.location.reload();
                        }, 3000);
                  }
        });
	
	
});
$('body').on('click', '#remove_to_ineligible', function(e){
        e.preventDefault();
        var survey_id = $(this).attr('survey_id');
      
     
        $.ajax({
              url: "{{ route('remove_to_ineligible') }}",
              type: 'POST',
              data:{_token: '{{csrf_token()}}', survey_id:survey_id},
                  success: function (response) {
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
                        title: "Remove From Ineligible List!"
                    });
                     $('#loader_data').hide();
                    
                    setTimeout(function() {
                        window.location.reload();
                        }, 3000);
                      
                  },
                  error: function (response){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Something went wrong!",
                        toast: true,         
                        position: 'top-end', 
                        showConfirmButton: false, 
                        timer: 3000         
                        });
                    setTimeout(function() {
                        window.location.reload();
                        }, 3000);
                  }
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
