<!-- icon style  -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .custom-icon {
        font-size: 19px;
        color: #e9e3e3;
    }
</style>
<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard Nav -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <!-- End Dashboard Nav -->

        <!-- Admin Panel Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i>
                <span>Manage Clinic/Doctor</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="components-nav"
                class="nav-content collapse {{ request()->routeIs('clients*', 'doctors*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('clients.index') }}"
                        class="{{ request()->routeIs('clients.index') ? 'active' : '' }}">
                        <i class="bi bi-person" style="font-size: 19px; color: #e9e3e3;"></i>
                        <span>Clinics </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('doctors.index') }}"
                        class="{{ request()->routeIs('doctors.index') ? 'active' : '' }}">
                        <i class="bi bi-person" style="font-size: 19px; color: #e9e3e3;"></i>
                        <span>Doctors </span>
                    </a>
                </li>

            </ul>
        </li>




    </ul>

</aside>
<!-- End Sidebar -->
