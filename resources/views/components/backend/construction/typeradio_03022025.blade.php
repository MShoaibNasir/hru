@if(count($question->consructionanswer) > 0)
<?php
$skip_questionn=null;
?>
            
{{-- @foreach([2550, 2552, 2554,  277396, 277398, 277400,  277407, 277409, 277411] as $id) --}}
@foreach([2550, 2552, 2554] as $id)
    @if($question->id == $id)
        <?php 
            $check_answer = get_construction_answer($id, $question->consructionanswer[0]->construction_json_id); 
            if ($check_answer->answer == 'No') {
                $skip_question = $id;
            }
        ?>
    @endif
@endforeach

@if($question->id != $skip_questionn )
<div class="card mb-2">
  <h5 id="question_{{ $question->id }}" class="card-header {{ $question->type }}">{{ $question->name }}</h5>
  <div class="card-body">
    <p class="card-text">
            @foreach($question->consructionanswer as $answer)
            {!! $answer->answer ?? '<span class="badge bg-danger">Not Available</span>' !!}
            {{-- is_array($answer->answer) ? 'Not Available' : ($answer->answer ?? 'Not Available') --}}
            @endforeach
        </p>
       
  </div>
</div> 
@endif
@endif