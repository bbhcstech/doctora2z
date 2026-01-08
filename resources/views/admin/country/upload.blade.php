@extends('admin.layout.app')

@section('title', 'Add Country Upload Excel File')

@section('content')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Add Country Upload Excel File</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Add Country Upload Excel File</li>
                </ol>
            </nav>
        </div>

        <div class="card" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px; ">
            <div class="card-body">


                @if (session('success'))
                    <p style="color: green;">{{ session('success') }}</p>
                @endif
                <p><a href="{{ url('/sample_countries.xlsx') }}" download>Download Sample Excel</a></p>

                <div class="card" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px; ">
                    <div class="card-body">
                        <form action="{{ route('country.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" required>
                            <button type="submit">Upload</button>
                        </form>
                    </div>
                </div>


    </main>
@endsection
