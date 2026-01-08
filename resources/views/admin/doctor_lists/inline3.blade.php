{{-- resources/views/admin/doctor_lists/inline3.blade.php --}}
@extends('admin.layout.app')

@section('title', 'Manage Doctors')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <main id="main" class="main bg-app">
        <div class="container-fluid mt-3">
            <div class="pagetitle mb-3">
                <h1 class="page-heading">Manage Doctors</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Doctors</li>
                    </ol>
                </nav>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- TABS --}}
            <ul class="nav nav-tabs" id="doctorTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="table-tab" data-bs-toggle="tab" data-bs-target="#tablePane"
                        type="button" role="tab">
                        <i class="bi bi-table me-1"></i> Doctors List
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="form-tab" data-bs-toggle="tab" data-bs-target="#formPane" type="button"
                        role="tab">
                        <i class="bi bi-person-plus me-1"></i> Add Doctor
                    </button>
                </li>
            </ul>

            <div class="tab-content mt-3" id="doctorTabsContent">

                {{-- TAB 1: TABLE --}}
                <div class="tab-pane fade show active" id="tablePane" role="tabpanel" aria-labelledby="table-tab">
                    <div class="card card-table">
                        <div class="card-body p-2">
                            <div class="table-responsive" style="max-height:520px; overflow:auto;">
                                <table id="doctorsTable" class="table table-modern align-middle w-100 table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width:44px"><input type="checkbox" id="selectAll"
                                                    aria-label="Select all"></th>
                                            <th style="width:70px">ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Alt Phone</th>
                                            <th>Speciality</th>
                                            <th>Reg No</th>
                                            <th>Council</th>
                                            <th>Pincode</th>
                                            <th>Website</th>
                                            <th>Whatsapp</th>
                                            <th>Facebook</th>
                                            <th>Instagram</th>
                                            <th>Address</th>
                                            <th>Country</th>
                                            <th>State</th>
                                            <th>District</th>
                                            <th>City</th>
                                            <th>Category</th>
                                            <th>Clinic</th>
                                            <th>Status</th>
                                            <th>Consultation</th>
                                            <th style="width:150px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- STICKY ACTION BAR --}}
                    <div class="sticky-actions shadow-sm">
                        <div class="container-fluid d-flex justify-content-between align-items-center">
                            <button id="bulkDeleteBtn" class="btn btn-square-lg btn-outline-danger" disabled>
                                <i class="bi bi-trash3 me-2"></i> Bulk Delete
                            </button>
                            <button id="saveAllBtn" class="btn btn-brand">
                                <i class="bi bi-check2-square me-2"></i> Save All
                            </button>
                        </div>
                    </div>
                </div>

                {{-- TAB 2: FORM --}}
                <div class="tab-pane fade" id="formPane" role="tabpanel" aria-labelledby="form-tab">
                    {{-- CREATE FORM --}}
                    <div class="card card-lite mb-4">
                        <div class="card-body py-3 px-3 px-md-4">
                            <form id="doctorCreateForm" class="row g-3" action="{{ route('doctor_inline.store') }}"
                                method="POST" enctype="multipart/form-data" novalidate>
                                @csrf

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_name">Name <span
                                            class="text-danger">*</span></label>
                                    <input id="cf_name" type="text" name="name"
                                        class="form-control form-control-compact" required>
                                    <div class="invalid-feedback">Please enter doctor's name</div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_email">Email <span
                                            class="text-danger">*</span></label>
                                    <input id="cf_email" type="email" name="email"
                                        class="form-control form-control-compact" required>
                                    <div class="invalid-feedback">Please enter a valid email</div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_phone">Phone <span
                                            class="text-danger">*</span></label>
                                    <input id="cf_phone" type="text" name="phone_number"
                                        class="form-control form-control-compact" required>
                                    <div class="invalid-feedback">Please enter phone number</div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_phone2">Alternate Phone</label>
                                    <input id="cf_phone2" type="text" name="phone_number_2"
                                        class="form-control form-control-compact">
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_speciality">Speciality <span
                                            class="text-danger">*</span></label>
                                    <input id="cf_speciality" type="text" name="speciality"
                                        class="form-control form-control-compact" required>
                                    <div class="invalid-feedback">Please enter speciality</div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_degree">Degree</label>
                                    <input id="cf_degree" type="text" name="degree"
                                        class="form-control form-control-compact">
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_regno">Registration No.</label>
                                    <input id="cf_regno" type="text" name="registration_no"
                                        class="form-control form-control-compact">
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_council">Council</label>
                                    <input id="cf_council" type="text" name="council"
                                        class="form-control form-control-compact">
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_pincode">Pincode</label>
                                    <input id="cf_pincode" type="text" name="pincode"
                                        class="form-control form-control-compact">
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_website">Website</label>
                                    <input id="cf_website" type="url" name="website"
                                        class="form-control form-control-compact">
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_whatsapp">Whatsapp</label>
                                    <input id="cf_whatsapp" type="text" name="whatsapp"
                                        class="form-control form-control-compact">
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_facebook">Facebook</label>
                                    <input id="cf_facebook" type="text" name="facebook"
                                        class="form-control form-control-compact">
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_instagram">Instagram</label>
                                    <input id="cf_instagram" type="text" name="instagram"
                                        class="form-control form-control-compact">
                                </div>

                                {{-- New Fields --}}
                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_experience">Experience
                                        (Years)</label>
                                    <input id="cf_experience" type="number" name="experience_years"
                                        class="form-control form-control-compact" min="0" max="100">
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_languages">Languages</label>
                                    <input id="cf_languages" type="text" name="languages"
                                        class="form-control form-control-compact" placeholder="English, Hindi, Bengali">
                                </div>

                                <div class="col-12">
                                    <label class="form-label form-label-xs mb-1" for="cf_address">Address</label>
                                    <textarea id="cf_address" name="address" class="form-control form-control-compact" rows="2"></textarea>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_country">Country <span
                                            class="text-danger">*</span></label>
                                    <select name="country_id" id="cf_country" class="form-select form-select-compact"
                                        required>
                                        <option value="">Select Country</option>
                                        @foreach ($countries as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select country</div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_state">State <span
                                            class="text-danger">*</span></label>
                                    <select name="state_id" id="cf_state" class="form-select form-select-compact"
                                        required>
                                        <option value="">Select State</option>
                                        @foreach ($states as $s)
                                            <option value="{{ $s->id }}" data-country="{{ $s->country_id }}">
                                                {{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select state</div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_district">District</label>
                                    <select name="district_id" id="cf_district" class="form-select form-select-compact">
                                        <option value="">Select District</option>
                                        @foreach ($districts as $d)
                                            <option value="{{ $d->id }}" data-state="{{ $d->state_id }}">
                                                {{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_city">City <span
                                            class="text-danger">*</span></label>
                                    <select name="city_id" id="cf_city" class="form-select form-select-compact"
                                        required>
                                        <option value="">Select City</option>
                                        @foreach ($cities as $ci)
                                            <option value="{{ $ci->id }}" data-district="{{ $ci->district_id }}">
                                                {{ $ci->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select city</div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_category">Category <span
                                            class="text-danger">*</span></label>
                                    <select name="category_id" id="cf_category" class="form-select form-select-compact"
                                        required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select category</div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_photo">Photo</label>
                                    <input id="cf_photo" type="file" name="photo" accept="image/*"
                                        class="form-control form-control-compact">
                                    <div class="form-text">Max 4MB, JPG, PNG, GIF</div>
                                </div>

                                {{-- Hidden clinic fields --}}
                                <input type="hidden" name="clinic_id" id="cf_clinic_hidden" value="">
                                <input type="hidden" name="clinic_name" id="cf_clinic_name_hidden" value="">

                                {{-- Clinics & Schedules --}}
                                <div class="col-12">
                                    <hr>
                                    <h6 class="mb-3">Clinics & Schedules</h6>
                                    <div id="schedulesContainer" class="mb-3"></div>
                                    <button type="button" id="addScheduleBtn"
                                        class="btn btn-outline-secondary btn-compact mb-2">
                                        <i class="bi bi-plus-lg me-1"></i> Add Clinic / Schedule
                                    </button>
                                    <input type="hidden" name="schedules_json" id="schedules_json" value="[]">
                                    <small class="text-muted d-block mt-2">Pick clinic(s) inside the schedule rows. The
                                        first schedule's clinic will be used as the doctor's primary clinic
                                        automatically.</small>
                                </div>

                                <template id="scheduleRowTpl">
                                    <div class="schedule-row card p-2 mb-2" data-tempid="">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-md-4">
                                                <label class="form-label form-label-xs mb-1">Clinic (select or
                                                    enter)</label>
                                                <div class="d-flex gap-2">
                                                    <select
                                                        class="form-select form-select-compact flex-grow-1 clinic_select">
                                                        <option value="">Select Clinic</option>
                                                        @foreach ($clinics as $cl)
                                                            <option value="{{ $cl->id }}"
                                                                data-category="{{ $cl->category_id }}">
                                                                {{ $cl->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text"
                                                        class="form-control form-control-compact clinic_text"
                                                        placeholder="Or type clinic name">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label form-label-xs mb-1">Days</label>
                                                <select class="form-select form-select-compact days_select" multiple
                                                    style="height: 80px;">
                                                    <option value="monday">Monday</option>
                                                    <option value="tuesday">Tuesday</option>
                                                    <option value="wednesday">Wednesday</option>
                                                    <option value="thursday">Thursday</option>
                                                    <option value="friday">Friday</option>
                                                    <option value="saturday">Saturday</option>
                                                    <option value="sunday">Sunday</option>
                                                </select>
                                                <small class="text-muted">Ctrl/Cmd+click to multi-select</small>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label form-label-xs mb-1">Start Time</label>
                                                <input type="time"
                                                    class="form-control form-control-compact start_time">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label form-label-xs mb-1">End Time</label>
                                                <input type="time" class="form-control form-control-compact end_time">
                                            </div>

                                            <div class="col-md-11 mt-2">
                                                <label class="form-label form-label-xs mb-1">Note (optional)</label>
                                                <input type="text" class="form-control form-control-compact alt_text"
                                                    placeholder="e.g. by appointment only">
                                            </div>

                                            <div class="col-md-1 d-flex align-items-start mt-2">
                                                <button type="button"
                                                    class="btn btn-outline-danger btn-square removeScheduleBtn"
                                                    title="Remove">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_status">Status</label>
                                    <select id="cf_status" name="status" class="form-select form-select-compact">
                                        <option value="active" selected>Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label form-label-xs mb-1" for="cf_consultation">Consultation Mode
                                        <span class="text-danger">*</span></label>
                                    <select id="cf_consultation" name="consultation_mode"
                                        class="form-select form-select-compact" required>
                                        <option value="online">Online</option>
                                        <option value="face-to-face" selected>Face to Face</option>
                                        <option value="both">Both</option>
                                        <option value="offline">Offline</option>
                                    </select>
                                    <div class="invalid-feedback">Please select consultation mode</div>
                                </div>

                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-brand" id="createSubmitBtn">
                                        <span class="btn-spinner me-2 d-none" aria-hidden="true"></span>
                                        <i class="bi bi-plus-square me-1"></i> Save Doctor
                                    </button>
                                </div>

                                <div class="col-12 d-flex flex-column flex-md-row gap-2 justify-content-between mt-4">
                                    <div class="btn-group">
                                        <a href="{{ route('doctor_inline.export.sample') }}"
                                            class="btn btn-outline-secondary btn-compact" data-bs-toggle="tooltip"
                                            title="Download Sample CSV">
                                            <i class="bi bi-filetype-csv me-1"></i> Sample
                                        </a>
                                        <label class="btn btn-outline-secondary btn-compact mb-0" data-bs-toggle="tooltip"
                                            title="Import CSV/XLSX">
                                            <i class="bi bi-upload me-1"></i> Import
                                            <input id="import_file" type="file" class="d-none"
                                                accept=".csv,.xls,.xlsx,.txt">
                                        </label>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ route('doctor_inline.export.csv') }}"
                                            class="btn btn-outline-secondary btn-compact" data-bs-toggle="tooltip"
                                            title="Export CSV"><i class="bi bi-filetype-csv me-1"></i> CSV</a>
                                        <a href="{{ route('doctor_inline.export.excel') }}"
                                            class="btn btn-outline-secondary btn-compact" data-bs-toggle="tooltip"
                                            title="Export Excel"><i class="bi bi-filetype-xlsx me-1"></i> Excel</a>
                                        <a href="{{ route('doctor_inline.export.pdf') }}"
                                            class="btn btn-outline-secondary btn-compact" data-bs-toggle="tooltip"
                                            title="Export PDF"><i class="bi bi-filetype-pdf me-1"></i> PDF</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- TOAST + CONFIRM --}}
    <div id="toastStack" class="position-fixed top-0 end-0 p-3" style="z-index:1080"></div>
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true" aria-labelledby="confirmTitle">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0">
                    <h6 class="modal-title fw-semibold" id="confirmTitle">Confirm</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0" id="confirmMessage"></div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        id="confirmCancel">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmOk">Yes</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Assets (CSS/JS) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" />
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

    <style>
        .bg-app {
            background: #f8f9fb;
            min-height: 100%;
            padding-bottom: 84px;
        }

        .page-heading {
            font-weight: 700;
            color: #222;
        }

        .card-lite {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(17, 24, 39, 0.04);
        }

        .card-table {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(17, 24, 39, 0.04);
        }

        .table-modern thead tr {
            background: #f1f3f5;
            color: #333;
            font-weight: 700;
            position: sticky;
            top: 0;
            z-index: 5
        }

        .table-modern th,
        .table-modern td {
            padding: .6rem .6rem;
            vertical-align: middle;
        }

        .form-control-compact,
        .form-select-compact {
            height: 36px;
            padding: .25rem .5rem;
            border: 1px solid #ddd !important;
            border-radius: 8px;
            font-size: .9rem;
            box-shadow: none;
        }

        .form-label-xs {
            font-size: .75rem;
            color: #6c757d;
        }

        .btn-compact {
            padding: .35rem .6rem;
            font-size: .85rem;
            border-radius: 8px;
        }

        .btn-brand {
            background: #16a34a;
            border: 1px solid #16a34a;
            color: #fff;
            padding: .5rem .9rem;
            border-radius: 10px;
        }

        .btn-brand:hover {
            background: #138a3f;
            border-color: #138a3f;
        }

        .btn-square {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border-radius: 6px;
        }

        .btn-square-lg {
            height: 40px;
            padding: .35rem .9rem;
            border-radius: 10px;
        }

        .sticky-actions {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            background: #fff;
            border-top: 1px solid #e9ecef;
            padding: .6rem 1rem;
            z-index: 1030;
        }

        .edited {
            box-shadow: inset 0 0 0 9999px rgba(255, 244, 186, .5);
        }

        .btn-spinner {
            width: 1rem;
            height: 1rem;
            border: .15em solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            display: inline-block;
            animation: spin .75s linear infinite;
            vertical-align: -2px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .text-danger {
            color: #dc3545;
        }

        .form-text {
            font-size: .75rem;
            color: #6c757d;
        }

        /* small responsive tweaks */
        @media (max-width:900px) {
            .table-modern thead {
                font-size: .8rem
            }
        }
    </style>

    <script>
        (function() {
            const $ = window.jQuery;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            // Initialize tooltips
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

            const htmlEsc = (v) => String(v ?? '')
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;').replace(/'/g, '&#39;');

            function showToast(message, {
                type = 'success',
                title = '',
                delay = 4000
            } = {}) {
                const id = 't' + Date.now() + Math.random().toString(16).slice(2);
                const header = title ?
                    `<div class="toast-header border-0"><strong class="me-auto">${htmlEsc(title)}</strong><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button></div>` :
                    '';
                const bodyClass = title ? '' : (type === 'success' ? 'bg-success text-white' : type === 'danger' ?
                    'bg-danger text-white' : 'bg-info text-dark');
                const html =
                    `<div id="${id}" class="toast shadow-sm mb-2 overflow-hidden" data-bs-delay="${delay}" role="status" aria-live="polite">${header}<div class="toast-body ${bodyClass}">${htmlEsc(message)}</div></div>`;
                const stack = document.getElementById('toastStack');
                stack.insertAdjacentHTML('beforeend', html);
                new bootstrap.Toast(document.getElementById(id)).show();
            }

            const notify = {
                ok: (m, t = 'Success') => showToast(m, {
                    type: 'success',
                    title: t
                }),
                err: (m, t = 'Error') => showToast(m, {
                    type: 'danger',
                    title: t
                }),
                info: (m, t = 'Info') => showToast(m, {
                    type: 'info',
                    title: t
                })
            };

            function confirmAction(message, {
                title = 'Confirm',
                okText = 'Yes',
                cancelText = 'Cancel',
                okClass = 'btn-danger'
            } = {}) {
                return new Promise(resolve => {
                    const m = document.getElementById('confirmModal');
                    document.getElementById('confirmTitle').textContent = title;
                    document.getElementById('confirmMessage').textContent = message;
                    const ok = document.getElementById('confirmOk'),
                        cc = document.getElementById('confirmCancel');
                    ok.textContent = okText;
                    cc.textContent = cancelText;
                    ok.className = 'btn ' + okClass;
                    const modal = new bootstrap.Modal(m, {
                        backdrop: 'static'
                    });
                    const cleanup = () => {
                        ok.onclick = null;
                        cc.onclick = null;
                        m.removeEventListener('hidden.bs.modal', onHide);
                    };
                    const onHide = () => {
                        cleanup();
                        resolve(false);
                    };
                    m.addEventListener('hidden.bs.modal', onHide);
                    ok.onclick = () => {
                        cleanup();
                        modal.hide();
                        resolve(true);
                    };
                    cc.onclick = () => {
                        cleanup();
                        modal.hide();
                        resolve(false);
                    };
                    modal.show();
                });
            }

            const COUNTRIES = @json($countries);
            const STATES = @json($states);
            const DISTRICTS = @json($districts);
            const CITIES = @json($cities);
            const CATEGORIES = @json($categories);
            const CLINICS = @json($clinics);

            function buildOptions(list, selectedId, {
                placeholder = 'Select'
            } = {}, filter = () => true) {
                let html = `<option value="">${htmlEsc(placeholder)}</option>`;
                list.filter(filter).forEach(it => {
                    const sel = (Number(selectedId) === Number(it.id)) ? ' selected' : '';
                    html += `<option value="${it.id}"${sel}>${htmlEsc(it.name)}</option>`;
                });
                return html;
            }

            // dependent selects for address/category filtering (client-side fallback)
            $('#cf_country').on('change', function() {
                const id = $(this).val();
                $('#cf_state').html(buildOptions(STATES, null, {
                    placeholder: 'Select State'
                }, s => s.country_id == id)).val('');
                $('#cf_district').html('<option value="">Select District</option>').val('');
                $('#cf_city').html('<option value="">Select City</option>').val('');
            });

            $('#cf_state').on('change', function() {
                const id = $(this).val();
                $('#cf_district').html(buildOptions(DISTRICTS, null, {
                    placeholder: 'Select District'
                }, d => d.state_id == id)).val('');
                $('#cf_city').html('<option value="">Select City</option>').val('');
            });

            $('#cf_district').on('change', function() {
                const id = $(this).val();
                $('#cf_city').html(buildOptions(CITIES, null, {
                    placeholder: 'Select City'
                }, c => c.district_id == id)).val('');
            });

            // SCHEDULES UI
            function buildScheduleRow() {
                const tpl = document.getElementById('scheduleRowTpl').content.cloneNode(true);
                const $row = $(tpl).find('.schedule-row');
                $row.attr('data-tempid', Date.now() + '-' + Math.random().toString(16).slice(2));

                // clinic_text tries to resolve clinic select
                $row.find('.clinic_text').on('input', function() {
                    const text = $(this).val().trim().toLowerCase();
                    if (!text) return;
                    const $sel = $(this).closest('.schedule-row').find('.clinic_select');
                    let found = CLINICS.find(c => String(c.name).toLowerCase() === text);
                    if (!found) found = CLINICS.find(c => String(c.name).toLowerCase().includes(text));
                    if (found) $sel.val(found.id);
                });

                return $row;
            }

            function ensureInitialSchedule() {
                if ($('#schedulesContainer .schedule-row').length === 0) {
                    $('#schedulesContainer').append(buildScheduleRow());
                }
            }

            $(document).ready(() => ensureInitialSchedule());

            $('#addScheduleBtn').on('click', function() {
                $('#schedulesContainer').append(buildScheduleRow());
            });

            $(document).on('click', '.removeScheduleBtn', function() {
                $(this).closest('.schedule-row').remove();
            });

            // normalize time to HH:mm (24h)
            function to24Hour(timeStr) {
                if (!timeStr) return null;
                if (/^\d{1,2}:\d{2}$/.test(timeStr)) {
                    const parts = timeStr.split(':').map(p => p.padStart(2, '0'));
                    return parts[0] + ':' + parts[1];
                }
                const m = timeStr.match(/(\d{1,2}):(\d{2})\s*([ap]m)?/i);
                if (m) {
                    let hh = parseInt(m[1], 10);
                    const mm = m[2];
                    const ampm = (m[3] || '').toLowerCase();
                    if (ampm === 'pm' && hh < 12) hh += 12;
                    if (ampm === 'am' && hh === 12) hh = 0;
                    return String(hh).padStart(2, '0') + ':' + mm;
                }
                try {
                    const d = new Date('1970-01-01T' + timeStr);
                    if (!isNaN(d.getTime())) {
                        const hh = String(d.getHours()).padStart(2, '0');
                        const mm = String(d.getMinutes()).padStart(2, '0');
                        return hh + ':' + mm;
                    }
                } catch (e) {}
                return null;
            }

            function readScheduleRow($row) {
                const clinic_id = $row.find('.clinic_select').val() || null;
                const clinic_name = $row.find('.clinic_text').val().trim() || null;
                const days = $row.find('.days_select').val() || [];
                const start_time_raw = $row.find('.start_time').val() || null;
                const end_time_raw = $row.find('.end_time').val() || null;
                const alternative_text = $row.find('.alt_text').val() || null;

                const empty = !clinic_id && !clinic_name && (!days || days.length === 0) && !start_time_raw && !
                    end_time_raw && !alternative_text;
                if (empty) return null;

                const normalizedDays = (Array.isArray(days) ? days : []).map(d => {
                    const s = String(d).trim().toLowerCase();
                    const map = {
                        mon: 'monday',
                        monday: 'monday',
                        tue: 'tuesday',
                        tuesday: 'tuesday',
                        wed: 'wednesday',
                        wednesday: 'wednesday',
                        thu: 'thursday',
                        thursday: 'thursday',
                        fri: 'friday',
                        friday: 'friday',
                        sat: 'saturday',
                        saturday: 'saturday',
                        sun: 'sunday',
                        sunday: 'sunday'
                    };
                    return map[s] || s;
                }).filter(Boolean);

                const start_time = to24Hour(start_time_raw);
                const end_time = to24Hour(end_time_raw);

                return {
                    clinic_id: clinic_id ? Number(clinic_id) : null,
                    clinic_name: clinic_name,
                    days: normalizedDays,
                    start_time: start_time,
                    end_time: end_time,
                    alternative_text: alternative_text
                };
            }

            // SERIALIZE + SUBMIT (CREATE)
            $('#doctorCreateForm').on('submit', function(e) {
                e.preventDefault();

                // Basic form validation
                if (!this.checkValidity()) {
                    e.stopPropagation();
                    this.classList.add('was-validated');
                    notify.err('Please fill all required fields');
                    return;
                }

                // serialize schedules
                const schedules = [];
                $('#schedulesContainer .schedule-row').each(function() {
                    const obj = readScheduleRow($(this));
                    if (obj) schedules.push(obj);
                });
                $('#schedules_json').val(JSON.stringify(schedules));

                // set top-level clinic hidden field from first schedule if not provided
                const topClinicId = $('#cf_clinic_hidden').val();
                if ((!topClinicId || topClinicId === '') && schedules.length > 0) {
                    const first = schedules[0];
                    if (first.clinic_id) {
                        $('#cf_clinic_hidden').val(first.clinic_id);
                        $('#cf_clinic_name_hidden').val('');
                    } else if (first.clinic_name) {
                        let found = CLINICS.find(c => String(c.name).toLowerCase() === String(first.clinic_name)
                            .toLowerCase());
                        if (!found) found = CLINICS.find(c => String(c.name).toLowerCase().includes(String(first
                            .clinic_name).toLowerCase()));
                        if (found) {
                            $('#cf_clinic_hidden').val(found.id);
                            $('#cf_clinic_name_hidden').val('');
                        } else {
                            $('#cf_clinic_hidden').val('');
                            $('#cf_clinic_name_hidden').val(first.clinic_name);
                        }
                    }
                }

                const btn = $('#createSubmitBtn');
                const spinner = btn.find('.btn-spinner');
                btn.prop('disabled', true);
                spinner.removeClass('d-none');
                const fd = new FormData(this);

                // For debugging
                console.debug('Submitting schedules:', schedules);

                $.ajax({
                        url: this.action,
                        method: 'POST',
                        data: fd,
                        processData: false,
                        contentType: false,
                        dataType: 'json'
                    })
                    .done(res => {
                        notify.ok(res.message || 'Doctor created successfully');
                        this.reset();
                        this.classList.remove('was-validated');
                        $('#schedulesContainer').empty();
                        $('#schedules_json').val('[]');
                        $('#cf_clinic_hidden').val('');
                        $('#cf_clinic_name_hidden').val('');
                        ensureInitialSchedule();
                        table.ajax.reload(null, false);

                        // Switch to table tab after successful creation
                        const tableTab = new bootstrap.Tab(document.getElementById('table-tab'));
                        tableTab.show();
                    })
                    .fail(xhr => {
                        console.error('Create failed', xhr);
                        let msg = 'Create failed';
                        if (xhr.responseJSON) {
                            if (xhr.status === 422 && xhr.responseJSON.errors) {
                                const errs = xhr.responseJSON.errors;
                                const flat = [];
                                Object.keys(errs).forEach(k => {
                                    (errs[k] || []).forEach(m => flat.push(m));
                                });
                                msg = flat.slice(0, 5).join('; ');
                                notify.err(msg);
                                console.groupCollapsed('Validation errors');
                                console.table(errs);
                                console.groupEnd();
                            } else if (xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message + (xhr.responseJSON.error ? (': ' + xhr
                                    .responseJSON.error) : '');
                                notify.err(msg);
                            } else {
                                notify.err('Server error');
                            }
                        } else {
                            notify.err('Server error');
                        }
                    })
                    .always(() => {
                        btn.prop('disabled', false);
                        spinner.addClass('d-none');
                    });
            });

            // DATATABLE + inline editing
            const selectedIds = new Set();
            const editedIds = new Set();

            let table = $('#doctorsTable').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('doctor_inline.list') }}",
                    type: "GET",
                    error: function(xhr, error, thrown) {
                        console.error('DataTables error:', error, thrown);
                        notify.err('Failed to load doctors data');
                    }
                },
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                order: [
                    [1, 'desc']
                ],
                createdRow: (row, data) => {
                    $(row).attr('id', 'row-' + data.id).attr('data-id', data.id);
                },
                columns: [{
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: (_, _2, row) => `
          <input type="checkbox" class="rowCheck" data-id="${row.id}" ${selectedIds.has(row.id)?'checked':''} aria-label="Select row ${row.id}">
        `
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },

                    // Basic fields
                    {
                        data: 'name',
                        name: 'name',
                        render: (d) =>
                            `<input class="form-control form-control-sm editable" data-field="name" value="${htmlEsc(d??'')}">`
                    },
                    {
                        data: 'email',
                        name: 'email',
                        render: (d) =>
                            `<input class="form-control form-control-sm editable" data-field="email" value="${htmlEsc(d??'')}">`
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number',
                        render: (d) =>
                            `<input class="form-control form-control-sm editable" data-field="phone_number" value="${htmlEsc(d??'')}">`
                    },
                    {
                        data: 'phone_number_2',
                        name: 'phone_number_2',
                        render: (d) =>
                            `<input class="form-control form-control-sm editable" data-field="phone_number_2" value="${htmlEsc(d??'')}">`
                    },
                    {
                        data: 'speciality',
                        name: 'speciality',
                        render: (d) =>
                            `<input class="form-control form-control-sm editable" data-field="speciality" value="${htmlEsc(d??'')}">`
                    },

                    // extra form fields visible in table
                    {
                        data: 'registration_no',
                        name: 'registration_no',
                        render: (d) =>
                            `<input class="form-control form-control-sm editable" data-field="registration_no" value="${htmlEsc(d??'')}">`
                    },
                    {
                        data: 'council',
                        name: 'council',
                        render: (d) =>
                            `<input class="form-control form-control-sm editable" data-field="council" value="${htmlEsc(d??'')}">`
                    },
                    {
                        data: 'pincode',
                        name: 'pincode',
                        render: (d, _, r) => {
                            const pincodeValue = (r.manual_pincode ?? '') || (r.pincode ? (r.pincode
                                .pincode ?? '') : '');
                            return `<input class="form-control form-control-sm editable" data-field="pincode" value="${htmlEsc(pincodeValue)}">`;
                        }
                    },
                    {
                        data: 'website',
                        name: 'website',
                        render: (d) =>
                            `<input class="form-control form-control-sm editable" data-field="website" value="${htmlEsc(d??'')}">`
                    },
                    {
                        data: 'whatsapp',
                        name: 'whatsapp',
                        render: (d) =>
                            `<input class="form-control form-control-sm editable" data-field="whatsapp" value="${htmlEsc(d??'')}">`
                    },
                    {
                        data: 'facebook',
                        name: 'facebook',
                        render: (d) =>
                            `<input class="form-control form-control-sm editable" data-field="facebook" value="${htmlEsc(d??'')}">`
                    },
                    {
                        data: 'instagram',
                        name: 'instagram',
                        render: (d) =>
                            `<input class="form-control form-control-sm editable" data-field="instagram" value="${htmlEsc(d??'')}">`
                    },
                    {
                        data: 'address',
                        name: 'address',
                        render: (d) =>
                            `<input class="form-control form-control-sm editable" data-field="address" value="${htmlEsc(d??'')}">`
                    },

                    // Location & relationships
                    {
                        data: 'country_id',
                        name: 'country_id',
                        render: (d, __, r) =>
                            `<select class="form-select form-select-sm editable countrySel" data-field="country_id">${buildOptions(COUNTRIES, d, {placeholder:'Country'})}</select>`
                    },
                    {
                        data: 'state_id',
                        name: 'state_id',
                        render: (d, __, r) =>
                            `<select class="form-select form-select-sm editable stateSel" data-field="state_id">${buildOptions(STATES, d, {placeholder:'State'}, s=>String(s.country_id)===String(r.country_id))}</select>`
                    },
                    {
                        data: 'district_id',
                        name: 'district_id',
                        render: (d, __, r) =>
                            `<select class="form-select form-select-sm editable districtSel" data-field="district_id">${buildOptions(DISTRICTS, d, {placeholder:'District'}, x=>String(x.state_id)===String(r.state_id))}</select>`
                    },
                    {
                        data: 'city_id',
                        name: 'city_id',
                        render: (d, __, r) =>
                            `<select class="form-select form-select-sm editable citySel" data-field="city_id">${buildOptions(CITIES, d, {placeholder:'City'}, x=>String(x.district_id)===String(r.district_id))}</select>`
                    },

                    // Category
                    {
                        data: 'category_id',
                        name: 'category_id',
                        render: (d, __, r) => {
                            const sel = (r && r.category && r.category.id) ? r.category.id : d;
                            return `<select class="form-select form-select-sm editable catSel" data-field="category_id">${buildOptions(CATEGORIES, sel, {placeholder:'Category'})}</select>`;
                        }
                    },

                    // Clinic
                    {
                        data: 'clinic_id',
                        name: 'clinic_id',
                        render: (d, __, r) => {
                            const sel = (r && r.clinic && r.clinic.id) ? r.clinic.id : d;
                            const cat = (r && r.category && r.category.id) ? r.category.id : (r && r
                                .category_id ? r.category_id : null);
                            return `<select class="form-select form-select-sm editable clinicSel" data-field="clinic_id">${buildOptions(CLINICS, sel, {placeholder:'Clinic'}, x=>!cat || String(x.category_id)===String(cat))}</select>`;
                        }
                    },

                    {
                        data: 'status',
                        name: 'status',
                        render: (d) => {
                            const v = d ?? 'active';
                            return `<select class="form-select form-select-sm editable" data-field="status">
            <option value="active" ${v==='active'?'selected':''}>Active</option>
            <option value="inactive" ${v==='inactive'?'selected':''}>Inactive</option>
          </select>`;
                        }
                    },
                    {
                        data: 'consultation_mode',
                        name: 'consultation_mode',
                        render: (d) => {
                            const v = d ?? 'face-to-face';
                            return `<select class="form-select form-select-sm editable" data-field="consultation_mode">
            <option value="online" ${v==='online'?'selected':''}>Online</option>
            <option value="face-to-face" ${v==='face-to-face'?'selected':''}>Face to face</option>
            <option value="both" ${v==='both'?'selected':''}>Both</option>
            <option value="offline" ${v==='offline'?'selected':''}>Offline</option>
          </select>`;
                        }
                    },

                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-nowrap',
                        render: (_, _2, row) => `
          <button class="btn btn-square btn-outline-success saveRow" data-id="${row.id}" data-bs-toggle="tooltip" title="Save" aria-label="Save row ${row.id}">
            <i class="bi bi-check2"></i>
          </button>
          <button class="btn btn-square btn-outline-danger deleteRow" data-id="${row.id}" data-bs-toggle="tooltip" title="Delete" aria-label="Delete row ${row.id}">
            <i class="bi bi-trash3"></i>
          </button>
        `
                    }
                ],
                drawCallback: function() {
                    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap
                        .Tooltip(el));
                    $('#doctorsTable tbody .rowCheck').each(function() {
                        const id = Number($(this).data('id'));
                        $(this).prop('checked', selectedIds.has(id));
                    });
                    toggleBulkButton();
                    editedIds.forEach(id => $('#row-' + id).addClass('edited'));
                }
            });

            // row dependent selects handlers (in-table)
            $('#doctorsTable').on('change', '.countrySel', function() {
                const $tr = $(this).closest('tr');
                editedRow($tr);
                const cid = $(this).val();
                $tr.find('.stateSel').html(buildOptions(STATES, null, {
                    placeholder: 'State'
                }, s => String(s.country_id) === String(cid))).val('');
                $tr.find('.districtSel').html('<option value="">District</option>').val('');
                $tr.find('.citySel').html('<option value="">City</option>').val('');
            });

            $('#doctorsTable').on('change', '.stateSel', function() {
                const $tr = $(this).closest('tr');
                editedRow($tr);
                const sid = $(this).val();
                $tr.find('.districtSel').html(buildOptions(DISTRICTS, null, {
                    placeholder: 'District'
                }, d => String(d.state_id) === String(sid))).val('');
                $tr.find('.citySel').html('<option value="">City</option>').val('');
            });

            $('#doctorsTable').on('change', '.districtSel', function() {
                const $tr = $(this).closest('tr');
                editedRow($tr);
                const did = $(this).val();
                $tr.find('.citySel').html(buildOptions(CITIES, null, {
                    placeholder: 'City'
                }, c => String(c.district_id) === String(did))).val('');
            });

            $('#doctorsTable').on('change', '.catSel', function() {
                const $tr = $(this).closest('tr');
                editedRow($tr);
                const cat = $(this).val();
                const keep = $tr.find('.clinicSel').val();
                const html = buildOptions(CLINICS, keep, {
                    placeholder: 'Clinic'
                }, c => !cat || String(c.category_id) === String(cat));
                $tr.find('.clinicSel').html(html);
                if (keep && $tr.find('.clinicSel option[value="' + keep + '"]').length) {
                    $tr.find('.clinicSel').val(keep);
                }
            });

            function editedRow($tr) {
                $tr.addClass('edited');
                editedIds.add(Number($tr.data('id')));
            }

            $('#doctorsTable').on('input change', '.editable', function() {
                editedRow($(this).closest('tr'));
            });

            window.addEventListener('beforeunload', function(e) {
                if (editedIds.size) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });

            // checkboxes
            $('#selectAll').on('change', function() {
                const check = this.checked;
                $('#doctorsTable tbody .rowCheck').each(function() {
                    $(this).prop('checked', check);
                    const id = Number($(this).data('id'));
                    if (check) selectedIds.add(id);
                    else selectedIds.delete(id);
                });
                toggleBulkButton();
            });

            $('#doctorsTable').on('change', '.rowCheck', function() {
                const id = Number($(this).data('id'));
                this.checked ? selectedIds.add(id) : selectedIds.delete(id);
                toggleBulkButton();
            });

            function toggleBulkButton() {
                $('#bulkDeleteBtn').prop('disabled', selectedIds.size === 0);
            }

            // save single row
            $('#doctorsTable').on('click', '.saveRow', function() {
                const id = $(this).data('id');
                const $btn = $(this).prop('disabled', true).addClass('disabled');
                const $tr = $('#row-' + id);
                const payload = collectRow($tr);

                $.ajax({
                        url: "{{ route('doctor_inline.update', ':id') }}".replace(':id', id),
                        type: 'PUT',
                        data: payload,
                        dataType: 'json'
                    })
                    .done(res => {
                        notify.ok(res.message || 'Saved successfully');
                        editedIds.delete(id);
                        $tr.removeClass('edited');
                        table.ajax.reload(null, false);
                    })
                    .fail(xhr => {
                        console.error(xhr);
                        let msg = 'Save failed';
                        if (xhr && xhr.responseJSON) {
                            if (xhr.status === 422 && xhr.responseJSON.errors) {
                                const errs = xhr.responseJSON.errors;
                                const flat = [];
                                Object.keys(errs).forEach(k => {
                                    (errs[k] || []).forEach(m => flat.push(m));
                                });
                                msg = flat.slice(0, 5).join('; ');
                                console.groupCollapsed('Validation errors');
                                console.table(errs);
                                console.groupEnd();
                            } else if (xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message + (xhr.responseJSON.error ? (': ' + xhr
                                    .responseJSON.error) : '');
                            }
                        }
                        notify.err(msg);
                    })
                    .always(() => {
                        $btn.prop('disabled', false).removeClass('disabled');
                    });
            });

            // save all
            $('#saveAllBtn').on('click', function() {
                if (!editedIds.size) return notify.info('No changes to save');
                const ids = Array.from(editedIds);
                const rows = ids.map(id => {
                    const rowData = collectRow($('#row-' + id));
                    return rowData;
                });
                const $btn = $(this).prop('disabled', true).addClass('disabled');
                $.ajax({
                        url: "{{ route('doctor_inline.bulk.update') }}",
                        type: 'POST',
                        data: {
                            rows: rows
                        },
                        dataType: 'json'
                    })
                    .done(res => {
                        notify.ok(res.message || 'All changes saved successfully');
                        editedIds.clear();
                        table.ajax.reload(null, false);
                    })
                    .fail(xhr => {
                        console.error(xhr);
                        notify.err(xhr.responseJSON?.message || 'Bulk save failed');
                    })
                    .always(() => $btn.prop('disabled', false).removeClass('disabled'));
            });

            // delete one
            $('#doctorsTable').on('click', '.deleteRow', function() {
                const id = $(this).data('id');
                confirmAction('Are you sure you want to delete this doctor?', {
                    okText: 'Delete'
                }).then(ok => {
                    if (!ok) return;
                    $.ajax({
                            url: "{{ route('doctor_inline.destroy', ':id') }}".replace(':id', id),
                            method: 'DELETE',
                            dataType: 'json'
                        })
                        .done(res => {
                            notify.ok(res.message || 'Doctor deleted successfully');
                            selectedIds.delete(id);
                            editedIds.delete(id);
                            table.ajax.reload(null, false);
                            toggleBulkButton();
                        })
                        .fail((xhr) => {
                            console.error(xhr);
                            notify.err(xhr.responseJSON?.message || 'Delete failed');
                        });
                });
            });

            // bulk delete (POST to route defined in your routes)
            $('#bulkDeleteBtn').on('click', function() {
                const ids = Array.from(selectedIds);
                if (!ids.length) return;
                confirmAction(`Are you sure you want to delete ${ids.length} selected doctor(s)?`, {
                    okText: 'Delete'
                }).then(ok => {
                    if (!ok) return;
                    $.ajax({
                            url: "{{ route('doctor_inline.bulk.destroy') }}",
                            method: 'POST',
                            data: {
                                ids
                            },
                            dataType: 'json'
                        })
                        .done(res => {
                            notify.ok(res.message || 'Doctors deleted successfully');
                            ids.forEach(id => {
                                selectedIds.delete(id);
                                editedIds.delete(id);
                            });
                            table.ajax.reload(null, false);
                            toggleBulkButton();
                        })
                        .fail((xhr) => {
                            console.error(xhr);
                            notify.err(xhr.responseJSON?.message || 'Bulk delete failed');
                        });
                });
            });

            // import
            $('#import_file').on('change', function() {
                const file = this.files?.[0];
                if (!file) return;
                const fd = new FormData();
                fd.append('excel_file', file);
                $.ajax({
                        url: "{{ route('doctor_inline.import') }}",
                        method: 'POST',
                        data: fd,
                        processData: false,
                        contentType: false,
                        dataType: 'json'
                    })
                    .done(res => {
                        notify.ok(res.message || 'Import completed successfully');
                        table.ajax.reload(null, false);
                        this.value = '';
                    })
                    .fail(xhr => {
                        console.error(xhr);
                        notify.err(xhr.responseJSON?.message || 'Import failed');
                        this.value = '';
                    });
            });

            function collectRow($tr) {
                const id = Number($tr.data('id'));
                const data = {
                    id
                };
                $tr.find('.editable').each(function() {
                    data[$(this).data('field')] = $(this).val();
                });
                return data;
            }

        })();
    </script>
@endsection
