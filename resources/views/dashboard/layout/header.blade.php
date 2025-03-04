<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Housing Reconstruction Unit</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <!-- Favicon -->
    <style>
        a.nav-link.dropdown-toggle {
            font-size: 14px;
        }

        .sidebar {
            width: 298px !important;
        }
        

        .form_width {
            padding-left: 3.5rem !important;
        }

        .navbar-light .navbar-brand {
            color: rgba(0, 0, 0, 0.9);
            width: 100%;
            display: flex;
            justify-content: center;
            /* border-radius: 22%; */
            /* border: 2px solid; */
        }
    </style>
    @include('dashboard.layout.css')
</head>

<body>
<!--Preloader-->
    <div id="hrupreloader">
        <div id="loader" class="loader">
            <div class="loader-container">
                <div class="loader-icon"><img src="https://mis.hru.org.pk/admin/assets/img/logo.jpeg" alt="Preloader"></div>
            </div>
        </div>
    </div>
<!--Preloader-end -->    
    
    
    <div class="container-xxl position-relative  d-flex p-0">
        <!-- Sidebar Start -->
        @php
            $current_user = Auth::user();
            if ($current_user) {
                $allow_access = DB::table('users')
                    ->join('roles', 'users.role', '=', 'roles.id')
                    ->where('users.id', '=', $current_user->id)
                    ->first();
                 
            }
        @endphp
        @include('dashboard.layout.admin_sidebar')

        <!-- Sidebar End -->