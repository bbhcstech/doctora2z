@extends('admin.layout.app')

@section('title', 'Doctor Profile')

@push('styles')
<style>
  :root {
    --card-radius: .875rem;
  }
  .break-anywhere { overflow-wrap: anywhere; word-break: break-word; }
  .avatar-square { width: 110px; height: 110px; border-radius: var(--card-radius); overflow: hidden; }
  @media (min-width: 576px) { .avatar-square { width: 120px; height: 120px; } }
  .mini-title { font-size: .95rem; font-weight: 600; }
  .soft-card { border-radius: var(--card-radius); }
  .badge-soft { background: #f8f9fa; color: #212529; border: 1px solid #e9ecef; }
  .icon-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; background:#0d6efd; margin-right:.4rem; }
  .nav-tabs.nav-tabs-bordered { overflow-x: auto; flex-wrap: nowrap; }
  .nav-tabs .nav-link { white-space: nowrap; }
</style>
@endpush

@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1 class="mb-2">Doctor Profile</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
          @if(Auth::check())
            @if(Auth::user()->role === 'admin')
              <a href="{{ route('dashboard') }}" class="d-inline-flex align-items-center gap-2">
                <img src="{{ asset('admin/assets/img/logo.png') }}" alt="Micro Poem Admin" class="img-fluid" style="max-height:24px">
                <span class="d-none d-lg-inline">Micro Poem Admin</span>
              </a>
            @elseif(Auth::user()->role === 'manager')
              <a href="{{ route('manager.dashboard') }}" class="d-inline-flex align-items-center gap-2">
                <img src="{{ asset('admin/assets/img/logo.png') }}" alt="Micro Poem Manager" class="img-fluid" style="max-height:24px">
                <span class="d-none d-lg-inline">Micro Poem Manager</span>
              </a>
            @endif
          @endif
        </li>
        <li class="breadcrumb-item">Users</li>
        <li class="breadcrumb-item active" aria-current="page">Doctor Profile</li>
      </ol>
    </nav>
  </div>

  <!-- Full-width fluid container -->
  <section class="section profile">
    <div class="container-fluid">
      <div class="row g-3">

        <!-- Left profile summary -->
        <div class="col-12 col-xl-3">
          <div class="card soft-card h-100">
            <div class="card-body p-3 p-md-4">
              <div class="d-flex flex-column align-items-center text-center">
                <div class="avatar-square mb-3">
                  @php $hasPicture = !empty($doctor->profile_picture) && file_exists(public_path('storage/'.$doctor->profile_picture)); @endphp
                  @if($hasPicture)
                    <img src="{{ asset('storage/'.$doctor->profile_picture) }}" alt="{{ $doctor->name }}" class="w-100 h-100" style="object-fit:cover;">
                  @else
                    <img src="{{ asset('images/default-doctor.png') }}" alt="Default" class="w-100 h-100" style="object-fit:cover;">
                  @endif
                </div>
                <h2 class="h5 mb-1 break-anywhere">{{ $doctor->name }}</h2>
                <div class="text-muted small mb-3 break-anywhere">{{ $doctor->speciality ?? 'Speciality not set' }}</div>
                <div class="small text-secondary mb-1"><strong>Degree:</strong> {{ $doctor->degree ?? '-' }}</div>
                <div class="small text-secondary"><strong>Reg. No:</strong> {{ $doctor->registration_no ?? '-' }}</div>
                <div class="d-grid mt-3 w-100">
                  <a href="{{ route('doctor.profile.edit2') }}" class="btn btn-primary">Edit Profile</a>
                </div>
              </div>
              <hr class="my-3">
              <div class="small">
                <div class="d-flex justify-content-between"><span class="text-muted">Member since</span><span>{{ optional($doctor->created_at)->format('M d, Y') ?? '-' }}</span></div>
                <div class="d-flex justify-content-between mt-1"><span class="text-muted">Status</span><span class="text-success">Active</span></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right content -->
        <div class="col-12 col-xl-9">
          <div class="row g-3">

            <!-- Contact & Basic Information -->
            <div class="col-12">
              <div class="card soft-card h-100">
                <div class="card-body p-3 p-md-4">
                  <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="icon-dot"></span>
                    <div class="mini-title">Contact & Basic Information</div>
                  </div>
                  <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3">
                    <div class="col">
                      <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small">Email</div>
                        <div class="break-anywhere">{{ $doctor->email ?? '-' }}</div>
                      </div>
                    </div>
                    <div class="col">
                      <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small">Phone</div>
                        <div class="break-anywhere">{{ $doctor->phone_number ?? '-' }}</div>
                      </div>
                    </div>
                    <div class="col">
                      <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small">WhatsApp</div>
                        <div class="break-anywhere">{{ $doctor->whatsapp ?? '-' }}</div>
                      </div>
                    </div>
                    <div class="col">
                      <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small">Address</div>
                        <div class="break-anywhere">{{ $doctor->address ?? '-' }}</div>
                      </div>
                    </div>
                    <div class="col">
                      <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small">Council</div>
                        <div class="break-anywhere">{{ $doctor->council ?? '-' }}</div>
                      </div>
                    </div>
                    <div class="col">
                      <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small">Website</div>
                        <div>
                          @if(!empty($doctor->website))
                            <a class="break-anywhere" href="{{ $doctor->website }}" target="_blank" rel="noopener">{{ $doctor->website }}</a>
                          @else
                            -
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Professional & Practice Details -->
            <div class="col-12">
              <div class="card soft-card h-100">
                <div class="card-body p-3 p-md-4">
                  <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="icon-dot"></span>
                    <div class="mini-title">Professional & Practice Details</div>
                  </div>
                  <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3">
                    <div class="col">
                      <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small">Registration No</div>
                        <div class="break-anywhere">{{ $doctor->registration_no ?? '-' }}</div>
                      </div>
                    </div>
                    <div class="col">
                      <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small">Council</div>
                        <div class="break-anywhere">{{ $doctor->council ?? '-' }}</div>
                      </div>
                    </div>
                    <div class="col">
                      <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small">Category</div>
                        <div class="break-anywhere">{{ $doctor->speciality ?? '-' }}</div>
                      </div>
                    </div>
                    <div class="col">
                      <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small">Consultation Mode</div>
                        <div class="break-anywhere">{{ $doctor->consultation_mode ?? '-' }}</div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small mb-1">Profile Details</div>
                        <div class="break-anywhere" style="white-space:pre-wrap;">{{ $doctor->profile_details ?? '-' }}</div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small">User account</div>
                        <div class="break-anywhere">{{ $doctor->name }}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Clinics & Schedules -->
            <div class="col-12">
              <div class="card soft-card h-100">
                <div class="card-body p-3 p-md-4">
                  <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="icon-dot"></span>
                    <div class="mini-title">Clinics & Schedules</div>
                  </div>
                  <div class="row g-3">
                    <div class="col-12 col-md-6 col-xl-4">
                      <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small">Clinic / Hospital</div>
                        <div class="break-anywhere">{{ $doctor->clinic_name ?? '-' }}</div>
                      </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-4">
                      <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small">Clinic Days</div>
                        @php
                          $days = is_array($doctor->clinic_days) ? $doctor->clinic_days : (!empty($doctor->clinic_days) ? explode(',', $doctor->clinic_days) : []);
                          $days = array_filter(array_map('trim', $days));
                        @endphp
                        @if(count($days))
                          <div class="d-flex flex-wrap gap-1 mt-1">
                            @foreach($days as $d)
                              <span class="badge badge-soft">{{ $d }}</span>
                            @endforeach
                          </div>
                        @else
                          <div>-</div>
                        @endif
                      </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-4">
                      <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small">Clinic Hours</div>
                        <div class="break-anywhere">{{ $doctor->clinic_start_time ?? '-' }} — {{ $doctor->clinic_end_time ?? '-' }}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Social & Website -->
            <div class="col-12">
              <div class="card soft-card h-100">
                <div class="card-body p-3 p-md-4">
                  <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="icon-dot"></span>
                    <div class="mini-title">Social & Website</div>
                  </div>
                  <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3">
                    <div class="col">
                      <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small">Website</div>
                        <div>
                          @if(!empty($doctor->website))
                            <a class="break-anywhere" href="{{ $doctor->website }}" target="_blank" rel="noopener">{{ $doctor->website }}</a>
                          @else
                            -
                          @endif
                        </div>
                      </div>
                    </div>
                    <!-- Add more social links if you store them in DB in future -->
                    <div class="col">
                      <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small">Facebook</div>
                        <div>-</div>
                      </div>
                    </div>
                    <div class="col">
                      <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small">Instagram</div>
                        <div>-</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </section>
</main>
@endsection
