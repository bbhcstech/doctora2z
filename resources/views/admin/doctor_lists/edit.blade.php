@extends('admin.layout.app')

@section('title', 'Edit Doctor')

@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Edit Doctor</h1>
        </div><!-- End Page Title -->

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form to Add a New Doctor -->
        <form action="{{ route('doctors.update', $doctor->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- This will send a PUT request -->
            <div style="text-align: right;">
                <button type="submit" class="btn btn-success mt-3">Update</button>
            </div>
            <input type="hidden" name="type" id="type" value="doctor">
            <!-- Clinic Dropdown -->
            <div class="container" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px; margin:20px;">
                <!-- Clinic Dropdown -->
                <div class="row">

                    <!-- Name (Full Name) -->
                    <div class="col-md-4 mb-3">
                        <label for="name" class="form-label">Doctor Name <span style="color: red;">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $doctor->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-md-4 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email"
                            value="{{ $doctor->email ?? '' }}">
                    </div>

                    <!-- Phone Number -->
                    <div class="col-md-4 mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" pattern="\d{10}" maxlength="10" minlength="10"
                            class="form-control @error('phone_number') is-invalid @enderror" id="phone_number"
                            name="phone_number" value="{{ old('phone_number', $doctor->phone_number) }}">
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>



                </div>

                <!-- Clinic Details Display Div -->
                <!--<div id="clinic-details" style="display: none; margin-top: 15px;">-->
                <!--  <p><strong>Address:</strong> <span id="clinic-address"></span></p>-->
                <!--  <p><strong>Country:</strong> <span id="clinic-country"></span></p>-->
                <!--  <p><strong>State:</strong> <span id="clinic-state"></span></p>-->
                <!--  <p><strong>District:</strong> <span id="clinic-district"></span></p>-->
                <!--</div>-->

                <div class="row">

                    <!-- Country Dropdown -->
                    <div class="col-md-4">
                        <div class="mb-2">
                            <label for="country" class="form-label">Country <span style="color: red;">*</span></label>
                            <select class="form-control @error('country') is-invalid @enderror" name="country_id"
                                id="country_id" required oninvalid="this.setCustomValidity('Please select country')"
                                oninput="this.setCustomValidity('')">
                                <option value="">Select a country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" data-name="{{ $country->name }}"
                                        {{ old('country_id', $doctor->country_id) == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>

                            <input type="hidden" name="country_name" id="country_name">
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- State Dropdown -->
                    <div class="col-md-4">
                        <div class="mb-2">
                            <label for="state" class="form-label">State (Part) <span style="color: red;">*</span></label>
                            <select class="form-control @error('state') is-invalid @enderror" name="state_id" id="state_id"
                                required oninvalid="this.setCustomValidity('Please select state')"
                                oninput="this.setCustomValidity('')">
                                <option value="">Select a state</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}"
                                        {{ old('state_id', $doctor->state_id) == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="state_name" id="state_name">
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- District Dropdown -->
                    <div class="col-md-4">
                        <div class="mb-2">
                            <label for="district_id" class="form-label">District <span style="color: red;">*</span></label>
                            <select name="district_id" id="district_id" class="form-select" required
                                oninvalid="this.setCustomValidity('Please select district')"
                                oninput="this.setCustomValidity('')">
                                <option value="">Select District</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}"
                                        {{ old('district_id', $doctor->district_id) == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="district_name" id="district_name">
                        </div>
                    </div>

                </div>


                <div class="row">
                    <!-- Category Dropdown -->
                    <div class="col-md-4 mb-3">
                        <label for="category_id" class="form-label">Category <span style="color: red;">*</span></label>
                        <select class="form-control" name="category_id[]" id="category_id" multiple required>
                            @foreach ($category as $cat)
                                <option value="{{ $cat->id }}" @if (in_array($cat->id, explode(',', $doctor->category_id))) selected @endif>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <button type="button" id="addCategoryTag" class="btn btn-primary mt-2">Category Add to
                            Tags</button>
                    </div>




                    <!-- Reg No -->
                    <div class="col-md-4 mb-3">
                        <label for="reg_no" class="form-label">Registration No </label>

                        <!-- Pre-populate the value with the doctor's current reg_no or old input if validation fails -->
                        <input type="text" class="form-control @error('reg_no') is-invalid @enderror" id="reg_no"
                            name="reg_no" value="{{ old('reg_no', $doctor->reg_no) }}">

                        <!-- Display validation error message for reg_no -->
                        @error('reg_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <!-- Degree -->
                    <div class="col-md-4 mb-3">
                        <label for="degree" class="form-label">Degree</label>
                        <input type="text" class="form-control @error('degree') is-invalid @enderror" id="degree"
                            name="degree" value="{{ old('degree', $doctor->degree) }}">
                        @error('degree')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <div class="row">

                    <!-- Sub-category -->
                    <div class="col-md-4 mb-3">
                        <label for="sub_category" class="form-label">Sub-Category</label>
                        <input type="text" class="form-control @error('sub_category') is-invalid @enderror"
                            id="sub_category" name="sub_category"
                            value="{{ old('sub_category', $doctor->sub_category) }}">
                        @error('sub_category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-md-4 mb-3">
                        <label for="personal_phone_number" class="form-label">Personal Phone Number</label>
                        <input type="text" pattern="\d{10}" maxlength="10" minlength="10"
                            class="form-control @error('personal_phone_number') is-invalid @enderror"
                            id="personal_phone_number" name="personal_phone_number"
                            value="{{ old('personal_phone_number', $doctor->personal_phone_number) }}">
                        @error('personal_phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="personal_phone_number" class="form-label">Visiting Time</label>
                        <input type="text" class="form-control @error('visiting_time') is-invalid @enderror"
                            id="visiting_time" name="visiting_time"
                            value="{{ old('visiting_time', $doctor->visiting_time) }}">
                        @error('visiting_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>




                <div class="row">
                    <!-- Date Wise Checkbox -->
                    <div class="col-md-2 mb-3">
                        <label for="date_wise_checkbox">Date Wise:</label>
                        <input type="checkbox" id="date_wise_checkbox" name="date_wise_checkbox" value="1"
                            {{ old('date_wise_checkbox', $doctor->date_wise_checkbox == 1) ? 'checked' : '' }}>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="day_wise_checkbox">Day Wise:</label>
                        <input type="checkbox" id="day_wise_checkbox" name="day_wise_checkbox" value="1"
                            {{ old('day_wise_checkbox', $doctor->day_wise_checkbox == 1) ? 'checked' : '' }}>
                    </div>

                    <!-- Date Picker (Date Wise) -->
                    <div class="col-md-4 mb-3" id="date-picker-container"
                        style="display: {{ old('date_wise_checkbox', $doctor->date_wise_checkbox) ? 'block' : 'none' }};">
                        <label for="date_picker">Select Date:</label>
                        <input type="text" id="date_picker" name="date_picker" class="form-control"
                            value="{{ old('date_picker', $doctor->date_picker) }}" />
                        @error('date_picker')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Month Select (Day Wise) -->
                    <div class="col-md-4 mb-3" id="month-container"
                        style="display: {{ old('day_wise_checkbox', $doctor->day_wise_checkbox) ? 'block' : 'none' }};">
                        <label for="month">Month:</label>
                        <select id="month" name="month[]" class="form-control w-100" multiple>
                            @php
                                $selectedMonths = json_decode($doctor->month, true) ?? [];
                                $months = [
                                    'january' => 'January',
                                    'february' => 'February',
                                    'march' => 'March',
                                    'april' => 'April',
                                    'may' => 'May',
                                    'june' => 'June',
                                    'july' => 'July',
                                    'august' => 'August',
                                    'september' => 'September',
                                    'october' => 'October',
                                    'november' => 'November',
                                    'december' => 'December',
                                ];
                            @endphp

                            <option value="all" {{ in_array('all', $selectedMonths) ? 'selected' : '' }}>All</option>

                            @foreach ($months as $monthValue => $monthName)
                                <option value="{{ $monthValue }}"
                                    {{ in_array($monthValue, $selectedMonths) ? 'selected' : '' }}>{{ $monthName }}
                                </option>
                            @endforeach
                        </select>
                        @error('month')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Day Select (Day Wise) -->
                    <div class="col-md-4 mb-3" id="day-container"
                        style="display: {{ old('day_wise_checkbox', $doctor->day_wise_checkbox) ? 'block' : 'none' }};">
                        <label for="day">Day:</label>
                        <select id="day" name="day[]" class="form-control w-100" multiple>
                            @php
                                $selectedDays = json_decode($doctor->day, true) ?? [];
                                $days = [
                                    'everyday' => 'Everyday',
                                    'monday' => 'Monday',
                                    'tuesday' => 'Tuesday',
                                    'wednesday' => 'Wednesday',
                                    'thursday' => 'Thursday',
                                    'friday' => 'Friday',
                                    'saturday' => 'Saturday',
                                    'sunday' => 'Sunday',
                                ];
                            @endphp

                            <option value="everyday" {{ in_array('everyday', $selectedDays) ? 'selected' : '' }}>Everyday
                            </option>

                            @foreach ($days as $dayValue => $dayName)
                                <option value="{{ $dayValue }}"
                                    {{ in_array($dayValue, $selectedDays) ? 'selected' : '' }}>{{ $dayName }}
                                </option>
                            @endforeach
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
                            {{ old('time_checkbox', $doctor->time_checkbox) == 1 ? 'checked' : '' }}>
                    </div>

                    <!-- Time Fields Container -->
                    <div class="col-md-12 mb-3" id="time-fields-container"
                        style="display: {{ old('time_checkbox', $doctor->time_checkbox) ? 'block' : 'none' }};">
                        <div id="time-fields-wrapper">
                            <!-- Render existing Start and End Time Pairs -->
                            @php
                                // Exploding the time_slot string to handle the individual slots
                                $timeSlots = old('time_slot', $doctor->time_slot)
                                    ? explode(', ', $doctor->time_slot)
                                    : [];
                            @endphp

                            @foreach ($timeSlots as $index => $timeSlot)
                                @php
                                    $times = explode(' - ', $timeSlot); // Split each time slot into start and end
                                @endphp
                                <div class="time-field-pair">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="start_time_{{ $index }}">Start Time:</label>
                                            <input type="text" name="time_slot[{{ $index }}][start]"
                                                class="form-control start-time" value="{{ $times[0] ?? '' }}" required>
                                            @error('time_slot.' . $index . '.start')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="end_time_{{ $index }}">End Time:</label>
                                            <input type="text" name="time_slot[{{ $index }}][end]"
                                                class="form-control end-time" value="{{ $times[1] ?? '' }}" required>
                                            @error('time_slot.' . $index . '.end')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <button type="button" class="btn btn-danger remove-time-pair">X</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Add More Button -->
                        <button type="button" id="add-time-pair" class="btn btn-primary">+</button>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-8 mb-3">
                        <label for="profile_text">Profile Details</label>
                        <textarea class="form-control" name="profile_text" id="profile_text">{{ $doctor->profile_text }}</textarea>
                    </div>
                </div>

                <div class="row">
                    <!-- Tags Field -->
                    <div class="col-md-12 mb-3">
                        <label for="tags" class="form-label">Tags (comma-separated)</label>
                        <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags"
                            name="tags" value="{{ $doctor->tags }}" placeholder="Enter tags separated by commas">
                        @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <!--<div class="form-group">-->
                    <!--  <label for="rating">Rating</label>-->
                    <!--  <input type="number" class="form-control" name="rating" id="rating" step="0.1" min="1" max="5" value="{{ $doctor->rating }}">-->
                    <!--</div>-->
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="active">Active</label>
                            <select class="form-control" name="active" id="active">
                                <option value="1" {{ $doctor->active == 1 ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ $doctor->active == 0 ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="last_update">Last Update</label>
                            <input type="date" class="form-control" name="last_update" id="last_update"
                                value="{{ \Carbon\Carbon::parse($doctor->last_update)->format('Y-m-d') }}">
                        </div>
                    </div>


                    <!-- Doctor's Image Upload -->
                    <div class="col-md-4 mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                            name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if ($doctor->image)
                            <div>
                                <img src="{{ asset('/admin/uploads/doctor/' . $doctor->image) }}" alt="Current Image"
                                    class="img-fluid mb-2" style="max-width: 100px;">
                                <p>Current Image</p>
                            </div>
                        @else
                            <div>
                                <img src="{{ asset('/admin/assets/adminimg/demo_doctor_image.jpeg') }}"
                                    alt="Default Image" class="img-fluid mb-2" style="max-width: 100px;">
                                <p>Default Image</p>
                            </div>
                        @endif

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="clinic_ids" class="form-label">Clinics </label>
                        <select class="form-control" name="clinic_ids" id="clinic_ids">
                            <option value="" disabled>Select Clinic</option>

                            @foreach ($clinics as $clinic)
                                <option value="{{ $clinic->id }}"
                                    {{ old('clinic_ids', $doctor->clinic_id ?? '') == $clinic->id ? 'selected' : '' }}
                                    <!--data-address="{{ $clinic->address }}"-->
                                    <!--data-country="{{ $clinic->country_name }}"-->
                                    <!--data-state="{{ $clinic->state_name }}"-->
                                    <!--data-district="{{ $clinic->district_name }}"-->
                                    >
                                    {{ $clinic->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('clinic_ids')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Fess -->
                    <div class="col-md-4 mb-3">
                        <label for="fees" class="form-label">Dr. Fees</label>
                        <input type="number" class="form-control @error('fees') is-invalid @enderror" id="fees"
                            name="fees" value="{{ $doctor->fees }}" inputmode="numeric" pattern="\d*">
                        @error('fees')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- whatsapp -->
                    <div class="col-md-4 mb-3">

                        <label for="fees" class="form-label">Dr. whatsapp</label>
                        <input type="text" class="form-control @error('whatsapp') is-invalid @enderror"
                            id="whatsapp" name="whatsapp" value="{{ $doctor->whatsapp }}">
                        @error('whatsapp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="row">

                    <!-- facebook -->
                    <div class="col-md-4 mb-3">
                        <label for="facebook" class="form-label">Dr.Facebook</label>
                        <input type="text" class="form-control @error('facebook') is-invalid @enderror"
                            id="facebook" name="facebook" value="{{ old('facebook', $doctor->facebook) }}">
                        @error('facebook')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- instagram -->
                    <div class="col-md-4 mb-3">
                        <label for="instagram" class="form-label">Dr.Instagram</label>
                        <input type="text" class="form-control @error('instagram') is-invalid @enderror"
                            id="instagram" name="instagram" value="{{ old('instagram', $doctor->instagram) }}">
                        @error('instagram')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- website -->
                    <div class="col-md-4 mb-3">
                        <label for="website" class="form-label">Dr.Website</label>
                        <input type="text" class="form-control @error('website') is-invalid @enderror" id="website"
                            name="website" value="{{ old('website', $doctor->website) }}">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>


                <div class="row">

                    <!-- latitude -->
                    <div class="col-md-4 mb-3">
                        <label for="latitude" class="form-label">Dr.Latitude</label>
                        <input type="text" class="form-control @error('latitude') is-invalid @enderror"
                            id="latitude" name="latitude" value="{{ old('latitude', $doctor->latitude) }}">
                        @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- logitude -->
                    <div class="col-md-4 mb-3">
                        <label for="logitude" class="form-label">Dr.Logitude</label>
                        <input type="text" class="form-control @error('logitude') is-invalid @enderror"
                            id="logitude" name="logitude" value="{{ old('logitude', $doctor->logitude) }}">
                        @error('logitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- language -->
                    <div class="col-md-4 mb-3">
                        <label for="language" class="form-label">Dr.Language known</label>
                        <input type="text" class="form-control @error('logitude') is-invalid @enderror"
                            id="language" name="language" value="{{ old('language', $doctor->language) }}">
                        @error('language')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- mode_of_payment -->
                    <div class="col-md-4 mb-3">
                        <label for="mode_of_payment" class="form-label">Dr. Mode of Payment Received</label>
                        <input type="text" class="form-control @error('mode_of_payment') is-invalid @enderror"
                            id="mode_of_payment" name="mode_of_payment"
                            value="{{ old('mode_of_payment', $doctor->mode_of_payment) }}">
                        @error('mode_of_payment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- loc1 -->
                    <div class="col-md-4 mb-3">
                        <label for="loc1" class="form-label">Dr. Location One</label>
                        <input type="text" class="form-control @error('loc1') is-invalid @enderror" id="loc1"
                            name="loc1" value="{{ old('loc1', $doctor->loc1) }}">
                        @error('loc1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- loc2 -->
                    <div class="col-md-4 mb-3">
                        <label for="loc2" class="form-label">Dr. Location Two</label>
                        <input type="text" class="form-control @error('loc2') is-invalid @enderror" id="loc2"
                            name="loc2" value="{{ old('loc2', $doctor->loc2) }}">
                        @error('loc2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>


                <div class="row">
                    <!-- loc3 -->
                    <div class="col-md-4 mb-3">
                        <label for="loc3" class="form-label">Dr. Location Three</label>
                        <input type="text" class="form-control @error('loc3') is-invalid @enderror" id="loc3"
                            name="loc3" value="{{ old('loc3', $doctor->loc3) }}">
                        @error('loc3')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- loc4 -->
                    <div class="col-md-4 mb-3">
                        <label for="loc4" class="form-label">Dr. Location Four</label>
                        <input type="text" class="form-control @error('loc4') is-invalid @enderror" id="loc4"
                            name="loc4" value="{{ old('loc4', $doctor->loc4) }}">
                        @error('loc4')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- loc5 -->
                    <div class="col-md-4 mb-3">
                        <label for="loc5" class="form-label">Dr. Location Five</label>
                        <input type="text" class="form-control @error('loc5') is-invalid @enderror" id="loc5"
                            name="loc5" value="{{ old('loc5', $doctor->loc5) }}">
                        @error('loc5')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="row">

                    <!-- Membership -->
                    <div class="col-md-4 mb-3">
                        <label for="membership" class="form-label">Dr. Membership</label>
                        <input type="text" class="form-control @error('membership') is-invalid @enderror"
                            id="membership" name="membership" value="{{ old('membership', $doctor->membership) }}">
                        @error('membership')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>



                </div>
            </div>

        </form>

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


        document.addEventListener("DOMContentLoaded", function() {
            const tagsField = document.getElementById("tags");
            const clinicDropdown = document.getElementById("clinic_ids");
            const emailField = document.getElementById("email");
            const typeField = document.getElementById("type");
            const nameField = document.getElementById("name");
            const categoryDropdown = document.getElementById("category_id");
            const subCategoryField = document.getElementById("sub_category");
            const monthDropdown = document.getElementById("month");
            const dayDropdown = document.getElementById("day");
            const addCategoryTagButton = document.getElementById("addCategoryTag");

            function updateTags() {
                let tags = [];

                // Clinic Info
                const selectedClinic = clinicDropdown.options[clinicDropdown.selectedIndex];
                if (selectedClinic) {
                    tags.push(
                        selectedClinic.dataset.name?.toLowerCase(),
                        selectedClinic.dataset.country?.toLowerCase(),
                        selectedClinic.dataset.state?.toLowerCase(),
                        selectedClinic.dataset.district?.toLowerCase(),
                        selectedClinic.dataset.city?.toLowerCase()
                    );
                }

                // Always include the type field's value as lowercase
                if (typeField.value) {
                    tags.push(typeField.value.toLowerCase());
                }

                // Email
                if (emailField.value) tags.push(emailField.value.toLowerCase());

                // Doctor Name
                if (nameField.value) tags.push(nameField.value.toLowerCase());

                // Sub-category
                if (subCategoryField.value) tags.push(subCategoryField.value.toLowerCase());

                // Month
                const selectedMonths = Array.from(monthDropdown.selectedOptions).map(opt => opt.text.toLowerCase());
                tags = tags.concat(selectedMonths);

                // Day
                const selectedDays = Array.from(dayDropdown.selectedOptions).map(opt => opt.text.toLowerCase());
                tags = tags.concat(selectedDays);

                // Set tags field
                tagsField.value = tags.filter(Boolean).join(", ");
            }

            // Add Category Tags on Button Click
            addCategoryTagButton.addEventListener("click", function() {
                const selectedCategories = Array.from(categoryDropdown.selectedOptions).map(opt => opt.text
                    .toLowerCase());
                let existingTags = tagsField.value.split(",").map(tag => tag.trim().toLowerCase()).filter(
                    tag => tag);

                selectedCategories.forEach(category => {
                    if (!existingTags.includes(category)) {
                        existingTags.push(category);
                    }
                });

                tagsField.value = existingTags.join(", ");
            });

            // Event Listeners
            clinicDropdown.addEventListener("change", updateTags);
            emailField.addEventListener("input", updateTags);
            nameField.addEventListener("input", updateTags);
            subCategoryField.addEventListener("input", updateTags);
            monthDropdown.addEventListener("change", updateTags);
            dayDropdown.addEventListener("change", updateTags);
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
                        addTimeFieldPair(); // Re-add default time pair
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
                    initializeTimePickers();
                }

                // Initialize time pickers
                function initializeTimePickers() {
                    $('.start-time, .end-time').each(function() {
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

                // Initialize the time picker for existing fields
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
                } else {
                    // When unchecked, hide the date picker
                    $j('#date-picker-container').css('display', 'none');
                    $j('#date_picker').val(''); // Clear the value
                    $j('#date_picker').removeAttr('required');
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
                } else {
                    // When unchecked, hide the month and day selectors
                    $j('#month-container').css('display', 'none');
                    $j('#day-container').css('display', 'none');

                    // Clear month and day values
                    $j('#month').val([]);
                    $j('#day').val([]);

                    // Remove required from month and day
                    $j('#month').removeAttr('required');
                    $j('#day').removeAttr('required');
                }
            });

            // Initialize date picker for date selection
            $j('#date_picker').datepicker({
                dateFormat: 'yy-mm-dd' // You can change the format as per your requirement
            });
        });
    </script>
    <script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {
            // Function to set hidden input values
            function updateHiddenField(selectId, hiddenInputId) {
                var selectedOption = $(selectId).find(':selected');
                var name = selectedOption.data('name') || selectedOption.text();
                $(hiddenInputId).val(name);
            }

            // ✅ Set Country, State, and District Names on Page Load
            updateHiddenField('#country_id', '#country_name');
            updateHiddenField('#state_id', '#state_name');
            updateHiddenField('#district_id', '#district_name');

            let selectedCountry = $('#country_id').val();
            let selectedState = $('#state_id').val();
            let selectedDistrict = $('#district_id').val();

            // ✅ If a country is already selected, load states
            if (selectedCountry) {
                $.ajax({
                    url: '/public/get-states/' + selectedCountry,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        const stateSelect = $('#state_id');
                        stateSelect.empty().append('<option value="">Select State</option>');

                        if (response.length > 0) {
                            $.each(response, function(index, state) {
                                let isSelected = state.id == selectedState ? 'selected' : '';
                                stateSelect.append(
                                    `<option value="${state.id}" data-name="${state.name}" ${isSelected}>${state.name}</option>`
                                    );
                            });

                            // ✅ If state is already selected, load districts
                            if (selectedState) {
                                $('#state_id').trigger('change');
                            }
                        } else {
                            stateSelect.append('<option value="">No states found</option>');
                        }
                    }
                });
            }

            // ✅ Capture Country Name and Load States Dynamically
            $('#country_id').on('change', function() {
                updateHiddenField('#country_id', '#country_name');
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
                            $('#state_name').val('');
                            $('#district_name').val('');

                            if (response.length > 0) {
                                $.each(response, function(index, state) {
                                    stateSelect.append(
                                        `<option value="${state.id}" data-name="${state.name}">${state.name}</option>`
                                        );
                                });
                            } else {
                                stateSelect.append('<option value="">No states found</option>');
                            }
                        }
                    });
                } else {
                    $('#state_id, #district_id').empty().append('<option value="">Select</option>');
                    $('#state_name').val('');
                    $('#district_name').val('');
                }
            });

            // ✅ Capture State Name and Load Districts Dynamically
            $('#state_id').on('change', function() {
                updateHiddenField('#state_id', '#state_name');
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
                            $('#district_name').val('');

                            if (response.length > 0) {
                                $.each(response, function(index, district) {
                                    let isSelected = district.id == selectedDistrict ?
                                        'selected' : '';
                                    districtSelect.append(
                                        `<option value="${district.id}" data-name="${district.name}" ${isSelected}>${district.name}</option>`
                                        );
                                });

                                // ✅ If district is already selected, update hidden field
                                updateHiddenField('#district_id', '#district_name');
                            } else {
                                districtSelect.append(
                                    '<option value="">No districts found</option>');
                            }
                        }
                    });
                } else {
                    $('#district_id').empty().append('<option value="">Select District</option>');
                    $('#district_name').val('');
                }
            });

            // ✅ Capture District Name
            $('#district_id').on('change', function() {
                updateHiddenField('#district_id', '#district_name');
            });
        });
    </script>



    <style>
        #start-time-container,
        #end-time-container {
            display: none;
        }
    </style>
