@extends('admin.layout.app')

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Edit Trending Doctor</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('trending-doctors.index') }}">Trending Doctors</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('trending-doctors.update', $trendingDoctor->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Doctor Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $trendingDoctor->name }}" required>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</main>
@endsection
