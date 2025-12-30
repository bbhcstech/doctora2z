@extends('admin.layout.app')

@section('title', 'Add State (Part)')

@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Add State</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('state.index') }}">State (Part)</a></li>
        <li class="breadcrumb-item active">Add</li>
      </ol>
    </nav>
  </div>
<div class="card" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px; ">
    <div class="card-body">
  <form action="{{ route('state.store') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label for="country_id" class="form-label">Country</label>
      <select name="country_id" id="country_id" class="form-select" required>
        <option value="">Select Country</option>
        @foreach($countries as $country)
          <option value="{{ $country->id }}">{{ $country->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label for="name" class="form-label">State (Part) Name</label>
      <input type="text" name="name" id="name" class="form-control" required>
    </div>
</div>
</div>
     <div class="text-end">
        <button type="submit" class="btn btn-primary">Save</button>
        </div>
  </form>

</main>

@endsection
