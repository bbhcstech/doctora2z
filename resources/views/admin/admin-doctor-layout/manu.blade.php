<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Home Page Nav -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home', ['auth_id' => encrypt(Auth::id())]) }}" target="_blank">
                <i class="bi bi-house-door"></i>
                <span>Home Page</span>
            </a>
        </li>

        <!-- Dashboard Nav -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <!-- End Dashboard Nav -->

        <!-- Doctor Profile (self-edit main) -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('doctor.profile.edit') ? 'active' : '' }}"
                href="{{ route('doctor.profile.edit') }}">
                <i class="bi bi-person-circle"></i>
                <span>Edit Profile</span>
            </a>
        </li>
        <!-- End Doctor Profile -->

        <!-- Doctor Profile Tabs -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#doctorProfile-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-person-badge"></i>
                <span>Profile Tabs</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="doctorProfile-nav"
                class="nav-content collapse 
          {{ request()->routeIs('doctor.profile.personal') ||
          request()->routeIs('doctor.profile.professional') ||
          request()->routeIs('doctor.profile.location') ||
          request()->routeIs('doctor.profile.educationSchedule')
              ? 'show'
              : '' }}"
                data-bs-parent="#sidebar-nav">

                <li>
                    <a href="{{ route('doctor.profile.personal') }}"
                        class="{{ request()->routeIs('doctor.profile.personal') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Personal</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('doctor.profile.professional') }}"
                        class="{{ request()->routeIs('doctor.profile.professional') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Professional</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('doctor.profile.location') }}"
                        class="{{ request()->routeIs('doctor.profile.location') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Location</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('doctor.profile.educationSchedule') }}"
                        class="{{ request()->routeIs('doctor.profile.educationSchedule') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Education & Schedule</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End Doctor Profile Tabs -->

        <!-- Doctor Change Password -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('doctor.profile.password.edit') ? 'active' : '' }}"
                href="{{ route('doctor.profile.password.edit') }}">
                <i class="bi bi-key"></i>
                <span>Change Password</span>
            </a>
        </li>
        <!-- End Doctor Change Password -->

        @if (Auth::check() && Auth::user()->role === 'admin')
            <!-- Admin Panel Nav -->
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#manageDoctor-nav" data-bs-toggle="collapse"
                    href="#">
                    <i class="bi bi-menu-button-wide"></i>
                    <span>Manage Doctor</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="manageDoctor-nav"
                    class="nav-content collapse {{ request()->routeIs('doctors*') ? 'show' : '' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('doctors.index') }}"
                            class="{{ request()->routeIs('doctors.index') ? 'active' : '' }}">
                            <i class="bi bi-person custom-icon"></i>
                            <span>Doctors</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('doctors.create') }}"
                            class="{{ request()->routeIs('doctors.create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle custom-icon"></i>
                            <span>Add Doctor</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- End Admin Panel Nav -->
        @endif

    </ul>
</aside>
<!-- End Sidebar -->
