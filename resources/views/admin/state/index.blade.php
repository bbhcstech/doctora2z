@extends('admin.layout.app')

@section('title', 'State Management')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- Start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <div class="page-title">
                                <h4 class="mb-0 font-size-18">State Management</h4>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active">State List</li>
                                </ol>
                            </div>
                            <div class="page-title-right">
                                <a href="{{ route('state.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-1"></i> Add New State
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End page title -->

                <!-- Success/Error Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Main Card -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <!-- Card Header with Actions -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h4 class="card-title mb-0">State List</h4>
                                        <p class="text-muted mb-0">Manage your states here</p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-end gap-2">


                                            <form method="GET" action="{{ route('state.index') }}">
                                                <select name="per_page" class="form-select form-select-sm"
                                                    onchange="this.form.submit()" style="width:90px">
                                                    <option value="25"
                                                        {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                                                    <option value="50"
                                                        {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                                    <option value="100"
                                                        {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                                </select>
                                            </form>

                                            {{-- ✅ Import Button --}}
                                            <a href="{{ route('states.import.form') }}" class="btn btn-outline-primary">
                                                <i class="fas fa-file-import me-1"></i> Import
                                            </a>

                                            



                                            <form id="bulkDeleteForm" action="{{ route('state.bulkDelete') }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <!-- ✅ MUST -->
                                                <input type="hidden" name="ids" id="selectedIds">

                                                <button type="submit" class="btn btn-danger" id="deleteSelected" disabled>
                                                    <i class="fas fa-trash-alt me-1"></i> Delete Selected
                                                </button>
                                            </form>

                                            <div class="btn-group">
                                                <button type="button" class="btn btn-outline-success dropdown-toggle"
                                                    data-bs-toggle="dropdown">
                                                    <i class="fas fa-download me-1"></i> Export
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item export-link"
                                                            href="{{ url('/state/export/excel') }}" target="_blank">
                                                            <i class="fas fa-file-excel text-success me-2"></i> Excel
                                                        </a></li>
                                                    <li><a class="dropdown-item export-link"
                                                            href="{{ url('/state/export/csv') }}" target="_blank">
                                                            <i class="fas fa-file-csv text-primary me-2"></i> CSV
                                                        </a></li>
                                                    <li><a class="dropdown-item export-link"
                                                            href="{{ url('/state/export/pdf') }}" target="_blank">
                                                            <i class="fas fa-file-pdf text-danger me-2"></i> PDF
                                                        </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Card Header -->

                                <!-- States Table -->
                                <div class="table-responsive">
                                    <table class="table table-hover table-centered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 50px;">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="selectAllCurrentPage">
                                                        <label class="form-check-label" for="selectAllCurrentPage"></label>
                                                    </div>
                                                </th>
                                                <th style="width: 60px;">#</th>
                                                <th style="width: 100px;">State ID</th>
                                                <th>State Name</th>
                                                <th>Country</th>
                                                <th style="width: 120px;" class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $startIndex = ($states->currentPage() - 1) * $states->perPage();
                                            @endphp
                                            @forelse ($states as $index => $state)
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input stateCheckbox"
                                                                name="ids[]" value="{{ $state->id }}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="fw-medium">{{ $startIndex + $index + 1 }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-dark">#{{ $state->id }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-xs me-2">
                                                                <div
                                                                    class="avatar-title bg-info-subtle text-info rounded-circle">
                                                                    <i class="fas fa-map-marker-alt"></i>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <h5 class="font-size-14 mb-0">{{ $state->name }}</h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if ($state->country)
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-xs me-2">
                                                                    <div
                                                                        class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                                        <i class="fas fa-globe"></i>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <span
                                                                        class="font-size-13">{{ $state->country->name }}</span>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a href="{{ route('state.edit', $state->id) }}"
                                                                class="btn btn-outline-warning btn-sm"
                                                                data-bs-toggle="tooltip" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('state.destroy', $state->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-outline-danger btn-sm"
                                                                    onclick="return confirm('Are you sure you want to delete this state?')"
                                                                    data-bs-toggle="tooltip" title="Delete">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-5">
                                                        <div class="empty-state">
                                                            <div class="empty-state-icon">
                                                                <i class="fas fa-map"
                                                                    style="font-size: 60px; color: #dee2e6;"></i>
                                                            </div>
                                                            <h5 class="empty-state-title mt-3">No States Found</h5>
                                                            <p class="empty-state-subtitle mb-4">Get started by creating
                                                                your first state</p>
                                                            <a href="{{ route('state.create') }}"
                                                                class="btn btn-primary">
                                                                <i class="fas fa-plus-circle me-1"></i> Add First State
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <!-- End Table -->

                                <!-- Pagination and Info -->
                                @if ($states->hasPages())
                                    <div class="row mt-4 align-items-center">
                                        <div class="col-md-6 col-12 mb-2 mb-md-0">
                                            <div class="text-muted">
                                                Showing <strong>{{ $states->firstItem() }}</strong>
                                                to <strong>{{ $states->lastItem() }}</strong>
                                                of <strong>{{ $states->total() }}</strong> results
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="d-flex justify-content-end">
                                                {{ $states->links('pagination::bootstrap-5') }}
                                            </div>
                                        </div>
                                    </div>
                                @endif


                            </div>
                            <!-- End Card Body -->
                        </div>
                        <!-- End Card -->
                    </div>
                    <!-- End Col -->
                </div>
                <!-- End Row -->

            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
    </div>

    <!-- Custom CSS -->
    <style>
        .page-title-box {
            padding: 20px 0;
            background: #fff;
            margin-bottom: 20px;
            border-bottom: 1px solid #f1f1f1;
        }

        .card {
            border: none;
            box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.03);
            margin-bottom: 24px;
            border-radius: 12px;
        }

        .card-body {
            padding: 24px;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
            transition: all 0.2s ease;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: #495057;
            padding: 16px 12px;
            background-color: #f8f9fa;
        }

        .table td {
            padding: 16px 12px;
            vertical-align: middle;
        }

        .btn-group-sm>.btn {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
        }

        .btn-outline-warning {
            border-color: #f7b84b;
            color: #f7b84b;
        }

        .btn-outline-warning:hover {
            background-color: #f7b84b;
            color: #fff;
        }

        .btn-outline-danger {
            border-color: #f06548;
            color: #f06548;
        }

        .btn-outline-danger:hover {
            background-color: #f06548;
            color: #fff;
        }

        .empty-state {
            padding: 40px 20px;
        }

        .empty-state-icon {
            margin-bottom: 20px;
        }

        .empty-state-title {
            font-size: 18px;
            font-weight: 600;
            color: #495057;
        }

        .empty-state-subtitle {
            color: #6c757d;
            font-size: 14px;
        }

        .badge {
            padding: 0.35em 0.65em;
            font-weight: 500;
        }

        .avatar-xs {
            width: 32px;
            height: 32px;
        }

        .avatar-title {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        #deleteSelected:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .form-check-input:checked {
            background-color: #4361ee;
            border-color: #4361ee;
        }

        .page-link {
            color: #4361ee;
            border-color: #dee2e6;
        }

        .page-item.active .page-link {
            background-color: #4361ee;
            border-color: #4361ee;
        }

        /* Export Link Styles */
        .export-link:hover {
            background-color: #f8f9fa !important;
        }

        .dropdown-item:active {
            background-color: #4361ee;
            color: white;
        }

        .dropdown-item:active .text-success,
        .dropdown-item:active .text-primary,
        .dropdown-item:active .text-danger {
            color: white !important;
        }
    </style>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const selectAll = document.getElementById('selectAllCurrentPage');
            const checkboxes = document.querySelectorAll('.stateCheckbox');
            const deleteBtn = document.getElementById('deleteSelected');
            const selectedIdsInput = document.getElementById('selectedIds');
            const form = document.getElementById('bulkDeleteForm');

            function updateSelected() {
                let ids = [];
                checkboxes.forEach(cb => {
                    if (cb.checked) ids.push(cb.value);
                });

                selectedIdsInput.value = ids.join(',');
                deleteBtn.disabled = ids.length === 0;

                deleteBtn.innerHTML = ids.length ?
                    `<i class="fas fa-trash-alt me-1"></i> Delete Selected (${ids.length})` :
                    `<i class="fas fa-trash-alt me-1"></i> Delete Selected`;
            }

            // Select all (current page only)
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateSelected();
                });
            }

            // Individual checkbox
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateSelected);
            });

            // Confirm before submit
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (!selectedIdsInput.value) {
                        Swal.fire('Warning', 'Please select at least one state', 'warning');
                        return;
                    }

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Selected states will be permanently deleted!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4361ee',
                        confirmButtonText: 'Yes, delete'
                    }).then(result => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }

        });
    </script>

    <!-- SweetAlert2 for better alerts (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
