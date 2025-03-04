
@if(isset($question->mneimage))

            <div class="col-md-6">
            <div class="card pb-3 mb-3">
            
            <img src="{{ asset('storage/mne_files') }}/{{ $question->mneimage->name }}" class="question_{{ $question->id }} card-img-top myImg rotating-image" alt="{{ $question->name }}" />
                @php
                    $answer = json_decode($question->mneanswer[0]->answer ?? '');

                @endphp
            
            <button class="button_rotate">Rotate</button>
            <div class="card-body">
            <p class="card-text">{{ $question->name }}</p>
                @if(isset($answer->fatchLocation))
                    <h5>Longitude:-</h5><span>{{ $answer->fatchLocation->longitude ?? '' }}</span>
                    <h5>Latitude:-</h5><span>{{ $answer->fatchLocation->latitude ?? '' }}</span>
                @endif
            </div>
            </div>
            </div>
@endif



