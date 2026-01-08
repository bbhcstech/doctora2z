@extends('admin.layout.app')

@section('title', 'Import Category in Excel')

@section('content')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Import Category in Excel</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('state.index') }}">Category</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </nav>
        </div>

        <div class="card" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px; ">
            <div class="card-body">
                @if (session('success'))
                    <p style="color: green;">{{ session('success') }}</p>
                @endif

                <p><a href="{{ url('/sample_category.xlsx') }}" download>Download Sample Excel</a></p>

                <form action="{{ route('category.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label>Select Excel File:</label>
                    <input type="file" name="file" required>
                    <button type="submit">Import</button>
                </form>
            </div>
        </div>
    </main>

@endsection
