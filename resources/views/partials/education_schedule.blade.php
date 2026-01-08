{{-- Education & Schedule partial --}}
@php $d = $doctor ?? null; @endphp

<form action="{{ route('doctor.profile.updateEducationSchedule', $d->id ?? '') }}" method="POST"
    id="educationFormPartial">
    @csrf

    <div class="row g-3">
        <div class="col-12">
            <label class="form-label">Degrees (one per line)</label>
            <textarea name="degrees" rows="4" class="form-control" placeholder="MBBS
MD">{{ old('degrees', is_array($d->degrees ?? null) ? implode("\n", $d->degrees) : $d->degrees ?? '') }}</textarea>
            <small class="text-muted">Enter each degree on a new line</small>
        </div>

        <div class="col-12">
            <label class="form-label">Clinic Days</label>
            <div class="d-flex gap-2 flex-wrap">
                @php
                    $daysMap = [
                        'monday' => 'Mon',
                        'tuesday' => 'Tue',
                        'wednesday' => 'Wed',
                        'thursday' => 'Thu',
                        'friday' => 'Fri',
                        'saturday' => 'Sat',
                        'sunday' => 'Sun',
                    ];
                    $initialClinicDays = old('clinic_days', $d->clinic_days ?? []);
                    if (is_string($initialClinicDays) && !empty($initialClinicDays)) {
                        $initialClinicDays = json_decode($initialClinicDays, true) ?: [$initialClinicDays];
                    }
                    $initialClinicDays = (array) $initialClinicDays;
                @endphp

                @foreach ($daysMap as $token => $label)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="clinic_days[]"
                            id="edu_day_{{ $token }}" value="{{ $token }}" @checked(in_array($token, $initialClinicDays))>
                        <label class="form-check-label small"
                            for="edu_day_{{ $token }}">{{ $label }}</label>
                    </div>
                @endforeach

                <div class="ms-3">
                    <label class="form-label small mb-1 d-block">Start</label>
                    <input type="time" name="clinic_start_time" class="form-control form-control-sm"
                        value="{{ old('clinic_start_time', isset($d->clinic_start_time) ? \Carbon\Carbon::parse($d->clinic_start_time)->format('H:i') : '') }}">
                </div>
                <div class="ms-2">
                    <label class="form-label small mb-1 d-block">End</label>
                    <input type="time" name="clinic_end_time" class="form-control form-control-sm"
                        value="{{ old('clinic_end_time', isset($d->clinic_end_time) ? \Carbon\Carbon::parse($d->clinic_end_time)->format('H:i') : '') }}">
                </div>
            </div>
        </div>

        <div class="col-12">
            <label class="form-label">Alternative Schedule</label>
            <textarea name="alternative_schedule" class="form-control" rows="3">{{ old('alternative_schedule', $d->alternative_schedule ?? '') }}</textarea>
        </div>

        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary">Save Education & Schedule</button>
        </div>
    </div>
</form>
