@extends('admin.layout.app')

@section('title', 'Doctors Management')

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Doctors Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Doctors Management</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <!-- Messages -->
    <div class="row mb-3">
        <div class="col-12">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-octagon me-1"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <div id="ajax-message" class="alert d-none" role="alert"></div>
        </div>
    </div>

    <!-- Add/Edit Doctor Form -->
    @if (auth()->user()->role == 'admin')
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0" id="form-title">Add New Doctor</h5>
                <button type="button" class="btn btn-sm btn-secondary" id="reset-form">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </button>
            </div>
            
            <form id="doctor-form">
                @csrf
                <input type="hidden" name="id" id="doctor-id">
                
                <div class="row g-3">
                    <!-- Personal Info -->
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="name" id="name" class="form-control" placeholder=" " required>
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder=" ">
                            <label for="phone_number">Phone Number</label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    
                    <!-- Professional Info -->
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="email" name="email" id="email" class="form-control" placeholder=" " required>
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="speciality" id="speciality" class="form-control" placeholder=" ">
                            <label for="speciality">Speciality</label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    
                    <!-- Category & Client -->
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select name="category_id" id="category_id" class="form-select">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <label for="category_id">Category</label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select name="clinic_id" id="clinic_id" class="form-select">
                                <option value="">Select Clinic</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                            <label for="clinic_id">Clinic</label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    
                    <!-- Location -->
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <!--<select name="country_id" id="country_id" class="form-select" required>-->
                            <!--    <option value="">Select Country</option>-->
                            <!--    @foreach ($countries as $country)-->
                            <!--        <option value="{{ $country->id }}">{{ $country->name }}</option>-->
                            <!--    @endforeach-->
                            <!--</select>-->
                            <select id="country_id" name="country_id" class="form-control">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            <label for="country_id">Country <span class="text-danger">*</span></label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <!--<select name="state_id" id="state_id" class="form-select" disabled required>-->
                            <!--    <option value="">Select State</option>-->
                            <!--</select>-->
                            <select id="state_id" name="state_id" class="form-control">
                                <option value="">Select State</option>
                            </select>
                             <label for="state_id">State <span class="text-danger">*</span></label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <!--<select name="district_id" id="district_id" class="form-select" disabled required>-->
                            <!--    <option value="">Select District</option>-->
                            <!--</select>-->
                            <select id="district_id" name="district_id" class="form-control">
                                    <option value="">Select District</option>
                                </select>
                            <label for="district_id">District <span class="text-danger">*</span></label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <!--<select name="city_id" id="city_id" class="form-select" disabled required>-->
                            <!--    <option value="">Select City</option>-->
                            <!--</select>-->
                            
                            <select id="city_id" name="city_id" class="form-control">
                                <option value="">Select City</option>
                            </select>
                            <label for="city_id">City <span class="text-danger">*</span></label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    
                    <!-- Status & Mode -->
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select name="status" id="status" class="form-select">
                                <option value="">Select Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <label for="status">Status</label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select name="consultation_mode" id="consultation_mode" class="form-select">
                                <option value="">Select Mode</option>
                                <option value="online">Online</option>
                                <option value="face-to-face">Face-to-Face</option>
                            </select>
                            <label for="consultation_mode">Consultation Mode</label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save"></i> Save Doctor
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Import Form -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Import Doctors</h5>
            <form id="import-form" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <div class="form-floating">
                            <input type="file" name="excel_file" id="excel_file" class="form-control" accept=".xlsx,.xls" required>
                            <label for="excel_file">Select Excel File</label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-upload"></i> Import Doctors
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Doctors Table -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Doctors List</h5>
                <div>
                    @if (auth()->user()->role == 'admin')
                    <button type="button" class="btn btn-danger me-2" id="bulk-delete" disabled>
                        <i class="bi bi-trash"></i> Delete Selected
                    </button>
                    @endif
                </div>
            </div>

            <div class="table-responsive">
                <table id="doctors-table" class="table table-hover table-bordered" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            @if (auth()->user()->role == 'admin')
                            <th width="40">
                                <input type="checkbox" id="select-all">
                            </th>
                            @endif
                            <th width="60">#</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Speciality</th>
                            <th>Category</th>
                            <th>Clinic</th>
                            <th>Country</th>
                            <th>State</th>
                            <th>District</th>
                            <th>City</th>
                            <th>Status</th>
                            <th>Mode</th>
                            @if (auth()->user()->role == 'admin')
                            <th width="120">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($doctors as $index => $doctor)
                        <tr data-id="{{ $doctor->id }}">
                            @if (auth()->user()->role == 'admin')
                            <td>
                                <input type="checkbox" class="select-doctor" value="{{ $doctor->id }}">
                            </td>
                            @endif
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $doctor->id }}</td>
                            <td data-field="name">{{ $doctor->name }}</td>
                            <td data-field="phone_number">{{ $doctor->phone_number ?? '-' }}</td>
                            <td data-field="email">{{ $doctor->email ?? '-' }}</td>
                            <td data-field="speciality">{{ $doctor->speciality ?? '-' }}</td>
                            <td data-field="category_id" data-category-id="{{ $doctor->category_id }}">{{ $doctor->category->name ?? 'N/A' }}</td>
                            <td data-field="clinic_id" data-clinic-id="{{ $doctor->clinic_id }}">{{ $doctor->client->name ?? 'N/A' }}</td>
                            <td data-field="country_id" data-country-id="{{ $doctor->country_id }}">{{ $doctor->country->name ?? 'N/A' }}</td>
                            <td data-field="state_id" data-state-id="{{ $doctor->state_id }}">{{ $doctor->state->name ?? 'N/A' }}</td>
                            <td data-field="district_id" data-district-id="{{ $doctor->district_id }}">{{ $doctor->district->name ?? 'N/A' }}</td>
                            <td data-field="city_id" data-city-id="{{ $doctor->city_id }}">{{ $doctor->city->name ?? 'N/A' }}</td>
                            <td data-field="status">
                                <span class="badge bg-{{ $doctor->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($doctor->status ?? 'N/A') }}
                                </span>
                            </td>
                            <td data-field="consultation_mode">
                                <span class="badge bg-info">
                                    {{ ucfirst($doctor->consultation_mode ?? 'N/A') }}
                                </span>
                            </td>
                            @if (auth()->user()->role == 'admin')
                            <td>
                                <button type="button" class="btn btn-sm btn-primary edit-doctor" data-id="{{ $doctor->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-doctor" data-id="{{ $doctor->id }}" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection

@section('styles')
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">
<style>
    .form-floating label::after {
        background: transparent !important;
    }
    .table th {
        white-space: nowrap;
    }
    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }
    .dataTables_wrapper .dt-buttons {
        margin-bottom: 1rem;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .table-responsive {
        border-radius: 0.375rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .is-invalid {
        border-color: #dc3545;
    }
    .invalid-feedback {
        display: block;
    }
    #ajax-message {
        position: relative;
        z-index: 1000;
    }
</style>
@endsection

@section('scripts')
<!-- Add CSRF token meta tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- jQuery and other libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    
    
    
      // Location cascading dropdowns
// Country → State
$('#country_id').on('change', function () {
    
     var country_id = $(this).val();
    console.log("Selected Country ID:", country_id); // Debug log
    var country_id = $(this).val();
    $('#state_id').empty().append('<option value="">Select State</option>');
    $('#district_id').empty().append('<option value="">Select District</option>');
    $('#city_id').empty().append('<option value="">Select City</option>');

    if (country_id) {
        $.ajax({
            url: "/doctor/getStatesByCountry/" + country_id,
            type: "GET",
            success: function (response) {
                if (response.success && response.data.length > 0) {
                    $.each(response.data, function (index, state) {
                        $('#state_id').append(`<option value="${state.id}">${state.name}</option>`);
                    });
                } else {
                    $('#state_id').append('<option value="">No State Found</option>');
                }
            }
        });
    }
});

// State → District
$('#state_id').on('change', function () {
    var state_id = $(this).val();

    $('#district_id').empty().append('<option value="">Select District</option>');
    $('#city_id').empty().append('<option value="">Select City</option>');

    if (state_id) {
        $.ajax({
            url: "{{ route('doctor.getDistrictsByState', ':id') }}".replace(':id', state_id),
            type: "GET",
            success: function (response) {
                if (response.success) {
                    $.each(response.data, function (index, district) {
                        $('#district_id').append(`<option value="${district.id}">${district.name}</option>`);
                    });
                }
            }
        });
    }
});

// District → City
$('#district_id').on('change', function () {
    var district_id = $(this).val();

    $('#city_id').empty().append('<option value="">Select City</option>');

    if (district_id) {
        $.ajax({
            url: "{{ route('doctor.getCitiesByDistrict', ':id') }}".replace(':id', district_id),
            type: "GET",
            success: function (response) {
                if (response.success) {
                    $.each(response.data, function (index, city) {
                        $('#city_id').append(`<option value="${city.id}">${city.name}</option>`);
                    });
                }
            }
        });
    }
});
    
    // Initialize DataTable with export buttons
    const table = $('#doctors-table').DataTable({
        dom: '<"top"Bf>rt<"bottom"lip><"clear">',
        buttons: [
            {
                extend: 'excel',
                className: 'btn btn-sm btn-outline-secondary',
                text: '<i class="bi bi-file-excel"></i> Excel'
            },
            {
                extend: 'pdf',
                className: 'btn btn-sm btn-outline-danger',
                text: '<i class="bi bi-file-pdf"></i> PDF'
            },
            {
                extend: 'print',
                className: 'btn btn-sm btn-outline-primary',
                text: '<i class="bi bi-printer"></i> Print'
            }
        ],
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [0, @if(auth()->user()->role == 'admin') -1 @endif] }
        ],
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50]
    });

    // AJAX setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

  

    // Form reset
    $('#reset-form').click(function() {
        $('#doctor-form')[0].reset();
        $('#doctor-id').val('');
        $('#form-title').text('Add New Doctor');
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('#state_id, #district_id, #city_id').html('<option value="">Select...</option>').prop('disabled', true);
        $('#country_id').val('').trigger('change');
    });

    // Edit doctor
    $(document).on('click', '.edit-doctor', function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: '/doctor/edit/' + id,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const doctor = response.data;
                    
                    $('#form-title').text('Edit Doctor');
                    $('#doctor-id').val(doctor.id);
                    $('#name').val(doctor.name || '');
                    $('#phone_number').val(doctor.phone_number || '');
                    $('#email').val(doctor.email || '');
                    $('#speciality').val(doctor.speciality || '');
                    $('#status').val(doctor.status || '');
                    $('#consultation_mode').val(doctor.consultation_mode || '');
                    $('#category_id').val(doctor.category_id || '');
                    $('#clinic_id').val(doctor.clinic_id || '');
                    
                    // Handle location dropdowns with proper cascade
                    if (doctor.country_id) {
                        $('#country_id').val(doctor.country_id).trigger('change');
                        setTimeout(() => {
                            if (doctor.state_id) {
                                $('#state_id').val(doctor.state_id).trigger('change');
                                setTimeout(() => {
                                    if (doctor.district_id) {
                                        $('#district_id').val(doctor.district_id).trigger('change');
                                        setTimeout(() => {
                                            if (doctor.city_id) {
                                                $('#city_id').val(doctor.city_id);
                                            }
                                        }, 500);
                                    }
                                }, 500);
                            }
                        }, 500);
                    }
                    
                    $('.form-control, .form-select').removeClass('is-invalid');
                    $('.invalid-feedback').text('');
                    $('html, body').animate({
                        scrollTop: $('#doctor-form').offset().top - 100
                    }, 500);
                } else {
                    showAlert('danger', response.message || 'Failed to load doctor data');
                }
            },
            error: function(xhr, status, error) {
                showAlert('danger', 'Failed to load doctor data');
            }
        });
    });

    // Form submission
    $('#doctor-form').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        
        $.ajax({
            url: '/doctor/store-or-update',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    $('#doctor-form')[0].reset();
                    $('#doctor-id').val('');
                    $('#form-title').text('Add New Doctor');
                    $('#state_id, #district_id, #city_id').html('<option value="">Select...</option>').prop('disabled', true);
                    $('#country_id').val('').trigger('change');
                    
                    const doctor = response.doctor;
                    const index = table.data().length;
                    const rowData = [
                        @if(auth()->user()->role == 'admin') `<input type="checkbox" class="select-doctor" value="${doctor.id}">` @endif,
                        index + 1,
                        doctor.id,
                        doctor.name,
                        doctor.phone_number || '-',
                        doctor.email,
                        doctor.speciality || '-',
                        doctor.category ? doctor.category.name : 'N/A',
                        doctor.client ? doctor.client.name : 'N/A',
                        doctor.country ? doctor.country.name : 'N/A',
                        doctor.state ? doctor.state.name : 'N/A',
                        doctor.district ? doctor.district.name : 'N/A',
                        doctor.city ? doctor.city.name : 'N/A',
                        `<span class="badge bg-${doctor.status == 'active' ? 'success' : 'secondary'}">${doctor.status ? doctor.status.charAt(0).toUpperCase() + doctor.status.slice(1) : 'N/A'}</span>`,
                        `<span class="badge bg-info">${doctor.consultation_mode ? doctor.consultation_mode.charAt(0).toUpperCase() + doctor.consultation_mode.slice(1) : 'N/A'}</span>`,
                        @if(auth()->user()->role == 'admin')
                        `<button type="button" class="btn btn-sm btn-primary edit-doctor" data-id="${doctor.id}" title="Edit"><i class="bi bi-pencil"></i></button>` +
                        `<button type="button" class="btn btn-sm btn-danger delete-doctor" data-id="${doctor.id}" title="Delete"><i class="bi bi-trash"></i></button>`
                        @endif
                    ];
                    
                    if ($('#doctor-id').val()) {
                        const row = table.row($(`tr[data-id="${doctor.id}"]`));
                        if (row.length) {
                            row.data(rowData).draw();
                        }
                    } else {
                        table.row.add(rowData).draw();
                    }
                    $('tr[data-id="' + doctor.id + '"]').attr('data-id', doctor.id);
                } else {
                    showAlert('danger', response.message || 'Failed to save doctor');
                }
            },
            error: function(xhr, status, error) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    $('.form-control, .form-select').removeClass('is-invalid');
                    $('.invalid-feedback').text('');
                    $.each(xhr.responseJSON.errors, function(field, errors) {
                        $(`#${field}`).addClass('is-invalid');
                        $(`#${field}`).siblings('.invalid-feedback').text(errors[0]);
                    });
                }
                const message = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred while saving.';
                showAlert('danger', message);
            }
        });
    });

    // Delete doctor
    $(document).on('click', '.delete-doctor', function() {
        const id = $(this).data('id');
        
        if (confirm('Are you sure you want to delete this doctor?')) {
            $.ajax({
                url: '/doctor/destroy/' + id,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        table.row($(`tr[data-id="${id}"]`)).remove().draw();
                    } else {
                        showAlert('danger', response.message || 'Failed to delete doctor');
                    }
                },
                error: function(xhr, status, error) {
                    const message = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to delete doctor.';
                    showAlert('danger', message);
                }
            });
        }
    });

    // Import form submission
    $('#import-form').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: '/doctor/import',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    $('#import-form')[0].reset();
                    
                    // Reload the page to show new data
                    location.reload();
                } else {
                    showAlert('danger', response.message || 'Import failed');
                }
            },
            error: function(xhr, status, error) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    $('.form-control').removeClass('is-invalid');
                    $('.invalid-feedback').text('');
                    $.each(xhr.responseJSON.errors, function(field, errors) {
                        $(`#${field}`).addClass('is-invalid');
                        $(`#${field}`).siblings('.invalid-feedback').text(errors[0]);
                    });
                }
                const message = xhr.responseJSON ? xhr.responseJSON.message : 'Import failed.';
                showAlert('danger', message);
            }
        });
    });

    // Bulk delete functionality
    $('#select-all').change(function() {
        $('.select-doctor').prop('checked', this.checked);
        toggleBulkDeleteButton();
    });

    $(document).on('change', '.select-doctor', function() {
        toggleBulkDeleteButton();
        $('#select-all').prop('checked', 
            $('.select-doctor:checked').length === $('.select-doctor').length
        );
    });

    $('#bulk-delete').click(function() {
        const selectedIds = $('.select-doctor:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            showAlert('warning', 'Please select at least one doctor to delete.');
            return;
        }

        if (confirm(`Are you sure you want to delete ${selectedIds.length} selected doctor(s)?`)) {
            let deletedCount = 0;
            const totalCount = selectedIds.length;
            
            selectedIds.forEach(id => {
                $.ajax({
                    url: '/doctor/destroy/' + id,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            deletedCount++;
                            table.row($(`tr[data-id="${id}"]`)).remove();
                            
                            if (deletedCount === totalCount) {
                                table.draw();
                                showAlert('success', `${totalCount} doctor(s) deleted successfully.`);
                                $('#select-all').prop('checked', false);
                                toggleBulkDeleteButton();
                            }
                        } else {
                            showAlert('danger', `Failed to delete doctor with ID: ${id}`);
                        }
                    },
                    error: function(xhr, status, error) {
                        showAlert('danger', `Failed to delete doctor with ID: ${id}`);
                    }
                });
            });
        }
    });

    // Helper functions
    function toggleBulkDeleteButton() {
        $('#bulk-delete').prop('disabled', $('.select-doctor:checked').length === 0);
    }

    function showAlert(type, message) {
        const alert = $('#ajax-message');
        alert.removeClass('d-none alert-success alert-danger alert-warning alert-info')
             .addClass(`alert-${type}`)
             .html(`<i class="bi ${type === 'success' ? 'bi-check-circle' : type === 'danger' ? 'bi-exclamation-octagon' : 'bi-exclamation-triangle'} me-2"></i>${message}`);
        
        setTimeout(() => {
            alert.addClass('d-none');
        }, 5000);
    }
});
</script>
@endsection