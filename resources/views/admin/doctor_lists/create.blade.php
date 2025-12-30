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
  @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

  <!-- Form to Add New Doctor -->
  <div class="card" style="background-color: #d8e0f1; border-radius: 10px;">
    <div class="card-body">
      <form action="{{ route('doctors.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
         <input type="hidden" name="type" id="type" value="doctor" >
         
         <!-- Submit Button -->
        <div class="mb-3 text-end">
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
        <div class="container" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px; margin:20px;">
        <!-- Clinic Dropdown -->
          <div class="row">
              
               <!-- Doctor Name -->
        <div class="col-md-4 mb-3">
            <label for="name" class="form-label">Doctor Name <span style="color: red;">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <!-- Email -->
        <div class="col-md-4 mb-3">
            <label for="email" class="form-label">Email <span style="color: red;">*</span></label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <!-- Phone Number -->
        <div class="col-md-4 mb-3">
            <label for="phone_number" class="form-label">Phone Number <span style="color: red;">*</span></label>
            <input type="text" pattern="\d{10}" maxlength="10" minlength="10" class="form-control @error('phone_number') is-invalid @enderror"
            id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
            @error('phone_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
              
              
            
    
        
       
    
    </div>
    
    <div class="row">
        
         <!-- Country -->
            <div class="col-md-4">
                <div class="mb-2">
                    <label for="country_id" class="form-label">Country <span style="color: red;">*</span></label>
                    <select class="form-select" name="country_id" id="country_id" required>
                        <option value="">Select a country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}"  data-name="{{ $country->name }}" {{ old('country') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                        <!-- Hidden input to store country name -->
                    <input type="hidden" name="country_name" id="country_name">
                    </select>
                </div>
            </div>

            <!-- State -->
            <div class="col-md-4">
                <div class="mb-2">
                    <label for="state_id" class="form-label">State (Part) <span style="color: red;">*</span></label>
                    <select class="form-select" name="state_id" id="state_id" required>
                        <option value="">Select a state (part)</option>
                    </select>
                    <input type="hidden" name="state_name" id="state_name">
                </div>
            </div>

            <!-- District -->
            <div class="col-md-4">
                <div class="mb-2">
                    <label for="district_id" class="form-label">District <span style="color: red;">*</span></label>
                    <select name="district_id" id="district_id" class="form-select" required>
                        <option value="">Select District</option>
                    </select>
                     
                     <input type="hidden" name="district_name" id="district_name">
                </div>
            </div>
        
    </div>
            
          

    
        <!-- Name (First Name + Last Name) -->
        
   <div class="row">
        
        
        <!-- Category -->
        <div class="col-md-4">
    <div class="mb-2">
        <label for="category_id" class="form-label">Category <span style="color: red;">*</span></label>
        <select class="form-select" name="category_id[]" id="category_id" multiple required>
            @foreach ($category as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
        @error('category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <button type="button" id="addCategoryTag" class="btn btn-primary mt-2">Category Add to Tags</button>
    </div>
    
</div>
       

        <!-- Registration No -->
        <div class="col-md-4 mb-3">
            <label for="reg_no" class="form-label">Registration No</label>
            <input type="text" class="form-control @error('reg_no') is-invalid @enderror" id="reg_no" name="reg_no" value="{{ old('reg_no') }}">
            @error('reg_no')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <!-- Degree -->
        <div class="col-md-4 mb-3">
            <label for="degree" class="form-label">Degree </label>
            <input type="text" class="form-control @error('degree') is-invalid @enderror" id="degree" name="degree" value="{{ old('degree') }}" >
            @error('degree')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row">
         

        <!-- Sub-category -->
        <div class="col-md-4 mb-3">
            <label for="sub_category" class="form-label">Sub-Category</label>
            <input type="text" class="form-control @error('sub_category') is-invalid @enderror" id="sub_category" name="sub_category" value="{{ old('sub_category') }}">
            @error('sub_category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Phone Number -->
        <div class="col-md-4 mb-3">
            <label for="personal_phone_number" class="form-label">Dr. Personal Number</label>
            <input type="text" pattern="\d{10}" maxlength="10" minlength="10" class="form-control @error('personal_phone_number') is-invalid @enderror" id="personal_phone_number" name="personal_phone_number" value="{{ old('personal_phone_number') }}">
            @error('personal_phone_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="col-md-4 mb-3">
            <label for="visiting_time" class="form-label">Visiting Time</label>
            <input type="text" class="form-control @error('visiting_time') is-invalid @enderror" id="visiting_time" name="visiting_time" value="{{ old('visiting_time') }}" >
            @error('visiting_time')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

 
   
    </div>
<div class="row">
    <!-- Date Wise Checkbox -->
    <div class="col-md-2 mb-3">
        <label for="date_wise_checkbox">Date Wise:</label>
        <input type="checkbox" id="date_wise_checkbox" name="date_wise_checkbox" value="1">
    </div>

    <!-- Day Wise Checkbox -->
    <div class="col-md-2 mb-3">
        <label for="day_wise_checkbox">Day Wise:</label>
         <input type="checkbox" id="day_wise_checkbox" name="day_wise_checkbox" value="1">
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
        <input type="checkbox" id="time_checkbox" name="time_checkbox" value="1" {{ old('time_checkbox') ? 'checked' : '' }}>
    </div>

    <!-- Time Fields Container -->
    <div class="col-md-12 mb-3" id="time-fields-container" style="display: none;">
        <div id="time-fields-wrapper">
            <!-- Default Start and End Time Pair -->
            <div class="time-field-pair">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="start_time">Start Time:</label>
                        <input type="text" name="time_slot[0][start]" class="form-control start-time" value="{{ old('time_slot.0.start') }}" required>
                        @error('time_slot.0.start')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="end_time">End Time:</label>
                        <input type="text" name="time_slot[0][end]" class="form-control end-time" value="{{ old('time_slot.0.end') }}" required>
                        @error('time_slot.0.end')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <button type="button" class="btn btn-danger remove-time-pair" style="display: none;">X</button>
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
            <label for="profile_text" class="form-label">Profile Details</label>
            <textarea class="form-control @error('profile_text') is-invalid @enderror" id="profile_text" name="profile_text">{{ old('profile_text') }}</textarea>
            @error('profile_text')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
       
        <!-- Active (Yes/No) -->
        <div class="col-md-4 mb-3">
            <label for="active" class="form-label">Active </span></label>
            <select class="form-select @error('active') is-invalid @enderror" id="active" name="active" required>
                <option value="1" {{ old('active') == '1' ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('active') == '0' ? 'selected' : '' }}>No</option>
            </select>
            @error('active')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        </div>
        
        <div class="row">
            <!-- Tags Field -->
            <div class="col-md-12 mb-3">
                <label for="tags" class="form-label">Tags (comma-separated)</label>
                <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags" value="{{ old('tags') }}" placeholder="Enter tags separated by commas">
                @error('tags')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        
        <div class="row">
            <!-- Image Upload -->
            <div class="col-md-4 mb-3">
                <label for="image" class="form-label">Upload Doctor's Image</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
             <!-- Fess -->
            <div class="col-md-4 mb-3">
                <label for="fees" class="form-label">Fees</label>
                <input type="number" class="form-control @error('fees') is-invalid @enderror"
                       id="fees" name="fees" value="{{ old('fees') }}" 
                       inputmode="numeric" pattern="\d*">
                @error('fees')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div> 
            
            <!-- whatsapp -->
            <div class="col-md-4 mb-3">
               
                    <label for="fees" class="form-label">Whatsapp</label>
                <input type="text" class="form-control @error('whatsapp') is-invalid @enderror"
                       id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" 
                       >
                @error('whatsapp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>
        
        
        <div class="row">
            
             <!-- facebook -->
            <div class="col-md-4 mb-3">
                <label for="facebook" class="form-label">Facebook</label>
                <input type="text" class="form-control @error('facebook') is-invalid @enderror"
                       id="facebook" name="facebook" value="{{ old('facebook') }}">
                @error('facebook')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
             <!-- instagram -->
            <div class="col-md-4 mb-3">
                <label for="instagram" class="form-label">Instagram</label>
                <input type="text" class="form-control @error('instagram') is-invalid @enderror"
                       id="instagram" name="instagram" value="{{ old('instagram') }}">
                @error('instagram')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- website -->
            <div class="col-md-4 mb-3">
                <label for="website" class="form-label">Website</label>
                <input type="text" class="form-control @error('website') is-invalid @enderror"
                       id="website" name="website" value="{{ old('website') }}">
                @error('website')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
        </div>
        
        
        <div class="row">
            
             <!-- latitude -->
            <div class="col-md-4 mb-3">
                <label for="latitude" class="form-label">Latitude</label>
                <input type="text" class="form-control @error('latitude') is-invalid @enderror"
                       id="latitude" name="latitude" value="{{ old('latitude') }}">
                @error('latitude')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- logitude -->
            <div class="col-md-4 mb-3">
                <label for="logitude" class="form-label">Longitude</label>
                <input type="text" class="form-control @error('logitude') is-invalid @enderror"
                       id="logitude" name="logitude" value="{{ old('logitude') }}">
                @error('logitude')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- language -->
            <div class="col-md-4 mb-3">
                <label for="language" class="form-label">Language known</label>
                <input type="text" class="form-control @error('logitude') is-invalid @enderror"
                       id="language" name="language" value="{{ old('language') }}">
                @error('language')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="row">
            <!-- mode_of_payment -->
            <div class="col-md-4 mb-3">
                <label for="mode_of_payment" class="form-label">Mode of Payment Received</label>
                <input type="text" class="form-control @error('mode_of_payment') is-invalid @enderror"
                       id="mode_of_payment" name="mode_of_payment" value="{{ old('mode_of_payment') }}">
                @error('mode_of_payment')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- loc1 -->
            <div class="col-md-4 mb-3">
                <label for="loc1" class="form-label">Location One</label>
                <input type="text" class="form-control @error('loc1') is-invalid @enderror"
                       id="loc1" name="loc1" value="{{ old('loc1') }}">
                @error('loc1')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- loc2 -->
            <div class="col-md-4 mb-3">
                <label for="loc2" class="form-label">Location Two</label>
                <input type="text" class="form-control @error('loc2') is-invalid @enderror"
                       id="loc2" name="loc2" value="{{ old('loc2') }}">
                @error('loc2')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
        </div>
        
        
        <div class="row">
            <!-- loc3 -->
            <div class="col-md-4 mb-3">
                <label for="loc3" class="form-label">Location Three</label>
                <input type="text" class="form-control @error('loc3') is-invalid @enderror"
                       id="loc3" name="loc3" value="{{ old('loc3') }}">
                @error('loc3')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- loc4 -->
            <div class="col-md-4 mb-3">
                <label for="loc4" class="form-label">Location Four</label>
                <input type="text" class="form-control @error('loc4') is-invalid @enderror"
                       id="loc4" name="loc4" value="{{ old('loc4') }}">
                @error('loc4')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- loc5 -->
            <div class="col-md-4 mb-3">
                <label for="loc5" class="form-label">Location Five</label>
                <input type="text" class="form-control @error('loc5') is-invalid @enderror"
                       id="loc5" name="loc5" value="{{ old('loc5') }}">
                @error('loc5')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
        </div>
        
        <div class="row">
            
            <!-- Membership -->
            <div class="col-md-4 mb-3">
                <label for="membership" class="form-label">Membership</label>
                <input type="text" class="form-control @error('membership') is-invalid @enderror"
                       id="membership" name="membership" value="{{ old('membership') }}">
                @error('membership')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
                <div class="col-md-4 mb-3">
                         <label for="clinic_ids" class="form-label">Clinics </label>
                        <select class="form-control" name="clinic_ids" id="clinic_ids" >
                    <!-- Default option to choose a clinic -->
                    <option value="" disabled selected>Select Clinic</option>
            
                    <!-- Loop through the clinics -->
                    @foreach ($clinics as $clinic)
                        <option value="{{ $clinic->id }}" 
                            {{ (old('clinic_ids', $selectedClinics ?? '') == $clinic->id) ? 'selected' : '' }} 
                            <!--data-address="{{ $clinic->address }}"-->
                            <!--data-country="{{ $clinic->country_name }}"-->
                            <!--data-state="{{ $clinic->state_name }}"-->
                            <!--data-district="{{ $clinic->district_name }}"-->
                            <!-->-->
                            {{ $clinic->name }}
                        </option>
                    @endforeach
                </select>
            
                @error('clinic_ids')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <!--@foreach ($clinics as $clinic)-->
            <!--<input type="hidden" name="country_id" value= "{{ $clinic->country_id }}">-->
            <!--<input type="hidden" name="country_name" value= "{{ $clinic->country_name }}">-->
            <!--<input type="hidden" name="state_id" value= "{{ $clinic->state_id }}">-->
            <!--<input type="hidden" name="state_name" value= "{{ $clinic->state_name }}">-->
            <!--<input type="hidden" name="district_id" value= "{{ $clinic->district_id }}">-->
            <!--<input type="hidden" name="district_name" value= "{{ $clinic->district_name }}">-->
            <!--<input type="hidden" name="city_id" value= "{{ $clinic->city_id }}">-->
            <!--<input type="hidden" name="city_name" value= "{{ $clinic->city_name }}">-->
            <!--@endforeach-->
            
        </div>
        
          <!-- Clinic Details Display Div -->
            <div id="clinic-details" style="display: none; margin-top: 15px;">
                <p><strong>Address:</strong> <span id="clinic-address"></span></p>
                <p><strong>Country:</strong> <span id="clinic-country"></span></p>
                <p><strong>State:</strong> <span id="clinic-state"></span></p>
                <p><strong>District:</strong> <span id="clinic-district"></span></p>
            </div>

        
    </div>

   </div>
  </div>
</div>

        
      </form>
    
</main><!-- End #main -->

@endsection

<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.16.4/tagify.css">
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.16.4/tagify.min.js"></script>
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
    
    document.addEventListener("DOMContentLoaded", function () {
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
    addCategoryTagButton.addEventListener("click", function () {
        const selectedCategories = Array.from(categoryDropdown.selectedOptions).map(opt => opt.text.toLowerCase());
        let existingTags = tagsField.value.split(",").map(tag => tag.trim().toLowerCase()).filter(tag => tag);

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
                    width: '100%'  // Explicitly set Select2 width
                });
                
                $('#day').select2({
                    placeholder: "Select Day",
                    allowClear: true,
                    width: '100%'  // Explicitly set Select2 width
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
    (function ($) {
        $(document).ready(function () {
            // Show/Hide time fields based on checkbox state
            $('#time_checkbox').change(function () {
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
                const index = $('#time-fields-wrapper .time-field-pair').length;
                const timePairHtml = `
                    <div class="time-field-pair">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="start_time_${index}">Start Time:</label>
                                <input type="text" name="time_slot[${index}][start]" class="form-control start-time" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="end_time_${index}">End Time:</label>
                                <input type="text" name="time_slot[${index}][end]" class="form-control end-time" required>
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
                $('.start-time, .end-time').each(function () {
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
            $('#add-time-pair').click(function () {
                addTimeFieldPair();
            });

            // Remove button functionality
            $(document).on('click', '.remove-time-pair', function () {
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
        // Capture Country Name
        $('#country_id').on('change', function() {
            var selectedOption = $(this).find(':selected');
            $('#country_name').val(selectedOption.data('name'));

            const countryId = $(this).val();
            if (countryId) {
                $.ajax({
                    url: '/public/get-states/' + countryId,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        const stateSelect = $('#state_id');
                        stateSelect.empty().append('<option value="">Select State</option>');
                        $('#district_id').empty().append('<option value="">Select District</option>'); // Reset district
                        if (response.length > 0) {
                            $.each(response, function(index, state) {
                                stateSelect.append(`<option value="${state.id}" data-name="${state.name}">${state.name}</option>`);
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

        // Capture State Name
        $('#state_id').on('change', function() {
            var selectedOption = $(this).find(':selected');
            $('#state_name').val(selectedOption.data('name'));

            const stateId = $(this).val();
            if (stateId) {
                $.ajax({
                    url: '/public/get-districts/' + stateId,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        const districtSelect = $('#district_id');
                        districtSelect.empty().append('<option value="">Select District</option>');
                        if (response.length > 0) {
                            $.each(response, function(index, district) {
                                districtSelect.append(`<option value="${district.id}" data-name="${district.name}">${district.name}</option>`);
                            });
                        } else {
                            districtSelect.append('<option value="">No districts found</option>');
                        }
                    }
                });
            } else {
                $('#district_id').empty().append('<option value="">Select District</option>');
                $('#district_name').val('');
            }
        });

        // Capture District Name
        $('#district_id').on('change', function() {
            var selectedOption = $(this).find(':selected');
            $('#district_name').val(selectedOption.data('name'));
        });
    });
</script>



<script>
    document.getElementById('country_id').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        document.getElementById('country_name').value = selectedOption.getAttribute('data-name') || '';
    });
</script>
<style>
    #start-time-container, #end-time-container {
    display: none;
}


    /* Hide arrows for number inputs in all browsers */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>

