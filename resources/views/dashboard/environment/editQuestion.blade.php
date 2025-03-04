<div>
    <form method='post' action='{{route('update_environment_option')}}'>
        @csrf
        <select class='form-control' name='option'>
            <option>Select Option</option>
            @foreach($options as $item)
            <option value='{{$item->name}}'>{{$item->name}}</option>
            @endforeach
            <input type='hidden' value='{{$survey_id}}' name='survey_id'>
            <input type='hidden' value='{{$question_id}}' name='question_id'>
            
    </select>
        <input type='submit' class='btn btn-success btn-sm' value='update'>
    </form>
    
</div>