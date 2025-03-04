<div class="col-md-12 my-3 text-end">
{!! Form::open(array('route'=>'batch_datalist_export','method'=>'POST')) !!}                 
	{!! Form::hidden('json_data', $jsondata) !!} 
	{!! Form::submit('Export Batch Data', array('name' => 'export_batch_data', 'class' => 'btn btn-danger')); !!}
{!! Form::close() !!}   

</div>  
<div class="row">
<div class="col-md-12 my-3 text-end">
    
</div>
<div class="col-md-12">
<div class="table-responsive">
<table class="table table-striped table-bordered">
 <thead>
<tr class="text-dark">
                            <th scope="col">S no</th>
                            <th scope="col">Batch No</th>
                            <th scope="col">Tranche No</th>
                            <th scope="col">Cheque No</th>
                            <th scope="col">Actions</th>
                         
</tr>
 </thead>
 
 <tbody>
     
        @foreach($data->chunk(3) as $chunks)
  
         @foreach($chunks as $item)
                        
                @php
                if($item->trench_no==1){
                $item->trench_no='First Tranche';
                }
                if($item->trench_no==2){
                $item->trench_no='Second Tranche';
                }
                if($item->trench_no==3){
                $item->trench_no='Third Tranche';
                }
                if($item->trench_no==4){
                $item->trench_no='Fourth Tranche';
                }
                @endphp
                            
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$item->batch_no}}</td>
                                <td>{{$item->trench_no}}</td>
                                <td>{{$item->cheque_no}}</td>
                                <td>
                                    <a class="btn btn-sm btn-danger" href="{{route('batch_detail', [$item->id])}}">View</a>                        
                                    @if($item->is_complete==1)
                                    <a class="btn btn-sm btn-primary">Completed</a>
                                    @else
                                    <a class="btn btn-sm btn-success" href="{{route('firstbatch.edit', [$item->id])}}">Edit</a>                        
                                    <a class="btn btn-sm btn-primary" href="{{route('firstbatch.complete', [$item->id])}}">Complete</a>                
                                    @endif
                                </td>
                            </tr>
            @endforeach
        @endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
<div class="col-md-12">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries</div>
</div>