var base_url= 'https://mis.hru.org.pk/';
localStorage.setItem("check_of_value_type",false);
// var base_url= localStorage.getItem('app_url');

function show_range_limit(id){
var value= $(`#type_option_value${id}`).val();
if(value=='number'){
 $(`#range_limit_for_option${id}`).css('display','block');
}
}



function add_options(i) {
    var select_location_type_value_for_dynamic_options=select_location_type.value;
    var showLocalStorageList=false;
    var localstorage_list_data='';
    var ListHTML='';
    if(select_location_type_value_for_dynamic_options=='lot'){
        var ListHTML = $('#lots_list').html();     
    }
    else if(select_location_type_value_for_dynamic_options=='district'){    
          var ListHTML = $('#district_list').html(); 
    }
    else if(select_location_type_value_for_dynamic_options=='tehsil'){    
          var ListHTML = $('#tehsil_list').html(); 
    }
    else if(select_location_type_value_for_dynamic_options=='uc'){    
          var ListHTML = $('#uc_list').html(); 
    }
    else if(select_location_type_value_for_dynamic_options=='zone'){    
          var ListHTML = $('#zone_list').html(); 
    }
    
    if(ListHTML !== null && ListHTML.trim() !== ''){    
    localStorage.setItem('list_data', ListHTML);
    localstorage_list_data=localStorage.getItem('list_data');  
    }
    
    if (localstorage_list_data !== null && localstorage_list_data.trim() !== '') {
        showLocalStorageList=true;
    } else {
        showLocalStorageList=false;
    }
    
    var check_of_value_type=localStorage.getItem('check_of_value_type');
    var add_more_option = document.getElementById("add_more_option_" + i);
    var j = i + 1;
    var html = `
        <div class="col-12 d-flex justify-content-end">
            <a class="btn btn-danger" id="add_options_${j}" onclick="add_options(${j})">Add Options</a>
        </div>
        <div class="row">
        `
            if(showLocalStorageList){
            html+=`<div class="mb-3 col-6">`;
            html+=localstorage_list_data;
            html+=`</div>`;
            }else{
                
            html+=`<div class="mb-3 col-6">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" name="name[]" required>
            </div>`;
                
            }
             if(check_of_value_type && check_of_value_type=='true'){
              html+= `
             <div class="mb-3 col-4">
                <label class="form-label">Type</label>
                <select name="type[]" class="form-control" required>
                <option value="">Select Type</option>
                <option value="text">Text</option>
                <option value="number">Number</option>
                </select>
            </div>`;
             } else{
               html+= `
             <div class="mb-3 col-4">
                <label class="form-label">Type</label>
                <select name="type[]" class="form-control" id='type_option_value${j}' required onchange='show_range_limit(${j})'>
                <option value="">Select Type</option>
                <option value="radio">Radio Button</option>
                <option value="text">Text</option>
                <option value="number">Normal Number</option>
                <option value="image">Image</option>
                <option value="file">File</option>
                <option value="date">Date</option>
                <option value="counter">Counter</option>
                <option value="checkbox">Checkbox</option>
                <option value="cnic_number">Cnic Number</option>
                <option value="mobile_number">Mobile Number</option>
                </select>
            </div>
              <div class="mb-3 col-6" style='display:none;' id='range_limit_for_option${j}'>
                                <label class="form-label">Do you want to set range for this number?</label>
                                <input type='number' class='form-control' name='range_number[]'>
                            </div>
            
            
            
            
            `;
             }
            //  checking for mapping option
            if(check_of_value_type && check_of_value_type=='true'){
           html += ` <div class="row">
                            <div class="mb-3 col-6" id="map_value_check" style="display: flex; flex-direction: column;">
                                <label class="form-label">Select Value Type</label>
                                <select class="form-control" name="variable_type[]" required>
                                    <option value="">Select</option>
                                    <option value="latitude">Latitude</option>
                                    <option value="longitude">Longitude</option>
                                    <option value="altitude">Altitude</option>
                                    <option value="accuracy">Accuracy</option>
                                </select>
                            </div>`; }
                        html +=` <div class="mb-3 col-2" style='margin-top:33px;'>
            <a class="btn btn-danger" onclick="remove_option(${j})">-<a/>
            </div>
            </div>
           
            <div id="add_more_option_${j}"></div>
        </div>`;
    
    add_more_option.innerHTML = html;
}
function remove_option(id) {
    id = id - 1;
    var add_options = document.getElementById(`add_more_option_${id}`);
    add_options.innerHTML = '';
}



function showQuestion(id) {
    var token = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        type: "GET",
        dataType: "json",
        url: `${base_url}admin/question/filter`,
        data: {
            question_id: id,
            _token: token,
        },
        success: function (response) {
            console.log(response);
            $('#question_name').val(response.name);
        },
        error: function (request, status, error) {
            console.log(error);
            alert("Couldn't retrieve lots. Please try again later.");
        },
    });
}
function show_related_question() {
   
    var token = $('meta[name="csrf-token"]').attr("content");
    var option_id=$('#option_id').val();
    console.log(option_id);
    
    if(option_id==null || option_id==''){
        alert("plz select any option");
    }else{
        $.ajax({
            type: "GET",
            dataType: "json",
            url: `${base_url}admin/question/related`,
            data: {
                option_id: option_id,
                _token: token,
            },
            success: function (response) {
                console.log(response);
                $('#question_name_for_show').val(response.name);
            },
            error: function (request, status, error) {
                console.log(error);
                alert("Couldn't retrieve lots. Please try again later.");
            },
        });
    }

    
}


$(document).ready(function(){
    $('#option_type').change(function(){
        var value_of_option= $(this).val();
        if(value_of_option=='number'){
            $('#range_limit_for_option').css('display','block');
        }else{
            $('#range_limit_for_option').css('display','none');
        }
        if(value_of_option=='text' || value_of_option=='number'){
            $('#map_option_check').css('display','flex');
            
        }
        else{
            $('#map_option_check').css('display','none');
        }
        
    })
    $('#check_of_value_type').change(function(){
        var isChecked = $(this).is(":checked");
        
        if(isChecked===true){
            localStorage.setItem("check_of_value_type",true);
            $('#map_value_check').css({'display':'flex','flex-direction':'column'});
        }else{
            localStorage.setItem("check_of_value_type",false);
            $('#map_value_check').css('display','none');
        }
        
    })
    $('#check_of_question_related_type').change(function(){
        var isChecked = $(this).is(":checked");
       
        if(isChecked===true){
            $('#related_question').css({'display':'flex'});
            $('#question_value_check').css({'display':'flex','flex-direction':'column'});
        }else{
            $('#related_question').css('display','none');
            $('#question_value_check').css('display','none');
        }
        
    })
})
$('#question_type').change(function(){
  
    var val_of_question_type=$(this).val();
    
    if(val_of_question_type=='number'){
        
        $("#range_limit").css("display","block");
    }
})





$('#select_location_type').change(function(){
    var select_location_type=$(this).val();
    
    const lots_list=$('#lots_list');
    const district_list=$('#district_list');
    const tehsil_list=$('#tehsil_list');
    const uc_list=$('#uc_list');
    const option_name=$('#option_name');
    const zone_list=$('#zone_list');
    if(select_location_type=='lot'){
        showAccordingToCondition(lots_list,option_name,district_list,tehsil_list,uc_list,zone_list);
    }
    if(select_location_type=='district'){
        showAccordingToCondition(district_list,option_name,lots_list,tehsil_list,uc_list,zone_list);
    }
    if(select_location_type=='tehsil'){
        showAccordingToCondition(tehsil_list,option_name,district_list,lots_list,uc_list,zone_list);
    }
    if(select_location_type=='uc'){
        showAccordingToCondition(uc_list,option_name,district_list,lots_list,tehsil_list,zone_list);
    }
    if(select_location_type=='zone'){
        showAccordingToCondition(zone_list,uc_list,option_name,district_list,lots_list,tehsil_list);
    }
    if(select_location_type=='name'){
        showAccordingToCondition(option_name,uc_list,district_list,lots_list,tehsil_list,zone_list,input_type=true);
    }
})



function showAccordingToCondition(showElement,firstBlockElement,secondBlockElement,thirdBlockElement,fourthBlockElement,fifthBlockElement,input_type=false){
    
    if(input_type==true){
    firstBlockElement.hide().find('select').prop('disabled', true);
    showElement.show().find('input').prop('disabled', false);
    }else{
    firstBlockElement.hide().find('input').prop('disabled', true);
     showElement.show().find('select').prop('disabled', false);
    }
    secondBlockElement.hide().find('select').prop('disabled', true);
    thirdBlockElement.hide().find('select').prop('disabled', true);
    fourthBlockElement.hide().find('select').prop('disabled', true);
    fifthBlockElement.hide().find('select').prop('disabled', true);
   

}



