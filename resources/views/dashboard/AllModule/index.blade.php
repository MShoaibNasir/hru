
<div class="row">
<div class="col-md-12">
<div class="table-responsive">
<table class="table table-striped table-bordered">
 <thead>
 <tr>
                          
                            <th scope="col">User Name</th>
                            <th scope="col">Role Name</th>
                            <th scope="col">Ref No</th>
                            <th scope="col">Action</th>
                            <th scope="col">District</th>
                            <th scope="col">Tehsil</th>
                            <th scope="col">Uc</th>
                            <th scope="col">Comments</th>
                            
                         
                          
                     
 </tr>
 </thead>
 
 <tbody>

                    @foreach($data->chunk(3) as $chunks)
                    @foreach($chunks as $item)
                            <tr>
                                <td>{{$item->created_by->name ?? null}}</td>
                                <td>{{$item->role->name ?? null}}</td>
                                <td>{{$item->surveyform->ref_no ?? null}}</td>
                                <td>{{$item->form_status ?? null}}</td>
                                <td>{{$item->surveyform->getdistrict->name ?? null}}</td>
                                <td>{{$item->surveyform->gettehsil->name ?? null}}</td>
                                <td>{{$item->surveyform->getuc->name ?? null}}</td>
                                <td>{{$item->comment ?? null}}</td>
                            </tr>
                        @endforeach
                        @endforeach
 </tbody>
</table>
</div></div>

<div class="col-md-12 my-3">{{ $data->links("pagination::bootstrap-4") }}</div>
<div class="col-md-12">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries</div>
</div>