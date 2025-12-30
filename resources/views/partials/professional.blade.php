{{-- Professional form partial --}}
@php $d = $doctor ?? null; @endphp

<form action="{{ route('doctor.profile.updateProfessional', $d->id ?? '') }}" method="POST" id="professionalFormPartial">
  @csrf

  <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label">Degree</label>
      <input name="degree" class="form-control" value="{{ old('degree', $d->degree ?? '') }}">
    </div>

    <div class="col-md-4">
      <label class="form-label">Speciality</label>
      <input name="speciality" class="form-control" value="{{ old('speciality', $d->speciality ?? '') }}">
    </div>

    <div class="col-md-4">
      <label class="form-label">Registration No</label>
      <input name="registration_no" class="form-control" value="{{ old('registration_no', $d->registration_no ?? '') }}">
    </div>

    <div class="col-md-6">
      <label class="form-label">Council</label>
      <input name="council" class="form-control" value="{{ old('council', $d->council ?? '') }}">
    </div>

    <div class="col-md-6">
      <label class="form-label">Category</label>
      <select name="category_id" id="category_id_partial" class="form-select">
        <option value="">-- Select Category --</option>
        @foreach(($categories ?? []) as $id => $name)
          <option value="{{ $id }}" @selected((string)old('category_id', $d->category_id ?? '') === (string)$id)>{{ $name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Clinic</label>
      <select name="clinic_id" id="clinic_id_partial" class="form-select">
        <option value="">-- Select Clinic --</option>
        @foreach(($clinics ?? []) as $id=>$name)
          <option value="{{ $id }}" @selected((string)old('clinic_id', $d->clinic_id ?? '') === (string)$id)>{{ $name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Clinic Name (optional)</label>
      <input name="clinic_name" class="form-control" value="{{ old('clinic_name', $d->clinic_name ?? '') }}">
    </div>

    <div class="col-md-4">
      <label class="form-label">Website</label>
      <input name="website" class="form-control" value="{{ old('website', $d->website ?? '') }}" placeholder="https://example.com">
    </div>

    <div class="col-md-4">
      <label class="form-label">Facebook</label>
      <input name="facebook" class="form-control" value="{{ old('facebook', $d->facebook ?? '') }}">
    </div>

    <div class="col-md-4">
      <label class="form-label">Instagram</label>
      <input name="instagram" class="form-control" value="{{ old('instagram', $d->instagram ?? '') }}">
    </div>

    <div class="col-12 text-end">
      <button type="submit" class="btn btn-primary">Save Professional</button>
    </div>
  </div>
</form>
