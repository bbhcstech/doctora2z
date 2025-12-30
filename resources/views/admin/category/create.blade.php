@extends('admin.layout.app')

@section('title', 'Create Category')

@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Create Category</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('category.index') }}">Category</a></li>
        <li class="breadcrumb-item active">Create</li>
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
      <h5 class="card-title">Add a New Category</h5>
      <form action="{{ route('category.store') }}" method="POST"  enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
          <label for="category" class="form-label">Category Type <span style="color: red;">*</span> </label>
          <select class="form-control" id="type" name="type" required>
            <option value="" disabled selected>Select a type</option>
            <option value="clinic">Clinic</option>
            <option value="doctor">Doctor</option>
           
          </select>
        </div>
                
        <div class="mb-3">
          <label for="name" class="form-label">Category Name <span style="color: red;">*</span></label>
          <input type="text" class="form-control" id="name" name="name" placeholder="Enter category name" required>
        </div>
        
        <div class="mb-3">
          <label for="name" class="form-label">Category Image <span style="color: red;">*</span></label>
          <input type="file" class="form-control" id="image" name="image" placeholder="Enter category image" required>
        </div>
        </div>
  </div>
<div class="text-end">
        <button type="submit" class="btn btn-primary">Save</button>
        
        <a href="{{ route('category.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    
</main>
@endsection
