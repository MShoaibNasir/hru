<div class="row">
<div class="col-md-12">
<div class="table-responsive">
<table class="table table-striped table-bordered">
 <thead>
 <tr>
    <th scope="col">Actions</th>
    <th scope="col">VRC Name</th>
    <th scope="col">Date</th>
    <th scope="col">Create By</th>
    <th scope="col">Form Name</th>
    <th scope="col">Department</th>
    <th scope="col">Status</th>
    <th scope="col">Report Trail</th>
    <th scope="col">Lot</th>
    <th scope="col">District</th>
    <th scope="col">Tehsil</th>
    <th scope="col">UC</th>
 </tr>
 </thead>
 
 <tbody>
    
 
@foreach($data->chunk(3) as $chunks)
@foreach($chunks as $item)
  @php
  $beneficairy_details=json_decode($item->beneficiary_details);
  $form_name=form_name($item->form_id);

  @endphp
                            <tr style='background-color: {{$item->priority==1 ? '#19875433' : 'transparent'}}';>
                                <td><a href='{{ route("gender.view",[$item->id]) }}' target="_blank" class='btn btn-success'>View ID:{{ $item->id }}</a></td>
                                <td>{{$item->unique_name_of_vrc ?? ''}}</td>
                                <td>{{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                <td>{{$item->getuser->name ?? ''}}</td>
                                <td>{{ $form_name }}</td>
                                <td>{{$item->role_name ?? ''}}</td>

    @php
        if($item->status=='CR')
        $item->status='Case Register';
        else if($item->status=='C')
        $item->status='Case Close';
        else
        $item->status='Pending';
    @endphp
                                
                                
                                <td>{{$item->status ?? '' }}</td>
                                <td>
                                @if($item->getstatustrail->count() > 0)
                                <a class="btn btn-sm btn-danger report_trail_btn" style="height:30px;" construction_id="{{ $item->id }}" href="javascript:void(0)">Report Trail</a>
                                @else
                                No Trail
                                @endif
                                </td>
                              
                                @php
                                $lot=DB::table('lots')->where('id',$item->lot)->select('name')->first() ?? null;
                                $district=DB::table('districts')->where('id',$item->district)->select('name')->first() ?? null;
                                $tehsil=DB::table('tehsil')->where('id',$item->tehsil)->select('name')->first() ?? null;
                                $uc=DB::table('uc')->where('id',$item->uc)->select('name')->first() ?? null;
                                @endphp
                                <td>{{$lot->name ?? ''}}</td>
                                <td>{{$district->name ?? ''}}</td>
                                <td>{{$tehsil->name ?? ''}}</td>
                                <td>{{$uc->name ?? ''}}</td>
                                
                                	  
  </tr>
@endforeach
@endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
<div class="col-md-12">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries</div>
</div>