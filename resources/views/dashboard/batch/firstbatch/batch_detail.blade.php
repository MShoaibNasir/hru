<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Letter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .logo {
            width: 100px;
            height: auto;
        }
       .remove_spacing {
        margin: 0;
        }
        
        .under_line {
        text-decoration: underline;
        }
        td {
        text-align: left;
        }
        .footer_text{
            font-size:13px;
        }
     
        
        @media print {
           .signature_margin {
                    margin-top: 120px !important;
                }
}
    /*        @media print {*/
    /*    @page {*/
    /*        size: A4;*/
    /*        margin: 20mm;*/
    /*    }*/

    /*    body {*/
    /*        font-size: 12pt;*/
    /*        line-height: 1.5;*/
    /*    }*/

    /*    .container {*/
    /*        width: 100%;*/
    /*        padding: 0;*/
    /*        margin: 0;*/
    /*    }*/

    /*     Centering the content for A3 */
    /*    .d-flex {*/
    /*        justify-content: space-between;*/
    /*    }*/

    /*    .logo {*/
    /*        width: 120px;  */
    /*    }*/

    /*    h4 {*/
    /*        font-size: 22pt;*/
    /*        text-align: center;*/
    /*    }*/

    /*    p {*/
    /*        font-size: 12pt;*/
    /*        text-align: center;*/
    /*    }*/

    /*    table {*/
    /*        width: 100%;*/
    /*        font-size: 10pt;*/
    /*        border-collapse: collapse;*/
    /*    }*/

    /*    th, td {*/
    /*        padding: 8px;*/
    /*        border: 1px solid #000;*/
    /*    }*/

    /*    .row {*/
    /*        display: flex;*/
    /*        justify-content: space-between;*/
    /*    }*/

    /*    .col {*/
    /*        width: 48%;*/
    /*        text-align: center;*/
    /*    }*/

    /*     Adjust the footer for A3 layout */
    /*    .text-center {*/
    /*        font-size: 12pt;*/
    /*    }*/
    /*}*/
    </style>
</head>

<body>
    <div class="container mt-5">
        <!-- Header Section with Two Logos -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <img src="{{asset('admin/assets/img/logo2.jpeg')}}" alt="Left Logo" class="logo">
            <div class="text-center">
                <h4>Housing Reconstruction Unit,Balochistan (IFRAP)</h4>
                <p class="mb-1">Ministry of Planning, Development & Special Initiatives</p>
                <p class="mb-1">hru.org.pk contact: +92812846555 , 081-2081372</p>
                
            </div>
            <img src="{{asset('admin/assets/img/logo1.jpeg')}}" alt="Right Logo" class="logo">
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <span>IFRAP-HRU/2024-25/ {{$batch->batch_no}}</span>
            <span>Dated: {{$batch->batch_created_date}}</span>
        </div>
        @php
        $get_bank_name=\DB::table('bank')->where('id',$batch->bank_id)->select('name')->first();
        $total_amount=0;
        foreach($form as $data){
          $total_amount +=$amount;
         }
        $total_amount_in_words=numberToWords($total_amount);
        @endphp

        <!-- Address Section -->
        <p class="mb-1">To</p>
        <p class="fw-bold remove_spacing">Branch Manager</p>
        <p class='fw-bold remove_spacing'>{{$get_bank_name->name}}</p>
        <p class='fw-bold remove_spacing'>Quetta, Pakistan</p>
        <br>

        <!-- Subject Section -->
        <p class="fw-bold under_line">Subject: TRANSFER OF AMOUNT TO BENEFICIARIES ACCOUNTS MAINTAINED WITH BANK NAME.</p>

        <p>Reference to the subject above, it is stated that Cheque No. <strong>{{$batch->cheque_no}}</strong> Dated <strong>{{$batch->batch_created_date}}</strong> Amounting to <strong>{{$total_amount_in_words}} rupees ({{number_format($total_amount)}}) </strong>) is issued to be transferred to the following beneficiaries' accounts. Necessary details of beneficiaries are provided below:</p>

        <!-- Table Section -->
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>S. No</th>
                    <th>Refrence No</th>
                    <th>Account Title</th>
                    <th>CNIC Number</th>
                    <th>Account No</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                
                @foreach($form as $data)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$data->survey_ref_no}}</td>
                    <td>{{$data->beneficiary_name}}</td>
                    <td>{{$data->beneficiary_cnic}}</td>
                    <td>{{$data->beneficiary_account_number}}</td>
                    <td>{{number_format(100000) }}</td>
                  
                </tr>
                @endforeach
               
              
                <tr>
                    <td colspan="4" class="fw-bold">Total Amount</td>
                    <td class="fw-bold">{{number_format($total_amount)}}/-</td>
                </tr>
            </tbody>
        </table>
        

     
        
        <div class="row mt-5 signature_margin">
    <div class="col text-center">
        <p class="fw-bold remove_spacing">Mr. Muhammad Idrees</p>
        <p class="remove_spacing">Financial Management Specialist</p>
        <p class="remove_spacing">Housing Reconstruction Unit</p>
    </div>
    <div class="col text-center">
        <p class="fw-bold remove_spacing">Mr. Rashid Razzaq</p>
        <p class="remove_spacing">Project Director</p>
        <p class="remove_spacing">Housing Reconstruction Unit</p>
    </div>
</div>

<!-- Add separating line and more space -->
<hr style="margin-top: 350px; margin-bottom: 10px; border: 1px solid #000;">

<!-- Footer Section -->
<div class="footer" >
    <p class="text-center mb-0 footer_text">Housing & Reconstruction Unit Balochistan. House No. 57-A. Chaman Housing Scheme. Airport Road Quetta. </p>
    <p class="text-center footer_text"> Sheet generated from MIS (HRU). </p>
</div>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
