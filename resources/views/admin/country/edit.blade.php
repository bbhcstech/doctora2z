@extends('admin.layout.app')

@section('title', 'Edit Country')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- Start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <div class="page-title">
                                <h4 class="mb-0 font-size-18">Edit Country</h4>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('country.index') }}">Country</a></li>
                                    <li class="breadcrumb-item active">Edit</li>
                                </ol>
                            </div>
                            <div class="page-title-right">
                                <a href="{{ route('country.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End page title -->

                <!-- Form Card -->
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Edit Country Information</h4>
                                <p class="card-title-desc mb-5">Update the country information below</p>

                                <form action="{{ route('country.update', $country->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-4">
                                                <label for="name" class="form-label">
                                                    Country Name <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="fas fa-globe text-primary"></i>
                                                    </span>
                                                    <input type="text" class="form-control ps-0" id="name"
                                                        name="name" value="{{ old('name', $country->name) }}"
                                                        placeholder="Enter country name" required>
                                                </div>
                                                @error('name')
                                                    <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">
                                                    Country ID: <span
                                                        class="badge bg-light text-dark">#{{ $country->id }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('country.index') }}" class="btn btn-light">
                                                    <i class="fas fa-times me-1"></i> Cancel
                                                </a>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save me-1"></i> Update Country
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Form Card -->

            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
    </div>

    <style>
        .page-title-box {
            padding: 20px 0;
            background: #fff;
            margin-bottom: 20px;
            border-bottom: 1px solid #f1f1f1;
        }

        .card {
            border: none;
            box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.03);
            margin-bottom: 24px;
            border-radius: 12px;
        }

        .card-body {
            padding: 32px;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .form-control {
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid #ced4da;
        }

        .form-control:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 0.15rem rgba(67, 97, 238, 0.25);
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            padding: 12px 16px;
        }

        .card-title-desc {
            color: #6c757d;
            margin-bottom: 24px;
        }

        .form-text {
            color: #6c757d;
            font-size: 13px;
            margin-top: 6px;
        }

        .badge {
            padding: 0.35em 0.65em;
            font-weight: 500;
        }

        .btn {
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-primary {
            background-color: #4361ee;
            border-color: #4361ee;
        }

        .btn-primary:hover {
            background-color: #3a56d4;
            border-color: #3a56d4;
        }
    </style>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
