@extends('admin.layout.app')

@section('title', 'Add Banner')

@section('content')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Add Banner</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('banner.index') }}">Banner</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <div class="card" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px; ">
            <div class="card-body">
                <h5 class="card-title">Add Banner</h5>
                <form action="{{ route('banner.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Banner Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Banner Image</label>

                        <!-- Input for uploading a new image -->
                        <input type="file" class="form-control" id="image" name="image"
                            placeholder="Enter banner image">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Banner Mobile Image</label>

                        <!-- Input for uploading a new image -->
                        <input type="file" class="form-control" id="mobile_image" name="mobile_image"
                            placeholder="Enter banner image">
                    </div>
            </div>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Add</button>
            <a href="{{ route('banner.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
        </form>


    </main>
@endsection
