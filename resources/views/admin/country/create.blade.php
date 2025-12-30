@extends('admin.layout.app')

@section('title', 'Add Country')

@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Add Country</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Add Country</li>
      </ol>
    </nav>
  </div>

  <div class="card" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px; ">
    <div class="card-body">
      <form action="{{ route('country.store') }}" method="POST">
        @csrf
        <div class="mb-3">
          <label for="name" class="form-label">Country Name</label>
          <input type="text" id="name" name="name" class="form-control" required>
          @error('name')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        </div>
  </div>
        <div class="text-end">
        <button type="submit" class="btn btn-primary">Save</button>
        </div>
         
  
      </form>
   

</main>
@endsection
