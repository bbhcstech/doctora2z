{{-- Location form partial --}}
@php $d = $doctor ?? null; @endphp

<form action="{{ route('doctor.profile.updateLocation', $d->id ?? '') }}" method="POST" id="locationFormPartial">
    @csrf

    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Country</label>
            <select name="country_id" id="country_id_partial" class="form-select">
                <option value="">-- Country --</option>
                @foreach ($countries ?? [] as $id => $name)
                    <option value="{{ $id }}" @selected((string) old('country_id', $d->country_id ?? '') === (string) $id)>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">State</label>
            <select name="state_id" id="state_id_partial" class="form-select">
                <option value="">-- State --</option>
                @foreach ($states ?? [] as $id => $name)
                    <option value="{{ $id }}" @selected((string) old('state_id', $d->state_id ?? '') === (string) $id)>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">District</label>
            <select name="district_id" id="district_id_partial" class="form-select">
                <option value="">-- District --</option>
                @foreach ($districts ?? [] as $id => $name)
                    <option value="{{ $id }}" @selected((string) old('district_id', $d->district_id ?? '') === (string) $id)>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">City</label>
            <select name="city_id" id="city_id_partial" class="form-select">
                <option value="">-- City --</option>
                @foreach ($cities ?? [] as $id => $name)
                    <option value="{{ $id }}" @selected((string) old('city_id', $d->city_id ?? '') === (string) $id)>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Pincode</label>
            <div class="d-flex gap-2">
                <input name="pincode" id="pincode_partial" class="form-control"
                    value="{{ old('pincode', optional($d->pincode)->pincode ?? '') }}" maxlength="6">
                <button type="button" id="pincodeLookupBtn_partial" class="btn btn-outline-primary">Lookup</button>
            </div>
            <input type="hidden" name="pincode_id" id="pincode_id_partial"
                value="{{ old('pincode_id', $d->pincode_id ?? '') }}">
            <small id="pincodeHelp_partial" class="text-muted">Enter 6-digit code and click Lookup</small>
        </div>

        <div class="col-md-8">
            <label class="form-label">Address</label>
            <input name="address" class="form-control" value="{{ old('address', $d->address ?? '') }}">
        </div>

        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary">Save Location</button>
        </div>
    </div>
</form>
