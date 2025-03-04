@if(count($question->consructionanswer) > 0)
@foreach([2550, 2552, 2554,  277396, 277398, 277400,  277407, 277409, 277411, 277463, 277466] as $qid)
    @if($question->id == $qid)
        <?php $check_answer = get_construction_answer($qid, $question->consructionanswer[0]->construction_json_id); ?>
<div class="card mb-2">
  <h5 id="question_{{ $question->id }}" class="card-header {{ $question->type }}">{{ $question->name }}</h5>
  <div class="card-body">
    <p class="card-text">
            @foreach($question->consructionanswer as $answer)
            {!! $answer->answer ?? '' !!}
            @endforeach
        </p>
        
            @if($check_answer->answer == 'No')
                
                @if($qid == 2550)
                <x-backend.construction.subsectionanswer :qid="2774" :cid="$question->consructionanswer[0]->construction_json_id" />
                @elseif($qid == 2552)
                <x-backend.construction.subsectionanswer :qid="2775" :cid="$question->consructionanswer[0]->construction_json_id" />
                @elseif($qid == 2554)
                <x-backend.construction.subsectionanswer :qid="2577" :cid="$question->consructionanswer[0]->construction_json_id" />
                <x-backend.construction.subsectionanswer :qid="2776" :cid="$question->consructionanswer[0]->construction_json_id" />
                
                
                @elseif($qid == 277396)
                <x-backend.construction.subsectionanswer :qid="277397" :cid="$question->consructionanswer[0]->construction_json_id" />
                @elseif($qid == 277398)
                <x-backend.construction.subsectionanswer :qid="277399" :cid="$question->consructionanswer[0]->construction_json_id" />
                @elseif($qid == 277400)
                <x-backend.construction.subsectionanswer :qid="277401" :cid="$question->consructionanswer[0]->construction_json_id" />
                
                
                
                @elseif($qid == 277407)
                <x-backend.construction.subsectionanswer :qid="277408" :cid="$question->consructionanswer[0]->construction_json_id" />
                @elseif($qid == 277409)
                <x-backend.construction.subsectionanswer :qid="277410" :cid="$question->consructionanswer[0]->construction_json_id" />
                @elseif($qid == 277411)
                <x-backend.construction.subsectionanswer :qid="277412" :cid="$question->consructionanswer[0]->construction_json_id" />
                
                
                
                @elseif($qid == 277463)
                <x-backend.construction.subsectionanswer :qid="277468" :cid="$question->consructionanswer[0]->construction_json_id" />
                @elseif($qid == 277466)
                <x-backend.construction.subsectionanswer :qid="277469" :cid="$question->consructionanswer[0]->construction_json_id" />
                @endif
               
 
            @else
            
            @endif
        
        
        
        
       
  </div>
</div>




    @endif
@endforeach 
@endif


<?php /*
Stage 1
Section 144
if No 2550 Get Q ANS LIST 2774
if No 2552 Get Q ANS LIST 2775        
if No 2554 Get Q ANS LIST 2577, 2776



Stage 2
Section 146158, 146160
if No 277396 Get Q ANS LIST 277397
if No 277398 Get Q ANS LIST 277399        
if No 277400 Get Q ANS LIST 277401



Stage 3
Section 131
if No 277407 Get Q ANS LIST 277408
if No 277409 Get Q ANS LIST 277410        
if No 277411 Get Q ANS LIST 277412
*/ ?>