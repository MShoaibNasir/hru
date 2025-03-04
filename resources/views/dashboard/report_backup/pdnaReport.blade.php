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
                <h6 class="mb-0">PDNA Report</h6>
            </div>
            @php
            $lots=lots();
            @endphp
            
       
             <div class='row'>
                   
                   
              
               <div class='col-4'>
                  <label class='label'>Select Lot</label>
                  <select class='form-control' id="lot" name='lot'>
                      <option value=''>Select Lot</option>
                      @foreach($lots as $item)
                        <option value='{{$item->id}}'>{{$item->name}}</option>
                      @endforeach
                  </select>
               </div>
               <div class='col-4'>
                  <label class='label'>From</label>
                  <input type='date' class='form-control' id='from'>
                 
               </div>
               <div class='col-4'>
                  <label class='label'>To</label>
                  <input type='date' class='form-control' id='to'>
                 
               </div>
               <div class='button_parent col-12 my-4'>
               <button onclick='get_filter_pdma()' class='btn btn-success btn-sm custom_btn'>Filter</button>
               
               <button onclick='window.location.reload()' style='margin-left:6px;' class='btn btn-success btn-sm custom_btn'>Reset</button>
               </div>
            </div>    
                              
                
                
           
            
            <div class="table-responsive">
                <!--myTable-->
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="" style='margin-top:20px;'>
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">S no</th>
                            <th scope="col">District Name</th>       
                            <th scope="col">Total target for the district</th>         
                            <th scope="col">Total no of validated</th>
                        </tr>
                    </thead>
                    <tbody id='table_body'>
                    

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
                    console.log(response);
                         
                        $('#loader').show(); 
                        var index_number = 1;
                        $('#table_body').html(''); 
                        if(response.data.length >0){
                        response.data.forEach(item => {
                            $('#table_body').append(`
                                <tr>
                                    <td>${index_number}</td>
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