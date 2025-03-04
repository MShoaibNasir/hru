<div>
    <form method='post' action='{{route('environment_case.upload_comment')}}' class='show_comment'>
        @csrf
        <textarea placeholder="Comment" class="form-control" name="comment" cols="50" rows="10"></textarea>
        <input type='hidden' value='social' name='modal_name'>
        <input type='hidden' value='{{$question_id}}' name='question_id'>
        <input type='hidden' value='{{$surveyid}}' name='primary_id'>
        <input type='submit' value='Add comment' class='btn btn-success'>
    </form>
</div>