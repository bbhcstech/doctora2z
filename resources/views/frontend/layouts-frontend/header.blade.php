{{-- resources/views/frontend/layouts-frontend/header.blade.php --}}

<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">

        {{-- Logo Section --}}
        <a href="{{ url('/') }}" class="logo d-flex align-items-center">
            <img src="{{ asset('frontend/images/logo.png') }}" alt="Doctors A2Z Logo">
            <span class="d-none d-lg-block ms-2">Doctors A2Z</span>
        </a>

        {{-- Mobile Navigation Toggle --}}
        <i class="bi bi-list mobile-nav-toggle"></i>

        {{-- Navigation Menu --}}
        <nav id="navbar" class="navbar">
            <ul class="d-flex align-items-center mb-0">

                <li><a class="nav-link scrollto" href="{{ url('/') }}">Home</a></li>
                <li><a class="nav-link scrollto" href="{{ url('/doctors') }}">Doctors</a></li>
                <li><a class="nav-link scrollto" href="{{ url('/clinics') }}">Clinics</a></li>
                <li><a class="nav-link scrollto" href="{{ url('/contact') }}">Contact</a></li>

                @guest
                    {{-- Login Button --}}
                    <li>
                        <a class="nav-link btn btn-primary text-white ms-2 px-3 rounded" href="{{ route('login') }}">
                            Login
                        </a>
                    </li>
                @else
                    {{-- User Dropdown --}}
                    <li class="nav-item dropdown ms-3">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                            data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->profile_photo ?? asset('frontend/images/default-user.jpg') }}"
                                class="rounded-circle" alt="User Profile"
                                style="width: 35px; height: 35px; object-fit: cover;">
                            <span class="ms-2">{{ auth()->user()->name }}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="bi bi-person me-2"></i> My Profile
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('appointments') }}">
                                    <i class="bi bi-calendar-check me-2"></i> Appointments
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest

            </ul>
        </nav><!-- End Navbar -->

    </div>
</header>
