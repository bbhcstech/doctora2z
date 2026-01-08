{{-- resources/views/Doctor/Profile/show.blade.php (Colored icons everywhere) --}}
@extends('admin.admin-doctor-layout.app')

@section('title', 'Doctor Profile')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Doctor Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </nav>
        </div>

        <style>
            :root {
                --card-bd: #e9ecef;
                --muted: #6b7280;
            }

            body {
                font-family: 'Inter', 'Roboto', sans-serif;
            }

            .profile-container {
                display: grid;
                gap: 24px;
            }

            @media (min-width:992px) {
                .profile-container {
                    grid-template-columns: 1fr 2fr;
                }
            }

            .profile-card,
            .info-card {
                background: #fff;
                border-radius: 12px;
                padding: 24px;
                border: 1px solid var(--card-bd);
                box-shadow: 0 4px 20px rgba(0, 0, 0, .05);
            }

            .avatar-container {
                width: 150px;
                height: 150px;
                border-radius: 50%;
                overflow: hidden;
                margin: 0 auto 8px;
            }

            .avatar-container img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .profile-heading {
                font-size: 1.75rem;
                font-weight: 600;
            }

            .profile-sub-heading {
                color: var(--muted);
            }

            .info-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 16px;
            }

            .clinic-schedule-item {
                background: #f8f9fa;
                padding: 8px;
                border-radius: 8px;
                border: 1px solid var(--card-bd);
                margin-bottom: 8px;
            }

            /* section headings (bold + colored + circular icon) */
            .section-title {
                font-weight: 700;
                color: #1e40af;
                margin-bottom: 1rem;
                display: flex;
                align-items: center;
                gap: .5rem;
            }

            .section-title .circle-icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 28px;
                height: 28px;
                border-radius: 50%;
                font-size: 16px;
                font-weight: 800;
                line-height: 1;
            }

            /* per-section colors */
            .info-sections .info-card:nth-of-type(1) .circle-icon {
                border: 2px solid #2563eb;
                color: #2563eb;
                background: #eff6ff;
            }

            /* Contact - blue */
            .info-sections .info-card:nth-of-type(2) .circle-icon {
                border: 2px solid #16a34a;
                color: #16a34a;
                background: #f0fdf4;
            }

            /* Professional - green */
            .info-sections .info-card:nth-of-type(3) .circle-icon {
                border: 2px solid #f59e0b;
                color: #b45309;
                background: #fffbeb;
            }

            /* Clinics - amber */
            .info-sections .info-card:nth-of-type(4) .circle-icon {
                border: 2px solid #9333ea;
                color: #9333ea;
                background: #f5f3ff;
            }

            /* Social - purple */

            /* bold "points" (labels) */
            .info-item .info-label {
                font-weight: 700;
                color: #374151;
                display: flex;
                align-items: center;
                gap: .5rem;
                margin-bottom: 2px;
            }

            .info-item .info-value {
                color: #111827;
            }

            /* phone inside blue box */
            .phone-box {
                background: #e0f2fe;
                color: #0c4a6e;
                font-weight: 700;
                padding: 6px 12px;
                border-radius: 6px;
                display: inline-block;
                border: 1px solid #bae6fd;
            }

            .text-muted-85 {
                color: #6b7280;
                font-size: .875rem;
            }

            /* ---------- colorful inline icons ---------- */
            .ico {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 26px;
                height: 26px;
                border-radius: 50%;
                font-size: 14px;
            }

            .ico-mail {
                background: #eff6ff;
                color: #1d4ed8;
            }

            .ico-phone {
                background: #eef2ff;
                color: #3730a3;
            }

            .ico-wa {
                background: #ecfdf5;
                color: #047857;
            }

            .ico-addr {
                background: #fef2f2;
                color: #b91c1c;
            }

            .ico-degree {
                background: #fffbeb;
                color: #b45309;
            }

            .ico-people {
                background: #f0fdf4;
                color: #15803d;
            }

            .ico-globe {
                background: #f5f3ff;
                color: #7c3aed;
            }

            .ico-fb {
                background: #eff6ff;
                color: #2563eb;
            }

            .ico-ig {
                background: #fff1f2;
                color: #db2777;
            }

            /* make bootstrap icons sit nicely in our colored circles */
            .ico>i {
                line-height: 1;
            }
        </style>

        <div class="container mt-4">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @php
                use Illuminate\Support\Str;
                use Illuminate\Support\Facades\Storage;
                use Carbon\Carbon;
                use Illuminate\Support\Facades\Route;

                /** @var \App\Models\Doctor|null $d */
                $d = $doctor ?? (Auth::user()->doctor ?? null);

                if (!function_exists('safe_string')) {
                    function safe_string($value, $fallback = '-')
                    {
                        if (is_null($value) || $value === '') {
                            return $fallback;
                        }
                        if (is_scalar($value)) {
                            return (string) $value;
                        }
                        if (is_array($value)) {
                            $flat = array_filter($value, fn($v) => is_scalar($v) && $v !== '');
                            return !empty($flat) ? implode(', ', $flat) : json_encode($value);
                        }
                        if (is_object($value)) {
                            if (method_exists($value, 'toArray')) {
                                $arr = $value->toArray();
                                $flat = array_filter($arr, fn($v) => is_scalar($v) && $v !== '');
                                return !empty($flat) ? implode(', ', $flat) : json_encode($arr);
                            }
                            if (method_exists($value, '__toString')) {
                                return (string) $value;
                            }
                            return json_encode($value);
                        }
                        return (string) $value;
                    }
                }

                // profile picture resolve
                $profileUrl = asset('images/default-doctor.png');
                if ($d && !empty($d->profile_picture)) {
                    $path = trim($d->profile_picture);
                    if (Str::startsWith($path, ['http://', 'https://'])) {
                        $profileUrl = $path;
                    } elseif (Str::contains($path, 'storage/')) {
                        $profileUrl = asset($path);
                    } elseif (Storage::disk('public')->exists($path)) {
                        $profileUrl = Storage::disk('public')->url($path);
                    } elseif (file_exists(public_path($path))) {
                        $profileUrl = asset($path);
                    } elseif (file_exists(public_path('storage/' . $path))) {
                        $profileUrl = asset('storage/' . $path);
                    } elseif (file_exists(public_path('admin/uploads/doctor/' . $path))) {
                        $profileUrl = asset('admin/uploads/doctor/' . $path);
                    } elseif (file_exists(public_path('admin/uploads/doctor/' . ($d->id ?? '') . '/' . $path))) {
                        $profileUrl = asset('admin/uploads/doctor/' . ($d->id ?? '') . '/' . $path);
                    }
                }

                /* clinics display */
                $displayClinics = [];
                $schedRows = collect();
                if ($d) {
                    if ($d->relationLoaded('clinicSchedules')) {
                        $schedRows = collect($d->clinicSchedules);
                    } else {
                        try {
                            $schedRows = \App\Models\DoctorClinicScheduler::where('doctor_profile_id', $d->id)->get();
                        } catch (\Throwable $e) {
                            $schedRows = collect();
                        }
                    }
                }
                if ($schedRows->isNotEmpty()) {
                    $grouped = [];
                    foreach ($schedRows as $r) {
                        $cid = $r->clinic_id ?? null;
                        $alt = trim((string) ($r->alternative_text ?? ''));
                        $addr = trim((string) ($r->clinic_address ?? ''));
                        $clinicName = $r->clinic->name ?? ($alt ?? '');
                        $key = ($cid !== null ? (string) $cid : '') . '||' . ($clinicName ?? '');
                        if (!isset($grouped[$key])) {
                            $grouped[$key] = [
                                'clinic_id' => $cid ?? '',
                                'clinic_name' => $clinicName ?? '',
                                'clinic_address' => $addr ?? '',
                                'schedules' => [],
                            ];
                        }
                        $days = $r->days ?? [];
                        if (is_string($days) && trim($days) !== '') {
                            $try = @json_decode($days, true);
                            $days = is_array($try) ? $try : [$days];
                        } elseif (!is_array($days)) {
                            $days = (array) $days;
                        }
                        $start = $r->start_time ?? ($r->start ?? null);
                        $end = $r->end_time ?? ($r->end ?? null);
                        try {
                            if ($start) {
                                $start = Carbon::parse($start)->format('H:i');
                            }
                        } catch (\Throwable $ex) {
                        }
                        try {
                            if ($end) {
                                $end = Carbon::parse($end)->format('H:i');
                            }
                        } catch (\Throwable $ex) {
                        }
                        if (empty($days)) {
                            $days = ['monday'];
                        }
                        foreach ($days as $day) {
                            $grouped[$key]['schedules'][] = [
                                'day' => is_string($day) ? strtolower(trim($day)) : (string) $day,
                                'start' => $start ?: '',
                                'end' => $end ?: '',
                            ];
                        }
                    }
                    foreach ($grouped as $g) {
                        if (empty($g['schedules'])) {
                            $g['schedules'] = [['day' => 'monday', 'start' => '', 'end' => '']];
                        }
                        $displayClinics[] = $g;
                    }
                } else {
                    $rawClinics = $d->clinics ?? null;
                    if ($rawClinics instanceof \Illuminate\Support\Collection) {
                        $rawClinics = $rawClinics->toArray();
                    }
                    if (is_string($rawClinics) && trim($rawClinics) !== '') {
                        $try = @json_decode($rawClinics, true);
                        if (is_array($try)) {
                            $rawClinics = $try;
                        } else {
                            $un = @unserialize($rawClinics);
                            $rawClinics = $un !== false && is_array($un) ? $un : null;
                        }
                    }
                    if (is_array($rawClinics)) {
                        foreach ($rawClinics as $c) {
                            $c = is_array($c) ? $c : (array) $c;
                            $schedules = $c['schedules'] ?? ($c['schedule'] ?? null);
                            if (is_string($schedules) && $schedules !== '') {
                                $sdec = @json_decode($schedules, true);
                                $schedules = is_array($sdec) ? $sdec : [];
                            } elseif ($schedules instanceof \Illuminate\Support\Collection) {
                                $schedules = $schedules->toArray();
                            } elseif (!is_array($schedules)) {
                                $schedules = [];
                            }
                            $displayClinics[] = [
                                'clinic_id' => $c['clinic_id'] ?? ($c['id'] ?? ''),
                                'clinic_name' => $c['clinic_name'] ?? ($c['name'] ?? ''),
                                'clinic_address' => $c['address'] ?? ($c['clinic_address'] ?? ''),
                                'schedules' => $schedules,
                            ];
                        }
                    }
                }
            @endphp

            @if (!$d)
                <div class="alert alert-warning">
                    No doctor profile found.
                    <a href="{{ route('doctor.profile.edit') }}" class="alert-link">Create profile</a>
                </div>
            @else
                <div class="profile-container">
                    {{-- left: profile --}}
                    <div class="profile-card">
                        <div class="profile-card-header text-center">
                            <div class="avatar-container">
                                <img src="{{ $profileUrl }}" alt="Profile Picture">
                            </div>
                            <h3 class="profile-heading">{{ safe_string($d->name) }}</h3>
                            <p class="profile-sub-heading mb-1">{{ safe_string($d->speciality ?? 'Speciality not set') }}
                            </p>
                            <p class="text-muted" style="font-size:.875rem;">{{ safe_string($d->degree) }}</p>
                        </div>

                        <hr class="my-4" style="border-color:#e9ecef;">

                        <div class="d-grid gap-2">
                            <a href="{{ route('doctor.profile.edit', $d->id ?? '') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-pencil-square me-2"></i>Edit Profile
                            </a>
                        </div>

                        <div class="mt-4 info-grid">
                            <div class="info-item">
                                <span class="info-label">Member since</span>
                                <span
                                    class="info-value">{{ $d->created_at ? \Carbon\Carbon::parse($d->created_at)->toFormattedDateString() : '-' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Status</span>
                                <span
                                    class="info-value text-{{ ($d->status ?? 'inactive') === 'active' ? 'success' : 'danger' }}">{{ ucfirst($d->status ?? 'inactive') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- right: information cards --}}
                    <div class="info-sections">

                        {{-- Contact & Basic --}}
                        <div class="info-card mb-4">
                            <h5 class="section-title">
                                <span class="circle-icon">+</span>
                                Contact & Basic Information
                            </h5>

                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">
                                        <span class="ico ico-mail"><i class="bi bi-envelope"></i></span>
                                        Email
                                    </span>
                                    <span class="info-value">{{ safe_string($d->email ?? Auth::user()->email) }}</span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">
                                        <span class="ico ico-phone"><i class="bi bi-phone"></i></span>
                                        Phone
                                    </span>
                                    <span class="info-value phone-box">
                                        {{ safe_string($d->phone_number) }}{{ !empty($d->phone_number_2) ? ', ' . safe_string($d->phone_number_2) : '' }}
                                    </span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">
                                        <span class="ico ico-wa"><i class="bi bi-whatsapp"></i></span>
                                        WhatsApp
                                    </span>
                                    <span class="info-value">{{ safe_string($d->whatsapp) }}</span>
                                </div>

                                <div class="info-item" style="grid-column:1 / -1;">
                                    <span class="info-label">
                                        <span class="ico ico-addr"><i class="bi bi-geo-alt"></i></span>
                                        Address
                                    </span>
                                    <span class="info-value">{{ safe_string($d->address) }}</span>
                                    <span class="text-muted-85">
                                        {{ optional($d->city)->name ? safe_string(optional($d->city)->name) . ', ' : '' }}
                                        {{ optional($d->district)->name ? safe_string(optional($d->district)->name) . ', ' : '' }}
                                        {{ optional($d->state)->name ? safe_string(optional($d->state)->name) . ', ' : '' }}
                                        {{ optional($d->country)->name ? safe_string(optional($d->country)->name) . ', ' : '' }}
                                        {{ optional($d->pincode)->pincode ?? safe_string($d->pincode_id) }}
                                    </span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">
                                        <span class="ico ico-degree"><i class="bi bi-mortarboard"></i></span>
                                        Degrees
                                    </span>
                                    <span class="info-value">
                                        @if (!empty($d->degrees) && is_array($d->degrees))
                                            {{ implode(', ', $d->degrees) }}
                                        @else
                                            {{ safe_string($d->degree) }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Professional & Practice --}}
                        <div class="info-card mb-4">
                            <h5 class="section-title">
                                <span class="circle-icon"><i class="bi bi-people"></i></span>
                                Professional & Practice Details
                            </h5>

                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Registration No</span>
                                    <span class="info-value">{{ safe_string($d->registration_no) }}</span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">Council</span>
                                    <span class="info-value">{{ safe_string($d->council) }}</span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">Category</span>
                                    <span
                                        class="info-value">{{ optional($d->category)->name ?? safe_string($d->category_id) }}</span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label d-flex align-items-center gap-2">
                                        <span class="ico ico-people"><i class="bi bi-people"></i></span>
                                        Consultation Mode
                                    </span>
                                    <span
                                        class="info-value">{{ safe_string(ucfirst($d->consultation_mode ?? '-')) }}</span>
                                </div>

                                <div class="info-item" style="grid-column:1 / -1;">
                                    <span class="info-label">Profile Details</span>
                                    <span class="info-value">{{ safe_string($d->profile_details) }}</span>
                                </div>

                                <div class="info-item" style="grid-column:1 / -1;">
                                    <span class="info-label">User account</span>
                                    <span class="info-value">
                                        @if (!empty($d->user))
                                            @if (Route::has('admin.users.show'))
                                                <a
                                                    href="{{ route('admin.users.show', $d->user->id) }}">{{ safe_string($d->user->name ?? $d->user->email) }}</a>
                                            @else
                                                {{ safe_string($d->user->name ?? $d->user->email) }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Clinics & Schedules --}}
                        <div class="info-card mb-4">
                            <h5 class="section-title">
                                <span class="circle-icon">+</span>
                                Clinics & Schedules
                            </h5>

                            @if (empty($displayClinics))
                                <p class="text-muted">No clinics listed.</p>
                            @else
                                <div class="accordion" id="clinicsAccordion">
                                    @foreach ($displayClinics as $cidx => $c)
                                        @php
                                            $c = is_array($c) ? $c : (array) $c;
                                            $clinic_name = $c['clinic_name'] ?? ($c['name'] ?? 'Clinic ' . ($cidx + 1));
                                            $clinic_id = $c['clinic_id'] ?? '';
                                            $clinic_address = $c['clinic_address'] ?? ($c['address'] ?? '');
                                            $schedules = $c['schedules'] ?? [];
                                            if (
                                                !is_array($schedules) &&
                                                $schedules instanceof \Illuminate\Support\Collection
                                            ) {
                                                $schedules = $schedules->toArray();
                                            }
                                        @endphp

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $cidx }}">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapse{{ $cidx }}" aria-expanded="false"
                                                    aria-controls="collapse{{ $cidx }}">
                                                    <strong>{{ safe_string($clinic_name) }}</strong>
                                                    <small class="ms-auto text-muted">ID:
                                                        {{ safe_string($clinic_id ?: '-') }}</small>
                                                </button>
                                            </h2>

                                            <div id="collapse{{ $cidx }}" class="accordion-collapse collapse"
                                                aria-labelledby="heading{{ $cidx }}"
                                                data-bs-parent="#clinicsAccordion">
                                                <div class="accordion-body">
                                                    <p class="text-muted mb-2"><i
                                                            class="bi bi-geo-alt me-1"></i>{{ safe_string($clinic_address ?: 'Address not specified') }}
                                                    </p>

                                                    <div class="info-grid mt-3">
                                                        @if (empty($schedules))
                                                            <p class="text-muted mb-0">No schedule set for this clinic.</p>
                                                        @else
                                                            @foreach ($schedules as $sch)
                                                                @php
                                                                    $sch = is_array($sch) ? $sch : (array) $sch;
                                                                    $day = $sch['day'] ?? ($sch['week_day'] ?? '');
                                                                    $start =
                                                                        $sch['start'] ??
                                                                        ($sch['start_time'] ??
                                                                            ($sch['startTime'] ?? ''));
                                                                    $end =
                                                                        $sch['end'] ??
                                                                        ($sch['end_time'] ?? ($sch['endTime'] ?? ''));
                                                                @endphp
                                                                <div class="clinic-schedule-item">
                                                                    <strong>{{ ucfirst(safe_string($day ?: '-')) }}</strong>
                                                                    <div class="text-muted" style="font-size:.875rem;">
                                                                        {{ $start && $end ? safe_string($start) . ' - ' . safe_string($end) : '-' }}
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Social & Website --}}
                        <div class="info-card mb-4">
                            <h5 class="section-title">
                                <span class="circle-icon"><i class="bi bi-globe"></i></span>
                                Social & Website
                            </h5>

                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">
                                        <span class="ico ico-globe"><i class="bi bi-globe"></i></span>
                                        Website
                                    </span>
                                    <span class="info-value">
                                        @if (!empty($d->website))
                                            <a href="{{ safe_string($d->website) }}"
                                                target="_blank">{{ \Illuminate\Support\Str::limit(safe_string($d->website), 30) }}</a>
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">
                                        <span class="ico ico-fb"><i class="bi bi-facebook"></i></span>
                                        Facebook
                                    </span>
                                    <span class="info-value">
                                        @if ($d->facebook)
                                            <a href="{{ safe_string($d->facebook) }}" target="_blank">View Profile</a>
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">
                                        <span class="ico ico-ig"><i class="bi bi-instagram"></i></span>
                                        Instagram
                                    </span>
                                    <span class="info-value">
                                        @if ($d->instagram)
                                            <a href="{{ safe_string($d->instagram) }}" target="_blank">View Profile</a>
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endif
        </div>
    </main>
@endsection
