@extends('admin.layout.app')

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Add New Trending Clinic</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Trending Clinic</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('trending-clinic.store') }}" method="POST">
        @csrf
        <label for="doctor">Select Clinic:</label>
        <select name="name" id="doctor" >
            <option value="">-- Select a Clinic --</option>
            @foreach($clinics as $clinic)
                <option value="{{ $clinic->name }}" data-clinic-id="{{ $clinic->id }}">
                    {{ $clinic->name }}
                </option>
            @endforeach
        </select>

        <!-- Hidden input for clinic_id -->
        <input type="hidden" name="clinic_id" id="clinic_id" value="{{ $clinics->first()->id ?? '' }}">

        <button type="submit">Save</button>
    </form>
</main>

@endsection
