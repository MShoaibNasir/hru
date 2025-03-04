@if(isset($question->genderimage))
    <div class="col-md-6">
        <div class="card pb-3 mb-3">
            <img src="{{ asset('storage/gender_safe_guard/' . $question->genderimage->name) }}" 
                 class="question_{{ $question->id }} card-img-top myImg rotating-image" 
                 alt="{{ $question->name }}">
            <button class="button_rotate">Rotate</button>
            <div class="card-body">
                <p class="card-text">{{ $question->name }}</p>

                @php
                    $answer = json_decode($question->gender_safeguard_answer[0]->answer ?? '');
                   
                @endphp

                @if(isset($answer->fatchLocation))
                    <h5>Longitude:-</h5><span>{{ $answer->fatchLocation->longitude ?? '' }}</span>
                    <h5>Latitude:-</h5><span>{{ $answer->fatchLocation->latitude ?? '' }}</span>
                @endif
            </div>
        </div>
    </div>
@endif
