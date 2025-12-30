{{-- resources/views/Doctor/Profile/edit.blade.php --}}
@extends('admin.admin-doctor-layout.app')

@section('title', 'Edit Doctor Profile')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<main id="main" class="main">
  <div class="pagetitle">
    <h1 class="mb-2">Edit Profile</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Doctor Profile</li>
      </ol>
    </nav>
  </div>

  <div class="container-fluid">
    {{-- Flash Messages --}}
    <div class="row">
      <div class="col-12">
        @if ($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
              <i class="bi bi-exclamation-triangle-fill me-2"></i>
              <div class="flex-grow-1">
                <h6 class="alert-heading mb-1">Please fix the following errors:</h6>
                <ul class="mb-0 ps-3">
                  @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                  @endforeach
                </ul>
              </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif
        
        @if (session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
              <i class="bi bi-check-circle-fill me-2"></i>
              <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif
      </div>
    </div>

    @php
      use Illuminate\Support\Str;
      use Illuminate\Support\Facades\Storage;
      use Carbon\Carbon;

      $d = $doctor ?? null;

      // Profile picture preview
      $stored = trim($d->profile_picture ?? '');
      $previewUrl = asset('images/default-doctor.png');
      if (!empty($stored)) {
          if (Str::startsWith($stored, ['http://','https://'])) {
              $previewUrl = $stored;
          } elseif (Str::contains($stored, 'storage/')) {
              $previewUrl = asset($stored);
          } elseif (Storage::disk('public')->exists($stored)) {
              $previewUrl = Storage::disk('public')->url($stored);
          } elseif (file_exists(public_path($stored))) {
              $previewUrl = asset($stored);
          } elseif (file_exists(public_path('storage/'.$stored))) {
              $previewUrl = asset('storage/'.$stored);
          } elseif (file_exists(public_path('admin/uploads/doctor/'.$stored))) {
              $previewUrl = asset('admin/uploads/doctor/'.$stored);
          }
      }
      if ($d && !empty($d->updated_at) && !empty($previewUrl)) {
          $previewUrl .= (Str::contains($previewUrl, '?') ? '&' : '?') . 'v=' . strtotime($d->updated_at);
      }

      // Degrees normalization
      $degreesText = '';
      if (!empty(old('degrees_text'))) {
        $degreesText = old('degrees_text');
      } else {
        $rawDegrees = $d->degrees ?? null;
        if (is_array($rawDegrees)) {
          $degreesText = implode("\n", $rawDegrees);
        } elseif (is_string($rawDegrees) && trim($rawDegrees) !== '') {
          $try = @json_decode($rawDegrees, true);
          if (is_array($try)) {
            $degreesText = implode("\n", $try);
          } else {
            $degreesText = $rawDegrees;
          }
        }
      }

      // Clinics data normalization
      $clinicsData = old('clinics');
      if (empty($clinicsData)) {
        $clinicsData = [];
        $rawClinicsAttr = $d->clinics ?? null;
        if (!empty($rawClinicsAttr)) {
          if (is_string($rawClinicsAttr)) {
            $parsed = @json_decode($rawClinicsAttr, true);
            if (is_array($parsed)) $clinicsData = $parsed;
          } elseif (is_array($rawClinicsAttr) || $rawClinicsAttr instanceof \Illuminate\Support\Collection) {
            $clinicsData = is_array($rawClinicsAttr) ? $rawClinicsAttr : $rawClinicsAttr->toArray();
          }
        }

        if (empty($clinicsData) && $d && isset($d->clinicSchedules)) {
          $grouped = [];
          foreach ($d->clinicSchedules as $row) {
            $clinicId = $row->clinic_id ?? null;
            $altText = optional($row->clinic)->name ?? null;
            $address  = $row->clinic_address ?? '';

            $key = ($clinicId !== null ? (string)$clinicId : '') . '||' . ($altText ?? '');

            if (!isset($grouped[$key])) {
              $grouped[$key] = [
                'clinic_id' => $clinicId ?? '',
                'clinic_name' => $altText ?? '',
                'clinic_address' => $address ?? '',
                'schedules' => []
              ];
            }

            $days = $row->days ?? [];
            if (is_string($days) && trim($days) !== '') {
              $try = @json_decode($days, true);
              if (is_array($try)) $days = $try;
              else {
                if (preg_match('/^\[.*\]$/', trim($days))) {
                  $try2 = @json_decode($days, true);
                  $days = is_array($try2) ? $try2 : [$days];
                } else {
                  $days = [$days];
                }
              }
            } elseif (!is_array($days)) {
              $days = (array)$days;
            }

            $start = null;
            $end = null;
            if (!empty($row->start_time)) {
              try { $start = Carbon::parse($row->start_time)->format('H:i'); } catch (\Throwable $ex) { $start = (string)$row->start_time; }
            }
            if (!empty($row->end_time)) {
              try { $end = Carbon::parse($row->end_time)->format('H:i'); } catch (\Throwable $ex) { $end = (string)$row->end_time; }
            }

            if (empty($days)) $days = ['monday'];

            foreach ($days as $day) {
              $grouped[$key]['schedules'][] = [
                'day' => is_string($day) ? strtolower(trim($day)) : (string)$day,
                'start' => $start ?? '',
                'end' => $end ?? ''
              ];
            }
          }

          foreach ($grouped as $g) {
            if (empty($g['schedules'])) $g['schedules'] = [['day'=>'monday','start'=>'','end'=>'']];
            $clinicsData[] = $g;
          }
        }
      }

      if (!is_array($clinicsData)) $clinicsData = [];
      if (empty($clinicsData)) {
        $clinicsData = [['clinic_id'=>'','clinic_name'=>'','clinic_address'=>'','schedules'=>[['day'=>'monday','start'=>'','end'=>'']]]];
      } else {
        $clinicsData = array_values(array_map(function($c){
          $c = is_array($c) ? $c : (array)$c;
          $s = $c['schedules'] ?? $c['schedule'] ?? null;
          if (is_string($s) && trim($s) !== '') {
            $sdec = @json_decode($s, true);
            if (is_array($sdec)) $s = $sdec;
            else $s = [['day'=>'monday','start'=>'','end'=>'']];
          } elseif (empty($s) || !is_array($s)) {
            $s = [['day'=>'monday','start'=>'','end'=>'']];
          }
          return [
            'clinic_id' => $c['clinic_id'] ?? $c['id'] ?? '',
            'clinic_name' => $c['clinic_name'] ?? $c['name'] ?? '',
            'clinic_address' => $c['clinic_address'] ?? $c['address'] ?? $c['addr'] ?? '',
            'schedules' => $s
          ];
        }, $clinicsData));
      }

      $initialCategory = old('category_id', $d->category_id ?? '');
      $initialCountry  = old('country_id', $d->country_id ?? '');
      $initialState    = old('state_id', $d->state_id ?? '');
      $initialDistrict = old('district_id', $d->district_id ?? '');
      $initialCity     = old('city_id', $d->city_id ?? '');
    @endphp

    {{-- Profile Picture Section --}}
    <div class="row mb-4">
      <div class="col-12">
        <div class="card profile-card">
          <div class="card-body text-center py-4">
            <div class="profile-avatar-wrapper position-relative mx-auto" style="max-width: 220px;">
              <div class="avatar-container position-relative">
                <img id="previewImg" src="{{ $previewUrl }}" alt="Doctor Profile" 
                     class="avatar-img rounded-circle shadow-sm">
                <div class="avatar-overlay">
                  <label for="profile_picture" class="btn btn-primary rounded-circle avatar-upload-btn">
                    <i class="bi bi-camera-fill fs-5"></i>
                  </label>
                </div>
              </div>
              
              <div class="mt-3">
                <h5 class="mb-1">{{ $d->name ?? 'Doctor Name' }}</h5>
                <p class="text-muted small mb-2">{{ $d->speciality ?? 'Speciality' }}</p>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                  <label for="profile_picture" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-upload me-1"></i>Change Photo
                  </label>
                  
                  @if(!empty($d) && !empty($d->profile_picture))
                    <form id="removePhotoForm" action="{{ route('doctor.profile.remove-photo', $d->id ?? '') }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="button" id="removePhotoBtn" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-trash me-1"></i>Remove
                      </button>
                    </form>
                  @endif
                </div>
              </div>
              
              <div class="text-muted small mt-3">
                <i class="bi bi-clock me-1"></i>
                Last updated: {{ $d && !empty($d->updated_at) ? $d->updated_at->format('M d, Y') : '—' }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Main Form --}}
    <div class="row">
      <div class="col-12">
        <form action="{{ route('doctor.profile.update', $d->id ?? '') }}" method="POST" enctype="multipart/form-data" id="doctorProfileForm" novalidate>
          @csrf
          <input type="hidden" name="remove_profile_picture" id="remove_profile_picture" value="0">
          <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="d-none">

          {{-- Legacy clinic fields --}}
          <input type="hidden" id="top_clinic_id" name="clinic_id" value="{{ old('clinic_id', $d->clinic_id ?? '') }}">
          <input type="hidden" id="top_clinic_name" name="clinic_name" value="{{ old('clinic_name', $d->clinic_name ?? '') }}">
          <input type="hidden" id="top_clinic_start_time" name="clinic_start_time" value="{{ old('clinic_start_time', $d->clinic_start_time ?? '') }}">
          <input type="hidden" id="top_clinic_end_time" name="clinic_end_time" value="{{ old('clinic_end_time', $d->clinic_end_time ?? '') }}">

          {{-- Personal Information Section --}}
          <div class="card mb-4">
            <div class="card-header">
              <h5 class="card-title mb-0">
                <i class="bi bi-person-badge me-2"></i>Personal Information
              </h5>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Full Name <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $d->name ?? '') }}" required>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <label class="form-label">Email <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $d->email ?? '') }}" required>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <label class="form-label">Primary Phone <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $d->phone_number ?? '') }}" required>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <label class="form-label">Secondary Phone</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                    <input type="text" name="phone_number_2" class="form-control" value="{{ old('phone_number_2', $d->phone_number_2 ?? '') }}">
                  </div>
                </div>
                
                <div class="col-md-6">
                  <label class="form-label">WhatsApp Number</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-whatsapp"></i></span>
                    <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $d->whatsapp ?? '') }}">
                  </div>
                </div>
                
                <div class="col-md-6">
                  <label class="form-label">Profile Status <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                    <select name="status" class="form-select">
                      <option value="active" @selected(old('status', $d->status ?? '') === 'active')>Active</option>
                      <option value="inactive" @selected(old('status', $d->status ?? '') === 'inactive')>Inactive</option>
                    </select>
                  </div>
                </div>
                
                <div class="col-12">
                  <label class="form-label">Address</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $d->address ?? '') }}">
                  </div>
                </div>
                
                <div class="col-12">
                  <label class="form-label">Profile Details</label>
                  <textarea name="profile_details" rows="3" class="form-control">{{ old('profile_details', $d->profile_details ?? '') }}</textarea>
                </div>
              </div>
            </div>
          </div>

          {{-- Professional Details Section --}}
          <div class="card mb-4">
            <div class="card-header">
              <h5 class="card-title mb-0">
                <i class="bi bi-briefcase-fill me-2"></i>Professional Details
              </h5>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label">Degree <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-mortarboard"></i></span>
                    <input type="text" name="degree" class="form-control" value="{{ old('degree', $d->degree ?? '') }}" required>
                  </div>
                </div>
                
                <div class="col-md-4">
                  <label class="form-label">Speciality <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-heart-pulse"></i></span>
                    <input type="text" name="speciality" class="form-control" value="{{ old('speciality', $d->speciality ?? '') }}" required>
                  </div>
                </div>
                
                <div class="col-md-4">
                  <label class="form-label">Registration No. <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                    <input type="text" name="registration_no" class="form-control" value="{{ old('registration_no', $d->registration_no ?? '') }}" required>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <label class="form-label">Medical Council</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                    <input type="text" name="council" class="form-control" value="{{ old('council', $d->council ?? '') }}">
                  </div>
                </div>
                
                <div class="col-md-6">
                  <label class="form-label">Category <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-tags"></i></span>
                    <select name="category_id" id="category_id" class="form-select" required>
                      <option value="">-- Select Category --</option>
                      @foreach(($categories ?? []) as $id => $name)
                        <option value="{{ $id }}" @selected((string)old('category_id', $d->category_id ?? '') === (string)$id)>{{ $name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <label class="form-label">Consultation Mode <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-camera-video"></i></span>
                    <select name="consultation_mode" class="form-select" required>
                      <option value="online" @selected(old('consultation_mode', $d->consultation_mode ?? '') === 'online')>Online</option>
                      <option value="offline" @selected(old('consultation_mode', $d->consultation_mode ?? '') === 'offline')>Offline</option>
                      <option value="both" @selected(old('consultation_mode', $d->consultation_mode ?? '') === 'both')>Both</option>
                      <option value="face-to-face" @selected(old('consultation_mode', $d->consultation_mode ?? '') === 'face-to-face')>Face-to-face</option>
                    </select>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <label class="form-label">Website</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-globe"></i></span>
                    <input type="url" name="website" class="form-control" value="{{ old('website', $d->website ?? '') }}">
                  </div>
                </div>
                
                <div class="col-md-6">
                  <label class="form-label">Facebook</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-facebook"></i></span>
                    <input type="url" name="facebook" class="form-control" value="{{ old('facebook', $d->facebook ?? '') }}">
                  </div>
                </div>
                
                <div class="col-md-6">
                  <label class="form-label">Instagram</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-instagram"></i></span>
                    <input type="url" name="instagram" class="form-control" value="{{ old('instagram', $d->instagram ?? '') }}">
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- Location & Contact Section --}}
          <div class="card mb-4">
            <div class="card-header">
              <h5 class="card-title mb-0">
                <i class="bi bi-geo-alt-fill me-2"></i>Location & Contact
              </h5>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-3">
                  <label class="form-label">Country <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-globe-americas"></i></span>
                    <select name="country_id" id="country_id" class="form-select" required>
                      <option value="">-- Country --</option>
                      @foreach(($countries ?? []) as $id=>$name)
                        <option value="{{ $id }}" @selected((string)old('country_id',$d->country_id??'')===(string)$id)>{{ $name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                
                <div class="col-md-3">
                  <label class="form-label">State <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-map"></i></span>
                    <select name="state_id" id="state_id" class="form-select" required>
                      <option value="">-- State --</option>
                      @foreach(($states ?? []) as $id=>$name)
                        <option value="{{ $id }}" @selected((string)old('state_id',$d->state_id??'')===(string)$id)>{{ $name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                
                <div class="col-md-3">
                  <label class="form-label">District <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-signpost-2"></i></span>
                    <select name="district_id" id="district_id" class="form-select" required>
                      <option value="">-- District --</option>
                      @foreach(($districts ?? []) as $id=>$name)
                        <option value="{{ $id }}" @selected((string)old('district_id',$d->district_id??'')===(string)$id)>{{ $name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                
                <div class="col-md-3">
                  <label class="form-label">Area <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                    <select name="city_id" id="city_id" class="form-select">
                      <option value="">-- Select Area --</option>
                      @foreach(($cities ?? []) as $id=>$name)
                        <option value="{{ $id }}" @selected((string)old('city_id',$d->city_id??'')===(string)$id)>
                          {{ $name }}
                        </option>
                      @endforeach
                    </select>
                  </div>

                  {{-- Cant find area --}}
                  <small class="text-primary d-block mt-1" id="cantFindArea" style="cursor:pointer">
                    Cant find your area?
                  </small>

                  {{-- Manual city input --}}
                  <input type="text"
                         name="other_city_name"
                         id="other_city_name"
                         class="form-control mt-2 d-none"
                         placeholder="Enter your area name">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Pincode</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="text" name="pincode" id="pincode" class="form-control" 
                           value="{{ old('pincode', optional($d->pincode)->pincode ?? '') }}" 
                           maxlength="6" inputmode="numeric" placeholder="6-digit pincode">
                    <button type="button" id="pincodeLookupBtn" class="btn btn-outline-primary">
                      <i class="bi bi-search"></i>
                    </button>
                  </div>
                  <input type="hidden" name="pincode_id" id="pincode_id" value="{{ old('pincode_id', $d->pincode_id ?? '') }}">
                  <small id="pincodeHelp" class="text-muted">Enter 6-digit pincode and click lookup</small>
                </div>
              </div>
            </div>
          </div>

          {{-- Clinics Section --}}
          <div class="card mb-4">
            <div class="card-header">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                  <i class="bi bi-hospital me-2"></i>Clinics & Schedules
                </h5>
                <div>
                  <button type="button" id="addClinicBtn" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Add Clinic
                  </button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <p class="text-muted mb-4">Add clinics where the doctor practices along with their schedules.</p>
              
              <div id="clinicsContainer">
                @foreach($clinicsData as $idx => $clinic)
                  @php
                    $schedules = $clinic['schedules'] ?? [['day'=>'monday','start'=>'','end'=>'']];
                  @endphp
                  <div class="clinic-block card border mb-3" data-index="{{ $idx }}">
                    <div class="card-header bg-light">
                      <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                          <i class="bi bi-hospital me-2"></i>Clinic {{ $idx + 1 }}
                        </h6>
                        <button type="button" class="btn btn-sm btn-outline-danger removeClinicBtn">
                          <i class="bi bi-trash me-1"></i>Remove
                        </button>
                      </div>
                    </div>
                    
                    <div class="card-body">
                      <div class="row g-3 mb-3">
                        <div class="col-md-6">
                          <label class="form-label">Select Clinic <span class="text-danger">*</span></label>
                          <select name="clinics[{{ $idx }}][clinic_id]" class="form-select clinic-select">
                            <option value="">Enter clinic name if not found</option>
                            @foreach(($clinics ?? []) as $id => $name)
                              <option value="{{ $id }}" @selected((string)($clinic['clinic_id'] ?? '') === (string)$id)>{{ $name }}</option>
                            @endforeach
                          </select>
                        </div>
                        
                        <div class="col-md-6">
                          <label class="form-label">Or enter Clinic Name</label>
                          <input type="text" name="clinics[{{ $idx }}][clinic_name]" 
                                 class="form-control clinic-name-input" 
                                 placeholder="Clinic name if not listed"
                                 value="{{ $clinic['clinic_name'] ?? '' }}">
                        </div>
                      </div>
                      
                      <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="clinics[{{ $idx }}][clinic_address]" class="form-control" rows="2">{{ $clinic['clinic_address'] ?? '' }}</textarea>
                      </div>
                      
                      <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                          <label class="form-label fw-semibold">Schedules</label>
                          <button type="button" class="btn btn-outline-success btn-sm addScheduleBtn">
                            <i class="bi bi-plus me-1"></i>Add Schedule
                          </button>
                        </div>
                        
                        <div class="schedule-rows">
                          @foreach($schedules as $sIdx => $sch)
                            <div class="row g-2 align-items-end schedule-row mb-2">
                              <div class="col-md-3">
                                <label class="form-label">Day <span class="text-danger">*</span></label>
                                <select name="clinics[{{ $idx }}][schedules][{{ $sIdx }}][day]" class="form-select" required>
                                  <option value="all">All Days</option>
                                  @foreach(['monday'=>'Monday','tuesday'=>'Tuesday','wednesday'=>'Wednesday','thursday'=>'Thursday','friday'=>'Friday','saturday'=>'Saturday','sunday'=>'Sunday'] as $token => $label)
                                    <option value="{{ $token }}" @selected(($sch['day'] ?? '') === $token)>{{ $label }}</option>
                                  @endforeach
                                </select>
                              </div>
                              
                              <div class="col-md-3">
                                <label class="form-label">Start Time <span class="text-danger">*</span></label>
                                <input type="time" name="clinics[{{ $idx }}][schedules][{{ $sIdx }}][start]" 
                                       class="form-control" value="{{ $sch['start'] ?? '' }}" required>
                              </div>
                              
                              <div class="col-md-3">
                                <label class="form-label">End Time <span class="text-danger">*</span></label>
                                <input type="time" name="clinics[{{ $idx }}][schedules][{{ $sIdx }}][end]" 
                                       class="form-control" value="{{ $sch['end'] ?? '' }}" required>
                              </div>
                              
                              <div class="col-md-3">
                                <label class="form-label d-none d-md-block">&nbsp;</label>
                                <button type="button" class="btn btn-outline-danger btn-sm w-100 removeScheduleBtn" @if($sIdx === 0) disabled @endif>
                                  <i class="bi bi-dash me-1"></i>Remove
                                </button>
                              </div>
                            </div>
                          @endforeach
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>

          {{-- Submit Section --}}
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Cancel
                  </a>
                </div>
                <div>
                  <button type="submit" id="saveBtn" class="btn btn-primary btn-lg px-4">
                    <i class="bi bi-check-circle me-2"></i>Save Changes
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
<style>
  /* Enhanced Card Styles */
  .card {
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    transition: box-shadow 0.2s ease;
  }
  
  .card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  }
  
  .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    padding: 1rem 1.5rem;
  }
  
  .card-title {
    color: #333;
    font-weight: 600;
  }
  
  /* Profile Avatar - FIXED SIZE REGARDLESS OF UPLOADED IMAGE */
  .profile-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  }
  
  .avatar-container {
    width: 160px;
    height: 160px;
    margin: 0 auto;
    position: relative;
    border-radius: 50%;
    overflow: hidden;
    background-color: #f8f9fa;
  }
  
  /* Fixed size for avatar image - This ensures consistent size */
  .avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    display: block;
    position: absolute;
    top: 0;
    left: 0;
  }
  
  /* Ensure the image maintains aspect ratio while filling the container */
  .avatar-img[src*="default-doctor.png"] {
    object-fit: contain;
    background-color: #f8f9fa;
    padding: 20px;
  }
  
  .avatar-overlay {
    position: absolute;
    bottom: 10px;
    right: 10px;
    z-index: 2;
  }
  
  .avatar-upload-btn {
    width: 40px;
    height: 40px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
  }
  
  /* Form Elements */
  .form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
  }
  
  .form-control, .form-select {
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    transition: all 0.2s ease;
  }
  
  .form-control:focus, .form-select:focus {
    border-color: #4dabf7;
    box-shadow: 0 0 0 0.2rem rgba(77, 171, 247, 0.25);
  }
  
  .input-group-text {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    color: #6c757d;
  }
  
  /* Clinic Blocks */
  .clinic-block {
    border-left: 4px solid #339af0 !important;
  }
  
  .schedule-row {
    background-color: #f8fafc;
    padding: 1rem;
    border-radius: 6px;
    border: 1px solid #e9ecef;
  }
  
  /* Buttons */
  .btn {
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.2s ease;
  }
  
  .btn-primary {
    background-color: #339af0;
    border-color: #339af0;
  }
  
  .btn-primary:hover {
    background-color: #228be6;
    border-color: #228be6;
    transform: translateY(-1px);
  }
  
  .btn-success {
    background-color: #51cf66;
    border-color: #51cf66;
  }
  
  .btn-outline-secondary {
    border-color: #adb5bd;
    color: #6c757d;
  }
  
  /* Alerts */
  .alert {
    border-radius: 8px;
    border: none;
  }
  
  .alert-danger {
    background-color: #ffe3e3;
    color: #c92a2a;
  }
  
  .alert-success {
    background-color: #d3f9d8;
    color: #2b8a3e;
  }
  
  /* Responsive Design */
  @media (max-width: 768px) {
    .pagetitle h1 {
      font-size: 1.5rem;
    }
    
    /* Fixed size for mobile */
    .avatar-container {
      width: 120px;
      height: 120px;
    }
    
    .avatar-upload-btn {
      width: 35px;
      height: 35px;
    }
    
    .btn-lg {
      padding: 0.5rem 1rem;
      font-size: 0.9rem;
    }
    
    .input-group-text {
      padding: 0.375rem 0.5rem;
    }
    
    .schedule-row .col-md-3 {
      margin-bottom: 0.5rem;
    }
    
    .schedule-row .col-md-3:last-child {
      margin-bottom: 0;
    }
  }
  
  @media (max-width: 576px) {
    .card-body {
      padding: 1rem;
    }
    
    .d-flex.justify-content-between {
      flex-direction: column;
      gap: 1rem;
    }
    
    .d-flex.justify-content-between > div {
      width: 100%;
    }
    
    .btn {
      width: 100%;
      margin-bottom: 0.5rem;
    }
    
    .btn:last-child {
      margin-bottom: 0;
    }
  }
  
  /* Loading States */
  .spinner-border {
    vertical-align: middle;
  }
</style>
@endpush

@push('scripts')
<script>
(function(){
  // DOM Helpers
  function $(selector, root = document) { return root.querySelector(selector); }
  function $$(selector, root = document) { return Array.from(root.querySelectorAll(selector)); }
  function createFromHTML(html) { const tpl = document.createElement('template'); tpl.innerHTML = html.trim(); return tpl.content.firstChild; }
  function getCsrf() { const meta = $('meta[name="csrf-token"]'); return meta ? meta.getAttribute('content') : ''; }

  // Apply fixed avatar styles
  function applyFixedAvatarStyles() { 
    const img = $('#previewImg'); 
    if (img) {
      img.style.width = '100%';
      img.style.height = '100%';
      img.style.objectFit = 'cover';
      img.style.objectPosition = 'center';
      img.style.borderRadius = '50%';
      img.style.display = 'block';
    }
  }

  // Profile Picture Handling
  const profileInput = $('#profile_picture');
  const changePhotoBtns = $$('#changePhotoBtn, label[for="profile_picture"]');
  
  changePhotoBtns.forEach(btn => {
    btn.addEventListener('click', function(e) {
      if (e.target.tagName === 'LABEL' && e.target.htmlFor === 'profile_picture') return;
      e.preventDefault();
      profileInput?.click();
    });
  });

  if (profileInput) {
    profileInput.addEventListener('change', function() {
      const file = this.files?.[0];
      if (!file) return;
      
      // Validate file type
      if (!file.type.startsWith('image/')) {
        alert('Please select an image file.');
        this.value = '';
        return;
      }
      
      // Validate file size (max 5MB)
      if (file.size > 5 * 1024 * 1024) {
        alert('Image size should be less than 5MB.');
        this.value = '';
        return;
      }
      
      const reader = new FileReader();
      reader.onload = function(e) {
        const img = $('#previewImg');
        if (img) {
          img.src = e.target.result;
          // Apply fixed styles to ensure consistent size
          img.style.objectFit = 'cover';
          img.style.objectPosition = 'center';
          img.style.width = '100%';
          img.style.height = '100%';
          img.style.display = 'block';
        }
      };
      reader.readAsDataURL(file);
      
      // Reset remove flag if new image is uploaded
      const removeField = $('#remove_profile_picture');
      if (removeField) removeField.value = '0';
    });
  }

  // Remove Photo
  const removeBtn = $('#removePhotoBtn');
  if (removeBtn) {
    removeBtn.addEventListener('click', function(e) {
      e.preventDefault();
      
      if (!confirm('Are you sure you want to remove the profile photo?')) return;
      
      const form = $('#removePhotoForm');
      if (!form) {
        alert('Remove form not found.');
        return;
      }
      
      // Optimistic UI update
      const img = $('#previewImg');
      if (img) {
        img.src = '{{ asset("images/default-doctor.png") }}';
        // Apply fixed styles for default image
        img.style.objectFit = 'contain';
        img.style.objectPosition = 'center';
        img.style.width = '100%';
        img.style.height = '100%';
        img.style.display = 'block';
      }
      
      form.submit();
    });
  }

  /* ---------- Clinics Management ---------- */
  const clinicsContainer = $('#clinicsContainer');
  const addClinicBtn = $('#addClinicBtn');

  function scheduleRowHTML(clinicIndex, scheduleIndex, data = {}) {
    const day = data.day || 'monday';
    const start = data.start || '';
    const end = data.end || '';
    
    const days = {
      'monday': 'Monday',
      'tuesday': 'Tuesday', 
      'wednesday': 'Wednesday',
      'thursday': 'Thursday',
      'friday': 'Friday',
      'saturday': 'Saturday',
      'sunday': 'Sunday'
    };
    
    return `
      <div class="row g-2 align-items-end schedule-row mb-2">
        <div class="col-md-3">
          <label class="form-label">Day <span class="text-danger">*</span></label>
          <select name="clinics[${clinicIndex}][schedules][${scheduleIndex}][day]" class="form-select" required>
            <option value="all">All Days</option>
            ${Object.entries(days).map(([value, label]) => `
              <option value="${value}" ${day === value ? 'selected' : ''}>${label}</option>
            `).join('')}
          </select>
        </div>
        
        <div class="col-md-3">
          <label class="form-label">Start Time <span class="text-danger">*</span></label>
          <input type="time" name="clinics[${clinicIndex}][schedules][${scheduleIndex}][start]" 
                 class="form-control" value="${start}" required>
        </div>
        
        <div class="col-md-3">
          <label class="form-label">End Time <span class="text-danger">*</span></label>
          <input type="time" name="clinics[${clinicIndex}][schedules][${scheduleIndex}][end]" 
                 class="form-control" value="${end}" required>
        </div>
        
        <div class="col-md-3">
          <label class="form-label d-none d-md-block">&nbsp;</label>
          <button type="button" class="btn btn-outline-danger btn-sm w-100 removeScheduleBtn" ${scheduleIndex === 0 ? 'disabled' : ''}>
            <i class="bi bi-dash me-1"></i>Remove
          </button>
        </div>
      </div>
    `;
  }

 function clinicBlockHTML(clinicIndex) {
  const clinicsOptions = document.querySelector(
    '#clinicsContainer .clinic-block select.clinic-select'
  )?.innerHTML || '<option value="">-- Choose existing clinic --</option>';

    
    return `
      <div class="clinic-block card border mb-3" data-index="${clinicIndex}">
        <div class="card-header bg-light">
          <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
              <i class="bi bi-hospital me-2"></i>Clinic ${clinicIndex + 1}
            </h6>
            <button type="button" class="btn btn-sm btn-outline-danger removeClinicBtn">
              <i class="bi bi-trash me-1"></i>Remove
            </button>
          </div>
        </div>
        
        <div class="card-body">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Select Clinic <span class="text-danger">*</span></label>
              <select name="clinics[${clinicIndex}][clinic_id]" class="form-select clinic-select" required>
                ${clinicsOptions}
              </select>
            </div>
            
            <div class="col-md-6">
              <label class="form-label">Or enter Clinic Name</label>
              <input type="text" name="clinics[${clinicIndex}][clinic_name]" 
                     class="form-control clinic-name-input" 
                     placeholder="Clinic name if not listed">
            </div>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="clinics[${clinicIndex}][clinic_address]" class="form-control" rows="2"></textarea>
          </div>
          
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <label class="form-label fw-semibold">Schedules</label>
              <button type="button" class="btn btn-outline-success btn-sm addScheduleBtn">
                <i class="bi bi-plus me-1"></i>Add Schedule
              </button>
            </div>
            
            <div class="schedule-rows">
              ${scheduleRowHTML(clinicIndex, 0, { day: 'monday', start: '', end: '' })}
            </div>
          </div>
        </div>
      </div>
    `;
  }

  function reindexClinics() {
    const clinicBlocks = $$('#clinicsContainer .clinic-block');
    
    clinicBlocks.forEach((block, blockIndex) => {
      // Update block data-index
      block.dataset.index = blockIndex;
      
      // Update clinic title
      const title = block.querySelector('h6.mb-0');
      if (title) {
        title.innerHTML = `<i class="bi bi-hospital me-2"></i>Clinic ${blockIndex + 1}`;
      }
      
      // Update input names
      const clinicIdSelect = block.querySelector('select.clinic-select');
      if (clinicIdSelect) {
        clinicIdSelect.name = `clinics[${blockIndex}][clinic_id]`;
      }
      
      const clinicNameInput = block.querySelector('input[name$="[clinic_name]"]');
      if (clinicNameInput) {
        clinicNameInput.name = `clinics[${blockIndex}][clinic_name]`;
      }
      
      const clinicAddressTextarea = block.querySelector('textarea[name$="[clinic_address]"]');
      if (clinicAddressTextarea) {
        clinicAddressTextarea.name = `clinics[${blockIndex}][clinic_address]`;
      }
      
      // Update schedule rows
      const scheduleRows = $$('.schedule-rows .schedule-row', block);
      scheduleRows.forEach((row, rowIndex) => {
        // Update day select
        const daySelect = row.querySelector('select[name$="[day]"]');
        if (daySelect) {
          daySelect.name = `clinics[${blockIndex}][schedules][${rowIndex}][day]`;
        }
        
        // Update time inputs
        const timeInputs = row.querySelectorAll('input[type="time"]');
        if (timeInputs[0]) {
          timeInputs[0].name = `clinics[${blockIndex}][schedules][${rowIndex}][start]`;
        }
        if (timeInputs[1]) {
          timeInputs[1].name = `clinics[${blockIndex}][schedules][${rowIndex}][end]`;
        }
        
        // Update remove button disabled state
        const removeBtn = row.querySelector('.removeScheduleBtn');
        if (removeBtn) {
          removeBtn.disabled = rowIndex === 0;
        }
      });
    });
    
    // Sync clinic select and input
    syncClinicSelectAndInput();
  }

  // Add new clinic block
  if (addClinicBtn) {
    addClinicBtn.addEventListener('click', function(e) {
      e.preventDefault();
      
      const clinicIndex = $$('#clinicsContainer .clinic-block').length;
      const newBlockHTML = clinicBlockHTML(clinicIndex);
      const newBlock = createFromHTML(newBlockHTML);
      
      clinicsContainer.appendChild(newBlock);
      reindexClinics();
      
      // Scroll to new block with smooth animation
      newBlock.scrollIntoView({ behavior: 'smooth', block: 'center' });
      
      // Focus on the first input in the new block
      const firstInput = newBlock.querySelector('input, select, textarea');
      if (firstInput) firstInput.focus();
    });
  }

  // Handle clinic and schedule actions
  document.addEventListener('click', function(e) {
    const target = e.target;
    
    // Remove clinic
    if (target.closest('.removeClinicBtn')) {
      e.preventDefault();
      
      const block = target.closest('.clinic-block');
      if (!block) return;
      
      const clinicBlocks = $$('#clinicsContainer .clinic-block');
      if (clinicBlocks.length <= 1) {
        alert('At least one clinic is required.');
        return;
      }
      
      if (!confirm('Are you sure you want to remove this clinic?')) return;
      
      block.remove();
      reindexClinics();
      return;
    }
    
    // Add schedule row
    if (target.closest('.addScheduleBtn')) {
      e.preventDefault();
      
      const block = target.closest('.clinic-block');
      if (!block) return;
      
      const clinicIndex = parseInt(block.dataset.index || 0, 10);
      const scheduleRows = $$('.schedule-rows .schedule-row', block);
      const scheduleIndex = scheduleRows.length;
      
      const scheduleRowsContainer = block.querySelector('.schedule-rows');
      if (scheduleRowsContainer) {
        const newRowHTML = scheduleRowHTML(clinicIndex, scheduleIndex, {});
        scheduleRowsContainer.insertAdjacentHTML('beforeend', newRowHTML);
        reindexClinics();
      }
      return;
    }
    
    // Remove schedule row
    if (target.closest('.removeScheduleBtn')) {
      e.preventDefault();
      
      const row = target.closest('.schedule-row');
      const block = row?.closest('.clinic-block');
      if (!row || !block) return;
      
      const scheduleRows = $$('.schedule-rows .schedule-row', block);
      if (scheduleRows.length <= 1) {
        alert('Each clinic must have at least one schedule.');
        return;
      }
      
      row.remove();
      reindexClinics();
      return;
    }
  });

  // Handle "All Days" selection
  document.addEventListener('change', function(e) {
    const select = e.target;
    
    if (!select.matches('select[name*="[schedules]"][name$="[day]"]')) return;
    
    if (select.value === 'all') {
      const row = select.closest('.schedule-row');
      const block = select.closest('.clinic-block');
      
      if (!row || !block) return;
      
      // Get current times
      const timeInputs = row.querySelectorAll('input[type="time"]');
      const startTime = timeInputs[0]?.value || '';
      const endTime = timeInputs[1]?.value || '';
      
      // Remove current row
      row.remove();
      
      // Add rows for each day
      const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
      const scheduleRowsContainer = block.querySelector('.schedule-rows');
      const clinicIndex = parseInt(block.dataset.index || 0, 10);
      
      days.forEach((day, index) => {
        const newRowHTML = scheduleRowHTML(clinicIndex, index, {
          day: day,
          start: startTime,
          end: endTime
        });
        scheduleRowsContainer.insertAdjacentHTML('beforeend', newRowHTML);
      });
      
      reindexClinics();
    }
  });

  // Sync clinic select and input
  function syncClinicSelectAndInput() {
    $$('.clinic-block').forEach(block => {
      const select = block.querySelector('.clinic-select');
      const input = block.querySelector('.clinic-name-input');
      
      if (!select || !input) return;
      
      // When select has value, disable input
      if (select.value) {
        input.value = '';
        input.disabled = true;
        input.placeholder = 'Select a clinic from dropdown';
      } else {
        input.disabled = false;
        input.placeholder = 'Clinic name if not listed';
      }
      
      // When input has value, disable select
      if (input.value.trim()) {
        select.disabled = true;
      } else {
        select.disabled = false;
      }
    });
  }

  // Initialize clinics sync
  document.addEventListener('DOMContentLoaded', function() {
    reindexClinics();
    applyFixedAvatarStyles();
    syncClinicSelectAndInput();
  });

  // Form validation and submission
  const form = $('#doctorProfileForm');
  if (form) {
    form.addEventListener('submit', function(e) {
      // Validate at least one clinic exists
      // Validate at least one clinic exists
const clinicBlocks = $$('#clinicsContainer .clinic-block');
if (!clinicBlocks.length) {
  alert('Please add at least one clinic.');
  e.preventDefault();
  return;
}

// 🔴 NEW VALIDATION: clinic_id OR clinic_name is required
for (let i = 0; i < clinicBlocks.length; i++) {
  const block = clinicBlocks[i];
  const clinicSelect = block.querySelector('.clinic-select');
  const clinicNameInput = block.querySelector('.clinic-name-input');

  const hasClinicId = clinicSelect && clinicSelect.value.trim() !== '';
  const hasClinicName = clinicNameInput && clinicNameInput.value.trim() !== '';

  if (!hasClinicId && !hasClinicName) {
    alert(`Clinic ${i + 1}: Please select a clinic or enter a clinic name.`);
    clinicNameInput?.focus();
    e.preventDefault();
    return;
  }
}

      
      // Validate each clinic has at least one complete schedule
      let isValid = true;
      let errorMessage = '';
      
      clinicBlocks.forEach((block, blockIndex) => {
        const scheduleRows = $$('.schedule-rows .schedule-row', block);
        
        scheduleRows.forEach((row, rowIndex) => {
          const daySelect = row.querySelector('select[name$="[day]"]');
          const startInput = row.querySelector('input[name$="[start]"]');
          const endInput = row.querySelector('input[name$="[end]"]');
          
          const dayValue = daySelect?.value || '';
          const startValue = startInput?.value || '';
          const endValue = endInput?.value || '';
          
          if (!dayValue) {
            isValid = false;
            errorMessage = `Clinic ${blockIndex + 1}, schedule ${rowIndex + 1}: Please select a day.`;
            return;
          }
          
          if (!startValue) {
            isValid = false;
            errorMessage = `Clinic ${blockIndex + 1}, schedule ${rowIndex + 1}: Please set start time.`;
            return;
          }
          
          if (!endValue) {
            isValid = false;
            errorMessage = `Clinic ${blockIndex + 1}, schedule ${rowIndex + 1}: Please set end time.`;
            return;
          }
          
          // Validate time range
          if (startValue && endValue) {
            if (startValue >= endValue) {
              isValid = false;
              errorMessage = `Clinic ${blockIndex + 1}, schedule ${rowIndex + 1}: End time must be after start time.`;
              return;
            }
          }
        });
      });
      
      if (!isValid) {
        alert(errorMessage);
        e.preventDefault();
        return;
      }
      
      // Map first clinic to legacy fields
      const firstClinicBlock = $$('#clinicsContainer .clinic-block')[0];
      if (firstClinicBlock) {
        const clinicIdSelect = firstClinicBlock.querySelector('select[name$="[clinic_id]"]');
        const clinicNameInput = firstClinicBlock.querySelector('input[name$="[clinic_name]"]');
        const firstScheduleRow = firstClinicBlock.querySelector('.schedule-rows .schedule-row');
        
        // Update legacy fields
        $('#top_clinic_id').value = clinicIdSelect?.value || '';
        $('#top_clinic_name').value = clinicNameInput?.value || '';
        
        if (firstScheduleRow) {
          const timeInputs = firstScheduleRow.querySelectorAll('input[type="time"]');
          $('#top_clinic_start_time').value = timeInputs[0]?.value || '';
          $('#top_clinic_end_time').value = timeInputs[1]?.value || '';
        }
      }
      
      // Disable submit button to prevent double submission
      const saveBtn = $('#saveBtn');
      if (saveBtn) {
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...';
      }
    });
  }

  /* ---------- Location Cascading Selects ---------- */
  async function fetchJson(url) {
    try {
      const res = await fetch(url, {
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': getCsrf()
        }
      });
      
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return await res.json();
    } catch (error) {
      console.warn('Fetch error:', error);
      return null;
    }
  }

  function populateSelect(select, items, placeholder) {
    if (!select) return;
    
    select.innerHTML = `<option value="">${placeholder}</option>`;
    
    if (!items || !items.length) return;
    
    items.forEach(item => {
      const option = document.createElement('option');
      option.value = item.id || item.value || '';
      option.textContent = item.name || item.label || item;
      select.appendChild(option);
    });
  }

  async function loadStates(countryId, preselect = '') {
    const select = $('#state_id');
    if (!select) return;
    
    if (!countryId) {
      populateSelect(select, [], '-- State --');
      return;
    }
    
    select.disabled = true;
    select.innerHTML = '<option value="">Loading states...</option>';
    
    const data = await fetchJson(`{{ rtrim(url('/doctor/profile'), '/') }}/get-states/${encodeURIComponent(countryId)}`);
    
    populateSelect(select, data || [], '-- State --');
    select.disabled = false;
    
    if (preselect) {
      select.value = preselect;
    }
  }

  async function loadDistricts(stateId, preselect = '') {
    const select = $('#district_id');
    if (!select) return;
    
    if (!stateId) {
      populateSelect(select, [], '-- District --');
      return;
    }
    
    select.disabled = true;
    select.innerHTML = '<option value="">Loading districts...</option>';
    
    const data = await fetchJson(`{{ rtrim(url('/doctor/profile'), '/') }}/get-districts/${encodeURIComponent(stateId)}`);
    
    populateSelect(select, data || [], '-- District --');
    select.disabled = false;
    
    if (preselect) {
      select.value = preselect;
    }
  }

  async function loadCities(districtId, preselect = '') {
    const select = $('#city_id');
    if (!select) return;
    
    if (!districtId) {
      populateSelect(select, [], '-- City --');
      return;
    }
    
    select.disabled = true;
    select.innerHTML = '<option value="">Loading cities...</option>';
    
    const data = await fetchJson(`{{ rtrim(url('/doctor/profile'), '/') }}/get-cities/${encodeURIComponent(districtId)}`);
    
    populateSelect(select, data || [], '-- City --');
    select.disabled = false;
    
    if (preselect) {
      select.value = preselect;
    }
  }

  // Initialize cascading selects
  const countrySelect = $('#country_id');
  const stateSelect = $('#state_id');
  const districtSelect = $('#district_id');
  const citySelect = $('#city_id');

  if (countrySelect) {
    countrySelect.addEventListener('change', function() {
      loadStates(this.value);
      if (districtSelect) districtSelect.innerHTML = '<option value="">-- District --</option>';
      if (citySelect) citySelect.innerHTML = '<option value="">-- City --</option>';
    });
  }

  if (stateSelect) {
    stateSelect.addEventListener('change', function() {
      loadDistricts(this.value);
      if (citySelect) citySelect.innerHTML = '<option value="">-- City --</option>';
    });
  }

  if (districtSelect) {
    districtSelect.addEventListener('change', function() {
      loadCities(this.value);
    });
  }

  // Load initial values
  document.addEventListener('DOMContentLoaded', function() {
    const initialCountry = {!! json_encode($initialCountry) !!};
    const initialState = {!! json_encode($initialState) !!};
    const initialDistrict = {!! json_encode($initialDistrict) !!};
    const initialCity = {!! json_encode($initialCity) !!};
    
    if (initialCountry) {
      if (countrySelect) countrySelect.value = initialCountry;
      loadStates(initialCountry, initialState).then(() => {
        if (initialState) {
          loadDistricts(initialState, initialDistrict).then(() => {
            if (initialDistrict) {
              loadCities(initialDistrict, initialCity);
            }
          });
        }
      });
    }
  });

  /* ---------- Category to Clinics AJAX ---------- */
  const categorySelect = $('#category_id');
  
  async function loadClinicsByCategory(categoryId) {
    if (!categoryId) return null;
    
    try {
      const res = await fetch(`{{ rtrim(url('/doctor/profile'), '/') }}/clinics-by-category/${encodeURIComponent(categoryId)}`, {
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': getCsrf()
        }
      });
      
      if (!res.ok) throw new Error('Failed to load clinics');
      return await res.json();
    } catch (error) {
      console.warn('Error loading clinics:', error);
      return null;
    }
  }

  async function refreshClinicSelectsForCategory(categoryId) {
    const clinics = await loadClinicsByCategory(categoryId);
    if (!clinics) return;
    
    const options = ['<option value="">-- Choose existing clinic --</option>'];
    clinics.forEach(clinic => {
      options.push(`<option value="${clinic.id}">${clinic.name}</option>`);
    });
    
    const optionsHTML = options.join('');
    
    $$('.clinic-select').forEach(select => {
      const previousValue = select.value || '';
      select.innerHTML = optionsHTML;
      
      if (previousValue) {
        const optionExists = select.querySelector(`option[value="${previousValue}"]`);
        if (optionExists) {
          select.value = previousValue;
        }
      }
    });
    
    // Re-sync after updating options
    syncClinicSelectAndInput();
  }

  if (categorySelect) {
    categorySelect.addEventListener('change', function() {
      const categoryId = this.value;
      if (categoryId) {
        refreshClinicSelectsForCategory(categoryId);
      }
    });
    
    // Load clinics for initial category
    document.addEventListener('DOMContentLoaded', function() {
      const initialCategory = categorySelect.value;
      if (initialCategory) {
        refreshClinicSelectsForCategory(initialCategory);
      }
    });
  }

  /* ---------- Pincode Lookup ---------- */
  const pincodeInput = $('#pincode');
  const pincodeBtn = $('#pincodeLookupBtn');
  const pincodeHelp = $('#pincodeHelp');
  const pincodeIdInput = $('#pincode_id');

  if (pincodeBtn && pincodeInput) {
    pincodeBtn.addEventListener('click', async function(e) {
      e.preventDefault();
      
      const pincode = pincodeInput.value.trim();
      
      // Validate pincode format
      if (!/^\d{6}$/.test(pincode)) {
        if (pincodeHelp) {
          pincodeHelp.textContent = 'Please enter a valid 6-digit pincode';
          pincodeHelp.style.color = '#dc3545';
        }
        pincodeInput.focus();
        return;
      }
      
      // Show loading state
      pincodeBtn.disabled = true;
      pincodeBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>';
      
      if (pincodeHelp) {
        pincodeHelp.textContent = 'Looking up pincode...';
        pincodeHelp.style.color = '#6c757d';
      }
      
      try {
        const url = `{{ rtrim(url('/doctor/profile'), '/') }}/pincode/${encodeURIComponent(pincode)}/lookup`;
        const response = await fetch(url, {
          credentials: 'same-origin',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': getCsrf()
          }
        });
        
        if (!response.ok) {
          throw new Error(`HTTP ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.error) {
          throw new Error(data.message || 'Lookup failed');
        }
        
        const payload = data.payload || data;
        
        // Update pincode ID
        if (pincodeIdInput && payload.id) {
          pincodeIdInput.value = payload.id;
        }
        
        // Show success message
        if (pincodeHelp) {
          pincodeHelp.textContent = `Found: ${payload.city || payload.office_name || payload.pincode}`;
          pincodeHelp.style.color = '#198754';
        }
        
        // Auto-populate location fields if data is available
        if (payload.country_id && countrySelect) {
          countrySelect.value = payload.country_id;
          await loadStates(payload.country_id, payload.state_id);
          
          if (payload.state_id && stateSelect) {
            stateSelect.value = payload.state_id;
            await loadDistricts(payload.state_id, payload.district_id);
            
            if (payload.district_id && districtSelect) {
              districtSelect.value = payload.district_id;
              await loadCities(payload.district_id, payload.city_id);
              
              if (payload.city_id && citySelect) {
                citySelect.value = payload.city_id;
              }
            }
          }
        }
        
      } catch (error) {
        console.error('Pincode lookup error:', error);
        
        if (pincodeHelp) {
          pincodeHelp.textContent = 'Pincode not found or lookup failed';
          pincodeHelp.style.color = '#dc3545';
        }
        
        // Clear location fields
        if (stateSelect) stateSelect.innerHTML = '<option value="">-- State --</option>';
        if (districtSelect) districtSelect.innerHTML = '<option value="">-- District --</option>';
        if (citySelect) citySelect.innerHTML = '<option value="">-- City --</option>';
        
      } finally {
        // Restore button state
        pincodeBtn.disabled = false;
        pincodeBtn.innerHTML = '<i class="bi bi-search"></i>';
      }
    });
    
    // Allow pressing Enter in pincode field to trigger lookup
    pincodeInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        pincodeBtn.click();
      }
    });
  }

  // Real-time sync for clinic select and input
  document.addEventListener('input', function(e) {
    if (e.target.matches('.clinic-select, .clinic-name-input')) {
      syncClinicSelectAndInput();
    }
  });
  
  document.addEventListener('change', function(e) {
    if (e.target.matches('.clinic-select, .clinic-name-input')) {
      syncClinicSelectAndInput();
    }
  });

})();
</script>
<script>
/* ---------- Cant find area toggle ---------- */
const cantFindArea = document.getElementById('cantFindArea');
const citySelectEl = document.getElementById('city_id');
const otherCityInput = document.getElementById('other_city_name');

if (cantFindArea && citySelectEl && otherCityInput) {
  cantFindArea.addEventListener('click', function () {
    citySelectEl.value = '';
    citySelectEl.required = false;
    otherCityInput.classList.remove('d-none');
    otherCityInput.required = true;
    otherCityInput.focus();
  });

  citySelectEl.addEventListener('change', function () {
    if (this.value) {
      otherCityInput.value = '';
      otherCityInput.classList.add('d-none');
      otherCityInput.required = false;
    }
  });
}
</script>
<script>
async function refreshClinicSelectsForCategory(categoryId) {
  const clinics = await loadClinicsByCategory(categoryId);
  if (!clinics) return;

  let options = '<option value="">-- Choose existing clinic --</option>';
  clinics.forEach(c => {
    options += `<option value="${c.id}">${c.name}</option>`;
  });

  document.querySelectorAll('.clinic-select').forEach(select => {
    const prev = select.value;
    select.innerHTML = options;
    if (prev && select.querySelector(`option[value="${prev}"]`)) {
      select.value = prev;
    }
  });

  syncClinicSelectAndInput();
}
</script>
@endpush