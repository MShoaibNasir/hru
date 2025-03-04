<!-- Widgets Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-md-12"><h4>Today Complaints</h4></div>
            <x-backend.grm.complaint.counter :data="get_today_total_complaint()" title="Today Register Complaints" url="{{route('complaints.today_total')}}"  />
        </div>
    </div>
<!-- Widgets End -->

<!-- Widgets Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-md-12"><h4>Overall Complaints</h4></div>
            <x-backend.grm.complaint.counter :data="get_total_complaint()" title="Overall Register Complaints" url="{{route('complaints.index')}}"  />
            <x-backend.grm.complaint.counter :data="get_total_pending_complaint()" title="Overall Pending Complaints" url="{{route('complaints.pending')}}" />
            <x-backend.grm.complaint.counter :data="get_total_inprocess_complaint()" title="Overall In Process Complaints" url="{{route('complaints.inprocess')}}" />
            <x-backend.grm.complaint.counter :data="get_total_returned_complaint()" title="Overall Returned Complaints" url="{{route('complaints.returned')}}" />
            <x-backend.grm.complaint.counter :data="get_total_closed_complaint()" title="Overall Closed Complaints" url="{{route('complaints.closed')}}" />
            <x-backend.grm.complaint.counter :data="get_forward_total_complaint()" title="Overall Forward Complaints" url="{{route('complaints.forward')}}" />
        </div>
    </div>
<!-- Widgets End -->

<!-- Widgets Start -->
    <div class="container-fluid pt-4 px-4 pb-5">
        <div class="row g-4">
            <div class="col-md-12"><h4>Exclusion Cases List</h4></div>
            <x-backend.grm.complaint.counter :data="get_total_exclusioncases_complaint()" title="Overall Exclusion Cases List" url="{{route('complaints.exclusioncases_complaint')}}" />
            <x-backend.grm.complaint.counter :data="get_today_exclusioncases_complaint()" title="Today Exclusion Cases List" url="{{route('complaints.exclusioncases_today')}}"  />
        </div>
    </div>
<!-- Widgets End -->