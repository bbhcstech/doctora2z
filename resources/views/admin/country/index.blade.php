@extends('admin.layout.app')

@section('title', 'Country Management')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- Start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <div class="page-title">
                                <h4 class="mb-0 font-size-18">Country Management</h4>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Country List</li>
                                </ol>
                            </div>
                            <div class="page-title-right">
                                <a href="{{ route('country.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus-circle me-1"></i> Add New Country
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
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h4 class="card-title mb-0">Country List</h4>
                                        <p class="text-muted mb-0">Manage your countries here</p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-end flex-wrap gap-2">



                                            <form method="GET" action="{{ route('country.index') }}" class="row g-2 mb-3">
                                                <div class="col-md-4">
                                                    <input type="text" name="search" value="{{ request('search') }}"
                                                        class="form-control form-control-sm"
                                                        placeholder="Search country...">
                                                </div>

                                                <div class="col-md-auto">
                                                    <button class="btn btn-sm btn-primary">
                                                        Search
                                                    </button>
                                                </div>
                                            </form>



                                            <form id="bulkDeleteForm" action="{{ route('country.bulkDelete') }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <!-- Hidden input for selected IDs -->
                                                <input type="hidden" name="ids" id="selectedIds">

                                                <button type="submit" class="btn btn-danger btn-sm" id="deleteSelected"
                                                    disabled>
                                                    <i class="fas fa-trash-alt me-1"></i> Delete Selected
                                                </button>
                                            </form>

                                            {{-- <form method="GET" action="{{ route('country.index') }}" class="d-flex mb-3">
                                                <input type="text" name="search" value="{{ request('search') }}"
                                                    class="form-control me-2" placeholder="Search by country name or ID">

                                                <button class="btn btn-primary">
                                                    <i class="fas fa-search"></i> Search
                                                </button>
                                            </form> --}}
                                            <a href="{{ route('country.upload') }}" class="btn btn-secondary btn-sm">
                                                <i class="fas fa-file-upload me-1"></i> Import Excel
                                            </a>
                                            {{-- <form method="GET" action="{{ route('country.index') }}" class="d-flex mb-3">
                                                <input type="text" name="search" value="{{ request('search') }}"
                                                    class="form-control me-2" placeholder="Search by country name or ID">

                                                <button class="btn btn-primary">
                                                    <i class="fas fa-search"></i> Search
                                                </button>
                                            </form> --}}

                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-outline-success btn-sm dropdown-toggle"
                                                    data-bs-toggle="dropdown">
                                                    <i class="fas fa-download me-1"></i> Export
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item export-link"
                                                            href="{{ url('/country/export/excel') }}" target="_blank">
                                                            <i class="fas fa-file-excel text-success me-2"></i> Excel
                                                        </a></li>
                                                    <li><a class="dropdown-item export-link"
                                                            href="{{ url('/country/export/csv') }}" target="_blank">
                                                            <i class="fas fa-file-csv text-primary me-2"></i> CSV
                                                        </a></li>
                                                    <li><a class="dropdown-item export-link"
                                                            href="{{ url('/country/export/pdf') }}" target="_blank">
                                                            <i class="fas fa-file-pdf text-danger me-2"></i> PDF
                                                        </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Card Header -->

                                <!-- Countries Table -->
                                <div class="table-responsive">
                                    <table class="table table-hover table-centered table-nowrap mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 40px;" class="text-center">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="selectAllCurrentPage">
                                                        <label class="form-check-label" for="selectAllCurrentPage"></label>
                                                    </div>
                                                </th>
                                                <th style="width: 60px;">#</th>
                                                <th style="width: 80px;">ID</th>
                                                <th>Country Name</th>
                                                <th style="width: 100px;" class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $startIndex = ($countries->currentPage() - 1) * $countries->perPage();
                                            @endphp
                                            @forelse ($countries as $index => $country)
                                                <tr>
                                                    <td class="text-center">
                                                        <div class="form-check">
                                                            <input type="checkbox"
                                                                class="form-check-input countryCheckbox" name="ids[]"
                                                                value="{{ $country->id }}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="fw-medium">{{ $startIndex + $index + 1 }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-dark">#{{ $country->id }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-xs me-3">
                                                                <div
                                                                    class="avatar-title bg-primary-subtle text-primary rounded-circle fs-6">
                                                                    <i class="fas fa-globe"></i>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <h5 class="font-size-14 mb-0">{{ $country->name }}</h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center gap-1">
                                                            <a href="{{ route('country.edit', $country->id) }}"
                                                                class="btn btn-outline-warning btn-sm px-2 py-1"
                                                                data-bs-toggle="tooltip" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('country.destroy', $country->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-outline-danger btn-sm px-2 py-1"
                                                                    onclick="return confirm('Are you sure you want to delete this country?')"
                                                                    data-bs-toggle="tooltip" title="Delete">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5">
                                                        <div class="empty-state">
                                                            <div class="empty-state-icon mb-3">
                                                                <i class="fas fa-globe-americas"
                                                                    style="font-size: 60px; color: #dee2e6;"></i>
                                                            </div>
                                                            <h5 class="empty-state-title mt-3">No Countries Found</h5>
                                                            <p class="empty-state-subtitle mb-4">Get started by creating
                                                                your first country</p>
                                                            <a href="{{ route('country.create') }}"
                                                                class="btn btn-primary btn-sm">
                                                                <i class="fas fa-plus-circle me-1"></i> Add First Country
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
                                @if ($countries->hasPages())
                                    <div class="row mt-4">
                                        <div class="col-sm-6">
                                            <div class="text-muted">
                                                Showing <strong>{{ $countries->firstItem() }}</strong> to
                                                <strong>{{ $countries->lastItem() }}</strong> of
                                                <strong>{{ $countries->total() }}</strong> entries
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="float-end">
                                                <nav aria-label="Page navigation">
                                                    {{ $countries->links('pagination::bootstrap-5') }}
                                                </nav>
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
            padding: 12px 10px;
            background-color: #f8f9fa;
            font-size: 13px;
        }

        .table td {
            padding: 12px 10px;
            vertical-align: middle;
            font-size: 13px;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            border-radius: 4px;
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
            font-size: 16px;
            font-weight: 600;
            color: #495057;
        }

        .empty-state-subtitle {
            color: #6c757d;
            font-size: 13px;
        }

        .badge {
            padding: 0.25em 0.5em;
            font-weight: 500;
            font-size: 11px;
        }

        .avatar-xs {
            width: 28px;
            height: 28px;
        }

        .avatar-title {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .fs-6 {
            font-size: 14px !important;
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
            font-size: 13px;
            padding: 0.375rem 0.75rem;
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

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 16px;
            }

            .table th,
            .table td {
                padding: 8px 6px;
                font-size: 12px;
            }

            .btn-sm {
                padding: 0.2rem 0.4rem;
                font-size: 0.7rem;
            }

            .avatar-xs {
                width: 24px;
                height: 24px;
            }
        }

        /* Button spacing */
        .btn-group .dropdown-toggle {
            padding: 0.25rem 0.5rem 0.25rem 0.75rem;
        }

        /* Fix dropdown alignment */
        .dropdown-menu {
            min-width: 160px;
        }

        /* Better button group spacing */
        .d-flex.gap-2 {
            gap: 8px !important;
        }

        /* Table column widths */
        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: 40px;
            min-width: 40px;
        }

        .table th:nth-child(2),
        .table td:nth-child(2) {
            width: 60px;
            min-width: 60px;
        }

        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 80px;
            min-width: 80px;
        }

        .table th:nth-child(5),
        .table td:nth-child(5) {
            width: 100px;
            min-width: 100px;
        }
    </style>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Country Management Page Loaded');

            // Get elements
            const selectAll = document.getElementById('selectAllCurrentPage');
            const deleteBtn = document.getElementById('deleteSelected');
            const checkboxes = document.querySelectorAll('.countryCheckbox');
            const form = document.getElementById('bulkDeleteForm');
            const selectedIdsInput = document.getElementById('selectedIds');
            const exportLinks = document.querySelectorAll('.export-link');

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // 1. Select All functionality
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    console.log('Select All:', this.checked);
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateDeleteButton();
                });
            }

            // 2. Individual checkbox functionality
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectAll();
                    updateDeleteButton();
                });
            });

            // 3. Update Select All checkbox
            function updateSelectAll() {
                if (!selectAll) return;

                const checkedCount = getCheckedCount();
                const totalCount = checkboxes.length;

                selectAll.checked = checkedCount === totalCount && totalCount > 0;

                // Indeterminate state
                if (selectAll.indeterminate !== undefined) {
                    selectAll.indeterminate = checkedCount > 0 && checkedCount < totalCount;
                }
            }

            // 4. Update Delete button
            function updateDeleteButton() {
                if (!deleteBtn) return;

                const checkedCount = getCheckedCount();
                deleteBtn.disabled = checkedCount === 0;

                if (checkedCount > 0) {
                    deleteBtn.innerHTML = `<i class="fas fa-trash-alt me-1"></i> Delete (${checkedCount})`;
                } else {
                    deleteBtn.innerHTML = `<i class="fas fa-trash-alt me-1"></i> Delete Selected`;
                }
            }

            // 5. Get checked count
            function getCheckedCount() {
                let count = 0;
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) count++;
                });
                return count;
            }

            // 6. Get selected IDs
            function getSelectedIds() {
                let ids = [];
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        ids.push(checkbox.value);
                    }
                });
                return ids;
            }

            // 7. Form submission - FIXED
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const checkedCount = getCheckedCount();
                    const selectedIds = getSelectedIds();

                    console.log('Selected IDs for bulk delete:', selectedIds);

                    if (checkedCount === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Selection',
                            text: 'Please select at least one country to delete.',
                            confirmButtonColor: '#4361ee',
                            confirmButtonText: 'OK'
                        });
                        return false;
                    }

                    // Set selected IDs to hidden input
                    if (selectedIdsInput) {
                        selectedIdsInput.value = selectedIds.join(',');
                    }

                    // Show confirmation
                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete ${checkedCount} selected countries. This action cannot be undone!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4361ee',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete!',
                        cancelButtonText: 'Cancel',
                        width: '400px'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading
                            deleteBtn.disabled = true;
                            deleteBtn.innerHTML =
                                '<i class="fas fa-spinner fa-spin me-1"></i> Deleting...';

                            // Submit the form
                            form.submit();
                        }
                    });

                    return false;
                });
            }

            // 8. Export link handling
            exportLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    console.log('Export clicked:', this.href);
                });
            });

            // Initialize
            updateDeleteButton();
        });
    </script>

    <!-- SweetAlert2 for better alerts (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
