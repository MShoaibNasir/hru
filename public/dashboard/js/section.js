// var base_url = localStorage.getItem("app_url");
var base_url= 'https://mis.hru.org.pk/';
$(document).ready(function () {
    // filter lots
    $("#select_option").change(function () {
        var option_id = $("#select_option").val();
     
        
        console.log(`${base_url}question/title/filter_question`);
        var token = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            type: "GET",
            url: `${base_url}admin/question/title/filter_question`,
            data: {
                option_id: option_id,
                _token: token,
            },
            success: function (response) {
                $('#filter_question').val(response.question.name);
                $('#filter_title').val(response.question_title.name);
                $('#filter_form').val(response.form.name);
            },
            error: function (request, status, error) {
                console.log(error);
                alert("Couldn't retrieve lots. Please try again later.");
            },
        });
    });
});



$(document).ready(function(){
    $('#check_to_show_sub_section').change(function(){
        var isChecked = $(this).is(":checked");
        if(isChecked==true){
            $('#show_subsection').css('display','flex');
        }else{
            $('#show_subsection').css('display','none');
        }
        
    })
})



