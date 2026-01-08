@extends('admin.admin-clinic-layout.app')

@section('title', 'Home Page')

@section('content')

    <head>
        <!-- Other meta tags, title, etc. -->

        <!-- Bootstrap Icons CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Other stylesheets, like your main CSS -->
    </head>

    <main id="main" class="main">

        <div class="pagetitle">

            <h1> Clinic Dashboard</h1>
            <nav>
                <ol class="breadcrumb">

                    <li class="breadcrumb-item active">Dashboard</li>


                </ol>
            </nav>
        </div><!-- End Page Title -->

        <div class="container mt-4">
            <div class="row">


                <!-- Doctors Box -->
                <div class="col-md-6">
                    <div class="card shadow d-flex flex-row align-items-center" style="border-left: 5px solid #28a745;">
                        <!-- Doctor Icon (Bootstrap Icon) -->
                        <div class="p-3">
                            <a href="{{ route('doctors.index') }}">
                                <i class="bi bi-person" style="font-size: 50px; color: #28a745;"></i>
                            </a>
                        </div>
                        <!-- Doctor Details -->
                        <div class="card-body">
                            <h5 class="card-title text-success"> <a href="{{ route('doctors.index') }}">Doctors</a></h5>
                            <p class="card-text">Total Doctors: <strong>{{ $totalDoctorCount }}</strong></p>
                            <p class="card-text text-success">Approved Doctors: <strong>{{ $approvedDoctorCount }}</strong>
                            </p>
                            <p class="card-text text-warning">Pending Doctors: <strong>{{ $pendingDoctorCount }}</strong>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Doctors Visit count -->
                <div class="col-md-6">
                    <div class="card shadow d-flex flex-row align-items-center" style="border-left: 5px solid #28a745;">
                        <!-- Doctor Icon (Bootstrap Icon) -->
                        <div class="p-3">
                            <a href="{{ route('doctors.index') }}">
                                <i class="bi bi-person" style="font-size: 50px; color: #28a745;"></i>
                            </a>
                        </div>
                        <!-- Doctor Details -->
                        <div class="card-body">
                            <h5 class="card-title text-success"> <a href="{{ route('doctors.index') }}">Doctors Visit</a>
                            </h5>
                            <p class="card-text">Total Doctors Visit Count: <strong>{{ $doctorVisitCount }}</strong></p>

                        </div>
                    </div>
                </div>
            </div>
        </div>







    </main><!-- End #main -->
@endsection
