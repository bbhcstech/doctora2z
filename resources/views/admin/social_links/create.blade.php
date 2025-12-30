@extends('admin.layout.app')

@section('title', 'Create Social Link')

@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Add Social Link</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('social_links.index') }}">Social Link</a></li>
        <li class="breadcrumb-item active">Add</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <div class="card" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px; ">
    <div class="card-body">
      <h5 class="card-title">Add Social Link</h5>

  <form action="{{ route('social_links.store') }}" method="POST">
    @csrf

    <div class="mb-3">
      <label for="link_address" class="form-label">Link Address</label>
      <input type="url" class="form-control" id="link_address" name="link_address" required>
    </div>

    <div class="mb-3">
      <label for="link_icon" class="form-label">Link Icon (FontAwesome Class)</label>
      <input type="text" class="form-control" id="link_icon" name="link_icon" required>
    </div>
   </div>
   </div>
   <div class="text-end">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('social_links.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</main>
@endsection
