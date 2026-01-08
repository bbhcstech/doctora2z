@extends('admin.layout.app')

@section('title', 'Category Listing')

@section('content')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Category Listing</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Category</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <div class="mb-3">
            <a href="{{ route('category.create') }}" class="btn btn-primary">Add New Category</a>
            <a href="{{ route('category.import.form') }}" class="btn btn-primary">Import New Category</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <form id="bulkDeleteForm" action="{{ route('category.bulkDelete') }}" method="POST">

                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger mb-2" id="deleteSelected" disabled>
                    Delete Selected
                </button>

                <table id="example1" class="table table-striped table-bordered" style="font-size:13px;">
                    <thead class="thead-dark">
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>#</th>
                            <th>Category Type</th>
                            <th>Category Name</th>
                            <th>Category Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $index => $category)
                            <tr>
                                <td><input type="checkbox" class="categoryCheckbox" name="category_ids[]"
                                        value="{{ $category->id }}"></td>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $category->type }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    @if (!empty($category->image))
                                        <img src="{{ asset('admin/uploads/category/' . $category->image) }}"
                                            alt="Category Image" style="max-width: 100px; height: auto;">
                                    @else
                                        <span>No Image</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('category.edit', $category->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('category.destroy', $category->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this category?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>

    </main>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Select all checkboxes when #selectAll is clicked
        $("#selectAll").on("click", function() {
            $(".categoryCheckbox").prop("checked", $(this).prop("checked"));
            toggleDeleteButton();
        });

        // Individual checkbox click event
        $(document).on("change", ".categoryCheckbox", function() {
            if ($(".categoryCheckbox:checked").length === $(".categoryCheckbox").length) {
                $("#selectAll").prop("checked", true);
            } else {
                $("#selectAll").prop("checked", false);
            }
            toggleDeleteButton();
        });

        // Enable/Disable delete button based on selection
        function toggleDeleteButton() {
            let anyChecked = $(".categoryCheckbox:checked").length > 0;
            $("#deleteSelected").prop("disabled", !anyChecked);
        }

        // Prevent accidental form submission without confirmation
        $("#bulkDeleteForm").on("submit", function() {
            return confirm("Are you sure you want to delete the selected categories?");
        });
    });
</script>
