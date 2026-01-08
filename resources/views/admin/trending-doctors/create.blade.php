@extends('admin.layout.app')

@section('content')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Add New Trending Doctor</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Trending Doctor</li>
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

        <form action="{{ route('trending-doctors.store') }}" method="POST">
            @csrf
            <label for="doctor">Select Doctor:</label>
            <select name="name" id="doctor">
                <option value="">-- Select a Doctor --</option>
                @foreach ($doctors as $doctor)
                    <option value="{{ $doctor->doctor_name }}" data-doctor-id="{{ $doctor->doctor_id }}"
                        data-visiting-table-id="{{ $doctor->id }}" data-total-visits="{{ $doctor->total_visits }}">
                        {{ $doctor->doctor_name }} ({{ $doctor->total_visits }})
                    </option>
                @endforeach
            </select>

            <!-- Pass other required data directly in the form -->
            @foreach ($doctors as $doctor)
                <input type="hidden" name="doctor_id" value="{{ $doctor->doctor_id }}">
                <input type="hidden" name="doctor_visiting_tbl_id" value="{{ $doctor->id }}">
                <input type="hidden" name="total_visit_count" value="{{ $doctor->total_visits }}">
            @endforeach

            <button type="submit">Save</button>
        </form>
    </main>

@endsection
