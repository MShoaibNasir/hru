@include('dashboard.layout.header')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.min.css">
<style>
    .form_width {
        padding-left: 3.5rem !important;
    }

    .back_button {
        background-color: rgba(51, 51, 51, 0.05);
        border-radius: 8px;
        border-width: 0;
        color: #333333;
        cursor: pointer;
        display: inline-block;
        font-family: "Haas Grot Text R Web", "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 14px;
        font-weight: 500;
        line-height: 20px;
        list-style: none;
        margin: 0;
        padding: 10px 12px;
        text-align: center;
        transition: all 200ms;
        vertical-align: baseline;
        white-space: nowrap;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
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

    a:hover {
        color: white;
    }
    .dt-search {
    display: flex !important;
    justify-content: end  !important;
    align-items: center  !important;
}
    .content .navbar .sidebar-toggler, .content .navbar .navbar-nav .nav-link i {

    color: var(--primary);
}

a.dt-button.buttons-csv.buttons-html5 span {
    background: black !important;
    color: #fff !important;
    padding: 6px !important;
    margin-bottom:10px;
}

a.dt-button.buttons-csv.buttons-html5 {
    display: flex !important;
    justify-content: end !important;
    width: 96% !important;
}
.alert-success {
    color: #0f5132;
    background-color: #d1e7dd;
    border-color: #badbcc;
    position: absolute;
    right: 9px;
    top: 83px;
}
</style>
@stack('ayiscss')
@php
    $url_value = env('APP_URL');
@endphp
<input type="hidden" id="app_url" value="{{$url_value}}">

@yield('content')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.3/js/dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
    var app_url = $('#app_url').val();
    localStorage.setItem('app_url', app_url);
    
    
$('#myTable').DataTable({
    processing: true,
    pageLength: 10, // Default number of rows to display per page
    lengthMenu: [10, 25, 50, 100], // Options for rows per page

    dom: 'Bfrtip', // Ensure the layout includes the buttons and the length menu
    buttons: [
        {
            extend: 'csv',
            text: 'Download CSV',
            title: 'HRU' // Optional: Set a title for the downloaded CSV
        }
    ]
});


// $('#finance').DataTable({
//     processing: true,
//     pageLength: 500, // Default number of rows to display per page
//     lengthMenu: [10, 25, 50, 100], // Options for rows per page

//     dom: 'Bfrtip', // Ensure the layout includes the buttons and the length menu
//     buttons: [
//         {
//             extend: 'csv',
//             text: 'Download CSV',
//             title: 'HRU' // Optional: Set a title for the downloaded CSV
//         }
//     ]
// });



$(document).ready(function() {
    let myTable = $('#finance').DataTable({
        columnDefs: [{
            orderable: false,
            className: 'select-checkbox',
            targets: 0,
        }],
        select: {
            style: 'os', // 'single', 'multi', 'os', 'multi+shift'
            selector: 'td:first-child',
        },
        order: [
            [1, 'asc'],
        ],
    });

    $('#MyTableCheckAllButton').click(function() {
        if (myTable.rows({
                selected: true
            }).count() > 0) {
            myTable.rows().deselect();
            return;
        }

        myTable.rows().select();
    });

    myTable.on('select deselect', function(e, dt, type, indexes) {
        if (type === 'row') {
            // We may use dt instead of myTable to have the freshest data.
            if (dt.rows().count() === dt.rows({
                    selected: true
                }).count()) {
                // Deselect all items button.
                $('#MyTableCheckAllButton i').attr('class', 'far fa-check-square');
                return;
            }

            if (dt.rows({
                    selected: true
                }).count() === 0) {
                // Select all items button.
                $('#MyTableCheckAllButton i').attr('class', 'far fa-square');
                return;
            }

            // Deselect some items button.
            $('#MyTableCheckAllButton i').attr('class', 'far fa-minus-square');
        }
    });
});






$('#complaintTable').DataTable( 
    {
        order: [[ 0, 'desc' ]],
        processing: true,
        dom: 'Bfrtip', 
         buttons: [{
     extend : 'csv',
     text: 'Download CSV',
     title : "Complaints",
     exportOptions: {
               columns: [0, 1,2,3,4,5,6,7,8]
          }
 }] 
    });


   

</script>
@stack('ayisscript')
@include('dashboard.layout.footer')