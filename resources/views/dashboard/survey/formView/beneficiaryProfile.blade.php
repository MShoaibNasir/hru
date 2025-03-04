<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #eef2f3;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
        }

        .profile-header {
            margin-bottom: 20px;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%; 
            border: 3px solid #009CFF;
            margin-bottom: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        h1 {
            font-size: 28px;
            margin: 10px 0;
            color: #333;
        }

        .email {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .data-view {
            text-align: left;
           
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }

        .accordion-header {
            background-color: #009CFF;
            color: white;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 10px;
        }

        .accordion-header:hover {
            background-color: #007bb5;
        }
        

        .accordion-content {
            display: none;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            margin-top: 5px;
        }

        .back-button {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #218838;
        }

        .custom_button {
            background-image: linear-gradient(#0dccea, #0d70ea);
            border: 0;
            border-radius: 4px;
            box-shadow: rgba(0, 0, 0, .3) 0 5px 15px;
            box-sizing: border-box;
            color: #fff;
            cursor: pointer;
            font-family: Montserrat, sans-serif;
            font-size: .9em;
            margin: 5px;
            padding: 10px 15px;
            text-align: center;
            user-select: none;
            margin-top: 16px;
        }
        
        
    .compare_data {
        margin: 20px auto;
        max-width: 800px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background-color: #009CFF;
        color: white;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
    }

    th {
        font-weight: 700;
        font-size: 16px;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

  

    td {
        font-size: 14px;
        color: #333;
    }
    .cnic{
        width:150px;
        height:150px;
    }
    </style>
</head>
<body>
    @php    
    $cnicFront= base64Image(json_decode($required_data['cnicFrontImage']));
    $cnicBack= base64Image(json_decode($required_data['cnicBackImage']));
    $profileImage= base64Image(json_decode($required_data['beneficiaryProfileImage']));
    $beneficiaryInformation=$required_data['beneficiaryInformation'];    
    $bankingInformation=$required_data['bankingInfo'];
    $location_info=$required_data['location_information'];
    $personal_limitation_for_two_persons=$required_data['functional_limitation_two_persons'];   
    $functional_limitation_third_person=$required_data['functional_limitation_third_persons'];
    $functional_limitation_fourth_person=$required_data['functional_limitation_fourth_person'];
    $functional_limitation_fifth_person=$required_data['functional_limitation_fifth_person'];
    $third_person_allow=$required_data['third_person_allow'];
    $fourth_person_allow=$required_data['fourth_person_allow'];
    $fifth_person_allow=$required_data['fifth_person_allow'];
    $Vulnerability=$required_data['Vulnerability'];
    $land_ownership=$required_data['land_ownership'];
    $reconstruction_status=$required_data['reconstruction_status'];
    $house_description=$required_data['house_description'];
    $hazaradous_location=$required_data['hazaradous_location'];
    $other_questions=$required_data['other_questions'];
    $environmental_screening=$required_data['environmental_screening'];
    $upload_docs=$required_data['upload_docs'];
    
   
   
    
    
 
 
    
  
    @endphp
    
    
    
    
    
    

  
    
    
    

    
    

    
    
    
    
    
    
    
    

    
    <div class="container">
        <div class="profile-header">
            <img src="{{$profileImage}}" alt="Profile Image" class="profile-img">
            <h1>Beneficiary Profile</h1>
        </div>
        <div class="data-view">
            <div class="accordion-header" onclick="toggleAccordion(this)">Beneficiary Information</div>
            <div class="accordion-content">
                @foreach($beneficiaryInformation->questions as $item)
                <h4>{{$item->question->name}}</h4>
                <p>{{$item->question->answer ?? 'Not Available'}}</p>
                @endforeach
            </div>
        </div>
        <div class="compare_data">
    <table class="table" style="margin-top:30px; width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th scope="col">Comparison Field</th>
                <th scope="col">NDMA INFO</th>
                <th scope="col">HRU INFO</th>
            </tr>
        </thead>
        <tbody>
            @php
            $i = 0;
            @endphp
            
            @foreach($beneficiary_details_data as $key => $item)
            <tr>
                <th scope="row">{{ $nameOfField[$i] }}</th>
                @php
                    $beneficiaryValue = $beneficiary_details_data[$key] ?? 'not available';
                    $hruValue = $hru_data[$key] ?? 'not available';
                    $color = ($beneficiaryValue === $hruValue) ? 'green' : 'red';
                @endphp
                <td style="color: {{ $color }};">
                    {{ $beneficiaryValue }}
                </td>
                <td style="color: {{ $color }};">
                    {{ $hruValue }}
                </td>
            </tr>
            @php
            $i++;
            @endphp
            @endforeach
        </tbody>
    </table>
</div>
        <div class="data-view">
            <div class="accordion-header" onclick="toggleAccordion(this)">CNIC Images</div>
            <div class="accordion-content">
                <h4>CNIC Front Image</h4>
               <img src="{{$cnicFront}}" alt="CNIC Front Image" class='cnic'>
                <h4>CNIC Back Image</h4>
                <img src="{{$cnicBack}}" alt="CNIC Back Image" class='cnic'>
            </div>
        </div>
        <div class="data-view">
            <div class="accordion-header" onclick="toggleAccordion(this)">{{$bank_info_heading}}</div>
            <div class="accordion-content">
                @foreach($bankingInformation as $item)
                <h4>{{$item->name ?? null}}</h4>
                <p>{{$item->answer ?? 'not available'}}</p>
                @endforeach
            </div>
        </div>
        <div class="data-view">
            <div class="accordion-header" onclick="toggleAccordion(this)">Location Information</div>
            <div class="accordion-content">
                @foreach($location_info->questions as $item)
                <h4>{{$item->question->name ?? null}}</h4>
                @if($item->question->type=='map')
                <ul>
                    @foreach($item->options as $opt)
                    <li>{{$opt->name}} : {{$opt->answer ?? 'not available'}}</li>
                    @endforeach
                </ul>
                @else
                <p>{{$item->question->answer ?? 'not available'}}</p>
                @endif
                @endforeach
            </div>
        </div>
        
        <div class="data-view">
            <div class="accordion-header" onclick="toggleAccordion(this)">Disability Data</div>
            <div class="accordion-content">
                @foreach($personal_limitation_for_two_persons->questions as $item)
                <h4>{{$item->question->name ?? null}}</h4>
                @if($item->question->type=='map')
                <ul>
                    @foreach($item->options as $opt)
                    <li>{{$opt->name}} : {{$opt->answer ?? 'not available'}}</li>
                    @endforeach
                </ul>
                @elseif($item->question->type=='checkbox')
                <ul>
                    @foreach($item->options as $opt)
                    <li>{{$opt->name}} : {{$opt->answer ?? 'not available'}}</li>
                    @endforeach
                </ul>
                @elseif($item->question->type=='image')
                @php
                $image= base64Image(json_decode($item->question->answer));
                @endphp
                 @if(@isset($image))
                 <img src="{{$image}}" alt="CNIC Front Image" class='cnic'>
                 @endif
                @else
               <p>{{ is_array($item->question->answer) ? 'not available' : ($item->question->answer ?? 'not available') }}</p>

                @endif
                @endforeach
                
                <!--disability data for third person-->
                
                
            @if($third_person_allow=='Yes')
            @include('dashboard.survey.disabilityData.show', [
                'personName' => 'Third Person',
                'questions' => $functional_limitation_third_person->questions
            ])
            @endif    
              
           
           
           @if($fourth_person_allow=='Yes')
           
            @include('dashboard.survey.disabilityData.show', [
                'personName' => 'Fourth Person',
                'questions' => $functional_limitation_fourth_person->questions
            ])
            @endif
            
            
             @if($fourth_person_allow=='Yes')
            @include('dashboard.survey.disabilityData.show', [
                'personName' => 'Fifth Person',
                'questions' => $functional_limitation_fifth_person->questions
            ])
            @endif


         

         
          
          
          
            </div>
                      
            <div class="data-view">
            <div class="accordion-header" onclick="toggleAccordion(this)">Vulnerability Data</div>
            <div class="accordion-content">
                <h4>{{$Vulnerability->question->name ?? null}}</h4>
                @if($Vulnerability->question->type=='checkbox')
                <ul>
                    @foreach($Vulnerability->options as $opt)
                    <li>{{$opt->name}} : {{$opt->answer ?? 'not available'}}</li>
                    @endforeach
                </ul>
                @endif
            </div>
            </div>  
        <!--here you show land ownership -->
        
        
        
        
        
        
        <div class="data-view">
            <div class="accordion-header" onclick="toggleAccordion(this)">Land Ownership</div>
            <div class="accordion-content">
                @foreach($land_ownership->questions as $item)
                <h4>{{$item->question->name}}</h4>
                <p>{{$item->question->answer ?? 'Not Available'}}</p>
                @endforeach
            </div>
        </div>
        <div class="data-view">
            <div class="accordion-header" onclick="toggleAccordion(this)">Reconstruction Status</div>
            <div class="accordion-content">
                @foreach($reconstruction_status->questions as $item)
                <h4>{{$item->question->name}}</h4>
                <p>{{$item->question->answer ?? 'Not Available'}}</p>
                @endforeach
            </div>
        </div>
        <div class="data-view">
            <div class="accordion-header" onclick="toggleAccordion(this)">House Description (Pre-floods)</div>
            <div class="accordion-content">
                @foreach($house_description->questions as $item)
                <h4>{{$item->question->name}}</h4>
                <p>{{$item->question->answer ?? 'Not Available'}}</p>
                @endforeach
            </div>
        </div>
        <div class="data-view">
            <div class="accordion-header" onclick="toggleAccordion(this)">Hazardous Location</div>
            <div class="accordion-content">
                @foreach($hazaradous_location->questions as $item)
                <h4>{{$item->question->name}}</h4>
                <p>{{$item->question->answer ?? 'Not Available'}}</p>
                @endforeach
            </div>
        </div>
        <div class="data-view">
            <div class="accordion-header" onclick="toggleAccordion(this)">Other Questions</div>
            <div class="accordion-content">
                @foreach($other_questions->questions as $item)
                <h4>{{$item->question->name}}</h4>
                <p>{{$item->question->answer ?? 'Not Available'}}</p>
                @endforeach
            </div>
        </div>
        <div class="data-view">
            <div class="accordion-header" onclick="toggleAccordion(this)">Environmental Screening</div>
            <div class="accordion-content">
                @foreach($environmental_screening->questions as $item)
                <h4>{{$item->question->name}}</h4>
                <p>{{$item->question->answer ?? 'Not Available'}}</p>
                @endforeach
            </div>
        </div>
        <div class="data-view">
            <div class="accordion-header" onclick="toggleAccordion(this)">Upload Images And Documents</div>
            <div class="accordion-content">
                @foreach($upload_docs->questions as $item)
                <h4>{{$item->question->name}}</h4>
                @if($item->question->type=="image")
                @php
                $image=json_decode($item->question->answer);
                $image=base64Image($image);
                @endphp
                @if(isset($image))
                 <img src="{{$image}}" alt="CNIC Front Image" class='cnic'>
                 @else
                 <p>Not Available</p>
                 @endif
                @endif
                @endforeach
            </div>
        </div>
        
        
      
       
      
            
            
            
            
            
       
        
        
                
                
                
            






        <button class="custom_button" onclick="goBack()">Go Back</button>
    </div>
    
   
    
    

    
        </div>

    <script>
        function goBack() {
            window.history.back();
        }

        function toggleAccordion(header) {
            const content = header.nextElementSibling;
            const isOpen = content.style.display === "block";

            // Close all accordion contents
            const allContents = document.querySelectorAll('.accordion-content');
            allContents.forEach(item => item.style.display = 'none');

            // If it was not open, open it
            if (!isOpen) {
                content.style.display = "block";
            }
        }
    </script>
</body>
</html>
