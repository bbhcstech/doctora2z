@extends('admin.layout.app')

@section('title', 'Edit City/Town/Village')

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Edit City/Town/Village</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('town-village.index') }}">City/Towns/Villages</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
<div class="card" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px; ">
    <div class="card-body">
    <form action="{{ route('town-village.update', $town->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="country_id" class="form-label">Country</label>
            <select name="country_id" id="country_id" class="form-select" required>
                <option value="">Select Country</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}" {{ $town->district->state->country_id == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="state_id" class="form-label">State(Part)</label>
            <select name="state_id" id="state_id" class="form-select" required>
                <option value="">Select State(Part)</option>
                @foreach($states as $state)
                    <option value="{{ $state->id }}" {{ $town->district->state_id == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="district_id" class="form-label">District</label>
            <select name="district_id" id="district_id" class="form-select" required>
                <option value="">Select District</option>
                @foreach($districts as $district)
                    <option value="{{ $district->id }}" {{ $town->district_id == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">City/Town/Village Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $town->name }}" required>
        </div>
</div>
</div>
<div class="text-end">
        <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</main>

@endsection
