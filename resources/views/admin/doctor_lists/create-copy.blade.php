@extends('admin.layout.app')

@section('title', 'Add New Doctor')

@section('content')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Add New Doctor</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctors.index') }}">Doctors Listing</a></li>
                    <li class="breadcrumb-item active">Add New Doctor</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <!-- Form to Add New Doctor -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('doctors.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="container">
                        <!-- Clinic Dropdown -->
                        <div class="mb-3">
                            <div class="mb-3">
                                <label for="clinic_ids" class="form-label">Clinics <span
                                        style="color: red;">*</span></label>
                                <select class="form-control" name="clinic_ids" id="clinic_ids" required>
                                    <!-- Default option to choose a clinic -->
                                    <option value="" disabled selected>Select Clinic</option>

                                    <!-- Loop through the clinics -->
                                    @foreach ($clinics as $clinic)
                                        <option value="{{ $clinic->id }}"
                                            {{ old('clinic_ids', $selectedClinics ?? '') == $clinic->id ? 'selected' : '' }}
                                            data-address="{{ $clinic->address }}" data-country="{{ $clinic->country_name }}"
                                            data-state="{{ $clinic->state_name }}"
                                            data-district="{{ $clinic->district_name }}">
                                            {{ $clinic->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('clinic_ids')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Clinic Details Display Div -->
                            <div id="clinic-details" style="display: none; margin-top: 15px;">
                                <p><strong>Address:</strong> <span id="clinic-address"></span></p>
                                <p><strong>Country:</strong> <span id="clinic-country"></span></p>
                                <p><strong>State:</strong> <span id="clinic-state"></span></p>
                                <p><strong>District:</strong> <span id="clinic-district"></span></p>
                            </div>



                            <!-- Name (First Name + Last Name) -->

                            <div class="row">
                                <!-- Doctor Name -->
                                <div class="col-md-4 mb-3">
                                    <label for="name" class="form-label">Doctor Name <span
                                            style="color: red;">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Degree -->
                                <div class="col-md-4 mb-3">
                                    <label for="degree" class="form-label">Degree <span
                                            style="color: red;">*</span></label>
                                    <input type="text" class="form-control @error('degree') is-invalid @enderror"
                                        id="degree" name="degree" value="{{ old('degree') }}" required>
                                    @error('degree')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Registration No -->
                                <div class="col-md-4 mb-3">
                                    <label for="reg_no" class="form-label">Registration No</label>
                                    <input type="text" class="form-control @error('reg_no') is-invalid @enderror"
                                        id="reg_no" name="reg_no" value="{{ old('reg_no') }}">
                                    @error('reg_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Category -->
                                <div class="col-md-4 mb-3">
                                    <label for="category_id" class="form-label">Category <span
                                            style="color: red;">*</span></label>
                                    <select class="form-control" name="category_id[]" id="category_id" multiple required>
                                        @foreach ($category as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Sub-category -->
                                <div class="col-md-4 mb-3">
                                    <label for="sub_category" class="form-label">Sub-Category</label>
                                    <input type="text" class="form-control @error('sub_category') is-invalid @enderror"
                                        id="sub_category" name="sub_category" value="{{ old('sub_category') }}">
                                    @error('sub_category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Phone Number -->
                                <div class="col-md-4 mb-3">
                                    <label for="personal_phone_number" class="form-label">Dr. Personal Number</label>
                                    <input type="text" pattern="\d{10}" maxlength="10" minlength="10"
                                        class="form-control @error('personal_phone_number') is-invalid @enderror"
                                        id="personal_phone_number" name="personal_phone_number"
                                        value="{{ old('personal_phone_number') }}">
                                    @error('personal_phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Visiting Time -->
                                <div class="col-md-4 mb-3">
                                    <label for="visiting_time" class="form-label">Visiting Time</label>
                                    <input type="text"
                                        class="form-control @error('visiting_time') is-invalid @enderror"
                                        id="visiting_time" name="visiting_time" value="{{ old('visiting_time') }}">
                                    @error('visiting_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                <!-- Month -->

                            </div>
                            <div class="row">
                                <!-- Date Wise Checkbox -->
                                <div class="col-md-2 mb-3">
                                    <label for="date_wise_checkbox">Date Wise:</label>
                                    <input type="checkbox" id="date_wise_checkbox" name="date_wise_checkbox"
                                        value="1">
                                </div>

                                <!-- Day Wise Checkbox -->
                                <div class="col-md-2 mb-3">
                                    <label for="day_wise_checkbox">Day Wise:</label>
                                    <input type="checkbox" id="day_wise_checkbox" name="day_wise_checkbox"
                                        value="1">
                                </div>

                                <!-- Date Picker (Date Wise) -->
                                <div class="col-md-4 mb-3" id="date-picker-container" style="display: none;">
                                    <label for="date_picker">Select Date:</label>
                                    <input type="text" id="date_picker" name="date_picker" class="form-control" />
                                    @error('date_picker')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Month Select (Day Wise) -->
                                <div class="col-md-4 mb-3 " id="month-container" style="display: none;">
                                    <label for="month">Month:</label>
                                    <select id="month" name="month[]" class="form-control w-100" multiple>
                                        <option value="all">All</option>
                                        <option value="january">January</option>
                                        <option value="february">February</option>
                                        <option value="march">March</option>
                                        <option value="april">April</option>
                                        <option value="may">May</option>
                                        <option value="june">June</option>
                                        <option value="july">July</option>
                                        <option value="august">August</option>
                                        <option value="september">September</option>
                                        <option value="october">October</option>
                                        <option value="november">November</option>
                                        <option value="december">December</option>
                                    </select>
                                    @error('month')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Day Select (Day Wise) -->
                                <div class="col-md-4 mb-3 " id="day-container" style="display: none;">
                                    <label for="day">Day:</label>
                                    <select id="day" name="day[]" class="form-control w-100" multiple>
                                        <option value="everyday">Everyday</option>
                                        <option value="monday">Monday</option>
                                        <option value="tuesday">Tuesday</option>
                                        <option value="wednesday">Wednesday</option>
                                        <option value="thursday">Thursday</option>
                                        <option value="friday">Friday</option>
                                        <option value="saturday">Saturday</option>
                                        <option value="sunday">Sunday</option>
                                    </select>
                                    @error('day')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>



                            <div class="row">
                                <!-- Checkbox to Show/Hide Time Fields -->
                                <div class="col-md-4 mb-3">
                                    <label for="time_checkbox">Show Start and End Time:</label>
                                    <input type="checkbox" id="time_checkbox" name="time_checkbox" value="1"
                                        {{ old('time_checkbox') ? 'checked' : '' }}>
                                </div>

                                <!-- Time Fields Container -->
                                <div class="col-md-12 mb-3" id="time-fields-container" style="display: none;">
                                    <div id="time-fields-wrapper">
                                        <!-- Default Start and End Time Pair -->
                                        <div class="time-field-pair">
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label for="start_time">Start Time:</label>
                                                    <input type="text" name="start_time[]"
                                                        class="form-control start-time" value="{{ old('start_time.0') }}"
                                                        required>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="end_time">End Time:</label>
                                                    <input type="text" name="end_time[]" class="form-control end-time"
                                                        value="{{ old('end_time.0') }}" required>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <button type="button" class="btn btn-danger remove-time-pair"
                                                        style="display: none;">X</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Add More Button -->
                                    <button type="button" id="add-time-pair" class="btn btn-primary">+</button>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Profile Text -->
                                <div class="col-md-8 mb-3">
                                    <label for="profile_text" class="form-label">Profile Text</label>
                                    <textarea class="form-control @error('profile_text') is-invalid @enderror" id="profile_text" name="profile_text">{{ old('profile_text') }}</textarea>
                                    @error('profile_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Email -->
                                <div class="col-md-4 mb-3">
                                    <label for="email" class="form-label">Email </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <!-- Active (Yes/No) -->
                                <div class="col-md-4 mb-3">
                                    <label for="active" class="form-label">Active <span
                                            style="color: red;">*</span></label>
                                    <select class="form-select @error('active') is-invalid @enderror" id="active"
                                        name="active" required>
                                        <option value="1" {{ old('active') == '1' ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ old('active') == '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                    @error('active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Image Upload -->
                                <div class="col-md-4 mb-3">
                                    <label for="image" class="form-label">Upload Doctor's Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                        id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>



                            <!-- Submit Button -->
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Add Doctor</button>
                            </div>
                </form>
            </div>
        </div>
        </div>
    </main><!-- End #main -->

@endsection

<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Flatpickr for start-time and end-time inputs
            flatpickr("#start_time", {
                enableTime: true, // Enable time picking
                noCalendar: true, // Disable date picking (only time)
                dateFormat: "H:i", // Display format (hours:minutes)
                time_24hr: true // 24-hour format (set to false for 12-hour format with AM/PM)
            });

            flatpickr("#end_time", {
                enableTime: true, // Enable time picking
                noCalendar: true, // Disable date picking (only time)
                dateFormat: "H:i", // Display format (hours:minutes)
                time_24hr: true // 24-hour format (set to false for 12-hour format with AM/PM)
            });
        });


        var $jq = jQuery.noConflict(); // No conflict with other libraries like prototype

        $jq(document).ready(function() {
            // Elements
            var clinicDropdown = $jq('#clinic_ids');
            var clinicDetailsDiv = $jq('#clinic-details');
            var addressSpan = $jq('#clinic-address');
            var countrySpan = $jq('#clinic-country');
            var stateSpan = $jq('#clinic-state');
            var districtSpan = $jq('#clinic-district');

            // Handle clinic change
            clinicDropdown.on('change', function() {
                var selectedOption = $jq(this).find('option:selected'); // Get selected option

                // Check if a clinic is selected
                if (selectedOption.val()) {
                    // Update the clinic details
                    addressSpan.text(selectedOption.data('address') || 'N/A');
                    countrySpan.text(selectedOption.data('country') || 'N/A');
                    stateSpan.text(selectedOption.data('state') || 'N/A');
                    districtSpan.text(selectedOption.data('district') || 'N/A');

                    // Show the clinic details
                    clinicDetailsDiv.show();
                } else {
                    // Hide the details if no clinic is selected
                    clinicDetailsDiv.hide();
                }
            });

            // Trigger change event to show selected clinic details on page load if already selected
            clinicDropdown.trigger('change');
        });
    </script>
    <script>
        jQuery.noConflict();
        (function($) {
            $(document).ready(function() {
                console.log("Initializing Select2 with noConflict");
                $('#clinic_ids').select2({
                    placeholder: "Select Clinics",
                    allowClear: true
                });
                console.log("Select2 initialized on #clinic_ids");

                $('#month').select2({
                    placeholder: "Select Month",
                    allowClear: true,
                    width: '100%' // Explicitly set Select2 width
                });

                $('#day').select2({
                    placeholder: "Select Day",
                    allowClear: true,
                    width: '100%' // Explicitly set Select2 width
                });

                $('#category_id').select2({
                    placeholder: "Select Category",
                    allowClear: true
                });


            });
        })(jQuery);
    </script>
    <script>
        jQuery.noConflict();
        (function($) {
            $(document).ready(function() {
                // Show/Hide time fields based on checkbox state
                $('#time_checkbox').change(function() {
                    if ($(this).prop('checked')) {
                        $('#time-fields-container').show();
                    } else {
                        $('#time-fields-container').hide();
                        $('#time-fields-wrapper').html(''); // Clear all dynamic fields
                        // Re-add the default time pair
                        addTimeFieldPair();
                    }
                });

                // Initialize checkbox state on page load
                if ($('#time_checkbox').prop('checked')) {
                    $('#time-fields-container').show();
                }

                // Function to add a new Start and End Time pair
                function addTimeFieldPair() {
                    const timePairHtml = `
                    <div class="time-field-pair">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="start_time">Start Time:</label>
                                <input type="text" name="start_time[]" class="form-control start-time" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="end_time">End Time:</label>
                                <input type="text" name="end_time[]" class="form-control end-time" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <button type="button" class="btn btn-danger remove-time-pair">X</button>
                            </div>
                        </div>
                    </div>`;
                    $('#time-fields-wrapper').append(timePairHtml);

                    // Reinitialize time pickers for new fields
                    initializeTimePickers();
                }

                // Function to initialize time pickers
                function initializeTimePickers() {
                    $('.start-time').each(function() {
                        if (!$(this).hasClass('flatpickr-initialized')) {
                            $(this).addClass('flatpickr-initialized');
                            flatpickr(this, {
                                enableTime: true,
                                noCalendar: true,
                                dateFormat: "H:i",
                                time_24hr: true
                            });
                        }
                    });

                    $('.end-time').each(function() {
                        if (!$(this).hasClass('flatpickr-initialized')) {
                            $(this).addClass('flatpickr-initialized');
                            flatpickr(this, {
                                enableTime: true,
                                noCalendar: true,
                                dateFormat: "H:i",
                                time_24hr: true
                            });
                        }
                    });
                }

                // Add more button functionality
                $('#add-time-pair').click(function() {
                    addTimeFieldPair();
                });

                // Remove button functionality
                $(document).on('click', '.remove-time-pair', function() {
                    $(this).closest('.time-field-pair').remove();
                });

                // Initialize the time picker for the default time pair
                initializeTimePickers();
            });
        })(jQuery);
    </script>

    <script>
        var $j = jQuery.noConflict();

        $j(document).ready(function() {
            // When the Date Wise checkbox is checked
            $j('#date_wise_checkbox').change(function() {
                if ($j(this).is(':checked')) {
                    // Show the date picker and hide month and day containers
                    $j('#date-picker-container').css('display', 'block');
                    $j('#month-container').css('display', 'none');
                    $j('#day-container').css('display', 'none');

                    // Clear values for month and day
                    $j('#month').val([]);
                    $j('#day').val([]);

                    // Set date picker as required
                    $j('#date_picker').attr('required', true);

                    // Uncheck the Day Wise checkbox
                    $j('#day_wise_checkbox').prop('checked', false);
                    // Remove required from month and day
                    $j('#month').removeAttr('required');
                    $j('#day').removeAttr('required');
                }
            });

            // When the Day Wise checkbox is checked
            $j('#day_wise_checkbox').change(function() {
                if ($j(this).is(':checked')) {
                    // Show month and day containers and hide date picker
                    $j('#month-container').css('display', 'block');
                    $j('#day-container').css('display', 'block');
                    $j('#date-picker-container').css('display', 'none');

                    // Clear value for date picker
                    $j('#date_picker').val('');

                    // Set month and day fields as required
                    $j('#month').attr('required', true);
                    $j('#day').attr('required', true);

                    // Uncheck the Date Wise checkbox
                    $j('#date_wise_checkbox').prop('checked', false);
                    // Remove required from date picker
                    $j('#date_picker').removeAttr('required');
                }
            });
            $j('#date_picker').datepicker({
                dateFormat: 'yy-mm-dd' // You can change the format as per your requirement
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#country_id').on('change', function() {
                const countryId = $(this).val();
                if (countryId) {
                    $.ajax({
                        url: '/public/get-states/' + countryId,
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            const stateSelect = $('#state_id');
                            stateSelect.empty().append(
                            '<option value="">Select State</option>');
                            $('#district_id').empty().append(
                                '<option value="">Select District</option>'); // Reset district
                            if (response.length > 0) {
                                $.each(response, function(index, state) {
                                    stateSelect.append(
                                        `<option value="${state.id}">${state.name}</option>`
                                        );
                                });
                            } else {
                                stateSelect.append('<option value="">No states found</option>');
                            }
                        }
                    });
                } else {
                    $('#state_id, #district_id').empty().append('<option value="">Select</option>');
                }
            });

            $('#state_id').on('change', function() {
                const stateId = $(this).val();
                if (stateId) {
                    $.ajax({
                        url: '/public/get-districts/' + stateId,
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            const districtSelect = $('#district_id');
                            districtSelect.empty().append(
                                '<option value="">Select District</option>');
                            if (response.length > 0) {
                                $.each(response, function(index, district) {
                                    districtSelect.append(
                                        `<option value="${district.id}">${district.name}</option>`
                                        );
                                });
                            } else {
                                districtSelect.append(
                                    '<option value="">No districts found</option>');
                            }
                        }
                    });
                } else {
                    $('#district_id').empty().append('<option value="">Select District</option>');
                }
            });

            $('#district_id').on('change', function() {
                const districtId = $(this).val();
                if (districtId) {
                    $.ajax({
                        url: '/public/get-towns/' + districtId,
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            const townsSelect = $('#city_id');
                            townsSelect.empty().append('<option value="">Select City</option>');
                            if (response.length > 0) {
                                $.each(response, function(index, towns) {
                                    townsSelect.append(
                                        `<option value="${towns.id}">${towns.name}</option>`
                                        );
                                });
                            } else {
                                townsSelect.append('<option value="">No city found</option>');
                            }
                        }
                    });
                } else {
                    $('#city_id').empty().append('<option value="">Select City</option>');
                }
            });
        });
    </script>
    <style>
        #start-time-container,
        #end-time-container {
            display: none;
        }
    </style>
