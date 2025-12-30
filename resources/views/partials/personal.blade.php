{{-- Personal form partial --}}
@php $d = $doctor ?? null; @endphp

<form action="{{ route('doctor.profile.updatePersonal', $d->id ?? '') }}" method="POST" enctype="multipart/form-data" id="personalFormPartial">
  @csrf

  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Name *</label>
      <input name="name" class="form-control" value="{{ old('name', $d->name ?? '') }}" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Email *</label>
      <input type="email" name="email" class="form-control" value="{{ old('email', $d->email ?? '') }}" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Phone Number *</label>
      <input name="phone_number" class="form-control" value="{{ old('phone_number', $d->phone_number ?? '') }}" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Phone Number 2</label>
      <input name="phone_number_2" class="form-control" value="{{ old('phone_number_2', $d->phone_number_2 ?? '') }}">
    </div>

    <div class="col-12">
      <label class="form-label">Profile Details</label>
      <textarea name="profile_details" class="form-control" rows="4">{{ old('profile_details', $d->profile_details ?? '') }}</textarea>
    </div>

    <div class="col-md-6">
      <label class="form-label">Profile Picture</label>
      <input type="file" name="profile_picture" class="form-control" accept="image/*">
      @if(!empty($d->profile_picture))
        <small class="text-muted d-block mt-1">Stored: <code>{{ $d->profile_picture }}</code></small>
      @endif
    </div>

    <div class="col-12 text-end">
      <button type="submit" class="btn btn-primary">Save Personal</button>
    </div>
  </div>
</form>
