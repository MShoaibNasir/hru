@extends('dashboard.layout.master')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="{{asset('dashboard/css/breadcrumbs.css')}}" rel="stylesheet">  

<!-- Content Start -->
<div class="content">
    
  
    <!-- Navbar Start -->
     @include('dashboard.layout.navbar')
 
    <!-- Navbar End -->
        @php
        $current_user = Auth::user();
        if ($current_user) {
            $allow_access = allow_access($current_user->id);
        }
        $form_management = json_decode($allow_access->form_management);
        array_unshift($form_management, 0);
       
       
    @endphp

    <div class="container-fluid pt-4 px-4 form_width">
        
        
         <nav role="navigation" aria-label="Breadcrumb">
            <ol itemscope itemtype="">
                <li itemprop="itemListElement" itemscope itemtype="#">
                    <a href="{{route('form.list')}}" itemprop="item" >
                        <span itemprop="name">{{$form_name->name}}</span>
                    </a>
                    <meta itemprop="position" content="1" />
                </li>
            </ol>
        </nav>
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="mb-0">Section List</h2>
                @if($form_management && $form_management[16] == 46)
                <a href="{{route('question.title.create',[$form_id])}}" class="create_button">Create Section</a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">S no</th>
                            <th scope="col">Manage Sequence</th>
                            <th scope="col">Section Name</th>
                            <th scope="col">Sub Heading</th>
                            @if($form_management &&  ($form_management[3] == 33 || $form_management[5] == 35))
                            <th scope="col">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                  
                        @foreach($question_titles as $item)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td><a class='btn btn-success btn-sm' style='margin-right:4px;' href='{{route('section_up',[$item->id])}}'><i class="fa-solid fa-arrow-up"></i></a><a href='{{route('section_down',[$item->id])}}' class='btn btn-danger btn-sm'><i class="fa-solid fa-arrow-down"></i></a></td>
                                <td>{{$item->name}}</td>
                                <td>{{ $item->sub_heading ? substr($item->sub_heading, 0, 15)  : 'Not Available' }}</td>
                                <td>
                                @if($form_management && $form_management[6] == 36)    
                                <a class="btn btn-sm btn-success" href="{{route('question.title.edit', [$item->id])}}">Edit</a>
                                @endif
                                @if($form_management && $form_management[7] == 37)
                                {{--<a class="btn btn-sm btn-danger" href="{{route('question.title.delete', [$item->id])}}" onclick="return confirm('Are you sure you want to delete?');">Delete</a> --}}
                                @endif
                                @if($form_management && $form_management[9] == 39)
                                <a class="btn btn-sm btn-secondary" href="{{route('question.list',[$item->id])}}">View</a>
                                @endif
                                @if($form_management && $form_management[8] == 38)
                                <a class="btn btn-sm btn-info" href="{{route('question.title.show', [$item->id])}}">Show</a>
                                @endif
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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