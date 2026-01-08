@extends('admin.layout.app')

@section('title', 'Contact Us')

@section('content')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Contact Us</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Contact Us</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px;">
            <div class="card-body">
                <h5 class="card-title">Contact Us</h5>
                <form action="{{ route('contact-us.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ $contactUs->title }}">
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3">{{ $contactUs->address }}</textarea>
                    </div>

                    <!-- Mail -->
                    <div class="mb-3">
                        <label for="mail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="mail" name="mail"
                            value="{{ $contactUs->mail }}">
                    </div>

                    <!-- Phone -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            value="{{ $contactUs->phone }}">
                    </div>

                    <!-- Map URL -->
                    <div class="mb-3">
                        <label for="map_url" class="form-label">Map URL</label>
                        <input type="text" class="form-control" id="map_url" name="map_url"
                            value="{{ $contactUs->map_url }}">
                    </div>

                    <!-- Banner Image -->
                    <div class="mb-3">
                        <label for="banner_image" class="form-label">Banner Image</label>
                        @if (!empty($contactUs->banner_image))
                            <div class="mb-2">
                                <img src="{{ asset('admin/uploads/contact/' . $contactUs->banner_image) }}"
                                    alt="Banner Image" style="max-width: 150px; height: auto;">
                            </div>
                        @endif
                        <input type="file" class="form-control" id="banner_image" name="banner_image">
                    </div>

                    <!-- Submit Button -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>

    </main>
@endsection
