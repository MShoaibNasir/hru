<!DOCTYPE html>
<html>
<head>
    <title>Housing Reconstruction Unit</title>
    <link href="{{asset('admin/assets/img/logo.jpeg')}}" type="image/x-icon" rel="icon">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-1JHMFQXCEW"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag("js", new Date());
        gtag("config", "G-1JHMFQXCEW");
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        /**Typeo CSS Start (Note if is not need so remove) **/
        @import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap");

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", sans-serif;
            color: #000;
            box-sizing: border-box;
            background-color: #f6f4fe;
        }

        .cw-fix {
            max-width: 800px !important;
            margin: auto;
            width: 100%;
            padding: 40px 0;
        }

        /**Typeo CSS End (Note if is not need so remove) **/
        .cw-section {
            display: flex;
            flex-direction: column;
            row-gap: 2rem;
        }

        .faq-top {
            text-align: center;
        }

        .faq-top p {
            margin: 0;
        }

        .cw-section__title {
            font-size: 2rem;
            text-align: center;
            margin-bottom: 0;
            color: #332470;
        }

        .cw-section__title span {
            color: #6A49F2;
        }

        .cw-accordion {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            row-gap: 1rem;
        }

        .cw-accordion__item {
            padding: 0.8rem 1rem;
            background: #fff;
            position: relative;
            width: 100%;
            cursor: pointer;
            box-shadow: 0 4px 10px #ede9fe;
            border: #cdcdcd 1px solid;
        }

        .cw-accordion__item .cw-label {
            padding-left: 26px;
            font-size: 15px;
            position: relative;
            width: 100%;
            display: inline-block;
            font-weight: 500;
        }

        .cw-accordion__item .cw-label:after {
            position: absolute;
            left: 0;
            content: "+";
            font-size: 16px;
            top: 3px;
            border: #000 1px solid;
            width: 16px;
            height: 16px;
            border-radius: 100%;
            line-height: 1.1;
            text-align: center;
        }

        .cw-accordion__item .cw-label.cw-open:after {
            transform: rotate(45deg);
        }

        .cw-accordion__item .cw-acordion-cont {
            height: 0;
            overflow: hidden;
            transition: 0.4s;
            -webkit-transition: 0.4s;
            font-size: 15px;
            padding-left: 25px;
            padding-right: 25px;
        }

        .cw-accordion__item .cw-acordion-cont ul {
            padding-left: 20px;
        }

        .cw-accordion__item .cw-acordion-cont ul li {
            margin: 7px 0;
        }

        .cw-accordion__item .cw-open + .cw-acordion-cont {
            height: auto;
            padding-top: 15px;
            padding-bottom: 15px;
        }

        .faq-contact {
            text-align: center;
            padding: 10px 0;
        }

        .faq-contact h3 {
            margin: 0 0 15px 0;
        }

        .faq-contact .contact-btn {
            padding: 8px 20px;
            background-color: #000;
            color: #fff;
            text-decoration: none;
            display: inline-block;
            border: #000 1px solid;
            transition: 0.5s all;
            font-weight: 700;
        }

        .faq-contact .contact-btn:hover {
            background-color: transparent;
            color: #000;
        }

        @media(max-width: 992px) {
            .cw-section__title {
                font-size: 1.5rem;
            }
        }

        span.cw-label.cw-open {
            font-weight: 800;
            font-size: 16px;
        }

        p.question {
            font-size: 20px;
            font-weight: 500;
        }
    .create_button {
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
    -webkit-user-select: none;
    touch-action: manipulation;
}
.certified_image {
    width: 100%;
    display: flex;
    justify-content: center;
}
    </style>

    @php
        $form_name = $survey_data->form_name;
        $beneficiary_details=json_decode($survey_data->beneficiary_details);
        
        $survey_data = json_decode($survey_data->survey_form_data);
        $sub_section = $survey_data->sub_sections;
        $array_sectiion = (array) $survey_data->sections;

    @endphp
</head>
<body>
    @php
    $certification_status=\DB::table('form_status')->where('form_id',$survey_form_id)->where('update_by','HRU')->select('certification')->first();
    @endphp
    <button class='create_button' onclick="history.back()">Back</button>
    @if($certification_status && $certification_status->certification==1)
    <div class='certified_image'>
    <img src='{{asset("dashboard/img/certified.png")}}' style='width:200px; height:200px;'>
    </div>
    @endif

    
 <div class="cw-fix">
        <section class="cw-section">
            <div class="faq-top">
                <h2 class="cw-section__title">{{ $form_name }}</h2>
            </div>
           
            <div class="cw-accordion">
                @foreach($array_sectiion as $key => $section)
               
                    @php
                        $questions = $section->questions;
                    @endphp
                    <article class="cw-accordion__item open">
                        <span class="cw-label">{{ $key }}</span>
                        <div class="cw-acordion-cont">
                            @foreach($questions as $ques)
                            
                            
                       
                                @php
                                    $question_name = $ques->question->name ?? null;
                                    
                                    $answer = $ques->question->answer ?? null;
                                @endphp
                                <p class='question'>Question: {{ $question_name }}</p>
                                @if($ques->question->type == 'checkbox')
                                    @php
                                        $options = $ques->options ?? null;
                                    @endphp
                                    @if($options != null)
                                        <ul>
                                            @foreach($options as $option)
                                                @if($option->answer == 'selected')
                                                    <li>{{ $option->name }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                @php
                             
                                @endphp
                                
                                @elseif($ques->question->type == 'map')
                                    @php
                                        $options = $ques->options ?? null;
                                    @endphp
                                    @if($options != null)
                                        <ul>
                                            @foreach($options as $option)
                                                <li>{{ $option->name }} : {{ $option->answer }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    
                              
                                @elseif($ques->question->type == 'image')
                               @php  
                               if(isset($ques->question->answer)){
                               $image_data=json_decode($ques->question->answer);
                               $base64Image = $image_data->image->base64;
                               if(isset($image_data->image->type)){
                               $imageType = $image_data->image->type;
                               }
                               if(isset($image_data->image->mime)){
                               $imageType = $image_data->image->mime;
                               }
                               
                               $dataUri = 'data:' . $imageType . ';base64,' . $base64Image;
                               }else{
                               $dataUri=null;
                               $image_data=null;
                               }
                              @endphp
                         
                         
                              @if(isset($dataUri))
                              <div class='row' style='width: 100%; display: flex; align-items: center;'>
                                <div class='col-6' style='width:50%;'>
                              <img src='{{$dataUri}}' style='width:200px; height:200px;'>
                                </div>  
                                <div class='col-6' style='width:50%;'>
                                    <h3>Coordinates:-</h3>
                                    <p>Longitude: {{$image_data ? ($image_data->fetchLocation->longitude ?? 'not available') : 'not available'}} </p>
                                    <p>Accuracy {{$image_data ? ($image_data->fetchLocation->accuracy ?? 'not available') : 'not available'}}</p>
                                    <p>Latitude {{$image_data ? ($image_data->fetchLocation->latitude ?? 'not available') : 'not available' }}</p>
                                    <p>Altitude {{$image_data ? ($image_data->fetchLocation->altitude ?? 'not available' ) : 'not available'}}</p>
                                </div>
                              </div> 
                              @else
                              <div class='row'>
                                  <p>Not available</p>
                              </div>
                              
                              @endif
                                @else
                                    <p class='answer'>Answer: {{ $answer ?? 'not available' }}</p>
                                    @if($ques->options != null)
                                        @php
                                            $options_id = [];
                                            foreach($ques->options as $option_id) {
                                                $options_id[] = $option_id->option_id;
                                            }
                                        @endphp
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </div>
            <div class='row'>
                <div class='col-6'>
               <Button class='create_button' data-bs-toggle="modal" data-bs-target="#exampleModal">Compare PDMA Data</Button>
            </div>    
            </div>    
        </section>
    </div>

    
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Compare PDMA Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table">
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
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
    
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script>
        let items = document.querySelectorAll(".cw-accordion .cw-accordion__item .cw-label");
        items.forEach(function(t) {
            t.addEventListener("click", function(e) {
                items.forEach(function(e) {
                    e !== t || e.classList.contains("cw-open")
                        ? e.classList.remove("cw-open")
                        : e.classList.add("cw-open");
                });
            });
        });
        
    
    </script>
</body>
</html>
