@extends('dashboard.layout.master')
@section('content')
   

<style>
.download_btn {
    width: 100%;
    display: flex;
    justify-content: end;
}
.bank_heading {
display: flex;
}
a.btn.btn-success {
    width: 143px;
    height: auto;
    margin-top: 25px;
}
#confirm_list{
    width:200px;
    margin-left:25px;
}
.buttons {
    display: flex;
    gap:5px;
}
</style>

<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
    @include('dashboard.layout.navbar')
    <!-- Navbar End -->
    <div class="container-fluid pt-4 px-4 form_width">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Beneficiaries With Account (New)</h6>
                   
                </div>
            </div>
        </div>
        
         <div class="bg-light text-center rounded p-4">
            <div class='row'>
                 <a class='btn btn-success my-4' style='margin-left:20px;' href='{{route("get_export_bio_metric_status")}}' id='generated_button'>Generate Sheet</a>
            </div>
            <div class='col-6 my-4'>
                <h5 class='bank_heading'>Select Bank</h5>
                <select class='form-control' id='bank_name'>
                    <option value=''>Select Bank</option>
                    @foreach($bank as $item)
                    <option value='{{$item->id}}'>{{$item->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class='buttons'>
            <button id="select-all-button" class='btn btn-success btn-sm'>Select All</button>
            <button id="deselect-all-button" class='btn btn-danger btn-sm'>Deselect All</button>
            </div> 
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0" id='finance'>
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">S NO</th>
                            <th scope="col">View</th>
                            <th scope="col">Select Beneficairy</th>
                            <th scope="col">REFERENCE NO</th>
                            <th scope="col">BENEFICIARY FULL NAME</th>
                            <th scope="col">BENEFICIARY FATHER'S/HUSBAND NAME</th>
                            <th scope="col">CNIC/ID NUMBER</th>
                            <th scope="col">MARITAL STATUS</th>
                            <th scope="col">ACCOUNT NUMBER</th>
                            <th scope="col">BANK NAME</th>
                            <th scope="col">BRANCH NAME</th>
                            <th scope="col">BANK ADDRESS</th>
                            <th scope="col" style='visibility:hidden;'>Is Bio Metric Verified</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                         
                     
                         @foreach($form as $item)
                         @php
                        @endphp
                         @if($item->answer=='No')
                            <tr>
                              <td>{{$loop->index+1}}</td>
                              <td> <a class='btn btn-success' href='{{route("beneficiaryProfile",[$item->survey_item,1])}}' style='margin-left:10px;'>View SID: {{$item->survey_item}} </a></td>
                              <td><input type="checkbox" class="row-checkbox" value='{{$item->ref_no}}'></td>
                               <td>{{$item->ref_no ?? 'not available'}}</td>
                               <td>{{$item->beneficiary_name}}</td>
                                <td>{{$item->father_name ?? 'not available'}}</td>
                                <td>{{$item->beneficiary_cnic  ?? 'not available'}}</td>
                                <td>{{$item->marital_status ?? 'not available'}}</td>
                                <td>{{$item->account_number ?? 'not available'}}</td>
                                <td>{{$item->bank_name ?? 'not available'}}</td>
                                <td>{{$item->branch_name ?? 'not available'}}</td>
                                <td>{{$item->bank_address ?? 'not available'}}</td>
                                <td style='visibility:hidden;'>Is Bio Metric Verified</td>

                            </tr>
                         @endif    
                        @endforeach 
                          
                           
                    </tbody>
                </table>
                
                 <div class='row my-4 button_custom'>
                    
                <button class='btn btn-danger' id='confirm_list'>Confirm Checklist</button>
                 
                </div>
            </div>
        </div>
        
        
        
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script>
$(document).ready(function () {
// $('.js-example-basic-multiple').select2();

let selectedItems = []; 

$(document).on('change', '.row-checkbox', function () {
    const value = $(this).val();

    if ($(this).is(':checked')) {
        // Add the value to the array if checked
        if (!selectedItems.includes(value)) {
            selectedItems.push(value);
        }
    } else {
        // Remove the value from the array if unchecked
        selectedItems = selectedItems.filter(item => item !== value);
    }

    // Display the selected IDs
    $('#selected-ids').text(selectedItems.join(', '));
    selected_values = selectedItems.join(", ");
    console.log(selected_values);
});


function selectAllAndStore() {
    // Select all checkboxes
    $('.row-checkbox').each(function () {
        const value = $(this).val();
        $(this).prop('checked', true); 

        if (!selectedItems.includes(value)) {
            selectedItems.push(value);
        }
    });

    // Display the updated selected IDs
    $('#selected-ids').text(selectedItems.join(', '));
    selected_values = selectedItems.join(", ");
}

// Attach the `selectAllAndStore` function to a button click
$(document).on('click', '#select-all-button', function () {
    selectAllAndStore();
    console.log(selected_values);
    });

});     
     
function deselectAllAndClear() {
    // Deselect all checkboxes
    $('.row-checkbox').each(function () {
        const value = $(this).val();
        $(this).prop('checked', false); // Uncheck the checkbox
    });

    // Clear the selectedItems array
    selectedItems = [];

    // Clear the displayed selected IDs
    $('#selected-ids').text('');
    selected_values = "";
}
 $(document).on('click', '#deselect-all-button', function () {
    deselectAllAndClear();
    console.log(selected_values);
});    
     
         

    $('#confirm_list').click(function(){
      console.log(selected_values);

    if(selected_values.length==0){
        alert('kindly select atleast one beneficiary!');
    }else{
    var bank_name= $('#bank_name').val();
    
    var token = $('meta[name="csrf-token"]').attr("content");
      $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{route("save_first_trench_value")}}',
            data: {
                ids: selected_values,
                bank_name: bank_name,
                _token: token,
            },
            success: function (response) {
                console.log(response);
                
                alert('The selected beneficiaries are confirmed.');
            },
            error: function (request, status, error) {
                console.log(error);
                alert("Couldn't retrieve lots. Please try again later.");
            },
        });
    }
  
    });
    
    
    
      
   
    
    
    
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