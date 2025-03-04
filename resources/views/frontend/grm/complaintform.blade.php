<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('GRM COMPLAINT FORM') }}</title>
    {{ Html::favicon( 'images/favicon.jpeg') }}
    
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>


<style>
.form-group.required .control-label:after {
  content:"*";
  color:red;
}
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
}

</style>
    </head>
    <body>
<div class="container">
            <center>
            <img src="{{ asset('images/ifrap_logo.png') }}" width="200" alt="Housing Reconstruction Unit" class="logo-ayis" />
            </center>


<div class="row">
<div class="col-md-12 text-center"><hr><h3 class="page-title">GRM COMPLAINT FORM</h3><hr></div>    
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


{!! Form::open(array('route' => 'complaintsubmit','method'=>'POST', 'files'=>'true', 'class'=>'form-horizontal')) !!}
<div class="form-body">



<fieldset class="px-4 mb-4">
        <legend>Complainant Information</legend>
    {{--
    <div class="row form-group required">
		<label class="col-md-3 control-label">{{ __('Source/Channel') }}</label>
		<div class="col-md-9">
			{!! Form::select('source_channel', $source_channels, null, ['placeholder' => 'Source/Channel', 'class' => 'form-control select2', 'required']) !!}
		</div>
	</div>
	--}}
	{!! Form::hidden('source_channel', 1) !!}

	
	<div class="row form-group required">
		<label class="col-md-3 control-label">{{ __('PIU') }}</label>
		<div class="col-md-9 complaint_type_list">
			{!! Form::select('piu', $pius, 2, ['placeholder' => 'Select PIU', 'class' => 'form-control select2', 'required']) !!}
		</div>
	</div>
</fieldset>

<fieldset class="px-4 mb-4">
        <legend>Personal Details</legend>	
	<div class="row form-group required">
		<label class="col-md-3 control-label">{{ __('Full Name') }}</label>
		<div class="col-md-9">
			{!! Form::text('full_name', null, array('placeholder' => 'Full Name','class' => 'form-control', 'required')) !!}
		</div>
	</div>
	
	<div class="row form-group required">
		<label class="col-md-3 control-label">{{ __('Father Name / Husband Name') }}</label>
		<div class="col-md-9">
			{!! Form::text('father_name', null, array('placeholder' => 'Father Name / Husband Name','class' => 'form-control', 'required')) !!}
		</div>
	</div>
	
	<div class="row form-group required">
		<label class="col-md-3 control-label">{{ __('CNIC No') }}</label>
		<div class="col-md-9">
			{!! Form::text('cnic', null, array('placeholder' => 'CNIC No','class' => 'form-control', 'id' => 'mask_cnic', 'required')) !!}
			<span class="help-block">99999-9999999-9</span>
		</div>
	</div>
	
	<div class="row form-group">
		<label class="col-md-3 control-label">{{ __('HRU Beneficiary ID') }}</label>
		<div class="col-md-9">
			{!! Form::text('hru_beneficiary_id', null, array('placeholder' => 'HRU Beneficiary ID','class' => 'form-control')) !!}
		</div>
	</div>
	
	<div class="row form-group required">
		<label class="col-md-3 control-label">{{ __('Mobile No') }}</label>
		<div class="col-md-9">
			{!! Form::text('mobile', null, array('placeholder' => 'Mobile No','class' => 'form-control', 'maxlength' => '13', 'required')) !!}
			{{--<span class="help-block">(9999) 999-9999</span>--}}
		</div>
	</div>
	
	
	
	<div class="row form-group">
		<label class="col-md-3 control-label">{{ __('Email ID (If Any)') }}</label>
		<div class="col-md-9">
			{!! Form::email('email', null, array('placeholder' => 'Email ID (If Any)', 'class' => 'form-control')) !!}
		</div>
	</div>
	
	<div class="row form-group required">
		<label class="col-md-3 control-label">{{ __('Gender') }}</label>
		<div class="col-md-9">
			{!! Form::select('gender', ['male'=>'Male', 'female'=>'Female', 'other'=>'Other'], null, ['placeholder' => 'Select Gender', 'class' => 'form-control', 'required']) !!}
		</div>
	</div>

	<div class="row form-group required">
		<label class="col-md-3 control-label">{{ __('District') }}</label>
		<div class="col-md-9">
			{!! Form::select('district_id', $districts, null, array('placeholder' => 'Select District', 'class' => 'district form-control select2', 'required')) !!}
		</div>
	</div>
	
	<div class="row form-group required">
		<label class="col-md-3 control-label">{{ __('Tehsil') }}</label>
		<div class="col-md-9 tehsil_list">
			{!! Form::select('tehsil_id', [], null, ['placeholder' => 'Select Tehsil', 'class' => 'form-control select2', 'required']) !!}
		</div>
	</div>
	
	<div class="row form-group required">
		<label class="col-md-3 control-label">{{ __('UC') }}</label>
		<div class="col-md-9 uc_list">
			{!! Form::select('uc_id', [], null, ['placeholder' => 'Select UC', 'class' => 'form-control select2', 'required']) !!}
		</div>
	</div>

	
	
	<div class="row form-group">
			<label class="col-md-3 control-label">Postal Address</label>
			<div class="col-md-9">
			{!! Form::textarea('postal_address', null, array('placeholder' => 'Postal Address', 'class' => 'form-control')) !!}
			</div>
	</div>
</fieldset>	

<fieldset class="px-4 mb-4">
        <legend>Grievance Registration</legend>
    <div class="row form-group required">
		<label class="col-md-3 control-label">{{ __('Grievance Type') }}</label>
		<div class="col-md-9">
			{!! Form::select('grievance_type', $grievance_types, null, ['placeholder' => 'Grievance Type', 'class' => 'grievance_type form-control select2', 'required']) !!}
		</div>
	</div>
	

	<div class="grievance_fields">
    	<div class="defaultfields"><x-frontend.complaint.grievance_type_defaultfields /></div>
    	<div class="exclusioncasesfields" style="display:none;"><x-frontend.complaint.grievance_type_exclusion_cases /></div>
	</div>
</fieldset>

<fieldset class="px-4 mb-4">
        <legend>Evidences</legend>
        

	<div class="row form-group">
      <label class="col-md-3 control-label">Upload any Evidence File</label>
      <div class="col-md-9">
      {!! Form::file('evidence_files[]', null, array('class' => '')) !!}
      </div>
     </div>
     
     <div class="row form-group">
      <label class="col-md-3 control-label">Upload Any Photo</label>
      <div class="col-md-9">
      {!! Form::file('evidence_photos[]', null, array('class' => '')) !!}
      </div>
     </div>
     
     <div class="row form-group">
      <label class="col-md-3 control-label">Upload Any Video</label>
      <div class="col-md-9">
      {!! Form::file('evidence_videos[]', null, array('class' => '')) !!}
      </div>
     </div>
     
     <div class="row form-group">
      <label class="col-md-3 control-label">Upload Scan Copy of the Grievance received by Hand</label>
      <div class="col-md-9">
      {!! Form::file('evidence_scan_copy_grievance_hands[]', null, array('class' => '')) !!}
      </div>
     </div>
     
     <div id='evidence_table'></div>
     <center><button type="button" name="add_more_evidence" class="btn btn-info btn-md mb-5 add_more_evidence">Add More Evidence</button></center>
	 
</fieldset>

<!-- Submit Button  -->
    <div class="form-group row">
        <div class="col-md-12">
            <center><button type="submit" name="save" value="save" class="btn btn-primary btn-lg btn-customized">Submit Complaint</button></center>
        </div>
    </div>
</div>
{!! Form::close() !!}

</div>
</div>





</div>






      
      



  
  
  
<script>
$(document).on('click', '.add_more_evidence', function(){
  var html = '';
  html += '<div id="evidence_main_div">';
  html += '<hr>';
  html += '<div class="row form-group"><label class="col-md-3 control-label">Upload any Evidence File</label><div class="col-md-9">{!! Form::file('evidence_files[]', null, array('class' => '')) !!}</div></div>'; 
  html += '<div class="row form-group"><label class="col-md-3 control-label">Upload Any Photo</label><div class="col-md-9">{!! Form::file('evidence_photos[]', null, array('class' => '')) !!}</div></div>';
  html += '<div class="row form-group"><label class="col-md-3 control-label">Upload Any Video</label><div class="col-md-9">{!! Form::file('evidence_videos[]', null, array('class' => '')) !!}</div></div>';
  html += '<div class="row form-group"><label class="col-md-3 control-label">Upload Scan Copy of the Grievance received by Hand</label><div class="col-md-9">{!! Form::file('evidence_scan_copy_grievance_hands[]', null, array('class' => '')) !!}</div></div>';
  html += '<button type="button" name="remove_evidence" class="btn btn-danger btn-sm float-right remove_evidence">Remove Evidence</button><br></div>';
  $('#evidence_table').append(html);
 });

 $(document).on('click', '.remove_evidence', function(){
  $(this).closest('#evidence_main_div').remove();
 });





$(document).ready(function() {
    
$('.select2').select2();    
        
        $("#mask_date").inputmask("d/m/y", {autoUnmask: true}); //direct mask        
        $("#mask_date1").inputmask("d/m/y", {"placeholder": "*"}); //change the placeholder
        $("#mask_date2").inputmask("d/m/y", {"placeholder": "dd/mm/yyyy"}); //multi-char placeholder
        
        //$("#mask_mobile").inputmask("mask",{"mask":"(9999) 999-9999"}),
        //$("#mask_phone").inputmask("mask", {"mask":"(999) 999-9999"}); //specifying fn & options
		//$("#mask_cnic").inputmask("mask", {"mask":"(99999) 9999999-9"}); //cnic
		 
		 $("#mask_mobile").inputmask("(9999) 999-9999", {"clearIncomplete":true});
         $("#mask_phone").inputmask("(999) 999-9999", {"clearIncomplete":true});
		 $("#mask_cnic").inputmask("99999-9999999-9", {"clearIncomplete":true});
    

	$('body').on('change', '.district', function(e){
     e.preventDefault();
		var district_id = $(e.target).find("option:selected").val();
		$.ajax({
              url: "{{ route('complaints.fetch_tehsil_list') }}",
              type: 'POST',
              data: {district_id: district_id, _token: '{{csrf_token()}}'},
              beforeSend: function(){$('.tehsil_list').html('Tehsil list Processing...');},
              success: function (response) {
				 $('.tehsil_list').empty();				 
				 $('.tehsil_list').html(response);
                 $('.uc_list').empty();
                 $('.uc_list').html('<select class="form-control select2"><option>Select UC</option></select>');				 
				 $('.select2').select2();
			  },
              error: function (response){$('.tehsil_list').empty(); $('.uc_list').empty(); }
              });
    });
	
	$('body').on('change', '.tehsil', function(e){
     e.preventDefault();
		var tehsil_id = $(e.target).find("option:selected").val();
		$.ajax({
              url: "{{ route('complaints.fetch_uc_list') }}",
              type: 'POST',
              data: {tehsil_id: tehsil_id, _token: '{{csrf_token()}}'},
              beforeSend: function(){$('.uc_list').html('UC List Processing...');},
              success: function (response) {
				 $('.uc_list').empty(); 
				 $('.uc_list').html(response); 
				 $('.select2').select2();
			  },
              error: function (response){$('.uc_list').empty();}
              });
    });
	
	
	
	
});

$(document).ready(function() {

        $('.grievance_type').change(function() {
            var grievance_type = $(".grievance_type").val();
            //alert(grievance_type);
            if(grievance_type==1){
                $(".grievance_fields .defaultfields").hide();
                $(".grievance_fields .exclusioncasesfields").show();
            }else{
                $(".grievance_fields .exclusioncasesfields").hide();
                $(".grievance_fields .defaultfields").show();
            }
                
            });   
    });
</script>
    </body>
</html>
