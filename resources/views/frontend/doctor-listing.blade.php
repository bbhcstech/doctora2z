@extends('partials.app')

@section('title', 'Doctor Listing')

@section('content')
    <div class="container-xxl py-5 bg-dark page-header mb-5">
        <div class="container my-5 pt-5 pb-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Doctor Listing</h1>
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .card-page {
            max-width: 1000px;
            margin: 18px auto 48px;
            background: #e8eef8;
            padding: 18px;
            border-radius: 10px;
        }

        .card-page h5 {
            font-weight: 700;
            margin-bottom: 14px;
        }

        .small-label {
            font-size: .92rem;
            display: block;
            margin-bottom: .35rem;
            color: #333;
        }

        .form-compact .form-control,
        .form-compact .form-select {
            height: 42px;
            padding: .45rem .8rem;
            border-radius: 6px;
        }

        .row.gx-3>[class*='col-'] {
            margin-bottom: .8rem;
        }

        .divider {
            border-top: 1px solid #d0d7e3;
            margin: 14px 0;
            padding-top: 12px;
        }

        .btn-save {
            background: #fff;
            color: #000;
            border: 1px solid #000;
            padding: .55rem 1.2rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            transition: .3s;
        }

        .btn-save:hover {
            background: #f3f4f6;
        }

        .btn-go-profile {
            background: #0d6efd;
            color: #fff;
            border: 1px solid #0d6efd;
            padding: .45rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            margin-left: 10px;
        }

        .text-small-muted {
            font-size: .85rem;
            color: #6b7280;
            margin-top: .3rem;
        }

        .ajax-error {
            color: #b91c1c;
            margin-top: .5rem;
        }

        .ajax-success {
            color: #065f46;
            margin-top: .5rem;
        }

        /* disabled Save button look */
        .btn-save[disabled] {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Hover / focus popup for email-help */
        .hover-wrap {
            position: relative;
            display: block;
        }

        .hover-help-popup {
            display: none;
            position: absolute;
            left: 0;
            top: calc(100% + 10px);
            min-width: 260px;
            max-width: 360px;
            background: #ffffff;
            border-radius: 6px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
            padding: 10px 12px;
            color: #1f2937;
            font-size: 0.92rem;
            line-height: 1.25;
            z-index: 40;
        }

        .hover-wrap:hover .hover-help-popup,
        .hover-wrap:focus-within .hover-help-popup {
            display: block;
        }

        .hover-help-indicator {
            display: none;
        }

        @media (max-width: 600px) {
            .hover-help-popup {
                position: static;
                margin-top: 8px;
                box-shadow: none;
                border: 1px solid #e5e7eb;
            }
        }

        @media(max-width:767px) {
            .card-page {
                padding: 14px;
            }
        }
    </style>

    <div class="container">
        <div class="card-page">

            <div id="serverErrors" style="display:none;" class="alert alert-danger"></div>
            <div id="serverSuccess" style="display:none;" class="alert alert-success"></div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form id="doctorForm" class="form-compact" action="{{ route('listdoctorstore') }}" method="POST"
                enctype="multipart/form-data" novalidate>
                @csrf

                {{-- Basic Info --}}
                <div class="row gx-3">
                    <div class="col-md-3">
                        <label for="email" class="small-label">Email <span class="text-danger">*</span></label>

                        <div class="hover-wrap" tabindex="-1" aria-describedby="emailHelpPopup">
                            <input id="email" type="email" name="email" class="form-control"
                                value="{{ old('email') }}" required>

                            <div id="emailHelpPopup" class="hover-help-popup" role="region" aria-live="polite">
                                Must be a valid email address (e.g., example@domain.com).<br>
                                Each doctor can use only one unique email for registration.<br>
                                If this email is already registered,<br>
                                please use a different one.
                            </div>
                        </div>

                        <div id="emailStatus" class="ajax-error" style="display:none;margin-top:8px;"></div>
                    </div>

                    <div class="col-md-3">
                        <label for="name" class="small-label">Name <span class="text-danger">*</span></label>
                        <input id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label for="phone_number" class="small-label">Contact Number 1 <span
                                class="text-danger">*</span></label>
                        <input id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number') }}"
                            required pattern="^[0-9]{10}$" title="Enter exactly 10 digits" inputmode="numeric"
                            maxlength="10">
                        <div id="phone1Error" class="ajax-error" style="display:none;"></div>
                    </div>

                    <div class="col-md-3">
                        <label for="contact_number_2" class="small-label">Contact Number 2</label>
                        <input id="contact_number_2" name="contact_number_2" class="form-control"
                            value="{{ old('contact_number_2') }}" maxlength="10" inputmode="numeric" pattern="[0-9]{0,10}"
                            title="Enter up to 10 digits only.">

                        <div id="contact2Error" style="display:none;" class="ajax-error text-danger small mt-1"></div>
                    </div>


                    {{-- Professional Info --}}
                    <div class="row gx-3 mt-3">
                        <div class="col-md-4">
                            <label for="category_id" class="small-label">Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="degree" class="small-label">Degree <span class="text-danger">*</span></label>
                            <input id="degree" name="degree" class="form-control" value="{{ old('degree') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label for="profile_picture" class="small-label">Profile Picture</label>
                            <input id="profile_picture" type="file" name="profile_picture" class="form-control"
                                accept="image/png,image/jpeg">
                            <small class="text-muted">JPG, PNG allowed (optional)</small>
                        </div>
                    </div>

                    {{-- Pincode Section - REORDERED --}}
                    <div class="row gx-3 mt-3">
                        <!-- Country First -->
                        <div class="col-md-3">
                            <label for="country_id" class="small-label">Country <span
                                    class="text-danger">*</span></label>
                            <select name="country_id" id="country_id" class="form-select" required>
                                <option value="">Select Country</option>
                                @foreach ($countries as $c)
                                    <option value="{{ $c->id }}" @selected(old('country_id') == $c->id)>{{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pincode Second -->
                        <div class="col-md-3">
                            <label for="pincode" class="small-label">Pincode <span class="text-danger">*</span></label>
                            <div class="d-flex gap-2">
                                <input id="pincode" name="pincode" class="form-control" value="{{ old('pincode') }}"
                                    placeholder="Enter 6-digit Pincode" maxlength="6" required {{-- Disabled until country selected --}}
                                    @if (!old('country_id')) disabled @endif>
                                <button type="button" id="pincodeLookupBtn" class="btn btn-outline-primary"
                                    {{-- Disabled until country selected --}} @if (!old('country_id')) disabled @endif>
                                    Lookup
                                </button>
                            </div>
                            <div id="pincodeHelp" class="text-small-muted">
                                @if (old('country_id'))
                                    Type a 6-digit pincode and press Lookup
                                @else
                                    Please select country first
                                @endif
                            </div>
                            <input type="hidden" name="pincode_id" id="pincode_id"
                                value="{{ old('pincode_id') ?? '' }}">
                        </div>

                        <div class="col-md-6">
                            <label for="address" class="small-label">Address</label>
                            <input id="address" name="address" class="form-control" value="{{ old('address') }}">
                        </div>
                    </div>

                    {{-- Location Section --}}
                    <div class="row gx-3 mt-3">
                        <!-- State -->
                        <div class="col-md-3">
                            <label for="state_id" class="small-label">State <span class="text-danger">*</span></label>
                            <select name="state_id" id="state_id" class="form-select" required>
                                <option value="">Select State</option>
                                @foreach ($state as $s)
                                    <option value="{{ $s->id }}" @selected(old('state_id') == $s->id)
                                        data-country="{{ $s->country_id }}">
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- District -->
                        <div class="col-md-3">
                            <label for="district_id" class="small-label">District <span
                                    class="text-danger">*</span></label>
                            <select name="district_id" id="district_id" class="form-select" required>
                                <option value="">Select District</option>
                            </select>
                        </div>

                        <!-- City/Area -->
                        <div class="col-md-3">
                            <label for="city_id" class="small-label">Area <span class="text-danger">*</span></label>
                            <select name="city_id" id="city_id" class="form-select" required>
                                <option value="">Select City</option>
                                @foreach ($city as $ct)
                                    <option value="{{ $ct->id }}" @selected(old('city_id') == $ct->id)
                                        data-district="{{ $ct->district_id }}">{{ $ct->name }}</option>
                                @endforeach
                            </select>

                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" value="1" id="cantFindArea"
                                    name="city_manual_toggle" autocomplete="off">
                                <label class="form-check-label small" for="cantFindArea">can't find area?</label>
                            </div>

                            <div id="cityOtherDiv" style="margin-top:8px;">
                                <input type="text" id="city_other" name="city_other" class="form-control"
                                    placeholder="Enter other area name" value="{{ old('city_other') }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>

                    {{-- Buttons --}}
                    <div class="row gx-3 mt-3">
                        <div class="col-md-6 d-flex justify-content-start">
                            <button type="reset" class="btn btn-secondary">Clear</button>
                        </div>

                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <!-- Confirmation checkbox (new) -->
                            <div class="me-3" style="max-width:420px;">
                                <div class="form-check d-flex align-items-start">
                                    <input class="form-check-input" type="checkbox" id="confirmReview"
                                        name="confirm_review" value="1" />
                                    <label class="form-check-label small ms-2" for="confirmReview"
                                        id="confirmReviewLabel">
                                        I have reviewed all the information and confirm that everything entered above is
                                        correct.
                                    </label>
                                </div>
                                <div id="confirmError" class="ajax-error" style="display:none;margin-top:.45rem;">
                                    Please confirm that all details are correct before submitting.
                                </div>
                            </div>

                            <button type="submit" id="saveBtn" class="btn btn-save" disabled>Save Doctor</button>

                            <a href="#" id="goToProfileBtn" class="btn btn-go-profile" style="display:none;"
                                target="_blank">Go to Profile</a>

                            @if (session('new_doctor_id'))
                                <a href="{{ route('doctor.details', session('new_doctor_id')) }}"
                                    class="btn btn-go-profile" style="display:inline-block;">Go to Profile</a>
                            @endif
                        </div>
                    </div>
            </form>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        // ডিবাগিং এর জন্য
        console.log('=== Doctor Listing Form Loading ===');
        console.log('States from controller:', @json($state));
        console.log('Countries from controller:', @json($countries));

        (function($) {
            $(document).ready(function() {
                console.log('Document ready - Doctor Listing Form initializing...');

                // 1. প্রথমে চেক করুন সব elements exist করছে কিনা
                console.log('Checking form elements:');
                console.log('Country select:', $('#country_id').length);
                console.log('State select:', $('#state_id').length);
                console.log('District select:', $('#district_id').length);
                console.log('City select:', $('#city_id').length);

                // 2. Select2 initialize করুন - SIMPLIFIED VERSION
                setTimeout(function() {
                    try {
                        // First destroy any existing Select2
                        if ($.fn.select2) {
                            $('#category_id, #country_id, #state_id, #district_id, #city_id').each(
                                function() {
                                    if ($(this).hasClass('select2-hidden-accessible')) {
                                        $(this).select2('destroy');
                                    }
                                });

                            // Then initialize fresh
                            $('#category_id, #country_id, #state_id, #district_id, #city_id').select2({
                                width: '100%'
                            });

                            console.log('Select2 initialized successfully');
                        } else {
                            console.error('Select2 library not loaded!');
                        }
                    } catch (e) {
                        console.error('Select2 initialization error:', e);
                    }
                }, 100);

                // 3. ডাটা চেক করুন
                setTimeout(function() {
                    console.log('Country options count:', $('#country_id option').length);
                    $('#country_id option').each(function(i) {
                        if ($(this).val()) {
                            console.log('Country option', i, ':', $(this).val(), '-', $(this)
                                .text());
                        }
                    });

                    console.log('State options count:', $('#state_id option').length);
                    $('#state_id option').each(function(i) {
                        if ($(this).val()) {
                            console.log('State option', i, ':', $(this).val(), '-', $(this)
                                .text());
                        }
                    });
                }, 200);

                // ==================== SIMPLIFIED FUNCTIONS ====================

                // Escape helper
                function escapeHtml(str) {
                    if (str === null || str === undefined) return '';
                    return String(str).replace(/[&<>"'`=/]/g, function(s) {
                    return ({
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#39;',
                        '/': '&#x2F;',
                        '`': '&#x60;',
                            '=': '&#x3D;'
                        })[s];
                    });
                }

                // Load districts function
                function loadDistrictsForState(stateId, selectedDistrictId = null) {
                    console.log('Loading districts for state:', stateId);
                    const $dist = $('#district_id');

                    if (!stateId) {
                        $dist.html('<option value="">Select District</option>');
                        if ($.fn.select2) $dist.trigger('change.select2');
                        return;
                    }

                    $dist.html('<option value="">Loading...</option>');
                    if ($.fn.select2) $dist.trigger('change.select2');

                    $.getJSON("{{ url('/get-districts') }}/" + encodeURIComponent(stateId))
                        .done(function(res) {
                            let html = '<option value="">Select District</option>';
                            if (Array.isArray(res)) {
                                res.forEach(d => {
                                    html +=
                                        `<option value="${escapeHtml(d.id)}">${escapeHtml(d.name)}</option>`;
                                });
                            }
                            $dist.html(html);
                            if ($.fn.select2) $dist.trigger('change.select2');

                            if (selectedDistrictId) {
                                $dist.val(selectedDistrictId);
                                if ($.fn.select2) $dist.trigger('change.select2');
                            }
                        })
                        .fail(function() {
                            $dist.html('<option value="">Error loading districts</option>');
                            if ($.fn.select2) $dist.trigger('change.select2');
                        });
                }

                // Load areas function - SIMPLIFIED
                function loadAreasByDistrict(districtId, pincode = null) {
                    console.log('Loading areas for district:', districtId, 'pincode:', pincode);
                    const $city = $('#city_id');

                    if (!districtId) {
                        $city.html('<option value="">Select Area</option>');
                        if ($.fn.select2) $city.trigger('change.select2');
                        return;
                    }

                    $city.html('<option value="">Loading...</option>');

                    let url = "{{ url('/get-areas') }}/" + encodeURIComponent(districtId);
                    if (pincode) {
                        url += "/" + encodeURIComponent(pincode);
                    }

                    $.getJSON(url)
                        .done(function(res) {
                            let html = '<option value="">Select Area</option>';
                            if (res && res.success && Array.isArray(res.areas)) {
                                res.areas.forEach(a => {
                                    html +=
                                        `<option value="${escapeHtml(a.id)}">${escapeHtml(a.name)}</option>`;
                                });
                            }
                            $city.html(html);
                            if ($.fn.select2) $city.trigger('change.select2');
                        })
                        .fail(function() {
                            $city.html('<option value="">Error loading areas</option>');
                            if ($.fn.select2) $city.trigger('change.select2');
                        });
                }

                // ==================== EVENT HANDLERS ====================

                // Country change
                $('#country_id').on('change', function() {
                    const countryId = $(this).val();
                    console.log('Country changed to:', countryId);

                    // Clear other fields
                    $('#state_id').val('');
                    $('#district_id').html('<option value="">Select District</option>');
                    $('#city_id').html('<option value="">Select Area</option>');

                    // Enable/disable pincode
                    if (countryId) {
                        $('#pincode, #pincodeLookupBtn').prop('disabled', false);
                        $('#pincodeHelp').text('Type a 6-digit pincode and press Lookup').css('color',
                            '#6b7280');
                    } else {
                        $('#pincode, #pincodeLookupBtn').prop('disabled', true);
                        $('#pincodeHelp').text('Please select country first').css('color', '#dc2626');
                    }
                });

                // State change
                $('#state_id').on('change', function() {
                    const stateId = $(this).val();
                    console.log('State changed to:', stateId);

                    $('#district_id').html('<option value="">Select District</option>');
                    $('#city_id').html('<option value="">Select Area</option>');

                    if (stateId) {
                        loadDistrictsForState(stateId);
                    }
                });

                // District change
                $('#district_id').on('change', function() {
                    const districtId = $(this).val();
                    console.log('District changed to:', districtId);

                    if (districtId) {
                        loadAreasByDistrict(districtId);
                    } else {
                        $('#city_id').html('<option value="">Select Area</option>');
                    }
                });

                // Mutual exclusion for area fields
                $('#cantFindArea').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('#city_other').prop('disabled', false).focus();
                        $('#city_id').prop('disabled', true).val('');
                    } else {
                        $('#city_other').prop('disabled', true).val('');
                        $('#city_id').prop('disabled', false);
                    }
                });

                // If user selects an area from dropdown
                $('#city_id').on('change', function() {
                    if ($(this).val()) {
                        $('#city_other').prop('disabled', true).val('');
                        $('#cantFindArea').prop('checked', false);
                    }
                });

                // Pincode validation
                function isValidPin(p) {
                    return /^\d{6}$/.test(String(p || '').trim());
                }

                // Pincode lookup - SIMPLIFIED
                function lookupPincode(pincode) {
                    const countryId = $('#country_id').val();
                    if (!countryId) {
                        $('#pincodeHelp').html(
                            '<div style="color: #dc2626;">Please select country first</div>');
                        return;
                    }

                    if (!isValidPin(pincode)) {
                        $('#pincodeHelp').html('<div style="color: #dc2626;">Invalid Pincode!</div>');
                        return;
                    }

                    $('#pincodeLookupBtn').prop('disabled', true).text('Looking...');

                    $.getJSON("{{ url('/api/pincode/lookup') }}/" + encodeURIComponent(pincode))
                        .done(function(res) {
                            if (!res || !res.success || !res.payload) {
                                $('#pincodeHelp').html('<div style="color: #dc2626;">Lookup failed</div>');
                                return;
                            }

                            const p = res.payload;
                            $('#pincode_id').val(p.id);

                            // Set state
                            if (p.state_id) {
                                $('#state_id').val(p.state_id).trigger('change');

                                // Wait and set district
                                setTimeout(() => {
                                    loadDistrictsForState(p.state_id, p.district_id);

                                    // Wait and set city
                                    setTimeout(() => {
                                        if (p.city_id) {
                                            loadAreasByDistrict(p.district_id, pincode);
                                            setTimeout(() => {
                                                $('#city_id').val(p.city_id)
                                                    .trigger('change');
                                            }, 500);
                                        }
                                    }, 600);
                                }, 400);
                            }
                        })
                        .fail(() => {
                            $('#pincodeHelp').html('<div style="color: #dc2626;">Lookup failed</div>');
                        })
                        .always(() => {
                            $('#pincodeLookupBtn').prop('disabled', false).text('Lookup');
                        });
                }

                // Pincode button click
                $('#pincodeLookupBtn').on('click', function() {
                    lookupPincode($('#pincode').val().trim());
                });

                // Pincode enter press
                $('#pincode').on('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        lookupPincode($(this).val().trim());
                    }
                });

                // Initial load if there are old values
                const OLD_STATE = @json(old('state_id'));
                const OLD_DISTRICT = @json(old('district_id'));

                if (OLD_STATE) {
                    console.log('Loading old state:', OLD_STATE);
                    setTimeout(() => {
                        $('#state_id').val(OLD_STATE).trigger('change');
                    }, 300);
                }

                // Form validation and submission
                $('#confirmReview').on('change', function() {
                    $('#saveBtn').prop('disabled', !$(this).is(':checked'));
                });

                // Form submit
                $('#doctorForm').on('submit', function(e) {
                    e.preventDefault();

                    if (!$('#confirmReview').is(':checked')) {
                        $('#confirmError').show();
                        return false;
                    }

                    const $btn = $('#saveBtn');
                    $btn.prop('disabled', true).text('Saving...');

                    const form = this;
                    const formData = new FormData(form);

                    $.ajax({
                        url: $(form).attr('action'),
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(resp) {
                            if (resp && resp.success) {
                                window.location.href =
                                    '{{ route('listdoctor.success') }}' + (resp.id ?
                                        '?id=' + resp.id : '');
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                let html = '<ul class="mb-0">';
                                const errors = xhr.responseJSON.errors;
                                Object.keys(errors).forEach(function(k) {
                                    errors[k].forEach(function(m) {
                                        html += '<li>' + m + '</li>';
                                    });
                                });
                                html += '</ul>';
                                $('#serverErrors').html(html).show();
                            }
                        },
                        complete: function() {
                            $btn.prop('disabled', false).text('Save Doctor');
                        }
                    });
                });

                // Email validation
                $('#email').on('blur', function() {
                    const val = this.value.trim().toLowerCase();
                    if (!val || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) return;

                    $.post('{{ route('ajax.check-email') }}', {
                            email: val,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        })
                        .done(res => {
                            if (res.exists === true || res.exists === 'true') {
                                $('#emailStatus').text('Email already registered.').css('color',
                                    '#b91c1c').show();
                            } else {
                                $('#emailStatus').text('Email available.').css('color', '#065f46')
                                    .show();
                            }
                        });
                });

                console.log('=== Doctor Listing Form Initialized ===');
            });
        })(jQuery);
    </script>
