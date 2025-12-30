@extends('admin.layout.app')

@section('title', 'State (Part) Listing')

@section('content')
<main id="main" class="main">

  <div class="pagetitle">
    <h1>State (Part) Listing</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">State (Part)</li>
      </ol>
    </nav>
  </div>

  <div class="mb-3">
    <a href="{{ route('state.create') }}" class="btn btn-primary">Add New State (Part)</a>
    <a href="{{ route('states.import.form') }}" class="btn btn-primary">Import New State (Part)</a>
  </div>

  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="table-responsive">
    <table id="example1" class="table table-striped table-bordered" style="font-size:13px;">
      <thead class="thead-dark">
        <tr>
          <th>#</th>
          <th>ID</th>
          <th>Name</th>
          <th>Country</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($states as $index => $state)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ $state->id }}</td>
          <td>{{ $state->name }}</td>
          <td>{{ $state->country?->name ?? '—' }}</td> {{-- Safe access --}}
          <td>
            <a href="{{ route('state.edit', $state->id) }}" class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ route('state.destroy', $state->id) }}" method="POST" style="display:inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm"
                onclick="return confirm('Are you sure you want to delete this state?')">
                Delete
              </button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

</main>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  var $jq = jQuery.noConflict();

  $jq(document).ready(function() {
      $jq('#example1').DataTable({
          pageLength: 25,
          rowReorder: {
              selector: 'td:nth-child(2)'
          },
          responsive: true,
          order: [[1, 'desc']],
          dom: 'Bfrtip',
          buttons: [
              'excelHtml5',
              'csvHtml5',
              'pdfHtml5'
          ]
      });
  });
</script>
@endpush
