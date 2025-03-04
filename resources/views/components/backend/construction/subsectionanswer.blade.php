<div class="card mb-2">
                  <h5 id="question_{{ $qid }}" class="card-header">{{ getquestionlabel($qid) }}</h5>
                  <div class="card-body">
                    <p class="card-text">
          <?php $check_answer = get_construction_answer($qid, $cid); ?>
            @if(is_json($check_answer->answer))
            <?php $checkbox = json_decode($check_answer->answer); ?>
                @if(is_array($checkbox))
                    @if($checkbox)
                        @foreach($checkbox as $item)
                          <span class="badge bg-primary">{{ getoptionlabel($item) }}</span>
                        @endforeach
                    @endif
                @else
                <span class="badge bg-primary">{{ $checkbox->answer }}</span>
                @endif
            @else
            <span class="badge bg-primary">{{ $check_answer->answer }}</span>
            @endif
                    </p>
                  </div>
                </div>