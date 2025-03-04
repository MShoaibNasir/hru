@extends('dashboard.layout.master')
@section('content')
<style>
label.label {
    width: 100%;
    text-align: left;
}

#loader {
    position: fixed;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000; /* Ensure it appears above other elements */
    display: none; /* Hidden by default */
}

.spinner {
    border: 8px solid #f3f3f3; /* Light grey */
    border-top: 8px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
.not_found {
    width: 100%;
    display: flex;
    white-space: nowrap;
}

.custom_btn {
    width: 33%;
}

.button_parent.col-4.my-4 {
    display: flex;
    justify-content: left;
    align-items: center;
    padding-top: 23px;
}
/* For Chrome, Safari, and Edge */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* For Firefox */
input[type=number] {
    -moz-appearance: textfield;
}


</style>
<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
     @include('dashboard.layout.navbar')
    <!-- Navbar End -->
    <div class="container-fluid pt-4 px-4 form_width">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">PDMA DATA</h6>
            </div>
            @php
            $lots=lots();
            $district=district();
            $tehsil=tehsil();
            $uc=uc();
            @endphp
            
       
             <div class='row'>
               <input type='hidden' value='filter' name='filter'>         
              
               <div class='col-4'>
                  <label class='label'>Select Districts</label>
                  
                  <select class='form-control' id="district" name='district'>
                      <option value=''>Select District</option>
                      @foreach($district as $item)
                        <option value='{{$item->id}}'>{{$item->name}}</option>
                      @endforeach
                  </select>
               </div>
               <div class='col-4'>
                  <label class='label'>Select Tehsil</label>
                  <select class='form-control' id="tehsil" >
                        <option value=''>Select Tehsil</option>
                       @foreach($tehsil as $item)
                       <option value='{{$item->id}}'>{{$item->name}}</option>
                       @endforeach
                  </select>
                  
               </div>
               <div class='col-4'>
                  <label class='label'>Select UC</label>
                  <select class='form-control'  id="uc" onchange='get_filter_pdma()'>
                       <option value=''>Select Uc</option>
                      @foreach($uc as $item)
                       <option value='{{$item->id}}'>{{$item->name}}</option>
                      @endforeach
                  </select>
               </div>
               <div class='col-4 my-4'>
                  <label class='label'>Enter CNIC</label>
                  <input type='number' placeholder='Enter CNIC' oninput='validateNumber()' class='form-control' id='cnic'>
               </div>
               <div class='col-4 my-4'>
                  <label class='label'>Enter Refrence Number</label>
                  <input type='number' placeholder='Enter Refrence Number'  class='form-control' id='refrence_number'>
               </div>
               <div class='button_parent col-4 my-4'>
                   
               <button onclick='get_filter_pdma()' class='btn btn-success btn-sm custom_btn'>Filter</button>
               <button onclick='window.location.reload()' style='margin-left:6px;' class='btn btn-success btn-sm custom_btn'>Reset</button>
               <input type='hidden' value='{{Auth::user()->role}}' id='is_admin'>
               </div>
            </div>    
                              
                
                
           
            
            <div class="table-responsive">
                <!--myTable-->
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="" style='margin-top:20px;'>
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">S no</th>
                            <th scope="col">Refrence Number</th> 
                            @if(Auth::user()->role==1)
                                  <th scope="col">Priority Status</th> 
                            @endif 
                            <th scope="col">Province</th>         
                            <th scope="col">CNIC</th>         
                            <th scope="col">Survey Date</th>         
                            <th scope="col">Address</th>         
                            <th scope="col">District</th>         
                            <th scope="col">Tehsil</th>         
                            <th scope="col">Uc</th>         
                            <th scope="col">Beneficiary Name</th>         
                            <th scope="col">Father/Husbent Name</th>         
                            <th scope="col">Contact number</th>         
                            <th scope="col">Gender</th>         
                            <th scope="col">Age</th>         
                            <th scope="col">Name of next kin</th>         
                            <th scope="col">Cnic of kin</th>         
                            <th scope="col">Damaged Rooms</th>         
                            <th scope="col">Damaged Type</th>         
                            <th scope="col">Damaged Category</th>         
                            <th scope="col">Auto Gender</th>         
                            <th scope="col">IS CNIC</th>         
                            <th scope="col">IS Contact</th>
                            <th scope="col">IS Complete</th>
                            <th scope="col">IS Potential</th>
                        </tr>
                    </thead>
                    <tbody id='table_body'>
                        @foreach($ndma_data as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$item->b_reference_number}}</td>
                                @if(Auth::user()->role==1)
                                <td><button class='btn btn-warning' onclick='Add_to_priority({{$item->id}},{{$item->priority==1 ? 0 : 1}})'> {{$item->priority==1 ? "Remove From Priority" : "Add to Priority"}} </button></td> 
                                @endif
                                <td>{{$item->province}}</td>
                                <td>{{$item->cnic}}</td>
                                <td>{{$item->survey_date}}</td>
                                <td>{{$item->address}}</td>
                                <td>{{$item->district}}</td>
                                <td>{{$item->tehsil}}</td>
                                <td>{{$item->uc}}</td>
                                <td>{{$item->beneficiary_name}}</td>
                                <td>{{$item->father_name}}</td>
                                <td>{{$item->contact_number}}</td>
                                <td>{{$item->gender}}</td>
                                <td>{{$item->age}}</td>
                                <td>{{$item->name_next_of_kin}}</td>
                                <td>{{$item->cnic_of_kin}}</td>
                                <td>{{$item->damaged_rooms}}</td>
                                <td>{{$item->damaged_type}}</td>
                                <td>{{$item->damaged_category}}</td>
                                <td>{{$item->auto_gender}}</td>
                                <td>{{$item->is_cnic}}</td>
                                <td>{{$item->is_contact}}</td>
                                <td>{{$item->is_complete}}</td>
                                <td>{{$item->is_potential}}</td>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class='pages'>
                {{$ndma_data->links()}}
                </div>
            </div>
        </div>
    </div>
    <div id="loader" style="display:none;">
    <div class="spinner"></div>
    <div class="loading-text">Loading...</div>
</div>
    
    <script>
    
    
    function validateNumber() {
       
    const input = document.getElementById('cnic');

    const value = input.value;

   
    if (value.length > 13) {
        input.value = value.slice(0, 13); // Limit to 13 digits
    }
}
    
    
    var base_url= 'https://mis.hru.org.pk/';
        function Add_to_priority(id,status){
        var token = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
        type: "GET",
        url: `${base_url}prority/data`,
        data: {
            id: id,
            status:status,
            _token: token,
        },
        success: function (response) {
            console.log(response);
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
              Toast.fire({
                icon: "success",
                title: "you set this form is in priority successfully"
            });
            setTimeout(()=>{
            window.location.reload();
            },3000)
        },
        error: function (request, status, error) {
            console.log(error);
            alert("Couldn't retrieve lots. Please try again later.");
        },
    });
    }  
        function get_filter_pdma() {
            var is_admin=$('#is_admin').val();
            var token = $('meta[name="csrf-token"]').attr("content"); 
           
            var district = $('#district').val();
            var tehsil = $('#tehsil').val();
            var cnic = $('#cnic').val();
            var uc = $('#uc').val();
            var refrence_number = $('#refrence_number').val();
        
            $('#loader').show();
            
            
            
            if(district=='' && tehsil=='' && cnic=='' && uc=='' && refrence_number==''){
               alert('kindly select atleast one field for filter data'); 
               $('#loader').hide();
            }
            else{
                
            if(cnic.length>0 &&  cnic.length<13){
                alert('plz enter correct cnic number');
                 $('#loader').hide();
            }else{
                $.ajax({
                type: "POST",
                url: '{{ route("filter_pdma") }}',
                data: {
                    
                    district: district,
                    tehsil: tehsil,
                    uc: uc,
                    cnic: cnic,
                    refrence_number: refrence_number,
                    _token: token,
                },
                success: function (response) {
                       
                        console.log(response);
                        $('#loader').show(); 
                        var index_number = 1;
                        $('#table_body').html(''); 
                        if(response.data.length >0){
                        response.data.forEach(item => {
                     
                        var priorityButton = '';
                        if (is_admin == 1) {
                            priorityButton = `<button class='btn btn-warning' onclick='Add_to_priority(${item.id}, ${item.priority == 1 ? 0 : 1})'>Add to Priority</button>`;
                        }   
                        console.log(priorityButton);
                           
                            $('#table_body').append(`
                                <tr>
                                    <td>${index_number}</td>
                                    <td>${item.b_reference_number}</td>
                                    <td>${priorityButton}</td>
                                    <td>${item.province}</td>
                                    <td>${item.cnic}</td>
                                    <td>${item.survey_date}</td>
                                    <td>${item.address}</td>
                                    <td>${item.district}</td>
                                    <td>${item.tehsil}</td>
                                    <td>${item.uc}</td>
                                    <td>${item.beneficiary_name}</td>
                                    <td>${item.father_name}</td>
                                    <td>${item.contact_number}</td>
                                    <td>${item.gender}</td>
                                    <td>${item.age}</td>
                                    <td>${item.name_next_of_kin}</td>
                                    <td>${item.cnic_of_kin}</td>
                                    <td>${item.damaged_rooms}</td>
                                    <td>${item.damaged_type}</td>
                                    <td>${item.damaged_category}</td>
                                    <td>${item.auto_gender}</td>
                                    <td>${item.is_cnic}</td>
                                    <td>${item.is_contact}</td>
                                    <td>${item.is_complete}</td>
                                    <td>${item.is_potential}</td>
                                </tr>
                            `);
                            index_number++;
                        });
                         $('#loader').hide();
                         $('.pages').hide();
                        }else{
                            $('#loader').hide();
                            $('.pages').hide();
                            $('#table_body').html('<div class="not_found"><h4>Data Not Found</h4></div>');
                        }
                    } 
                ,
                error: function (request, status, error) {
                    console.log(error);
                    alert("Couldn't retrieve lots. Please try again later.");
                     $('#loader').hide();
                },
            });
            }
                
            }

}


        
        
        
    </script>
    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                toast: true,         // This enables the toast mode
                position: 'top-end', // Position of the toast
                showConfirmButton: false, // Hides the confirm button
                timer: 3000          // Time to show the toast in milliseconds
            });
        </script>
    @endif
    @if(session('success'))
        <script>

            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: "{{ session('success') }}"
            });
        </script>
    @endif

    @endsection