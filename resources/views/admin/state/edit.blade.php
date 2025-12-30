@extends('admin.layout.app')

@section('title', 'Edit State(Part)')

@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Edit State(Part)</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('state.index') }}">State(Part)</a></li>
        <li class="breadcrumb-item active">Edit</li>
      </ol>
    </nav>
  </div>
 <div class="card" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px; ">
    <div class="card-body">
  <form action="{{ route('state.update', $state->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
      <label for="country_id" class="form-label">Country</label>
      <select name="country_id" id="country_id" class="form-select" required>
        @foreach($countries as $country)
          <option value="{{ $country->id }}" {{ $state->country_id == $country->id ? 'selected' : '' }}>
            {{ $country->name }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label for="name" class="form-label">State(Part) Name</label>
      <input type="text" name="name" id="name" class="form-control" value="{{ $state->name }}" required>
    </div>
</div>
</div>
<div class="text-end">
    <button type="submit" class="btn btn-primary">Update</button>
    </div>
  </form>

</main>

@endsection
