@extends('admin.layout.app')

@section('title', 'Banner Listing')

@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Banner Listing</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Banner</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <div class="mb-3">
    <a href="{{ route('banner.create') }}" class="btn btn-primary">Add New Banner</a>
  </div>

  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="table-responsive">
    <table id="example1" class="table table-striped table-bordered" style="font-size:13px;">
      <thead class="thead-dark">
        <tr>
          <th>#</th>
          <th>Banner Name</th>
           <th>Banner Image</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($banner as $ban)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $ban->name }}</td>
           <td>
                    @if (!empty($ban->image))
                        <img src="{{ asset('admin/uploads/banners/' . $ban->image) }}" alt="Banner Image" style="max-width: 100px; height: auto;">
                    @else
                        <span>No Image</span>
                    @endif
                </td>
               
          <td>
            <a href="{{ route('banner.edit', $ban->id) }}" class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ route('banner.destroy', $ban->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this banner?')">Delete</button>
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
