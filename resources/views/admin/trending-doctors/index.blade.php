@extends('admin.layout.app')

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Trending Doctors</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Trending Doctor</li>
            </ol>
        </nav>
    </div>
<!-- Add New Doctor Button -->
<div class="mb-2">
    <a href="{{ route('trending-doctors.create') }}" class="btn btn-primary">Add Trending Doctor</a>
</div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Doctor Name</th>
                <th>Total Visits</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trendingdoctors as $doctor)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $doctor->name }}</td>
                    <td>{{ $doctor->total_visit_count }}</td>
                    <td>
                        <!--<a href="{{ route('trending-doctors.edit', $doctor->id) }}" class="btn btn-primary">Edit</a>-->
                         <!-- Delete Button -->
                    <form action="{{ route('trending-doctors.destroy', $doctor->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this?')">Delete</button>
                    </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</main>
@endsection
