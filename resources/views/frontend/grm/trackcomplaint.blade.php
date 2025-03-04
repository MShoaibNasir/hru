<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('GRM Complaint Status') }}</title>
    {{ Html::favicon( 'images/favicon.jpeg') }}
    
<link media="all" type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">	
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
<style>
.form-group.required .control-label:after {
  content:"*";
  color:red;
}
.form-group{margin-bottom:1rem;}
fieldset {
	/*background-color: rgba(32, 88, 206, 0.3);*/
	/*background-color: rgba(237, 181, 68, 30%);*/
	border-radius: 5px;
	border:1px solid #2759ce;
}
legend {
	background-color: #fff;
	border:1px solid #2759ce;
	border-radius: 5px;
	color: #2759ce;
	font-size: 18px;
	font-weight: bold;
	padding: 5px 7px 5px 9px;
	width: auto;
	float:inherit;
}

</style>
    </head>
    <body>
<div class="container">
            <center>
            <img src="{{ asset('images/ifrap_logo.png') }}" width="200" alt="Housing Reconstruction Unit" class="logo-ayis" />
            </center>


<div class="row">
<div class="col-md-12 text-center"><hr><h3 class="page-title">GRM Complaint Status</h3><hr></div>    
<div class="col-md-12">
        
@if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
         {{ $message }}
        </div>
       @elseif ($message = Session::get('error'))
        <div class="alert alert-danger">
         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
         {{ $message }}
         </div>
	    @endif                
                
                
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


{!! Form::open(array('method' => 'POST', 'class'=> 'form-horizontal', 'id' => 'searchForm')) !!}
<div class="form-body">

<fieldset class="p-4 mb-4">
        <legend>TRACKING FORM</legend>
    <div class="row form-group required">
		<label class="col-md-3 control-label">{{ __('Search by') }}</label>
		<div class="col-md-9">
			{!! Form::select('search_by', ['ticket_no'=>'Ticket No', 'cnic'=>'CNIC No', 'mobile'=>'Mobile No'], null, ['placeholder' => '-- Select One --', 'class' => 'search_by form-control select2', 'required']) !!}
		</div>
	</div>
	<div id="searchfield"></div>
</fieldset>



<!-- Submit Button  -->
    <div class="form-group row">
        <div class="col-md-12">
            <center><button type="submit" name="save" value="save" class="btn btn-primary btn-lg btn-customized">Track Complaint</button></center>
        </div>
    </div>
</div>
{!! Form::close() !!}
<a href="{{-- route('complaint') --}}" class="btn my-new-btn" style="margin: 0; cursor: pointer;">Back</a>
</div>
</div>

</div>





<div class="container" style="padding-bottom: 80px;">
    <div class="row">
        <div class="col my-traking-tab">
            
        </div>
    </div>
</div>
  
<script>
$(document).ready(function() {        
        $('.search_by').change(function() {
            var search_by = $(".search_by").val();
            //alert(search_by);
            //console.log(search_by);
            $("#searchfield").empty();
            if(search_by=='ticket_no'){
                $("#searchfield").append('<div class="row form-group required"><label class="col-md-3 control-label">Ticket No</label><div class="col-md-9"><input type="text" class="form-control" id="searchfield" name="searchfield" placeholder="Enter Ticket No" required pattern="[0-9]+" /></div></div>');
                //$("#searchfield").attr('placeholder','Enter Ticket No');
                //$("#searchfield").attr('pattern','[0-9]+');
            }else if(search_by=='cnic'){
                $("#searchfield").append('<div class="row form-group required"><label class="col-md-3 control-label">CNIC No</label><div class="col-md-9"><input type="text" class="form-control" id="searchfield" name="searchfield" placeholder="Enter CNIC without dashes" minlength="13" maxlength="13" pattern="[0-9]{13}" required /></div></div>');
                //$("#searchfield").attr('placeholder','Enter CNIC without dashes');
                //$("#searchfield").attr('pattern','[0-9]{13}');
            }else if(search_by=='mobile'){
                $("#searchfield").append('<div class="row form-group required"><label class="col-md-3 control-label">Mobile No</label><div class="col-md-9"><input type="text" class="form-control" id="searchfield" name="searchfield" placeholder="Enter Mobile No without dashes" minlength="7" maxlength="15" pattern="[0-9]{7,15}" required /></div></div>');
                //$("#searchfield").attr('placeholder','Enter Mobile No without dashes');
                //$("#searchfield").attr('pattern','[0-9]{11}');
            }
        });   
    });
    

    // Function to validate the form
    function validateForm() {
        var isValid = true;
        // Validate each input field
        $('#searchForm input').each(function() {
            if (!this.checkValidity()) {
                // If the field is not valid, set isValid to false
                isValid = false;
                // Optionally, you can display a custom error message
                $(this).next('.error-message').remove();
                $(this).after('<p class="error-message">Please enter a valid value.</p>');
            }
        });
        return isValid;
    }







$(document).ready(function() {	
	
   $('body').on('submit', '#searchForm', function(i){
        i.preventDefault();
        
        //alert('click');
		//return validateForm();

          $.ajax({
			  
            url: "{{ route('trackcomplaintsubmit') }}",
		    type: 'POST',
		    //data:{_token: '{{csrf_token()}}'},
			//data: $('#searchForm').serialize(),
			data: new FormData(this),
		    //dataType: 'json',
		    contentType: false,
            //cache: false,
            processData:false,
			
		    
		  
              beforeSend: function(){ 
			  //alert('before');
			  },
              success: function (response) {
				  //alert('success');
				  console.log(response);
				$('#searchForm')[0].reset(); 
                $('#searchForm').css("opacity","");
                $("#submit_btn").removeAttr("disabled");
				  
				  
                  //console.log(response);
				  //$('#searchForm').trigger("reset");
                  $('.my-traking-tab').empty();
                  $('.my-traking-tab').html(response);
				  //alert('success_end');
              },
               complete:function(response){ 
			   //alert('complete');
			   },
               error: function (response){ 
			   alert('Server Error'); 
			   console.log(response);
			   }
          });
   });
   

   
});


$('body').on('click', "#complaintdetailbtn", function(i) {
	         i.preventDefault();
	         var complaint_id = $(this).data('id');
	$.ajax({
              url: "{{ route('getcomplaintdetail') }}",
              type: 'POST',
              data:{_token: '{{csrf_token()}}', complaint_id:complaint_id},
              //dataType: 'JSON',
                  beforeSend: function(){$('#complaintdetailmodal #modaldata').html('Processing...');},
                  success: function (response) {
                      $('#complaintdetailmodal #modaldata').empty();
                      $('#complaintdetailmodal #modaldata').html(response);  
                  },
                   error: function (response){
                       $('#complaintdetailmodal #modaldata').empty();
                       $('#complaintdetailmodal #modaldata').html('Error 401');
                       
                   }
              });
			
			
			
});





$('body').on('click', "#feedbackbtn", function(i) {
	         i.preventDefault();
	         var complaint_id = $(this).data('id');
			$.ajax({
              url: "{{-- route('feedbackform') --}}",
              type: 'POST',
              data:{_token: '{{csrf_token()}}', complaint_id:complaint_id},
              //dataType: 'JSON',
                  beforeSend: function(){$('#feedbackmodal #modaldata').html('Processing...');},
                  success: function (response) {
                      $('#feedbackmodal #modaldata').empty();
                      $('#feedbackmodal #modaldata').html(response);  
                  },
                   error: function (response){
                       $('#feedbackmodal #modaldata').empty();
                       $('#feedbackmodal #modaldata').html('Error 401');
                       
                   }
              });
			
			
			
});
	
	
$('body').on('submit', '#feedbackform', function(i) {
         i.preventDefault();
         //var formData = $(this).serialize();
		 var formData = new FormData(this);
         
              $.ajax({
              url: "{{-- route('feedbackformsubmit') --}}",
              type: 'POST',
              data:formData,
			  
		      contentType: false,
              processData:false,
			  
			  
			  
			  
			  
			  
			  
			  
                  beforeSend: function(){$('#modaldata').html('Processing...');},
                  success: function (response) {
                      $("#modaldata").trigger("reset");
                      $('#modaldata').empty();
                      $('#modaldata').html(response);
                      
                        
                       
                      
                      setTimeout(function(){
					  //$("#feedbackmodal").modal('hide');
                        $('.btn-close').click();
                      }, 6000);
                      
                  },
                   
                   error: function (response){
                       $('#modaldata').empty();
                       $('#modaldata').html('Error 401');
                       
                   }
              });

        });
</script>
    </body>
</html>
