@extends('admin.layout.app')

@section('title', 'Country Listing')

@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Country Listing</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Country</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <div class="mb-3">
    <a href="{{ route('country.create') }}" class="btn btn-primary mb-3">Add New Country</a>
 
    <a href="{{ route('country.upload') }}" class="btn btn-primary mb-3">Upload Country Excel Data</a>
  </div>

  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="table-responsive">
    <table id="example2" class="table table-striped table-bordered" style="font-size:13px;">
      <thead class="thead-dark">
        <tr>
          <th>#</th>
          <th>id</th>
          <th>Country Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($countries as $index => $country)
        <tr>
          
          <td>{{ $index + 1 }}</td>
          <th>{{ $country->id }}</th>
          <td>{{ $country->name }}</td>
          <td>
            <a href="{{ route('country.edit', $country->id) }}" class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ route('country.destroy', $country->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this country?')">Delete</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

</main>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

