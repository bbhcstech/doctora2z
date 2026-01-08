@extends('partials.app')

@section('title', 'Search Results')

@section('content')
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (window.Swal) Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: @json(session('error'))
                });
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (window.Swal) Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: @json(session('success'))
                });
            });
        </script>
    @endif

    <!-- Header -->
    <div class="container-xxl py-4 py-lg-5 bg-dark page-header mb-5">
        <div class="container my-lg-5 pt-lg-5 pb-3 pb-lg-4">
            <h1 class="display-5 display-lg-3 text-white mb-3 animated slideInDown">Search Results</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb text-uppercase">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active text-white" aria-current="page">
                        Search Results for "{{ $query }}"
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Header End -->

    <div class="container mt-3 mt-md-4 px-3 px-md-0">
        <h1 class="h3 h1-lg mt-3 mt-md-4">
            Search Results for:
            "<span class="text-primary">{{ $query }}</span>"
        </h1>

        @php
            $results = isset($results) ? collect($results) : collect();
            $advertisements = isset($advertisements) ? collect($advertisements) : collect();
            $index = 0;
        @endphp

        @if ($results->isEmpty())
            <div class="text-center mt-4 mt-md-5 py-4 py-md-5">
                <i class="fas fa-search fa-3x fa-4x-lg text-muted mb-3"></i>
                <h3 class="h4 h3-lg text-muted mb-2">No results found for "{{ $query }}"</h3>
                <p class="text-muted mb-3">Try searching with different keywords or browse categories</p>
                <a href="{{ url('/') }}" class="btn btn-primary mt-2">Return to Home</a>
            </div>
        @else
            <div class="mb-3">
                <small class="text-muted">Found {{ $results->count() }} result(s)</small>
            </div>

            <div id="search-results" class="search-results mt-3 mt-md-4">
                <div class="row g-3 g-md-4">
                    @foreach ($results as $result)
                        @php
                            $index++;

                            $type = data_get($result, 'type') ?: null;
                            $resultId = data_get($result, 'id');
                            $resultName = data_get($result, 'name') ?: '-';

                            $speciality = data_get($result, 'speciality') ?? (data_get($result, 'meta') ?? null);
                            $degree = data_get($result, 'degree') ?? '-';
                            $cityName = data_get($result, 'city.name') ?? (data_get($result, 'city_name') ?? '-');
                            $stateName = data_get($result, 'state.name') ?? (data_get($result, 'state_name') ?? '-');
                            $countryName =
                                data_get($result, 'country.name') ?? (data_get($result, 'country_name') ?? '-');
                            $phone = data_get($result, 'phone_number') ?? '';
                            $address = data_get($result, 'address') ?? '-';
                            $profilePic = data_get($result, 'profile_picture') ?? (data_get($result, 'image') ?? null);
                            $imageUrl = $profilePic
                                ? asset(ltrim($profilePic, '/'))
                                : asset('admin/assets/adminimg/demo_doctor_image.jpeg');

                            // visiting time from accessor (Doctor::getVisitingTimeAttribute)
                            $visitingTime = data_get($result, 'visiting_time') ?? '-';

                            // Check if it's a doctor (for category wise searches)
$isDoctor =
    $type === 'doctor' ||
    $speciality ||
    $degree ||
    str_contains(strtolower($resultName), 'dr.') ||
    str_contains(strtolower($resultName), 'doctor');

$lastUpdate =
    data_get($result, 'last_update') ??
    (data_get($result, 'updated_at')
        ? \Carbon\Carbon::parse(data_get($result, 'updated_at'))->format('Y-m-d')
        : '-');

$avg = \Illuminate\Support\Facades\DB::table('rating')
    ->where('doctor_id', $resultId)
    ->avg('rating_point');
                        @endphp

                        {{-- Doctor Card --}}
                        @if ($isDoctor)
                            <div class="col-12">
                                <div class="doctor-card p-3 p-md-4 mb-3 mb-md-4 w-100 border rounded shadow-sm">
                                    {{-- Mobile Layout - Stacked --}}
                                    <div class="d-block d-md-none">
                                        {{-- Image and Basic Info --}}
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="me-3">
                                                <img src="{{ $imageUrl }}" height="80" width="80"
                                                    style="border: 3px solid #588DDB; border-radius: 10px; object-fit: cover;"
                                                    class="img-fluid" alt="{{ e($resultName) }}">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="text-primary mb-1">{{ $resultName }}</h5>

                                                @if ($speciality)
                                                    <p class="mb-1 text-dark small">
                                                        <strong>Speciality:</strong> {{ $speciality }}
                                                    </p>
                                                @endif

                                                @if ($degree && $degree !== '-')
                                                    <p class="mb-1 text-dark small">
                                                        <strong>Qualification:</strong> {{ $degree }}
                                                    </p>
                                                @endif

                                                {{-- Visiting Time Badge - Always show for doctors --}}
                                                @if ($visitingTime && $visitingTime !== '-')
                                                    <div class="mb-2">
                                                        <span class="badge bg-info text-dark p-1 px-2 small">
                                                            <i class="fa fa-clock me-1"></i>
                                                            {{ $visitingTime }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Details --}}
                                        <div class="mb-3">
                                            @if ($cityName && $cityName !== '-' && $stateName && $stateName !== '-')
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fa fa-map-marker-alt text-primary me-2"></i>
                                                    <span class="text-dark small">{{ $cityName }},
                                                        {{ $stateName }}</span>
                                                </div>
                                            @endif

                                            @if ($address && $address !== '-')
                                                <div class="d-flex align-items-start mb-2">
                                                    <i class="fa fa-home text-primary me-2 mt-1"></i>
                                                    <span class="text-dark small">{{ Str::limit($address, 80) }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Rating --}}
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <small class="text-muted me-2">Rating:</small>
                                                @if ($avg)
                                                    <div class="d-flex align-items-center">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i
                                                                class="fa fa-star {{ $i <= round($avg) ? 'text-warning' : 'text-secondary' }} me-1"></i>
                                                        @endfor
                                                        <span
                                                            class="ms-2 text-dark small">({{ number_format($avg, 1) }}/5)</span>
                                                    </div>
                                                @else
                                                    <span class="text-muted small">No ratings yet</span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Buttons --}}
                                        <div class="d-grid gap-2">
                                            @if (!empty($phone))
                                                <a href="tel:{{ preg_replace('/\D+/', '', $phone) }}"
                                                    class="btn btn-primary btn-sm py-2">
                                                    <i class="fa fa-phone-alt me-2"></i>
                                                    Call Doctor
                                                </a>
                                            @endif

                                            @if ($resultId && $type === 'doctor')
                                                <a href="{{ route('doctor.details', $resultId) }}"
                                                    class="btn btn-success btn-sm py-2">
                                                    <i class="fa fa-info-circle me-2"></i>
                                                    View Details
                                                </a>
                                            @elseif($resultId && $type === 'clinic')
                                                <a href="{{ route('clinic.details', ['id' => $resultId]) }}"
                                                    class="btn btn-success btn-sm py-2">
                                                    <i class="fa fa-info-circle me-2"></i>
                                                    View Clinic
                                                </a>
                                            @elseif($resultId)
                                                <a href="{{ route('doctor.details', $resultId) }}"
                                                    class="btn btn-success btn-sm py-2">
                                                    <i class="fa fa-info-circle me-2"></i>
                                                    View Details
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Desktop Layout - Horizontal --}}
                                    <div class="d-none d-md-block">
                                        <div class="row g-4 align-items-center">
                                            <div class="col-md-8 d-flex align-items-center">
                                                <div>
                                                    <img src="{{ $imageUrl }}" height="120" width="120"
                                                        style="border: 3px solid #588DDB; border-radius: 15px; object-fit: cover;"
                                                        class="mt-3 ms-1" alt="{{ e($resultName) }}">
                                                </div>

                                                <div class="text-start ps-4 w-100">
                                                    <h5 class="mb-2 text-primary">{{ $resultName }}</h5>

                                                    @if ($speciality)
                                                        <p class="mb-1">
                                                            <strong>Speciality:</strong> {{ $speciality }}
                                                        </p>
                                                    @endif

                                                    @if ($degree && $degree !== '-')
                                                        <p class="mb-1">
                                                            <strong>Qualification:</strong> {{ $degree }}
                                                        </p>
                                                    @endif

                                                    <div class="row mt-2">
                                                        <div class="col-12 col-md-6 mt-1 text-start">
                                                            <small class="text-muted">
                                                                <i class="fa fa-map-marker-alt text-primary me-2"></i>
                                                                {{ $cityName }}, {{ $stateName }}
                                                            </small>
                                                        </div>
                                                        @if ($visitingTime && $visitingTime !== '-')
                                                            <div class="col-12 col-md-6 mt-1 text-start">
                                                                <small class="text-muted">
                                                                    <i class="fa fa-clock text-primary me-2"></i>
                                                                    Visiting: {{ $visitingTime }}
                                                                </small>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="mt-3">
                                                        <small class="text-muted">Rating: </small>
                                                        @if ($avg)
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <i
                                                                    class="fa fa-star {{ $i <= round($avg) ? 'text-warning' : 'text-secondary' }}"></i>
                                                            @endfor
                                                            <span class="ms-2">({{ number_format($avg, 1) }}/5)</span>
                                                        @else
                                                            <span class="ms-2 text-muted">No ratings yet</span>
                                                        @endif
                                                    </div>

                                                    @if ($address && $address !== '-')
                                                        <div class="mt-2">
                                                            <small class="text-muted">
                                                                <i class="fa fa-home text-primary me-2"></i>
                                                                {{ Str::limit($address, 100) }}
                                                            </small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                                                @if ($visitingTime && $visitingTime !== '-')
                                                    <div class="mb-3">
                                                        <span class="badge bg-warning text-dark p-2">
                                                            <i class="fa fa-clock me-1"></i>
                                                            Visiting: {{ $visitingTime }}
                                                        </span>
                                                    </div>
                                                @endif

                                                <div class="d-grid gap-2">
                                                    @if (!empty($phone))
                                                        <a href="tel:{{ preg_replace('/\D+/', '', $phone) }}"
                                                            class="btn btn-primary">
                                                            <i class="fa fa-phone-alt me-2"></i>
                                                            Call Doctor
                                                        </a>
                                                    @endif

                                                    @if ($resultId && $type === 'doctor')
                                                        <a href="{{ route('doctor.details', $resultId) }}"
                                                            class="btn btn-success">
                                                            <i class="fa fa-info-circle me-2"></i>
                                                            View Details
                                                        </a>
                                                    @elseif($resultId && $type === 'clinic')
                                                        <a href="{{ route('clinic.details', ['id' => $resultId]) }}"
                                                            class="btn btn-success">
                                                            <i class="fa fa-info-circle me-2"></i>
                                                            View Clinic
                                                        </a>
                                                    @elseif($resultId)
                                                        <a href="{{ route('doctor.details', $resultId) }}"
                                                            class="btn btn-success">
                                                            <i class="fa fa-info-circle me-2"></i>
                                                            View Details
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Clinic / Hospital Card --}}
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="card mb-3 h-100 border shadow-sm">
                                    <div class="card-body d-flex flex-column p-3 p-md-4">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fa-solid fa-hospital text-primary me-2"></i>
                                            <h6 class="card-title text-truncate mb-0">{{ $resultName }}</h6>
                                        </div>

                                        @if ($address && $address !== '-')
                                            <p class="small text-success mb-1">
                                                <i class="fa fa-map-marker-alt me-1"></i>
                                                {{ Str::limit($address, 80) }}
                                            </p>
                                        @endif

                                        <p class="small text-muted mb-2">
                                            <i class="fa-solid fa-location-dot me-1"></i>
                                            {{ $cityName }}, {{ $stateName }}
                                        </p>

                                        {{-- Show Visiting Time for Clinics too if available --}}
                                        @if ($visitingTime && $visitingTime !== '-')
                                            <p class="small text-info mb-2">
                                                <i class="fa fa-clock me-1"></i>
                                                Visiting: {{ $visitingTime }}
                                            </p>
                                        @endif

                                        @if ($lastUpdate !== '-')
                                            <p class="small text-muted mb-2">
                                                <i class="fa-solid fa-calendar-days me-1"></i>
                                                Updated: {{ $lastUpdate }}
                                            </p>
                                        @endif

                                        <div class="mt-auto pt-2">
                                            @if (!empty($phone))
                                                <a href="tel:+{{ preg_replace('/\D+/', '', $phone) }}"
                                                    class="btn btn-success btn-sm mb-2 w-100">
                                                    <i class="fa fa-phone-alt me-2"></i>
                                                    Call Now
                                                </a>
                                            @endif

                                            @if ($type === 'clinic')
                                                <a href="{{ route('clinic.details', ['id' => $resultId]) }}"
                                                    class="btn btn-primary btn-sm w-100">
                                                    <i class="fa fa-eye me-2"></i>
                                                    View Clinic
                                                </a>
                                            @elseif($resultId)
                                                <a href="{{ route('doctor.details', $resultId) }}"
                                                    class="btn btn-outline-primary btn-sm w-100">
                                                    <i class="fa fa-eye me-2"></i>
                                                    View Details
                                                </a>
                                            @else
                                                <a href="#" class="btn btn-outline-primary btn-sm w-100">
                                                    <i class="fa fa-eye me-2"></i>
                                                    View Details
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Advertisement insertion every 6 items --}}
                        @if ($advertisements->isNotEmpty() && $index % 6 === 0)
                            <div class="col-12">
                                <div class="text-center my-4 p-3 bg-light rounded border">
                                    @php $ad = $advertisements->random(); @endphp
                                    @if (data_get($ad, 'image'))
                                        <a href="{{ data_get($ad, 'url', '#') }}" target="_blank">
                                            <img src="{{ asset('admin/uploads/advertisement/' . ltrim(data_get($ad, 'image'), '/')) }}"
                                                alt="Advertisement" class="img-fluid rounded" style="max-height: 150px;">
                                        </a>
                                    @else
                                        <div class="p-4 bg-light border rounded">
                                            <h6>Advertisement</h6>
                                            <p class="small">Promotional content</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                @if ($advertisements->isNotEmpty() && $results->count() > 0)
                    <div class="col-12 mt-4">
                        <div class="text-center p-3 bg-light rounded border">
                            @php $ad = $advertisements->random(); @endphp
                            @if (data_get($ad, 'image'))
                                <a href="{{ data_get($ad, 'url', '#') }}" target="_blank">
                                    <img src="{{ asset('admin/uploads/advertisement/' . ltrim(data_get($ad, 'image'), '/')) }}"
                                        alt="Advertisement" class="img-fluid rounded" style="max-height: 120px;">
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <style>
        /* Mobile-specific adjustments */
        @media (max-width: 767.98px) {
            .display-lg-3 {
                font-size: calc(1.525rem + 3.3vw);
            }

            .h1-lg {
                font-size: 1.75rem;
            }

            .h3-lg {
                font-size: 1.5rem;
            }

            .h4-lg {
                font-size: 1.25rem;
            }

            .fa-4x-lg {
                font-size: 3.5rem;
            }

            .doctor-card {
                padding: 1rem !important;
                margin-bottom: 1rem !important;
            }

            .doctor-card img {
                height: 80px !important;
                width: 80px !important;
                border-radius: 10px !important;
            }

            .doctor-card h5 {
                font-size: 1rem;
                font-weight: 600;
                line-height: 1.3;
            }

            .doctor-card p,
            .doctor-card span,
            .doctor-card small {
                font-size: 0.85rem;
                line-height: 1.4;
            }

            .doctor-card .badge {
                font-size: 0.8rem;
                padding: 0.25rem 0.5rem !important;
                border-radius: 4px;
            }

            .doctor-card .btn {
                font-size: 0.85rem;
                padding: 0.5rem 0.75rem;
            }

            .doctor-card .fa-star {
                font-size: 0.8rem;
            }

            .breadcrumb {
                font-size: 0.8rem;
            }

            .breadcrumb-item.active {
                font-size: 0.75rem;
            }

            .page-header h1 {
                font-size: 1.5rem !important;
                margin-bottom: 1rem !important;
            }

            .container h1 {
                font-size: 1.25rem !important;
                margin-top: 1rem !important;
            }

            .card-body {
                padding: 1rem !important;
            }

            .card-body h6 {
                font-size: 0.95rem;
                font-weight: 600;
            }

            .card-body p {
                font-size: 0.82rem;
                margin-bottom: 0.4rem !important;
            }

            .card-body .btn {
                font-size: 0.82rem;
                padding: 0.4rem 0.75rem;
            }
        }

        /* Extra small devices (phones, less than 576px) */
        @media (max-width: 575.98px) {
            .doctor-card img {
                height: 70px !important;
                width: 70px !important;
            }

            .doctor-card h5 {
                font-size: 0.95rem;
            }

            .doctor-card p,
            .doctor-card span,
            .doctor-card small {
                font-size: 0.8rem;
            }

            .doctor-card .btn {
                font-size: 0.8rem;
                padding: 0.4rem 0.5rem;
            }

            .container {
                padding-left: 10px;
                padding-right: 10px;
            }

            .doctor-card {
                padding: 0.75rem !important;
            }
        }

        /* Small devices (landscape phones, 576px and up) */
        @media (min-width: 576px) and (max-width: 767.98px) {
            .doctor-card img {
                height: 90px !important;
                width: 90px !important;
            }

            .doctor-card h5 {
                font-size: 1.1rem;
            }
        }

        /* Ensure proper spacing on mobile */
        .doctor-card .d-flex.align-items-start {
            align-items: flex-start !important;
        }

        .doctor-card .flex-grow-1 {
            flex: 1;
            min-width: 0;
            /* Prevent flex items from overflowing */
        }

        .doctor-card .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
@endsection
