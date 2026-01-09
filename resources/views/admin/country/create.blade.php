@extends('admin.layout.app')

@section('title', 'Add New Country')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- Start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <div class="page-title">
                                <h4 class="mb-0 font-size-18">Add New Country</h4>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('country.index') }}">Country</a></li>
                                    <li class="breadcrumb-item active">Add New</li>
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
                                <h4 class="card-title mb-4">Country Information</h4>
                                <p class="card-title-desc mb-5">Fill all information below to add a new country</p>

                                <form action="{{ route('country.store') }}" method="POST">
                                    @csrf

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
                                                        name="name"
                                                        placeholder="Enter country name (e.g., United States)"
                                                        value="{{ old('name') }}" required>
                                                </div>
                                                @error('name')
                                                    <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">
                                                    Enter the full name of the country. This name must be unique.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="reset" class="btn btn-light">
                                                    <i class="fas fa-redo me-1"></i> Reset
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save me-1"></i> Save Country
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
