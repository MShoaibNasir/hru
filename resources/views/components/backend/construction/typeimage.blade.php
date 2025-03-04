@if(isset($question->consructionimage))
            <div class="col-md-6">
            <div class="card pb-3 mb-3">
            @if($stage == 'Stage 1')
            <img src="{{ asset('storage/construction_first_stage') }}/{{ $question->consructionimage->name }}" class="question_{{ $question->id }} card-img-top myImg rotating-image" alt="{{ $question->name }}" />
            @elseif($stage == 'Stage 2')
            <img src="{{ asset('storage/construction_second_stage') }}/{{ $question->consructionimage->name }}" class="question_{{ $question->id }} card-img-top myImg rotating-image" alt="{{ $question->name }}" />
            @elseif($stage == 'Stage 3')
            <img src="{{ asset('storage/construction_third_stage') }}/{{ $question->consructionimage->name }}" class="question_{{ $question->id }} card-img-top myImg rotating-image" alt="{{ $question->name }}" />
            @elseif($stage == 'Stage 4')
            <img src="{{ asset('storage/construction_fourth_stage') }}/{{ $question->consructionimage->name }}" class="question_{{ $question->id }} card-img-top myImg rotating-image" alt="{{ $question->name }}" />
            @endif
            
            <button class="button_rotate">Rotate</button>
            <div class="card-body">
            <p class="card-text">{{ $question->name }}</p>
            <x-backend.construction.typemap :question="$question"/>
            </div>
            </div>
            </div>
@endif



