
<div class="accordion-header">Disability Data For {{ $personName }}</div>
@foreach($questions as $item)
    <h4>{{ $item->question->name ?? null }}</h4>
    @if($item->question->type == 'map')
        <ul>
            @foreach($item->options as $opt)
                <li>{{ $opt->name }} : {{ $opt->answer ?? 'not available' }}</li>
            @endforeach
        </ul>
    @elseif($item->question->type == 'image')
        @php
            $image = base64Image(json_decode($item->question->answer));
        @endphp
        @if($image)
            <img src="{{ $image }}" alt="CNIC Front Image" class='cnic'>
        @else
            <p>Not Available</p>
        @endif
    @else
        <p>{{ $item->question->answer ?? 'not available' }}</p>
    @endif
@endforeach
