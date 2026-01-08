<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>Dashboard - Doctors A2Z</title>
        <meta content="" name="description">
        <meta content="" name="keywords">

        <!-- Favicons -->
        <link href="{{ asset('admin/assets/img/favicon.png') }}" rel="icon">
        <link href="{{ asset('admin/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

        <!-- Google Fonts -->
        <link href="https://fonts.gstatic.com" rel="preconnect">
        <link
            href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Nunito:300,400,600,700|Poppins:300,400,500,600,700"
            rel="stylesheet">

        <!-- Bootstrap CSS -->
        <link href="{{ asset('admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- Vendor CSS -->
        <link href="{{ asset('admin/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
        <link href="{{ asset('admin/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
        <link href="{{ asset('admin/assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
        <link href="{{ asset('admin/assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
        <link href="{{ asset('admin/assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
        <link href="{{ asset('admin/assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

        <!-- Owl Carousel -->
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

        <!-- DataTables -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">

        <!-- Select2 -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

        <!-- Summernote -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="{{ asset('admin/assets/css/style.css') }}" rel="stylesheet">

        <style>
            .header-avatar {
                width: 32px;
                height: 32px;
                object-fit: cover;
                border-radius: 50%;
                border: 2px solid rgba(255, 255, 255, 0.4);
                background: #f5f7fa;
                box-shadow: 0 2px 8px rgba(18, 38, 63, .06);
            }
        </style>
    </head>

    <body>

        <!-- ======= Header ======= -->
        <header id="header" class="header fixed-top d-flex align-items-center">

            <div class="d-flex align-items-center justify-content-between">
                @php $role = Auth::user()->role ?? 'user'; @endphp
                @if ($role === 'admin')
                    <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center">
                        <img src="{{ asset('admin/assets/img/favicon.png') }}" alt="logo" style="height:28px">
                        <span class="d-none d-lg-block">Doctor Listing Clinic Admin</span>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center">
                        <img src="{{ asset('admin/assets/img/favicon.png') }}" alt="logo" style="height:28px">
                        <span class="d-none d-lg-block">Doctor Dashboard</span>
                    </a>
                @endif

                <i class="bi bi-list toggle-sidebar-btn"></i>
            </div><!-- End Logo -->

            <nav class="header-nav ms-auto">
                <ul class="d-flex align-items-center">

                    <li class="nav-item dropdown pe-3">
                        @php
                            use Illuminate\Support\Str;
                            use Illuminate\Support\Facades\Storage;

                            $user = Auth::user();
                            $isDoctor = $role === 'doctor';
                            $doctor = $isDoctor ? $user->doctor ?? null : null;

                            $raw = $isDoctor && $doctor ? trim($doctor->profile_picture ?? '') : '';
                            $profileImg = asset('admin/assets/img/profile-img.jpg'); // default

                            if (!empty($raw)) {
                                if (Str::startsWith($raw, ['http://', 'https://'])) {
                                    $profileImg = $raw;
                                } elseif (file_exists(public_path($raw))) {
                                    $profileImg = asset($raw);
                                } elseif (Storage::disk('public')->exists($raw)) {
                                    $profileImg = Storage::disk('public')->url($raw);
                                } elseif (file_exists(public_path('storage/' . $raw))) {
                                    $profileImg = asset('storage/' . $raw);
                                } elseif (file_exists(public_path('admin/uploads/doctor/' . $raw))) {
                                    $profileImg = asset('admin/uploads/doctor/' . $raw);
                                }
                            }

                            if ($role === 'admin') {
                                $displayName = 'Admin';
                            } elseif ($isDoctor) {
                                $displayName = $doctor->name ?? $user->name;
                            } else {
                                $displayName = $user->name;
                            }
                        @endphp

                        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                            data-bs-toggle="dropdown">
                            <img src="{{ $profileImg }}" alt="Profile" class="header-avatar">
                            <span class="d-none d-md-block dropdown-toggle ps-2">{{ $displayName }}</span>
                        </a><!-- End Profile Image Icon -->

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                            <li class="dropdown-header">
                                <h6>{{ $displayName }}</h6>
                                <span class="text-muted">{{ ucfirst($role) }}</span>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                @if ($role === 'doctor')
                                    <a class="dropdown-item d-flex align-items-center"
                                        href="{{ route('doctor.profile.show', $doctor->id ?? '') }}">
                                        <i class="bi bi-person"></i>
                                        <span>View Profile</span>
                                    </a>
                                @else
                                    <a class="dropdown-item d-flex align-items-center"
                                        href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person"></i>
                                        <span>My Profile</span>
                                    </a>
                                @endif
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center"
                                        style="background:none; border:none; width:100%; text-align:left;">
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </li>
                        </ul><!-- End Profile Dropdown Items -->
                    </li><!-- End Profile Nav -->

                </ul>
            </nav><!-- End Icons Navigation -->

        </header><!-- End Header -->
