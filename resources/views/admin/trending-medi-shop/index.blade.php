@extends('admin.layout.app')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Trending Medical Shop</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Trending Medical Shop</li>
                </ol>
            </nav>
        </div>
        <!-- Add New Doctor Button -->
        <div class="mb-2">
            <a href="{{ route('trending-shop.create') }}" class="btn btn-primary">Add Trending Medical Shop</a>
        </div>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th> Medical Shop Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trendingshop as $doctor)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $doctor->name }}</td>
                        <td>

                            <form action="{{ route('trending-shop.destroy', $doctor->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
@endsection
