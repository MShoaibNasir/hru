@extends('dashboard.layout.master')
@section('content')
<link href="{{asset('dashboard/css/breadcrumbs.css')}}" rel="stylesheet"> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
    @include('dashboard.layout.navbar')
    @php
        $current_user = Auth::user();
        if ($current_user) {
                  $allow_access = DB::table('users')
                    ->join('roles', 'users.role', '=', 'roles.id')
                    ->where('users.id', '=', $current_user->id)
                    ->first();
        }
        $form_management = json_decode($allow_access->form_management);
        array_unshift($form_management, 0);
    @endphp

    <!-- Navbar End -->


    <div class="container-fluid pt-4 px-4 form_width">
        <nav role="navigation" aria-label="Breadcrumb">
            <ol itemscope itemtype="">
                <li itemprop="itemListElement" itemscope itemtype="#">
                    <a href="{{route('form.list')}}" itemprop="item" >
                        <span itemprop="name">{{$form_name->name}}</span>
                    </a>
                    <meta itemprop="position" content="1" />
                </li>
                <li itemprop="itemListElement" itemscope itemtype="#">
                    <a href="{{route('form.view',[$form_id])}}" itemprop="item">
                        <span itemprop="name">{{$section_name->name}}</span>

                    </a>
                    <meta itemprop="position" content="2" />
            </ol>
        </nav>
        
        <div class="bg-light text-center rounded p-4">
            
        <h2 class="mb-0">Questions List</h2>
            <div class="d-flex align-items-center justify-content-between mb-4">
            @if($form_management[10] == 40)
                <a href="{{route('question.create', [$title_id])}}" class="create_button">Create Questions</a>
            @endif    
            @if($form_management[13] == 43)
                <a href="{{route('options.list', [$title_id])}}" class="create_button">Options</a>
            @endif    
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0"  id="myTable">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">S no</th>
                            <th scope="col">Manage Sequence</th>
                            <th scope="col">Question Name</th>
                            <th scope="col">Type</th>
                            <th scope="col">Placeholder</th>
                            <th scope="col">Parent Option</th>
                            @if($form_management[11] == 41 || $form_management[12] == 42)
                            <th scope="col">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $currentUrl = Request::url();
                        $segments = explode('/', $currentUrl);
                        $lastSegment = end($segments);

                        if (!function_exists('wrap_text')) {
                            function wrap_text($text, $word_limit = 100) {
                                $words = explode(' ', $text);
                                $chunks = array_chunk($words, $word_limit);
                                $wrapped_text = array_map(function($chunk) {
                                    return implode(' ', $chunk);
                                }, $chunks);
                                
                                return implode('<br>', $wrapped_text);
                            }
                        }
                     
                        ?>
                        @foreach($question as $item)
                        <?php    $placeholder_text = $item->placeholder ? $item->placeholder : 'Not available'; ?>
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td><a class='btn btn-success btn-sm' style='margin-right:4px;' href='{{route('question_up',[$item->id])}}'><i class="fa-solid fa-arrow-up"></i></a><a href='{{route('question_down',[$item->id])}}' class='btn btn-danger btn-sm'><i class="fa-solid fa-arrow-down"></i></a></td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->type}}</td>
                                <td class='question_placeholder'  ><?php echo wrap_text($placeholder_text, 10) ?></td>
                                <td>
                                    @if($item->option_id)
                                        <a onclick="showOption({{$item->option_id}})" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal" class="btn button-60">Show</a>
                                    @else
                                        <span class="btn button-60">Not available</span>
                                    @endif
                                </td>
                                @if($form_management[11] == 41 || $form_management[12] == 42)
                                <td>
                                @if($form_management[11] == 41)
                                    <a class="btn btn-sm btn-success"
                                        href="{{route('question.edit', [$item->id, $lastSegment])}}">Edit</a>
                                @endif 
                                      
                                </td>
                                @endif

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Option Name</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea id="option_name" readonly class="form-control"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="{{asset('dashboard\js\option.js')}}"></script>
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