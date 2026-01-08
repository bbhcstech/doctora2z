@extends('admin.layout.app')

@section('title', 'District List')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Districts/City/Town/Village List</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Districts</li>
                </ol>
            </nav>
        </div>

        <div class="card p-3">
            <div class="d-flex justify-content-between mb-2">
                <a href="{{ route('district.create') }}" class="btn btn-success">+ Add District</a>
                <button id="bulk-delete-btn" class="btn btn-danger">Delete Selected</button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>#</th>
                            <th>District Name</th>
                            <th>State</th>
                            <th>Country</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($districts as $index => $district)
                            <tr>
                                <td><input type="checkbox" class="select-row" value="{{ $district->id }}"></td>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $district->name }}</td>
                                <td>{{ $district->state->name ?? '-' }}</td>
                                <td>{{ $district->state->country->name ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('district.edit', $district->id) }}"
                                        class="btn btn-sm btn-primary">Edit</a>
                                    <!-- Optional: individual delete -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Select/Deselect All Checkboxes
        $('#select-all').on('click', function() {
            $('.select-row').prop('checked', this.checked);
        });

        // Bulk Delete
        $('#bulk-delete-btn').on('click', function() {
            const selected = $('.select-row:checked');
            if (selected.length === 0) {
                alert('Please select at least one record to delete.');
                return;
            }

            if (!confirm('Are you sure you want to delete selected records?')) return;

            const ids = selected.map(function() {
                return $(this).val();
            }).get();

            $.ajax({
                url: "{{ route('district.bulk-delete') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids: ids
                },
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    alert('Something went wrong.');
                    console.log(xhr.responseText);
                }
            });
        });
    </script>
@endsection
