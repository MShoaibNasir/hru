        var base_url= 'https://mis.hru.org.pk/';
        function reload_window(){
            window.location.reload();
        }
    
    $('.form_status').change(function(){ 
        var confirmation=confirm('are you sure!');
        if(confirmation==true){     
        var token = $('meta[name="csrf-token"]').attr("content");
        var form_status= $(this).val();
        var team_member_status= $('#team_member_status').val();
        var is_m_and_e= $('#is_m_and_e').val();
        var survey_form_id = $(this).closest('tr').find('.survey_form_id').val();
        var update_by = $(this).closest('tr').find('.update_by').val();
        
        if( form_status=='P' || form_status=='H'){
        $.ajax({
            type: "GET",
            url: `${base_url}update/form/status`,
            data: {
                form_status: form_status,
                survey_form_id: survey_form_id,
                team_member_status: team_member_status,
                update_by: update_by,
                is_m_and_e: is_m_and_e,
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
                title: "You update form status successfully!"
            });
            },
            error: function (request, status, error) {
                
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error,
                toast: true,         // This enables the toast mode
                position: 'top-end', // Position of the toast
                showConfirmButton: false, // Hides the confirm button
                timer: 3000          // Time to show the toast in milliseconds
            });
                
                
                // alert("Couldn't retrieve lots. Please try again later.");
            },
        });
        }
        else if(form_status=='R' || form_status=='A'){
             let comment = prompt("Enter Comment About Your Decision");
             if(comment){
             $.ajax({
            type: "GET",
          
            url: `${base_url}update/form/status`,
            data: {
                form_status: form_status,
                survey_form_id: survey_form_id,
                team_member_status: team_member_status,
                comment: comment,
                _token: token,
                update_by: update_by,
                is_m_and_e: is_m_and_e,
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
                title: "You update form status successfully!"
            });
            },
            error: function (request, status, error) {
                
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error,
                toast: true,         // This enables the toast mode
                position: 'top-end', // Position of the toast
                showConfirmButton: false, // Hides the confirm button
                timer: 3000          // Time to show the toast in milliseconds
            });
                
                
                // alert("Couldn't retrieve lots. Please try again later.");
            },
        });
            
             }
             
        }
        
        }
        else{
            window.location.reload();
        }
        
        
    })
//})









      function view_beneficiary(id){
       var token = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
        type: "GET",
        url: `${base_url}beneficiary/details`,
        data: {
            id: id,
            _token: token,
        },
        success: function (response) {
            // console.log(response);
            $('#beneficiary_name').val(response.beneficiary_name);
            $('#beneficiary_address').val(response.address);
            $('#beneficiary_cnic').val(response.cnic);
            $('#beneficiary_refrence_number').val(response.b_reference_number);
         
        },
        error: function (request, status, error) {
            console.log(error);
            alert("Couldn't retrieve lots. Please try again later.");
        },
    });
        }
        
        
      function Add_to_priority(id,status){
        console.log('id'+id);
        console.log('status'+status);
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
        
      function showComment(comment){
          alert(comment);
      }
      
      
      
//     function changeStatus(formStatus=null,is_m_and_e=null) {
//     var confirmation = confirm('are you sure!');
//     if (confirmation == true) {
//         var token = $('meta[name="csrf-token"]').attr("content");
//         var form_status = formStatus;
//         var team_member_status = $('#team_member_status').val();
//         var is_m_and_e = is_m_and_e;
//         var survey_form_id = $('#survey_form_id').val();
//         var update_by = $('#updated_by').val();
//         if ( form_status == 'P' || form_status == 'H') {
//             $.ajax({
//                 type: "GET",
//                 url: `${base_url}update/form/status`,
//                 data: {
//                     form_status: form_status,
//                     survey_form_id: survey_form_id,
//                     team_member_status: team_member_status,
//                     update_by: update_by,
//                     is_m_and_e: is_m_and_e,
//                     _token: token,
//                 },
//                 success: function (response) {
//                     console.log(response);

//                     const Toast = Swal.mixin({
//                         toast: true,
//                         position: "top-end",
//                         showConfirmButton: false,
//                         timer: 3000,
//                         timerProgressBar: true,
//                         didOpen: (toast) => {
//                             toast.onmouseenter = Swal.stopTimer;
//                             toast.onmouseleave = Swal.resumeTimer;
//                         }
//                     });
//                     Toast.fire({
//                         icon: "success",
//                         title: "You update form status successfully!"
//                     });
//                     setTimeout(redirectLocation, 3000)
//                 },
//                 error: function (request, status, error) {

//                     Swal.fire({
//                         icon: 'error',
//                         title: 'Oops...',
//                         text: error,
//                         toast: true,         // This enables the toast mode
//                         position: 'top-end', // Position of the toast
//                         showConfirmButton: false, // Hides the confirm button
//                         timer: 3000          // Time to show the toast in milliseconds
//                     });


//                     // alert("Couldn't retrieve lots. Please try again later.");
//                 },
//             });
//         }
//         else if (form_status == 'R' || form_status == 'A' ) {
//             $("#commentFormStatus").val(form_status);
//             $('#commentWindow').modal('show');
//             // let comment = prompt("Enter Comment About Your Decision");
//             // if (comment) {
//             //     $.ajax({
//             //         type: "GET",

//             //         url: `${base_url}update/form/status`,
//             //         data: {
//             //             form_status: form_status,
//             //             survey_form_id: survey_form_id,
//             //             team_member_status: team_member_status,
//             //             comment: comment,
//             //             _token: token,
//             //             update_by: update_by,
//             //             is_m_and_e: is_m_and_e,
//             //         },
//             //         success: function (response) {
//             //             console.log(response);
//             //             const Toast = Swal.mixin({
//             //                 toast: true,
//             //                 position: "top-end",
//             //                 showConfirmButton: false,
//             //                 timer: 3000,
//             //                 timerProgressBar: true,
//             //                 didOpen: (toast) => {
//             //                     toast.onmouseenter = Swal.stopTimer;
//             //                     toast.onmouseleave = Swal.resumeTimer;
//             //                 }
//             //             });
//             //             Toast.fire({
//             //                 icon: "success",
//             //                 title: "You update form status successfully!"
//             //             });
//             //             setTimeout(redirectLocation, 3000)
//             //         },
//             //         error: function (request, status, error) {

//             //             Swal.fire({
//             //                 icon: 'error',
//             //                 title: 'Oops...',
//             //                 text: error,
//             //                 toast: true,         // This enables the toast mode
//             //                 position: 'top-end', // Position of the toast
//             //                 showConfirmButton: false, // Hides the confirm button
//             //                 timer: 3000          // Time to show the toast in milliseconds
//             //             });


//             //             // alert("Couldn't retrieve lots. Please try again later.");
//             //         },
//             //     });

//             // }

//         }
        
            
//     }
//     else {
//         window.location.reload();
//     }
    
//     function redirectLocation(){
//         window.location.href = 'https://mis.hru.org.pk/admin/survey/pending';
//     }
    
  
// }
