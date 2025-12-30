{{-- resources/views/Doctor/Dashboard/show.blade.php --}}
@extends('admin.admin-doctor-layout.app')

@section('title', 'Doctor Dashboard')

@section('content')
<main id="main" class="main">
@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use Carbon\Carbon;

    if (! function_exists('safe_string')) {
        function safe_string($v, $fallback='Not provided yet'){
            if ($v === null) return $fallback;
            if (is_string($v)) return trim($v) !== '' ? $v : $fallback;
            if (is_scalar($v)) return (string)$v;
            return $fallback;
        }
    }
    if (! function_exists('fmt_time')) {
        function fmt_time($t, $fallback='-'){
            if (!$t) return $fallback;
            try { return Carbon::parse($t)->format('h:i A'); } catch (\Throwable $e){ return $fallback; }
        }
    }

    /** @var \App\Models\Doctor|null $doctor */
    $doctor = $doctor ?? (Auth::user()->doctor ?? null);

    // avatar
    $avatarUrl = asset('images/default-doctor.png');
    if ($doctor && !empty($doctor->profile_picture)) {
        $path = trim($doctor->profile_picture);
        if (Str::startsWith($path, ['http://','https://']))        $avatarUrl = $path;
        elseif (Str::contains($path, 'storage/'))                   $avatarUrl = asset($path);
        elseif (Storage::disk('public')->exists($path))             $avatarUrl = Storage::disk('public')->url($path);
        elseif (file_exists(public_path($path)))                    $avatarUrl = asset($path);
        elseif (file_exists(public_path('storage/'.$path)))         $avatarUrl = asset('storage/'.$path);
        elseif (file_exists(public_path('admin/uploads/doctor/'.$path))) $avatarUrl = asset('admin/uploads/doctor/'.$path);
        elseif (file_exists(public_path('admin/uploads/doctor/'.($doctor->id ?? '').'/'.$path))) $avatarUrl = asset('admin/uploads/doctor/'.($doctor->id ?? '').'/'.$path);
    }

    // degrees (support both 'degrees' and 'degree' fields; handle JSON, newline, or comma lists)
    $degreesArr = [];
    if ($doctor) {
        $raw = null;
        if (isset($doctor->degrees) && $doctor->degrees !== null && $doctor->degrees !== '') {
            $raw = $doctor->degrees;
        } elseif (isset($doctor->degree) && $doctor->degree !== null && $doctor->degree !== '') {
            $raw = $doctor->degree;
        }

        if (is_array($raw)) {
            $degreesArr = $raw;
        } elseif (is_string($raw) && $raw !== '') {
            $decoded = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $degreesArr = $decoded;
            } else {
                // fallback: split by newlines or commas
                $degreesArr = preg_split("/\r\n|\n|\r|,/", $raw);
            }
        }

        // normalize: trim and remove empty values
        $degreesArr = array_values(array_filter(array_map('trim', (array) $degreesArr)));
    }

    $clinicSchedules = $doctor->clinicSchedules ?? collect();

    // *** ONLY CHANGE: use ONLY the raw address field from DB for display & maps (no concatenation) ***
    $fullAddress = trim(safe_string($doctor->address, ''));
    $mapsUrl = $fullAddress ? 'https://www.google.com/maps/search/?api=1&query='.urlencode($fullAddress) : null;

    // profile completion
    $fields = [
        $doctor->speciality ?? null,
        ($degreesArr ?? []) ? implode(',',$degreesArr) : null,
        $doctor->registration_no ?? null,
        $doctor->council ?? null,
        optional($doctor->category)->name ?? null,
        $doctor->phone_number ?? null,
        $doctor->address ?? null,
        optional($doctor->city)->name ?? null,
        optional($doctor->district)->name ?? null,
        optional($doctor->state)->name ?? null,
        optional($doctor->country)->name ?? null,
        optional($doctor->pincode)->pincode ?? null,
        $doctor->whatsapp ?? null,
        $doctor->website ?? null,
    ];
    $completed = collect($fields)->filter(fn($x)=>!empty($x))->count();
    $total = max(count($fields), 1);
    $completionPct = (int) round(($completed / $total) * 100);
@endphp

<div class="pagetitle">
    <h1>Doctor Dashboard</h1>
    <nav><ol class="breadcrumb"><li class="breadcrumb-item active">Dashboard</li></ol></nav>
</div>

<style>
    /* ===== spacing system ===== */
    :root{
        --bg: #f6f8fb;
        --card: #ffffff;
        --border: #e9eef5;
        --muted: #6b7280;
        --brand: #2563eb;
        --ok: #16a34a;

        --space-1: .5rem;
        --space-2: .75rem;
        --space-3: 1rem;
        --space-4: 1.5rem;
    }
    main.main{ background: var(--bg); }

    /* utility: vertical rhythm */
    .stack > * + * { margin-top: var(--space-2); }
    .stack-lg > * + * { margin-top: var(--space-4); }

    /* ===== flat cards & sections ===== */
    .card-flat{
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(0,0,0,.04);
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
    }
    .section-title{
        display:flex; align-items:center; gap:.6rem;
        font-weight:800; font-size:1.05rem;
        padding-bottom:.5rem; margin-bottom:1rem;
        border-bottom:1px solid var(--border);
    }
    .section-title i{ color: var(--brand); }

    /* make rows stretch so columns are equal height */
    .stretch-row{ align-items: stretch; }
    .stretch{ display:flex; }
    .stretch > .card-flat{ flex:1; }

    /* ===== profile header ===== */
    .profile-wrap{ display:flex; gap:1rem; align-items:flex-start; }
    .avatar{ width:110px; height:110px; border-radius:50%; overflow:hidden; border:3px solid #fff; box-shadow:0 6px 18px rgba(0,0,0,.08); }
    .avatar img{ width:100%; height:100%; object-fit:cover; }
    .doc-name{ font-size:1.35rem; font-weight:800; margin:0; }
    .doc-spec{ font-size:1rem; color:var(--muted); margin-top:.1rem; }

    .chip{ display:inline-flex; align-items:center; gap:.35rem; padding:.2rem .6rem; border-radius:999px; font-size:.78rem; font-weight:700; border:1px solid var(--border); background:#f3f6fc; color:#1d4ed8; }
    .chip.ok{ background:#ecfdf5; color:#065f46; border-color:#a7f3d0; }
    .chips{ display:flex; gap:.4rem; flex-wrap:wrap; margin-top:.5rem; }

    .progress-flat{ width:100%; height:8px; background:#eef2f7; border-radius:999px; overflow:hidden; }
    .progress-flat > span{ display:block; height:100%; background:var(--brand); width:0; transition:width .3s ease; }

    /* ===== label/value grid (perfect alignment) ===== */
    .kv{
        display:grid;
        grid-template-columns: 140px 1fr;
        column-gap: 16px;
        row-gap: 10px;
        align-items: start;
    }
    @media (max-width: 576px){
        .kv{ grid-template-columns: 1fr; }
    }
    .kv .label{ font-weight:800; color:#1f2937; }
    .kv .value{ font-weight:400; color:#111827; }
    .muted{ color:var(--muted); }

    /* socials */
    .social-list{ display:flex; gap:.55rem; flex-wrap:wrap; }
    .soc{
        width:40px; height:40px; border-radius:10px; border:1px solid var(--border);
        display:inline-flex; align-items:center; justify-content:center; background:#fff;
        color:#9ca3af;
    }
    .soc.active.whatsapp{ color:#25d366; border-color:#d6f5e5; }
    .soc.active.email{ color:#1d4ed8; border-color:#c7dbff; }
    .soc.active.facebook{ color:#1877f2; border-color:#cfe0ff; }
    .soc.active.instagram{ color:#d946ef; border-color:#f5d0fe; }
    .soc.active.website{ color:#0ea5e9; border-color:#bae6fd; }

    /* table */
    .table-flat thead th{ background:#f8fafc; border-bottom:2px solid var(--border); font-weight:800; }
    .table-flat td, .table-flat th{ vertical-align: middle; }
    .table-flat tbody tr:hover{ background:#f6f9ff; }
    .day-chip{ display:inline-block; padding:.12rem .5rem; border-radius:6px; background:#eff6ff; color:#1d4ed8; font-weight:700; font-size:.75rem; border:1px solid #bfdbfe; }
    .today-row{ background:#f0fff4 !important; }

    /* buttons */
    .btn-chip{ border-radius:12px; border:1px solid var(--border); background:#fff; font-weight:600; }
    .btn-chip:hover{ border-color:#d6deea; background:#f8fafc; }
    .btn-brand-outline{ border-color: var(--brand); color: var(--brand); }
    .btn-danger-outline{ border-color: #ef4444; color: #ef4444; }

    .link:hover{ text-decoration:underline; }
</style>

<section class="section">
    <div class="row g-4">

        @if(!$doctor)
            <div class="col-12">
                <div class="alert alert-warning">
                    Profile not found. <a class="alert-link" href="{{ route('doctor.profile.edit') }}">Create your profile</a>.
                </div>
            </div>
        @else

        {{-- PROFILE HEADER (full width) --}}
        <div class="col-12">
            <div class="card-flat">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div class="profile-wrap">
                        <div class="avatar"><img src="{{ $avatarUrl }}" alt="Doctor"></div>
                        <div class="stack">
                            <div>
                                <h3 class="doc-name">{{ safe_string($doctor->name ?? Auth::user()->name) }}</h3>
                                <div class="doc-spec">{{ safe_string($doctor->speciality) }}</div>
                            </div>
                            <div class="chips">
                                @if($doctor->status === 'active')
                                    <span class="chip ok"><i class="bi bi-check2-circle"></i> Active</span>
                                @else
                                    <span class="chip" style="background:#fff7ed;color:#92400e;border-color:#fed7aa">
                                        <i class="bi bi-exclamation-triangle"></i> Inactive
                                    </span>
                                @endif
                                <span class="chip"><i class="bi bi-people"></i> Mode: {{ safe_string(ucfirst($doctor->consultation_mode ?? '')) }}</span>
                                <span class="chip"><i class="bi bi-tag"></i> Category: {{ safe_string(optional($doctor->category)->name ?? $doctor->category_id) }}</span>
                            </div>
                            <div class="small">
                                <i class="bi bi-envelope me-1" style="color:#1d4ed8"></i>{{ safe_string($doctor->email ?? Auth::user()->email) }}
                                &nbsp;&nbsp;
                                <i class="bi bi-phone me-1" style="color:#059669"></i>{{ safe_string($doctor->phone_number) }}
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('doctor.profile.edit') }}" class="btn btn-chip btn-brand-outline">
                            <i class="bi bi-pencil-square me-1"></i> Edit Profile
                        </a>
                        <a href="{{ route('logout') }}" class="btn btn-chip btn-danger-outline"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </div>
                </div>

                {{-- completion bar --}}
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="muted">Profile completion</small>
                        <small class="fw-bold">{{ $completionPct }}%</small>
                    </div>
                    <div class="progress-flat"><span style="width: {{ $completionPct }}%"></span></div>
                </div>
            </div>
        </div>

        {{-- INFO ROW (aligned, equal height) --}}
        <div class="col-lg-7 col-12 stretch">
            <div class="card-flat">
                <div class="section-title"><i class="bi bi-geo-alt"></i> Contact Info</div>

                <div class="kv">
                    <div class="label"><i class="bi bi-geo me-1"></i> Address</div>
                    <div class="value">
                        @if($mapsUrl)
                            <a href="{{ $mapsUrl }}" target="_blank" class="link">{{ $fullAddress }}</a>
                        @else
                            <span class="muted">Not provided yet</span>
                        @endif
                    </div>

                    <div class="label"><i class="bi bi-buildings me-1"></i> City</div>
                    <div class="value">{{ safe_string(optional($doctor->city)->name) }}</div>

                    <div class="label"><i class="bi bi-map me-1"></i> District</div>
                    <div class="value">{{ safe_string(optional($doctor->district)->name) }}</div>

                    <div class="label"><i class="bi bi-flag me-1"></i> State</div>
                    <div class="value">{{ safe_string(optional($doctor->state)->name) }}</div>

                    <div class="label"><i class="bi bi-globe me-1"></i> Country</div>
                    <div class="value">{{ safe_string(optional($doctor->country)->name) }}</div>

                    <div class="label"><i class="bi bi-geo-alt-fill me-1"></i> Pincode</div>
                    <div class="value">{{ safe_string(optional($doctor->pincode)->pincode) }}</div>
                </div>

                <div class="section-title" style="border:none; padding:0; margin: var(--space-3) 0 var(--space-2)">
                    <i class="bi bi-share"></i> Social & Links
                </div>
                <div class="social-list">
                    @if($doctor->whatsapp)
                        <a class="soc active whatsapp" href="https://wa.me/{{ preg_replace('/\D/','',$doctor->whatsapp) }}" target="_blank" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                    @else <span class="soc" title="WhatsApp not provided"><i class="bi bi-whatsapp"></i></span> @endif

                    @if($doctor->email ?? Auth::user()->email)
                        <a class="soc active email" href="mailto:{{ e($doctor->email ?? Auth::user()->email) }}" title="Email"><i class="bi bi-envelope-fill"></i></a>
                    @else <span class="soc" title="Email not provided"><i class="bi bi-envelope"></i></span> @endif

                    @if($doctor->facebook)
                        <a class="soc active facebook" href="{{ e($doctor->facebook) }}" target="_blank" title="Facebook"><i class="bi bi-facebook"></i></a>
                    @else <span class="soc" title="Facebook not provided"><i class="bi bi-facebook"></i></span> @endif

                    @if($doctor->instagram)
                        <a class="soc active instagram" href="{{ e($doctor->instagram) }}" target="_blank" title="Instagram"><i class="bi bi-instagram"></i></a>
                    @else <span class="soc" title="Instagram not provided"><i class="bi bi-instagram"></i></span> @endif

                    @if($doctor->website)
                        <a class="soc active website" href="{{ e($doctor->website) }}" target="_blank" title="Website"><i class="bi bi-globe2"></i></a>
                    @else <span class="soc" title="Website not provided"><i class="bi bi-globe2"></i></span> @endif
                </div>
                <!--<div class="small muted mt-1">Gray = not provided yet</div>-->
            </div>
        </div>

        <div class="col-lg-5 col-12 stretch">
            <div class="card-flat">
                <div class="section-title"><i class="bi bi-clipboard-check"></i> Basic & Professional Info</div>

                <div class="kv">
                    <div class="label">Degrees</div>
                    <div class="value">{{ !empty($degreesArr) ? implode(', ', $degreesArr) : 'Not provided yet' }}</div>

                    <div class="label">Registration No</div>
                    <div class="value">{{ safe_string($doctor->registration_no) }}</div>

                    <div class="label">Council</div>
                    <div class="value">{{ safe_string($doctor->council) }}</div>

                    <div class="label">Category</div>
                    <div class="value">{{ safe_string(optional($doctor->category)->name ?? $doctor->category_id) }}</div>
                </div>

                <div class="stack" style="margin-top: var(--space-3)">
                    <span class="label">Profile Details</span>
                    <div class="p-3 border rounded bg-light">{!! nl2br(e(safe_string($doctor->profile_details))) !!}</div>
                </div>
            </div>
        </div>

        {{-- CLINIC SCHEDULES --}}
        <div class="col-12">
            <div class="card-flat">
                <div class="section-title"><i class="bi bi-calendar2-check"></i> Clinic Schedules</div>

                @php $todayName = strtolower(Carbon::now()->format('l')); @endphp

                @if($clinicSchedules->isEmpty())
                    <div class="muted">No schedules yet. <a class="link" href="{{ route('doctor.profile.edit') }}">Add a clinic schedule +</a></div>
                @else
                    <div class="table-responsive">
                        <table class="table table-flat align-middle">
                            <thead>
                                <tr>
                                    <th style="width:40px">#</th>
                                    <th>Clinic</th>
                                    <th style="width:220px">Days</th>
                                    <th style="width:120px">Start</th>
                                    <th style="width:120px">End</th>
                                    <th style="width:200px">Alternative Text</th>
                                    <th>Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clinicSchedules as $idx => $s)
                                    @php
                                        $daysStr = is_array($s->days) ? implode(', ', $s->days) : safe_string($s->days, '');
                                        $daysArr = array_filter(array_map('trim', explode(',', strtolower($daysStr))));
                                        $isToday = in_array($todayName, $daysArr, true);
                                    @endphp
                                    <tr class="{{ $isToday ? 'today-row' : '' }}">
                                        <td>{{ $idx + 1 }}</td>
                                        <td class="fw-bold"><i class="bi bi-hospital me-1"></i>{{ safe_string(optional($s->clinic)->name ?? $s->clinic_id) }}</td>
                                        <td>
                                            @forelse($daysArr as $d)
                                                <span class="day-chip me-1">{{ ucfirst($d) }}</span>
                                            @empty
                                                <span class="muted">Not provided yet</span>
                                            @endforelse
                                        </td>
                                        <td>{{ fmt_time($s->start_time ?? $s->start) }}</td>
                                        <td>{{ fmt_time($s->end_time ?? $s->end) }}</td>
                                        <td>{{ safe_string($s->alternative_text, '-') }}</td>
                                        <td>
                                            @php $addr = safe_string($s->clinic_address, ''); @endphp
                                            @if($addr)
                                                <a class="link" target="_blank" href="{{ 'https://www.google.com/maps/search/?api=1&query='.urlencode($addr) }}">
                                                    <i class="bi bi-geo-alt me-1"></i>{{ $addr }}
                                                </a>
                                            @else
                                                <span class="muted">Not provided yet</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        @endif
    </div>
</section>
</main>
@endsection
