@extends('admin.layout.app')

@section('title', 'About Us')

@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>About Us</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">About Us</li>
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
      <h5 class="card-title">About Us</h5>
      <form action="{{ route('about-us.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
          <label for="title" class="form-label">Title</label>
          <input type="text" class="form-control" id="title" name="title" value="{{ $aboutUs->title }}" >
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea class="form-control" id="description" name="description" rows="5" >{{ $aboutUs->description }}</textarea>
        </div>
         
          
        <div class="mb-3">
          <label for="banner_image" class="form-label">Banner Image</label>
          @if (!empty($aboutUs->banner_image))
            <div class="mb-2">
              <img src="{{ asset('admin/uploads/about/' . $aboutUs->banner_image) }}" alt="Banner Image" style="max-width: 150px; height: auto;">
            </div>
          @endif
          <input type="file" class="form-control" id="banner_image" name="banner_image">
        </div>

        <div class="mb-3">
          <label for="page_image" class="form-label">Page Image</label>
          @if (!empty($aboutUs->page_image))
            <div class="mb-2">
              <img src="{{ asset('admin/uploads/about/' . $aboutUs->page_image) }}" alt="Page Image" style="max-width: 150px; height: auto;">
            </div>
          @endif
          <input type="file" class="form-control" id="page_image" name="page_image">
        </div>

        <div class="mb-3">
          <label for="button_text" class="form-label">Button Text</label>
          <input type="text" class="form-control" id="button_text" name="button_text" value="{{ $aboutUs->button_text }}">
        </div>

        <div class="mb-3">
          <label for="button_url" class="form-label">Button URL</label>
          <input type="url" class="form-control" id="button_url" name="button_url" value="{{ $aboutUs->button_url }}">
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>

</main>
@endsection
