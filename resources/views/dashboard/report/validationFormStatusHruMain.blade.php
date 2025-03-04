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
                <h6 class="mb-0">Damage Assessment Form Report</h6>
            </div>

            <div class="table-responsive">
                <!--myTable-->
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable" style='margin-top:20px;'>
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">S no</th>
                            <th scope="col">Lot Name</th>       
                            <th scope="col">District Name</th>       
                            <th scope="col">Total Target For The District</th>         
                            <th scope="col">Total No Of Validated</th>
                            <th scope="col">Field Supervisor</th>
                            <th scope="col">IP</th>
                            <th scope="col">Data Review Committee</th>
                            <th scope="col">QA</th>
                            <th scope="col">Data Selection Committee</th>
                           
                           
                        </tr>
                    </thead>
                    <tbody id='table_body'>
                    
                    @foreach($result as $item)

                    @php
                    $user_lot=Auth::user()->lot_id;
                    $user_lots=json_decode($user_lot);
                    $field_superVisor_count=singleReporting($item->districtId,"field supervisor");
                    $ip_count=singleReporting($item->districtId,"IP");
                    $hru_count=singleReporting($item->districtId,"HRU");
                    $psia_count=singleReporting($item->districtId,"PSIA");
                    $hru_main_count=singleReporting($item->districtId,"HRU_MAIN");
                    $COO_count=singleReporting($item->districtId,"COO");
                    $CEO_count=singleReporting($item->districtId,"CEO");
                    $finance_count=singleReporting($item->districtId,"finance");
             
                  
                    @endphp
                  @if(Auth::user()->role==1 || Auth::user()->role==39 || Auth::user()->role==40)
                     <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$item->lotName}}</td>
                                <td>{{$item->district}}</td>
                                <td>{{$item->total_beneficiary}}</td>
                                <td>{{$item->validated_beneficiary ?? 0}}</td>
                                <td>{{$field_superVisor_count}}</td>
                                <td>{{$ip_count}}</td>
                                <td>{{$hru_count}}</td>
                                <td>{{$psia_count}}</td>
                                <td>{{$hru_main_count}}</td>
                               
                            </tr>
                    @else        
                  
                  @if(in_array($item->lotId,$user_lots))
                    
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$item->lotName}}</td>
                                <td>{{$item->district}}</td>
                                <td>{{$item->total_beneficiary}}</td>
                                <td>{{$item->validated_beneficiary ?? 0}}</td>
                                <td>{{$field_superVisor_count}}</td>
                                <td>{{$ip_count}}</td>
                                <td>{{$hru_count}}</td>
                                <td>{{$psia_count}}</td>
                                <td>{{$hru_main_count}}</td>
                             
                            </tr>
                 @endif            
                 @endif            
                    @endforeach
                   

                    </tbody>
                </table>
               
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
    
    
    var base_url= 'https://sld.devstaging.a2zcreatorz.com/ifrap/public/';
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
            var token = $('meta[name="csrf-token"]').attr("content");
            var lot = $('#lot').val();
            var from = $('#from').val();
            var to = $('#to').val();
            console.log('from',from);
            console.log('to',to);
            if((from=='' && to=='') || (from!='' && to!='')){
            $('#loader').show();
            if(lot==''){
               alert('kindly select atleast one lot for filter data'); 
               $('#loader').hide();
            }
            else{
                
            $.ajax({
                type: "POST",
                url: '{{ route("filterBeneficiaryReport") }}',
                data: {
                    
                    lot: lot,
                    from: from,
                    to: to,
                    _token: token,
                },
                success: function (response) {
                        $('#loader').show(); 
                        var index_number = 1;
                        $('#table_body').html(''); 
                        if(response.data.length >0){
                        response.data.forEach(item => {
                            getDistrict(item.district);
                            $('#table_body').append(`
                                <tr>
                                    <td>${index_number}</td>
                                    <td>${item.lotName}</td>
                                    <td>${item.district}</td>
                                    <td>${item.total_beneficiary}</td>
                                    <td>${item.validated_beneficiary}</td>
                                </tr>
                            `);
                            index_number++;
                        });
                         $('#loader').hide();
                        
                        
                        }else{
                            $('#loader').hide();
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
                
            }else{
                if(from=='' && to!=''){
                     alert('plz select both starting and ending data');
                }
                if(to=='' && from!=''){
                     alert('plz select both starting and ending data');
                }
            }
          
          

}

        function getDistrict($districtId){
            console.log($districtId);
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