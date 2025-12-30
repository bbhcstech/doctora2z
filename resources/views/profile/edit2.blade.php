@extends('admin.layout.app')

@section('title', 'Edit Doctor Profile')

@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Edit Doctor Profile</h1>
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
        <li class="breadcrumb-item">Doctors</li>
        <li class="breadcrumb-item active">Edit Profile</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section profile">
    <div class="row">
      <div class="col-xl-8">
        <div class="card">
          <div class="card-body pt-3">

            {{-- Flash messages --}}
            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('doctor.profile.update') }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')

              <div class="row g-3">

                {{-- Name --}}
                <div class="col-md-12">
                  <label class="form-label">Display Name <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control"
                         value="{{ old('name', $doctor->name) }}" required>
                  @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- Speciality & Degree --}}
                <div class="col-md-6">
                  <label class="form-label">Speciality</label>
                  <input type="text" name="speciality" class="form-control"
                         value="{{ old('speciality', $doctor->speciality) }}">
                  @error('speciality') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                  <label class="form-label">Degree</label>
                  <input type="text" name="degree" class="form-control"
                         value="{{ old('degree', $doctor->degree) }}">
                  @error('degree') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- Phones --}}
                <div class="col-md-6">
                  <label class="form-label">Phone (primary)</label>
                  <input type="text" name="phone_number" class="form-control"
                         value="{{ old('phone_number', $doctor->phone_number) }}">
                  @error('phone_number') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                  <label class="form-label">Phone (alternate)</label>
                  <input type="text" name="phone_number_2" class="form-control"
                         value="{{ old('phone_number_2', $doctor->phone_number_2) }}">
                  @error('phone_number_2') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- Clinic --}}
                <div class="col-md-12">
                  <label class="form-label">Clinic / Hospital</label>
                  <input type="text" name="clinic_name" class="form-control"
                         value="{{ old('clinic_name', $doctor->clinic_name) }}">
                  @error('clinic_name') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- Days --}}
                @php
                  $storedDaysArr = is_string($doctor->clinic_days) 
                      ? array_map('trim', explode(',', $doctor->clinic_days)) 
                      : (is_array($doctor->clinic_days) ? $doctor->clinic_days : []);
                  $week = ['Mon'=>'Monday','Tue'=>'Tuesday','Wed'=>'Wednesday','Thu'=>'Thursday','Fri'=>'Friday','Sat'=>'Saturday','Sun'=>'Sunday'];
                @endphp
                <div class="col-md-12">
                  <label class="form-label">Clinic Days</label><br>
                  @foreach($week as $token => $label)
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox"
                             name="clinic_days[]" id="day_{{ $token }}" value="{{ $token }}"
                             {{ in_array($token, $storedDaysArr) ? 'checked' : '' }}>
                      <label class="form-check-label" for="day_{{ $token }}">{{ $label }}</label>
                    </div>
                  @endforeach
                  @error('clinic_days') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- Times --}}
                <div class="col-md-6">
                  <label class="form-label">Clinic Start Time</label>
                  <input type="time" name="clinic_start_time" class="form-control"
                         value="{{ old('clinic_start_time', $doctor->clinic_start_time) }}">
                  @error('clinic_start_time') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                  <label class="form-label">Clinic End Time</label>
                  <input type="time" name="clinic_end_time" class="form-control"
                         value="{{ old('clinic_end_time', $doctor->clinic_end_time) }}">
                  @error('clinic_end_time') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- Website / WhatsApp --}}
                <div class="col-md-6">
                  <label class="form-label">Website</label>
                  <input type="url" name="website" class="form-control"
                         value="{{ old('website', $doctor->website) }}">
                  @error('website') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                  <label class="form-label">WhatsApp</label>
                  <input type="text" name="whatsapp" class="form-control"
                         value="{{ old('whatsapp', $doctor->whatsapp) }}">
                  @error('whatsapp') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- Address --}}
                <div class="col-md-12">
                  <label class="form-label">Address</label>
                  <textarea name="address" rows="3" class="form-control">{{ old('address', $doctor->address) }}</textarea>
                  @error('address') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- Bio --}}
                <div class="col-md-12">
                  <label class="form-label">Profile Details / Bio</label>
                  <textarea name="profile_details" rows="5" class="form-control">{{ old('profile_details', $doctor->profile_details) }}</textarea>
                  @error('profile_details') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- Picture --}}
                <div class="col-md-6">
                  <label class="form-label">Profile Picture</label>
                  <div class="d-flex align-items-center gap-3">
                    <div style="width:84px; height:84px; overflow:hidden; border-radius:6px; border:1px solid #eee;">
                      @if(!empty($doctor->profile_picture) && file_exists(public_path('storage/'.$doctor->profile_picture)))
                        <img src="{{ asset('storage/'.$doctor->profile_picture) }}" alt="profile" style="width:100%; height:100%; object-fit:cover;">
                      @else
                        <img src="{{ asset('images/default-doctor.png') }}" alt="default" style="width:100%; height:100%; object-fit:cover;">
                      @endif
                    </div>
                    <div>
                      <input type="file" name="profile_picture" class="form-control">
                      <small class="text-muted">Max 5MB. Upload replaces existing image.</small>
                      @error('profile_picture') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                  </div>
                </div>

                {{-- Registration --}}
                <div class="col-md-6">
                  <label class="form-label">Registration No.</label>
                  <input type="text" name="registration_no" class="form-control"
                         value="{{ old('registration_no', $doctor->registration_no) }}">
                  @error('registration_no') <div class="text-danger small">{{ $message }}</div> @enderror

                  <label class="form-label mt-3">Council</label>
                  <input type="text" name="council" class="form-control"
                         value="{{ old('council', $doctor->council) }}">
                  @error('council') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

              </div><!-- end row -->

              <div class="mt-4">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('doctor.profile.show') }}" class="btn btn-secondary">Cancel</a>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
