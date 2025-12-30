@extends('admin.layout.app')

@section('title', 'Social Links')

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Social link</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Social Link</li>
            </ol>
        </nav>
    </div>

    <div class="mb-3">
        <a href="{{ route('social_links.create') }}" class="btn btn-primary">Add New Social Link</a>
    </div>
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

    <div class="table-responsive">
        <table id="example1" class="table table-striped table-bordered" style="font-size:13px;">
    <thead>
      <tr>
        <th>ID</th>
        <th>Link Address</th>
        <th>Link Icon</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($socialLinks as $link)
        <tr>
          <td>{{ $link->id }}</td>
          <td>{{ $link->link_address }}</td>
          <td><i class="{{ $link->link_icon }}"></i></td>
          <td>
                            <!-- Edit Button -->
                            <a href="{{ route('social_links.edit', $link->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <!-- Delete Button (Modal Trigger) -->
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $link->id }}">
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="deleteModal{{ $link->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this Link?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('social_links.destroy', $link->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>

        
    </div>
</main>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var $jq = jQuery.noConflict();

$jq(document).ready(function() {
    var table = $jq('#example1').DataTable({
        pageLength: 25,
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true,
        order: [[1, 'desc']],
        dom: 'Bfrtip',
        buttons: [
            // 'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
});
</script>
