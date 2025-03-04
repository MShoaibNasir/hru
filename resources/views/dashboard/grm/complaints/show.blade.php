@extends('dashboard.layout.master')
@section('content')


<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
    @include('dashboard.layout.navbar')
    <!-- Navbar End -->



    <div class="container-fluid pt-4 px-4 form_width">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Show Ticket</h6>
                       <div class="row">
                            <div class="mb-3 col-12">
                             
<div class="accordion" id="accordionPanelsStayOpenExample">
  <div class="accordion-item">
    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
        Complaint Detail
      </button>
    </h2>
    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
      <div class="accordion-body">
        <x-backend.grm.complaint.complaintview :complaint="$complaint" />
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
        Complaint Followup History
      </button>
    </h2>
    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
      <div class="accordion-body">
        <x-backend.grm.complaint.followuplist :followups="$followups" />
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="panelsStayOpen-headingThree">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
        Complaint File History
      </button>
    </h2>
    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
      <div class="accordion-body">
        <x-backend.grm.complaint.filelist :filelist="$filelist" />
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="panelsStayOpen-headingFour">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false" aria-controls="panelsStayOpen-collapseFour">
        Complaint Assign History
      </button>
    </h2>
    <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingFour">
      <div class="accordion-body">
        <x-backend.grm.complaint.assignlist :assignlist="$assignlist" />
      </div>
    </div>
  </div>
  @if(Auth::user()->role==56 || Auth::user()->role==57)
  <div class="accordion-item">
    <h2 class="accordion-header" id="panelsStayOpen-headingFive">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="false" aria-controls="panelsStayOpen-collapseFive">
        Complaint Action Form
      </button>
    </h2>
    <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingFive">
      <div class="accordion-body">
        <x-backend.grm.complaint.followupform :complaint="$complaint"  />
      </div>
    </div>
  </div>
  @endif
</div>


{{--
<x-backend.grm.complaint.complaintview :complaint="$complaint" />
<x-backend.grm.complaint.followuplist :followups="$followups" />
<x-backend.grm.complaint.filelist :filelist="$filelist" />
<x-backend.grm.complaint.assignlist :assignlist="$assignlist" />
<x-backend.grm.complaint.followupform :complaint="$complaint"  />
--}}
{{--<x-backend.grm.complaint.assignform :complaint="$complaint" />--}}






                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

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
@push('ayiscss')
<link href="{{ asset('dashboard/css/components.min.css?v=1') }}" rel="stylesheet">
@endpush