@extends('admin.layout.app')

@section('title', 'Edit Category')

@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Edit Category</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('category.index') }}">Category</a></li>
        <li class="breadcrumb-item active">Edit</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->
  
   @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

  <div class="card" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px; ">
    <div class="card-body">
      <h5 class="card-title">Edit Category</h5>
      <form action="{{ route('category.update', $category->id) }}" method="POST"  enctype="multipart/form-data">
        @csrf
        @method('PUT')
       <div class="mb-3">
          <label for="type" class="form-label">
            Category Type <span style="color: red;">*</span>
          </label>
          <select class="form-control" id="type" name="type" required>
            <option value="" disabled>Select a type</option>
            <option value="clinic" {{ $category->type === 'clinic' ? 'selected' : '' }}>Clinic</option>
            <option value="doctor" {{ $category->type === 'doctor' ? 'selected' : '' }}>Doctor</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="name" class="form-label">Category Name</label>
          <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required>
        </div>
        
        <div class="mb-3">
            <label for="image" class="form-label">Category Image</label>
            
            <!-- Display existing image if available -->
            @if (!empty($category->image))
                <div class="mb-2">
                    <img src="{{ asset('admin/uploads/category/' . $category->image) }}" alt="Category Image" style="max-width: 150px; height: auto;">
                </div>
            @endif
        
            <!-- Input for uploading a new image -->
            <input type="file" class="form-control" id="image" name="image" placeholder="Enter category image">
        </div>
        </div>
  </div>
  <div class="text-end">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('category.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    

</main>
@endsection
