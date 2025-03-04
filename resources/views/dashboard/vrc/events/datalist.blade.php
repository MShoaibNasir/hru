<div class="col-md-12 my-3 text-end">
{!! Form::open(array('route' => 'export_vrc_event_list','method'=>'POST')) !!}                 
	{!! Form::hidden('environment_data', $jsondata) !!}
	{!! Form::submit('Export VRC Events', array('name' => 'export', 'class' => 'btn btn-danger')); !!}
{!! Form::close() !!}
</div>  


<div class="row">
<div class="col-md-12">
<div class="table-responsive">
<table class="table table-striped table-bordered">
 <thead>
 <tr>

    <th scope="col">Attendance</th>
    <th scope="col">Created At</th>
    <th scope="col">Name of Event</th>
    <th scope="col">District</th>
    <th scope="col">Tehsil</th>
    <th scope="col">UC</th>
    <th scope="col">VRC Name</th>
    <th scope="col">Venue</th>
    <th scope="col">Date</th>
    <th scope="col">Durations</th>
    <th scope="col">Responsibilities</th>
    <th scope="col">Image 1</th>
    <th scope="col">Image 2</th>
    <th scope="col">Image 3</th>
    <th scope="col">Image 4</th>
    <th scope="col">Image 5</th>
 </tr>
 </thead>
 
 <tbody>
 
@foreach($data->chunk(3) as $chunks)
@foreach($chunks as $item)


                            <tr>
                                <td><a href='{{ route("vrc_attendance_list",[$item->id]) }}' target="_blank" class='btn btn-success'>View ID:{{ $item->id }}</a></td>
                                <td>{{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                <td>{{$item->name_of_event ?? ''}}</td>
                                <td>{{$item->district ?? ''}}</td>
                                <td>{{$item->tehsil ?? ''}}</td>
                                <td>{{$item->uc ?? ''}}</td>
                                <td>{{$item->vrc_name ?? ''}}</td>
                                <td>{{$item->venue ?? ''}}</td>
                                <td>{{ Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
                                <td>{{$item->durations ?? ''}}</td>
                                <td>{{$item->responsibilities ?? ''}}</td>
                                
                                <td>{!! isset($item->capture_image_1) ? '<img  src="'.asset('storage/vrc_attendance/'.$item->capture_image_1).'" width="100" alt="" class="myImg" />' : 'No Image' !!}</td>
                                <td>{!! isset($item->capture_image_2) ? '<img src="'.asset('storage/vrc_attendance/'.$item->capture_image_2).'" width="100" alt="" class="myImg" />' : 'No Image' !!}</td>
                                <td>{!! isset($item->capture_image_3) ? '<img src="'.asset('storage/vrc_attendance/'.$item->capture_image_3).'" width="100" alt="" class="myImg" />' : 'No Image' !!}</td>
                                <td>{!! isset($item->capture_image_4) ? '<img src="'.asset('storage/vrc_attendance/'.$item->capture_image_4).'" width="100" alt="" class="myImg" />' : 'No Image' !!}</td>
                                <td>{!! isset($item->capture_image_5) ? '<img src="'.asset('storage/vrc_attendance/'.$item->capture_image_5).'" width="100" alt="" class="myImg" />' : 'No Image' !!}</td>
                                
                                   
  </tr>
@endforeach
@endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
<div class="col-md-12">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries</div>
<script>
  $(document).ready(function(){
      var mmodal = document.getElementById("mmyModal");

// Get the modal image and caption
var mmodalImg = document.getElementById("img01");
var ccaptionText = document.getElementById("ccaption");

// Get all images with class "myImg"
var imgs = document.getElementsByClassName("myImg");
console.log(imgs.length);

// Loop through the images and add click event listeners
for (var i = 0; i < imgs.length; i++) {
  imgs[i].onclick = function(){
      
    mmodal.style.display = "block";
    mmodalImg.src = this.src;
    ccaptionText.innerHTML = this.alt;
  }
}

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("cclose")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
  mmodal.style.display = "none";
}
      
      
  }) 
</script>
</div>