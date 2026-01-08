@extends('admin.layout.app')

@section('title', 'Import District/City/Town/Village in Excel')

@section('content')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Import District/City/Town/Village in Excel</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('district.index') }}">Districts</a></li>
                    <li class="breadcrumb-item active">Import</li>
                </ol>
            </nav>
        </div>

        <div class="card" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px;">
            <div class="card-body">

                {{-- success message --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- error messages --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Download sample Excel --}}
                <p>
                    <a href="{{ route('district.sample.download') }}" class="btn btn-link">
                        Download Sample Excel
                    </a>
                </p>

                {{-- Upload form --}}
                <form action="{{ route('district.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="file" class="form-label">Select Excel File:</label>
                        <input type="file" name="file" id="file"
                            class="form-control @error('file') is-invalid @enderror" accept=".xlsx,.csv" required>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Import</button>
                </form>
            </div>
        </div>
    </main>

@endsection
