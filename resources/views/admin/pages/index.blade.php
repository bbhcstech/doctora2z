@extends('admin.layout.app')

@section('title', 'Page Listing')

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Patients Say Listing</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Patients Say</li>
            </ol>
        </nav>
    </div>

    <div class="mb-3">
        <a href="{{ route('pages.create') }}" class="btn btn-primary">Add New Patients Say</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table id="example1" class="table table-striped table-bordered" style="font-size:13px;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client Name</th>
                    <th>Client Image</th>
                    <th>Profession</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pages as $page)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $page->title }}</td>
                        
                        <!-- Display Image -->
                        <td>
                            @if($page->banner_image)
                                <img src="{{ asset('/admin/uploads/pages/' . $page->banner_image) }}" alt="{{ $page->banner_image }}" width="50">
                            @else
                                No Image
                            @endif
                        </td>
                     <td>{{ $page->slug }}</td>
                     <td>{{ $page->desc }}</td>
                        <!-- Display Image Position -->
                        <!--<td>{{ ucfirst($page->image_position) }}</td>-->

                        <td>
                            <!-- Edit Button -->
                            <a href="{{ route('pages.edit', $page->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <!-- Delete Button (Modal Trigger) -->
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $page->id }}">
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="deleteModal{{ $page->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete the page titled "{{ $page->title }}"?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('pages.destroy', $page->id) }}" method="POST">
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
