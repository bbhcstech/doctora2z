@extends('admin.layout.app')

@section('title', 'Edit District')

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Edit District/City/Town/Village</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('district.index') }}">Districts/City/Town/Village</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>

    <div class="card" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px;">
        <div class="card-body">
            <form action="{{ route('district.update', $district->id) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                {{-- Country --}}
                <div class="mb-3">
                    <label for="country_id" class="form-label">Country</label>
                    <select name="country_id" id="country_id" class="form-select" disabled>
                        <option value="{{ $district->state->country->id }}" selected>
                            {{ $district->state->country->name }}
                        </option>
                    </select>
                </div>

                {{-- State --}}
                <div class="mb-3">
                    <label for="state_id" class="form-label">State (Part)</label>
                    <select name="state_id" id="state_id" class="form-select @error('state_id') is-invalid @enderror" required>
                        <option value="{{ $district->state->id }}" selected>{{ $district->state->name }}</option>
                        @foreach($countries as $country)
                            @foreach($country->states as $state)
                                <option value="{{ $state->id }}" {{ $state->id == $district->state_id ? 'selected' : '' }}>
                                    {{ $state->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    @error('state_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- District --}}
                <div class="mb-3">
                    <label for="name" class="form-label">District/City/Town/Village Name</label>
                    <input type="text"
                           name="name"
                           id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $district->name) }}"
                           required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- City --}}
                <div class="mb-3">
                    <label for="city_name" class="form-label">City / Town / Village Name</label>
                    <input type="text"
                           name="city_name"
                           id="city_name"
                           class="form-control @error('city_name') is-invalid @enderror"
                           value="{{ old('city_name', $district->primaryPincode?->city?->name) }}"
                           placeholder="e.g., Singur">
                    @error('city_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Pincode --}}
                <div class="mb-3">
                    <label for="pincode" class="form-label">Pincode</label>
                    <input type="text"
                           name="pincode"
                           id="pincode"
                           class="form-control @error('pincode') is-invalid @enderror"
                           maxlength="6"
                           pattern="\d{6}"
                           inputmode="numeric"
                           value="{{ old('pincode', $district->primaryPincode?->pincode) }}"
                           placeholder="Enter 6-digit pincode">
                    @error('pincode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</main>

@endsection
