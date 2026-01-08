{{-- resources/views/partials/header.blade.php --}}
<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Doctora2z</title>

        {{-- Bootstrap 5 --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

        {{-- Bootstrap Icons --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

        {{-- Font Awesome --}}
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

        <style>
            /* Navbar */
            .navbar {
                background-color: rgb(214, 240, 241);
                padding: 8px 0;
                transition: all 0.3s ease;
            }

            .navbar .row {
                width: 100%;
                align-items: center;
                justify-content: space-between;
            }

            /* Social Icons Left */
            .social-icons a {
                font-size: 18px;
                margin-right: 15px;
                transition: all 0.2s ease;
                color: inherit;
            }

            .social-icons a:hover {
                opacity: 0.9;
                transform: translateY(-1px);
            }

            /* Utility / Action Icons Right */
            .doctor-details a {
                margin: 0 8px;
                text-decoration: none;
                transition: 0.2s ease;
                color: inherit;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .doctor-details a i {
                vertical-align: middle;
            }

            .doctor-details a:hover {
                transform: scale(1.06);
            }

            /* Ensure icons are visible */
            .doctor-details .fa-envelope {
                color: #17a2b8 !important;
            }

            .doctor-details .fa-circle-info {
                color: #007bff !important;
            }

            .doctor-details .fa-house {
                color: #ff5733 !important;
            }

            .doctor-details .fa-magnifying-glass {
                color: #28a745 !important;
            }

            /* Spinner */
            #spinner {
                z-index: 1050;
            }

            /* Mobile Responsive Adjustments */
            @media (max-width: 991px) {
                .navbar .container-fluid {
                    padding: 0 15px;
                }

                .navbar .row {
                    flex-wrap: nowrap;
                }
            }

            @media (max-width: 767px) {
                .social-icons a {
                    font-size: 16px;
                    margin-right: 12px;
                }

                .doctor-details a {
                    margin: 0 6px;
                }

                .doctor-details a i,
                .social-icons a i {
                    font-size: 18px;
                }

                .doctor-details img[alt="Twitter"] {
                    width: 16px !important;
                    height: 16px !important;
                }
            }

            @media (max-width: 575px) {
                .navbar {
                    padding: 6px 0;
                }

                .social-icons a {
                    font-size: 15px;
                    margin-right: 10px;
                }

                .doctor-details a {
                    margin: 0 4px;
                }

                .doctor-details a i,
                .social-icons a i {
                    font-size: 17px;
                }

                .doctor-details img[alt="Twitter"] {
                    width: 15px !important;
                    height: 15px !important;
                }
            }

            @media (max-width: 480px) {
                .navbar {
                    padding: 5px 0;
                }

                .social-icons a {
                    font-size: 14px;
                    margin-right: 8px;
                }

                .doctor-details a {
                    margin: 0 3px;
                }

                .doctor-details a i,
                .social-icons a i {
                    font-size: 16px;
                }

                .doctor-details img[alt="Twitter"] {
                    width: 14px !important;
                    height: 14px !important;
                }
            }

            @media (max-width: 400px) {
                .social-icons a {
                    font-size: 13px;
                    margin-right: 6px;
                }

                .doctor-details a {
                    margin: 0 2px;
                }

                .doctor-details a i,
                .social-icons a i {
                    font-size: 15px;
                }

                .doctor-details img[alt="Twitter"] {
                    width: 13px !important;
                    height: 13px !important;
                }
            }

            /* Prevent horizontal scrolling on mobile */
            body {
                overflow-x: hidden;
            }

            .container-xxl {
                max-width: 100%;
            }
        </style>
    </head>

    <body>
        <div class="container-xxl bg-white p-0">

            <!-- Spinner -->
            <div id="spinner"
                class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50
       d-flex align-items-center justify-content-center">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <!-- Navbar Start -->
            <nav class="navbar navbar-expand-lg navbar-light shadow sticky-top">
                <div class="container-fluid">
                    <div class="row w-100 align-items-center justify-content-between py-2">


                        <!--<div class="col social-icons text-start d-flex align-items-center">-->
                        <!--  <a href="https://facebook.com" target="_blank" aria-label="Facebook" style="color: #1877f2;">-->
                        <!--    <i class="fab fa-facebook-f"></i>-->
                        <!--  </a>-->

                        <!--  <a href="https://twitter.com" target="_blank" aria-label="Twitter">-->
                        {{-- <!--    <img src="{{ asset('/img/twiter.PNG') }}" alt="Twitter" style="width: 18px; height: 18px;">--> --}}
                        <!--  </a>-->

                        <!--  <a href="https://instagram.com" target="_blank" aria-label="Instagram" style="color: #e1306c;">-->
                        <!--    <i class="fab fa-instagram"></i>-->
                        <!--  </a>-->

                        <!--  <a href="https://linkedin.com" target="_blank" aria-label="LinkedIn" style="color: #0077b5;">-->
                        <!--    <i class="fab fa-linkedin-in"></i>-->
                        <!--  </a>-->
                        <!--</div>-->

                        <!-- Right Side: Navigation / Utility Icons -->
                        <div class="col text-end doctor-details">

                            {{-- safe route helper usage: fall back to static path if named route missing --}}
                            @php use Illuminate\Support\Facades\Route; @endphp

                            <!-- About Us -->
                            <!-- Home -->
                            @if (Route::has('home'))
                                <a href="{{ route('home') }}" target="_blank" title="Home" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" aria-label="Home">
                                    <i class="fa-solid fa-house" style="font-size: 20px;"></i>
                                </a>
                            @else
                                <a href="/" target="_blank" title="Home" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" aria-label="Home">
                                    <i class="fa-solid fa-house" style="font-size: 20px;"></i>
                                </a>
                            @endif

                            <!-- Contact Us -->
                            @if (Route::has('contact'))
                                <a href="{{ route('contact') }}" target="_blank" title="Contact Us"
                                    data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Contact">
                                    <i class="fa-solid fa-envelope" style="font-size: 20px;"></i>
                                </a>
                            @else
                                <a href="/contact" target="_blank" title="Contact Us" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" aria-label="Contact">
                                    <i class="fa-solid fa-envelope" style="font-size: 20px;"></i>
                                </a>
                            @endif

                            <!-- About Us -->
                            @if (Route::has('about'))
                                <a href="{{ route('about') }}" target="_blank" title="About Us" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" aria-label="About">
                                    <i class="fa-solid fa-circle-info" style="font-size: 20px;"></i>
                                </a>
                            @else
                                <a href="/about" target="_blank" title="About Us" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" aria-label="About">
                                    <i class="fa-solid fa-circle-info" style="font-size: 20px;"></i>
                                </a>
                            @endif


                            <!-- Search -->
                            @if (Route::has('generalSearch'))
                                <a href="{{ route('generalSearch') }}" target="_blank" title="Search"
                                    data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Search">
                                    <i class="fa-solid fa-magnifying-glass" style="font-size: 20px;"></i>
                                </a>
                            @else
                                <a href="/search" target="_blank" title="Search" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" aria-label="Search">
                                    <i class="fa-solid fa-magnifying-glass" style="font-size: 20px;"></i>
                                </a>
                            @endif

                            {{-- Authentication / Dashboard --}}
                            @php $authId = session('auth_id'); @endphp

                            @if (!is_null($authId))
                                @if (Route::has('dashboard'))
                                    <a href="{{ route('dashboard') }}" title="Dashboard" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" aria-label="Dashboard">
                                        <i class="bi bi-grid" style="font-size: 20px;"></i>
                                    </a>
                                @else
                                    <a href="/dashboard" title="Dashboard" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" aria-label="Dashboard">
                                        <i class="bi bi-grid" style="font-size: 20px;"></i>
                                    </a>
                                @endif

                                @if (Route::has('logout'))
                                    <a href="{{ route('logout') }}" title="Logout" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" aria-label="Logout">
                                        <i class="bi bi-box-arrow-right" style="font-size: 20px;"></i>
                                    </a>
                                @else
                                    <a href="/logout" title="Logout" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" aria-label="Logout">
                                        <i class="bi bi-box-arrow-right" style="font-size: 20px;"></i>
                                    </a>
                                @endif
                            @else
                                @if (Route::has('login'))
                                    <a href="{{ route('login') }}" title="Login" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" aria-label="Login">
                                        <i class="bi bi-person-circle" style="font-size: 20px;"></i>
                                    </a>
                                @else
                                    <a href="/login" title="Login" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" aria-label="Login">
                                        <i class="bi bi-person-circle" style="font-size: 20px;"></i>
                                    </a>
                                @endif
                            @endif

                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->
        </div>

        {{-- Bootstrap JS --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize tooltips
                if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                        new bootstrap.Tooltip(el);
                    });
                }

                // Hide spinner after load
                const spinner = document.getElementById('spinner');
                if (spinner) {
                    setTimeout(() => {
                        spinner.classList.remove('show');
                        spinner.style.display = 'none';
                    }, 600);
                }
            });
        </script>

    </body>

</html>
