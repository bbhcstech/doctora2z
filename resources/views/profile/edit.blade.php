@extends('admin.layout.app')

@section('title', 'Edit Doctor Profile')

@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Edit Profile</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          @if(Auth::check())
            @if(Auth::user()->role === 'admin')
              <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center">
                <img src="{{ asset('admin/assets/img/logo.png') }}" alt="">
                <span class="d-none d-lg-block">Micro Poem Admin</span>
              </a>
            @elseif(Auth::user()->role === 'manager')
              <a href="{{ route('manager.dashboard') }}" class="logo d-flex align-items-center">
                <img src="{{ asset('admin/assets/img/logo.png') }}" alt="">
                <span class="d-none d-lg-block">Micro Poem Manager</span>
              </a>
            @endif
          @endif
        </li>
        <li class="breadcrumb-item">Users</li>
        <li class="breadcrumb-item active">Edit Profile</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section profile">
    <div class="row">
      <div class="col-xl-8">
        <div class="card">
          <div class="card-body pt-3">

            {{-- Flash success --}}
            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Form --}}
            <form action="{{ route('doctor.profile.update', $doctor->id ?? null) }}"
                  method="POST"
                  enctype="multipart/form-data">
              @csrf
              @method('PUT')

              <div class="row g-3">

                <div class="col-md-12">
                  <label class="form-label">Display name</label>
                  <input type="text" name="name" class="form-control"
                         value="{{ old('name', $doctor->name) }}" required>
                  @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                  <label class="form-label">Speciality</label>
                  <input type="text" name="speciality" class="form-control"
                         value="{{ old('speciality', $doctor->speciality) }}">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Degree</label>
                  <input type="text" name="degree" class="form-control"
                         value="{{ old('degree', $doctor->degree) }}">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Phone (primary)</label>
                  <input type="text" name="phone_number" class="form-control"
                         value="{{ old('phone_number', $doctor->phone_number) }}">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Phone (alternate)</label>
                  <input type="text" name="phone_number_2" class="form-control"
                         value="{{ old('phone_number_2', $doctor->phone_number_2) }}">
                </div>

                <div class="col-md-12">
                  <label class="form-label">Clinic / Hospital</label>
                  <input type="text" name="clinic_name" class="form-control"
                         value="{{ old('clinic_name', $doctor->clinic_name) }}">
                </div>

                {{-- Clinic days --}}
                @php
                  $storedDays = $doctor->clinic_days;
                  $storedDaysArr = is_string($storedDays)
                      ? array_map('trim', explode(',', $storedDays))
                      : (is_array($storedDays) ? $storedDays : []);
                  $week = ['Mon'=>'Monday','Tue'=>'Tuesday','Wed'=>'Wednesday','Thu'=>'Thursday','Fri'=>'Friday','Sat'=>'Saturday','Sun'=>'Sunday'];
                @endphp
                <div class="col-md-12">
                  <label class="form-label">Clinic days</label><br>
                  @foreach($week as $token => $label)
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox"
                             id="day_{{ $token }}" name="clinic_days[]"
                             value="{{ $token }}" {{ in_array($token, $storedDaysArr) ? 'checked' : '' }}>
                      <label class="form-check-label" for="day_{{ $token }}">{{ $label }}</label>
                    </div>
                  @endforeach
                </div>

                <div class="col-md-6">
                  <label class="form-label">Clinic start time</label>
                  <input type="time" name="clinic_start_time" class="form-control"
                         value="{{ old('clinic_start_time', $doctor->clinic_start_time) }}">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Clinic end time</label>
                  <input type="time" name="clinic_end_time" class="form-control"
                         value="{{ old('clinic_end_time', $doctor->clinic_end_time) }}">
                </div>

                <div class="col-md-12">
                  <label class="form-label">Website</label>
                  <input type="url" name="website" class="form-control"
                         value="{{ old('website', $doctor->website) }}">
                </div>

                <div class="col-md-12">
                  <label class="form-label">WhatsApp</label>
                  <input type="text" name="whatsapp" class="form-control"
                         value="{{ old('whatsapp', $doctor->whatsapp) }}">
                </div>

                <div class="col-md-12">
                  <label class="form-label">Address</label>
                  <textarea name="address" rows="3" class="form-control">{{ old('address', $doctor->address) }}</textarea>
                </div>

                <div class="col-md-12">
                  <label class="form-label">Profile details / bio</label>
                  <textarea name="profile_details" rows="5" class="form-control">{{ old('profile_details', $doctor->profile_details) }}</textarea>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Profile picture</label>
                  <div class="d-flex align-items-center gap-3">
                    <div style="width:84px; height:84px; overflow:hidden; border:1px solid #eee; border-radius:6px;">
                      @if(!empty($doctor->profile_picture) && file_exists(public_path('storage/'.$doctor->profile_picture)))
                        <img src="{{ asset('storage/'.$doctor->profile_picture) }}" style="width:100%; height:100%; object-fit:cover;">
                      @else
                        <img src="{{ asset('images/default-doctor.png') }}" style="width:100%; height:100%; object-fit:cover;">
                      @endif
                    </div>
                    <div>
                      <input type="file" name="profile_picture" class="form-control">
                      <small class="text-muted">Max 5MB. Upload will replace existing image.</small>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Registration No.</label>
                  <input type="text" name="registration_no" class="form-control"
                         value="{{ old('registration_no', $doctor->registration_no) }}">

                  <label class="form-label mt-3">Council</label>
                  <input type="text" name="council" class="form-control"
                         value="{{ old('council', $doctor->council) }}">
                </div>
              </div>

              <div class="mt-4">
                <button type="submit" class="btn btn-primary">Save changes</button>
                <a href="{{ route('doctor.profile.show', $doctor->id ?? null) }}" class="btn btn-secondary">Cancel</a>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
