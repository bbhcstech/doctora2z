@extends('admin.layout.app')

@section('title', 'Edit Banner')

@section('content')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Edit Banner</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('banner.index') }}">Banner</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <div class="card" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px; ">
            <div class="card-body">
                <h5 class="card-title">Edit Banner</h5>
                <form action="{{ route('banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Banner Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $banner->name }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Banner Image</label>

                        <!-- Display existing image if available -->
                        @if (!empty($banner->image))
                            <div class="mb-2">
                                <img src="{{ asset('admin/uploads/banners/' . $banner->image) }}" alt="Banner Image"
                                    style="max-width: 150px; height: auto;">
                            </div>
                        @endif

                        <!-- Input for uploading a new image -->
                        <input type="file" class="form-control" id="image" name="image"
                            placeholder="Enter banner image">
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Banner Mobile Image</label>

                        <!-- Display existing image if available -->
                        @if (!empty($banner->mobile_image))
                            <div class="mb-2">
                                <img src="{{ asset('admin/uploads/banners/' . $banner->mobile_image) }}"
                                    alt="Banner Mobile Image" style="max-width: 150px; height: auto;">
                            </div>
                        @endif

                        <!-- Input for uploading a new image -->
                        <input type="file" class="form-control" id="mobile_image" name="mobile_image"
                            placeholder="Enter banner mobile image">
                    </div>
            </div>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('banner.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
        </form>

    </main>
@endsection
