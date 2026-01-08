@extends('admin.layout.app')

@section('title', 'Clinics Listing')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Clinics</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Clinics Listing</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <!-- Add New Clinic Button -->
        <div class="mb-2">
            <a href="{{ route('clients.create') }}" class="btn btn-primary">Add Clinic</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Table Section -->
        <div class="table-responsive">
            <table id="example1" class="table table-striped table-bordered" style="font-size:13px;">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>id</th>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Country</th>
                        <th>State (Part)</th>
                        <th>District</th>
                        <th>City</th>
                        <th>Pincode</th>
                        <th>Other Information</th>
                        <th>Images</th>
                        <th>Website</th>
                        <th>Category</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($clinics as $index => $clinic)
                        @php
                            // keep this for now (can replace with relation + eager load later)
                            $user = DB::table('users')->where('id', $clinic->auth_id)->first();
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $clinic->id }}</td>
                            <td>{{ $clinic->name }}</td>
                            <td>{{ $clinic->phone_number }}</td>
                            <td>{{ $user->email ?? 'N/A' }}</td>
                            <td>{{ $clinic->address }}</td>
                            <td>{{ $clinic->country->name ?? 'N/A' }}</td>
                            <td>{{ $clinic->state->name ?? 'N/A' }}</td>
                            <td>{{ $clinic->district->name ?? 'N/A' }}</td>
                            <td>{{ $clinic->city->name ?? 'N/A' }}</td>
                            <td>{{ $clinic->pincode ?? 'N/A' }}</td>
                            <td>{{ $clinic->other_information ?? 'N/A' }}</td>

                            <!-- IMAGES (no json_decode; model casts images to array) -->
                            <td>
                                @php
                                    // thanks to casts, this should already be array|null; normalize defensively
                                    $images = $clinic->images ?? [];
                                    if (!is_array($images) && is_string($images)) {
                                        $decoded = json_decode($images, true);
                                        $images = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
                                    }
                                    // ensure it is an array
                                    $images = is_array($images) ? $images : [];
                                @endphp

                                @forelse($images as $image)
                                    @php
                                        $src = filter_var($image, FILTER_VALIDATE_URL) ? $image : asset($image);
                                    @endphp
                                    <img src="{{ $src }}" alt="Clinic Image"
                                        style="max-width:100px;max-height:100px;margin-right:10px">
                                @empty
                                    N/A
                                @endforelse
                            </td>

                            <td>{{ $clinic->website ?? 'N/A' }}</td>

                            <!-- CATEGORY (works with array cast or comma string) -->
                            <td>
                                @php
                                    $ids = $clinic->category_id ?? [];
                                    if (!is_array($ids)) {
                                        $ids = array_filter(array_map('trim', explode(',', (string) $ids)));
                                    }
                                    $categoryNames = $ids
                                        ? \App\Models\Category::whereIn('id', $ids)->pluck('name')->toArray()
                                        : [];
                                @endphp
                                {{ $categoryNames ? implode(', ', $categoryNames) : 'N/A' }}
                            </td>

                            <td>{{ $clinic->latitude ?? 'N/A' }}</td>
                            <td>{{ $clinic->longitude ?? 'N/A' }}</td>

                            <td>{{ $clinic->status ?? 'N/A' }}</td>

                            <td>
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('clients.edit', $clinic->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <a href="{{ route('clients.show', $clinic->id) }}"
                                        class="btn btn-success btn-sm">Show</a>

                                    <form action="{{ route('clients.destroy', $clinic->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this clinic?')">
                                            Delete
                                        </button>
                                    </form>

                                    @if (auth()->user()->role == 'admin')
                                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#statusModal"
                                            data-id="{{ $clinic->id }}" data-status="{{ $clinic->status }}">
                                            Change Status
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Status Modal -->
            <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="statusModalLabel">Update Status</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form id="statusForm" method="POST">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="clinic_id" id="clinic_id" value="">
                                <div class="form-group">
                                    <label for="status">Select Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="Approved">Approved</option>
                                        <option value="Pending">Pending</option>
                                    </select>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update Status</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Status Modal -->
        </div>
    </main>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        var $jq = jQuery.noConflict();
        $jq(document).ready(function() {
            var table = $jq('#example1').DataTable({
                pageLength: 25,
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                responsive: true,
                order: [
                    [1, 'desc']
                ],
                dom: 'Bfrtip',
                buttons: ['excelHtml5', 'csvHtml5', 'pdfHtml5']
            });
        });
    </script>

    <script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {
            // Set up CSRF token for Ajax
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Show modal and populate fields
            $('#statusModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                $('#clinic_id').val(button.data('id'));
                $('#status').val(button.data('status'));
            });

            // Handle form submission
            $('#statusForm').off('submit').on('submit', function(event) {
                event.preventDefault();
                var url = '{{ route('clients.update-status') }}';
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#statusModal').modal('hide');
                            alert(response.message);
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText || xhr.statusText);
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
@endsection
