@extends('admin.layout.app')

@section('title', 'District Management')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- Start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <div class="page-title">
                                <h4 class="mb-0 font-size-18">District Management</h4>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active">District List</li>
                                </ol>
                            </div>
                            <div class="page-title-right">
                                <a href="{{ route('district.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-1"></i> Add New District
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
                                        <h4 class="card-title mb-0">District List</h4>
                                        <p class="text-muted mb-0">Manage your districts here</p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-end gap-2">


                                            {{-- 🔍 Search --}}
                                            <form method="GET" action="{{ route('district.index') }}" class="d-inline">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" name="search" value="{{ request('search') }}"
                                                        class="form-control"
                                                        placeholder="Search district / state / pincode">
                                                    <button class="btn btn-outline-secondary" type="submit">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </form>

                                            {{-- 📥 Import --}}
                                            <a href="{{ route('district.import.form') }}" class="btn btn-outline-primary">
                                                <i class="fas fa-upload me-1"></i> Import
                                            </a>



                                            {{-- ✅ Bulk Delete Form --}}
                                            <form id="bulkDeleteForm" action="{{ route('district.bulkDelete') }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
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
                                                            href="{{ url('/district/export/excel') }}" target="_blank">
                                                            <i class="fas fa-file-excel text-success me-2"></i> Excel
                                                        </a></li>
                                                    <li><a class="dropdown-item export-link"
                                                            href="{{ url('/district/export/csv') }}" target="_blank">
                                                            <i class="fas fa-file-csv text-primary me-2"></i> CSV
                                                        </a></li>
                                                    <li><a class="dropdown-item export-link"
                                                            href="{{ url('/district/export/pdf') }}" target="_blank">
                                                            <i class="fas fa-file-pdf text-danger me-2"></i> PDF
                                                        </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Card Header -->

                                <!-- Districts Table -->
                                <div class="table-responsive">
                                    <table class="table table-hover table-centered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                {{-- Select All Checkbox --}}
                                                <th style="width: 40px;">
                                                    <input type="checkbox" id="selectAllCurrentPage">
                                                </th>

                                                {{-- Serial Number --}}
                                                <th style="width: 50px;">#</th>

                                                <th>Country</th>
                                                <th>State</th>
                                                <th>District Name</th>
                                                <th>Area / City</th>
                                                <th>Pincode</th>
                                                <th class="text-center" style="width:120px;">Actions</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($districts as $index => $pin)
                                                <tr>

                                                    {{-- Checkbox --}}
                                                    <td>
                                                        <input type="checkbox" class="form-check-input districtCheckbox"
                                                            name="ids[]" value="{{ $pin->district_id }}">
                                                    </td>

                                                    {{-- Serial --}}
                                                    <td>{{ $districts->firstItem() + $index }}</td>

                                                    {{-- Country --}}
                                                    <td>{{ $pin->district?->state?->country?->name ?? '—' }}</td>

                                                    {{-- State --}}
                                                    <td>{{ $pin->district?->state?->name ?? '—' }}</td>

                                                    {{-- District --}}
                                                    <td>{{ $pin->district?->name ?? '—' }}</td>

                                                    {{-- Area / City --}}
                                                    <td>{{ $pin->city?->name ?? '—' }}</td>

                                                    {{-- Pincode --}}
                                                    <td>{{ $pin->pincode }}</td>

                                                    {{-- Actions --}}
                                                    <td class="text-center">
                                                        <a href="{{ route('district.edit', $pin->district_id) }}"
                                                            class="btn btn-sm btn-warning">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </td>

                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">No data found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>



                                    </table>
                                </div>
                                <!-- End Table -->

                                <!-- Pagination and Info -->
                                @if ($districts->hasPages())
                                    <div class="row mt-4">
                                        <div class="col-sm-6">
                                            <div class="text-muted">
                                                Showing <strong>{{ $districts->firstItem() }}</strong> to
                                                <strong>{{ $districts->lastItem() }}</strong> of
                                                <strong>{{ $districts->total() }}</strong> entries
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="float-end">
                                                <nav aria-label="Page navigation">
                                                    {{ $districts->links('pagination::bootstrap-5') }}
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
            console.log('District Management Page Loaded');

            // Get elements
            const selectAll = document.getElementById('selectAllCurrentPage');
            const deleteBtn = document.getElementById('deleteSelected');
            const checkboxes = document.querySelectorAll('.districtCheckbox');
            const form = document.getElementById('bulkDeleteForm');
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
                    deleteBtn.innerHTML = `<i class="fas fa-trash-alt me-1"></i> Delete Selected (${checkedCount})`;
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

            // 6. Form submission
            if (form) {
                form.addEventListener('submit', function(e) {
                    const checkedCount = getCheckedCount();

                    if (checkedCount === 0) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Selection',
                            text: 'Please select at least one district to delete.',
                            confirmButtonColor: '#4361ee',
                        });
                        return false;
                    }

                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete ${checkedCount} selected districts. This action cannot be undone!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4361ee',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading
                            deleteBtn.disabled = true;
                            deleteBtn.innerHTML =
                                '<i class="fas fa-spinner fa-spin me-1"></i> Deleting...';

                            // Submit form
                            form.submit();
                        }
                    });

                    return false;
                });
            }

            // 7. Export link handling with loading indication
            exportLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    console.log('Export clicked:', this.href);

                    // Optional: Add loading indicator
                    const originalHTML = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Loading...';
                    this.classList.add('disabled');

                    // Reset after 3 seconds (in case download doesn't start)
                    setTimeout(() => {
                        this.innerHTML = originalHTML;
                        this.classList.remove('disabled');
                    }, 3000);
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
