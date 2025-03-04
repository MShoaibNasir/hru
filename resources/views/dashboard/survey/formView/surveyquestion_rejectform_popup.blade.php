<div class="col-md-12">
{!! Form::open(['route' => 'surveyquestion_rejectformsubmit', "method" => "post", "class" => "margin-bottom-5", "id" => "surveyquestion_rejectform_popup"]) !!}	
{!! Form::hidden('survey_id', $survey_id) !!}
{!! Form::hidden('ques_id', $ques_id) !!}
{!! Form::hidden('decision', $decision) !!}

{{--
<div class="row margin-bottom-15">
	<div class="col-md-4">sdsds</div>	
	<div class="col-md-8">dsdsds</div>	
</div>
--}}
	
	

<div class="row margin-bottom-15">
<label class="col-md-4">Comment:</label>
<div class="col-md-8">{!! Form::textarea('comment', null, ['placeholder' => 'Comment', 'class' => 'form-control']) !!}</div>
</div>	

<div class="row margin-bottom-15">
<div class="col-md-12">{!! Form::submit("Submit", ["class"=>"btn btn-primary"]) !!}</div>
</div>
	

{!! Form::close() !!}
</div>