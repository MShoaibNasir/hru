
<nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
              
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="{{ asset('admin/assets/img/' . Auth::user()->image) }}" alt="profile image"
                                style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex">{{Auth::user()->name}}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="{{route('edit_profile')}}" class="dropdown-item">My Profile</a>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                                class="dropdown-item">Log Out</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
            
            
            
            
            
            
            
            
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.querySelector('.sidebar-toggler'); // Sidebar toggler button
    const sidebar = document.querySelector('.sidebar'); // Sidebar element
    const content = document.querySelector('.content'); // Content area
    const navbar = document.querySelector('.navbar'); // Navbar element
    const overlay = document.createElement('div'); // Create overlay element
    overlay.classList.add('overlay');
    document.body.appendChild(overlay); // Add overlay to the DOM

    // Toggle sidebar on button click
    toggleButton.addEventListener('click', function(event) {
        event.stopPropagation(); // Prevent the click event from bubbling up to body

        // Toggle the 'open' class on the sidebar
        sidebar.classList.toggle('open'); 

        // Check screen size and sidebar status
        const isMobile = window.innerWidth <= 768; // Mobile screen condition
        if (sidebar.classList.contains('open')) {
            if (isMobile) {
                // Sidebar covers full screen on mobile
                sidebar.style.width = '100%';
                sidebar.style.zIndex = '10'; // Bring sidebar above navbar
                navbar.style.zIndex = '1'; // Push navbar below sidebar
                content.style.marginLeft = '0';
                content.style.width = '100%';
                overlay.style.display = 'block'; // Show overlay
            } else {
                // Sidebar and content for larger screens
                sidebar.style.width = '250px';
                sidebar.style.zIndex = '10';
                navbar.style.zIndex = '10';
                content.style.marginLeft = '250px';
                content.style.width = 'calc(100% - 250px)';
                overlay.style.display = 'none'; // Hide overlay
            }
        } else {
            // Sidebar closed
            sidebar.style.width = '250px';
            content.style.marginLeft = '0';
            content.style.width = '100%';
            navbar.style.zIndex = '10'; // Reset navbar z-index
            overlay.style.display = 'none'; // Hide overlay
        }
    });

    // Close sidebar when overlay is clicked
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('open');
        sidebar.style.width = '250px';
        content.style.marginLeft = '0';
        content.style.width = '100%';
        navbar.style.zIndex = '10'; // Reset navbar z-index
        overlay.style.display = 'none'; // Hide overlay
    });

    // Close sidebar if click happens anywhere else on the page (outside sidebar)
    document.addEventListener('click', function(event) {
        if (!sidebar.contains(event.target) && !toggleButton.contains(event.target)) {
            sidebar.classList.remove('open');
            sidebar.style.width = '250px';
            content.style.marginLeft = '0';
            content.style.width = '100%';
            navbar.style.zIndex = '10'; // Reset navbar z-index
            overlay.style.display = 'none'; // Hide overlay
        }
    });
});
</script>




<style>
    .sidebar {
    margin-left: -400px; /* Sidebar hidden by default */
    transition: margin-left 0.3s; /* Smooth transition for opening/closing */
}

.sidebar.open {
    margin-left: 0; /* Sidebar fully visible */
}

.content {
    width: 100% !important;
    margin-left: 0; /* Content takes up full width by default */
    transition: margin-left 0.3s, width 0.3s; /* Smooth transition for content movement */
}

</style>
