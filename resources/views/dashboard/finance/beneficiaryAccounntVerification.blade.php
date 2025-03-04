@extends('dashboard.layout.master')
@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
.download_btn {
    width: 100%;
    display: flex;
    justify-content: end;
}
   
button.btn.btn-danger {
    width: 200px;
    margin-left: 5px;
}
a.btn.btn-success {
width: 143px;
height: auto;

}
#confirm_list{
    width:200px;
    margin-left:25px;
}
.bank_heading {
    display: flex;
}
h6.bank_heading {
    margin: 0;
    padding: 0;
}
form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.search_btn{
    width:100px;
}
.buttons {
    display: flex;
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
                    <h6 class="mb-4">Beneficiaries Verified Account List</h6>
                 
                </div>
            </div>
        </div>
       
        
         <div class="bg-light text-center rounded p-4">
            <div class='row'>
                 <a class='btn btn-success my-4' style='margin-left:20px;' href='{{route("get_export_beneficiary_account")}}' id='generated_button'>Generate Sheet</a>
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
         <div class='col-6 my-4'>
                <form method='get' action='{{route("beneficiaryAccounntVerification")}}'>
                    @csrf
                <h5 class='bank_heading'>Search By Bank</h5>
                <select class='form-control js-example-basic-multiple' name='search_by_bank[]' multiple="multiple">
                    <option value=''>Select Bank</option>
                    @foreach($bank_names as $item)
                    <option value='{{$item}}'>{{$item}}</option>
                    @endforeach
                </select>
                 <input type='submit' value='search'  class='btn btn-danger search_btn'>
                </form>
            </div> 
            <div class='buttons'>
            <button id="select-all-button" class='btn btn-success btn-sm'>Select All</button>
            <button id="deselect-all-button" class='btn btn-danger btn-sm'>Deselect All</button>
            </div> 
             
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0" id='finance'>
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">S.No</th>
                            <th scope="col">View Form</th>
                             <th scope="col">Select Beneficairy</th>
                            <th scope="col">BENEFICIARY FULL NAME</th>
                            <th scope="col">BENEFICIARY FATHER'S/HUSBAND NAME</th>
                            <th scope="col">CNIC/ID NUMBER</th>
                            <th scope="col">MARITAL STATUS</th>
                            <th scope="col">ACCOUNT NUMBER</th>
                            <th scope="col">BANK NAME</th>
                            <th scope="col">BRANCH NAME</th>
                            <th scope="col">BANK ADDRESS</th>
                          
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($form as $item)
                        @php
                         $beneficiary=json_decode($item->beneficiary_details);
                         $marital_status=get_answer(656,$item->survey_id);
                         $account_number=get_answer(250,$item->survey_id);
                         $bank_name=get_answer(251,$item->survey_id);
                         $branch_name=get_answer(252,$item->survey_id);
                         $bank_address=get_answer(253,$item->survey_id);
                        @endphp
                        
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td><a class='btn btn-success' href='{{route("beneficiaryProfile",[$item->survey_id])}}' style='margin-left:10px;'>View SID: {{$item->survey_id}} </a></td>
                                 <td><input type="checkbox" class="row-checkbox" value='{{$item->ref_no}}'></td>
                                <td>{{$item->beneficiary_name ?? 'not available'}}</td>
                                <td>{{$beneficiary->father_name ?? 'not available'}}</td>
                                <td>{{$item->beneficiary_cnic ?? 'not available'}}</td>
                                <td>{{$marital_status->answer ?? 'not available'}}</td>
                                <td>{{$account_number->answer ?? 'not available'}}</td>
                                <td>{{$bank_name->answer ?? 'not available'}}</td>
                                <td>{{$branch_name->answer ?? 'not available'}}</td>
                                <td>{{$bank_address->answer ?? 'not available'}}</td>
                                
                            </tr>
                        
                        @endforeach
                    </tbody>
                </table>
               
                <div class='row my-4 button_custom'>
                <button class='btn btn-danger' id='confirm_list'>Confirm Checklist</button>
            </div>
            </div>
        </div>
        
        
        
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>

       <script>
$(document).ready(function () {
$('.js-example-basic-multiple').select2();

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