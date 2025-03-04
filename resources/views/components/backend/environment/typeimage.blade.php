@if(isset($question->consructionimage))
            <div class="col-md-6">
            <div class="card pb-3 mb-3">
            <img src="{{ asset('storage/construction_first_stage') }}/{{ $question->consructionimage->name }}" class="question_{{ $question->id }} card-img-top myImg rotating-image" alt="{{ $question->name }}">
            <button class="button_rotate">Rotate</button>
            <div class="card-body">
            <p class="card-text">{{ $question->name }}</p>
            @if(isset(json_decode($question->consructionanswer)[0]->answer))
            <h5>Longitude:-</h5><span>{{ json_decode(json_decode($question->consructionanswer)[0]->answer)->fetchLocation->longitude ?? '' }}</span>
            <h5>Latitude:-</h5><span>{{ json_decode(json_decode($question->consructionanswer)[0]->answer)->fetchLocation->latitude ?? '' }}</span>
            @endif
            </div>
            </div>
            </div>
@endif



