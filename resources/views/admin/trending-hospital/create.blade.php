@extends('admin.layout.app')

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Add New Trending Hospital</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Trending Hospital</li>
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

    <form action="{{ route('trending-hospital.store') }}" method="POST">
        @csrf
        <label for="doctor">Select Hospital:</label>
        <select name="name" id="doctor" >
            <option value="">-- Select a Hospital --</option>
            @foreach($hospitals as $hospital)
                <option value="{{ $hospital->name }}" data-hospital-id="{{ $hospital->id }}">
                    {{ $hospital->name }}
                </option>
            @endforeach
        </select>

        <!-- Hidden input for clinic_id -->
        <input type="hidden" name="hospital_id" id="hospital_id" value="{{ $hospitals->first()->id ?? '' }}">

        <button type="submit">Save</button>
    </form>
</main>

@endsection
