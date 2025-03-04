<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Environment Case</title>
    <link media="all" type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    
    <link href="{{asset('dashboard/css/style.css')}}" rel="stylesheet">
     <meta name="csrf-token" content="{{ csrf_token() }}">
        
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
    body {
    font-family: 'Roboto', sans-serif;
    background-color: #eef2f3;
       }
    
.boxcontainer{
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    padding:30px;
    text-align: center;
}
        .profile-header {
            margin-bottom: 20px;
        }

        .profile-img {
            width: 200px;
            height: 200px;
            border-radius:5px; 
            border: 3px solid #009CFF;
            margin-bottom: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        

        .email {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .data-view {
            text-align: left;
           
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }

        .accordion-header {
            background-color: #009CFF;
            color: white;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 10px;
        }

        .accordion-header:hover {
            background-color: #007bb5;
        }
        

        .accordion-content {
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            margin-top: 5px;
        }

        .back-button {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #218838;
        }

        .custom_button {
            background-image: linear-gradient(#0dccea, #0d70ea);
            border: 0;
            border-radius: 4px;
            box-shadow: rgba(0, 0, 0, .3) 0 5px 15px;
            box-sizing: border-box;
            color: #fff;
            cursor: pointer;
            font-family: Montserrat, sans-serif;
            font-size: .9em;
            margin: 5px;
            padding: 10px 15px;
            text-align: center;
            user-select: none;
            margin-top: 16px;
        }
        
        
        
        
        
#myImg {
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
  margin: 5px; /* Added margin for spacing */
}

#myImg:hover {opacity: 0.7;}

/* The Modal (background) */
.mmodal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
.mmodal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

/* Caption of Modal Image */
#ccaption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation */
.mmodal-content, #caption {  
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
}

/* The Close Button */
.cclose {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.cclose:hover,
.cclose:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .mmodal-content {
    width: 100%;
  }
}
        
   .button_rotate {
      margin-top: 20px;
      font-size: 16px;
      cursor: pointer;
      background: black;
      width: 100px;
      color: #fff;
    }
 
    
     .rotating-image {
      transition: transform 0.5s ease-in-out; /* Smooth rotation */

   
    } 
    #loader_data {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
}
/*.parent_div {*/
/*        display: flex;*/
/*        align-items: center;*/
/*        width: 100%;*/
/*        justify-content: space-between;*/
/*    }*/

.show_comment {
    display: flex;
    flex-direction: column;
    gap: 11px;
    justify-content: center;
    align-items: baseline;
}
.row.comment {
    display: flex;
    margin-left: 1px;
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


<div class="container">
    
<center>
<img src="{{ asset('images/ifrap_logo.png') }}" width="200" alt="Housing Reconstruction Unit" class="logo-ayis" />
</center>

<div class="row">
<div class="col-md-12 text-center"><hr><h3 class="page-title">Environment Case</h3><hr></div>    
<div class="col-md-12">
 {{--       
@if ($message = Session::get('success'))
       
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong> {{ $message }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        
        
       @elseif ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong> {{ $message }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
	    @endif  
	    --}}
                
                
   @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif    
</div></div>    
    
    
    
    
    
    
<div class="row">
<div class="col-md-10 offset-md-1"> 
<div class="boxcontainer my-5">
<div class="message"></div>

@foreach($question_cat as $section)
@if($loop->iteration == 2)@endif

<x-backend.environmentCase.section :constructionformid="$id" :section="$section"/>
@endforeach
<div id="mmyModal" class="mmodal">
  <span class="cclose">&times;</span>
  <img class="mmodal-content" id="img01">
  <div id="ccaption"></div>
</div>


@if($construction)
    @if($construction->status == 'P')
    @if(Auth::user()->role == $construction->role_id)
        @if(in_array($construction->lot_id, json_decode(Auth::user()->lot_id)))
        @if(in_array($construction->district_id, json_decode(Auth::user()->district_id)))
        @if(in_array($construction->tehsil_id, json_decode(Auth::user()->tehsil_id)))
        @if(in_array($construction->uc_id, json_decode(Auth::user()->uc_id)))
            <div class="row construction_take_action_btn">
            <div class="col-md-12 my-3">
                <div class="d-flex justify-content-center align-items-center gap-3">
                    <Button class="btn btn-success take_action" action="approve" construction_id="{{ $construction->id }}">Approve</Button>
                    <Button class="btn btn-danger take_action" action="reject" construction_id="{{ $construction->id }}">Reject</Button>
                </div>
            </div>    
            </div>
        @endif @endif @endif @endif
    @endif
    @endif
@endif

            
</div>
</div>
</div>
</div>



<x-backend.environmentCase.construction_modal />

<x-backend.environmentCase.commentModal />


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

</script>

<script>
$(function() {

$('body').on('click', '.take_action', function(e){
        e.preventDefault();
        var construction_id = $(this).attr('construction_id');
        var decision = $(this).attr('action');
        $('#construction_modal').modal('show');
        if (confirm('Are you sure you want to action this item?')) {
        $.ajax({
              url: "{{ route('environment_case.action_form') }}",
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
$('body').on('click', '.take_comment', function(e){
        e.preventDefault();
        var surveyid = $(this).attr('surveyid');
        var question_id = $(this).attr('question_id');
        $('#environment_comment_modal').modal('show');
            $.ajax({
              url: "{{ route('environment_case.add_comment.view') }}",
              
              type: 'POST',
              data:{_token: '{{csrf_token()}}',surveyid:surveyid,question_id:question_id},
              //dataType: 'JSON',
                  beforeSend: function(){$('#environment_comment_modal #modaldata').html('Processing...');},
                  success: function (response) {
                      $('#environment_comment_modal #modaldata').empty();
                      $('#environment_comment_modal #modaldata').html(response); 
                      
                  },
                   error: function (response){
                       $('#environment_comment_modal #modaldata').empty();
                       $('#environment_comment_modal #modaldata').html('Error 401');
                   }
        });
	
	
});



$('body').on('submit', '#construction_action_form_submit', function(f) {
         f.preventDefault();
         //var formData = $(this).serialize();
		 var formData = new FormData(this);
		 //var ques_id = formData.get('ques_id');
		 //console.log(ques_id);
		 
              $.ajax({
              url: "{{ route('environment_case.action_form.submit') }}", 
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
</script>
