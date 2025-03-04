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
            <div class="table-responsive">
                <!--myTable-->
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="" style='margin-top:20px;'>
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">S no</th>
                            <th scope="col">Lot Name</th>       
                            <th scope="col">District Name</th>       
                            <th scope="col">Total Target For The District</th>         
                            <th scope="col">Total No Of Validated</th>
                        </tr>
                    </thead>
                    <tbody id='table_body'>
                    
                           <td>1</td>
                           <td>lot name</td>
                           <td>district name</td>
                           <td>10000</td>
                           <td>20000</td>
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