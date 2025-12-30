@extends('admin.layout.app')

@section('content')
<div class="pagetitle">
    <h1>Add Doctor</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.doctors.index') }}">Doctors</a></li>
            <li class="breadcrumb-item active">Add Doctor</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Doctor Information</h5>
                    
                    <form id="doctorForm" method="POST" action="{{ route('admin.doctors.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Doctor Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">Phone Number *</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone_number_2" class="form-label">Alternative Phone</label>
                                    <input type="text" class="form-control" id="phone_number_2" name="phone_number_2">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="speciality" class="form-label">Speciality *</label>
                                    <input type="text" class="form-control" id="speciality" name="speciality" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="degree" class="form-label">Degree</label>
                                    <input type="text" class="form-control" id="degree" name="degree">
                                </div>
                            </div>
                            
                            <!-- Registration Details -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="registration_no" class="form-label">Registration Number</label>
                                    <input type="text" class="form-control" id="registration_no" name="registration_no">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="council" class="form-label">Council</label>
                                    <input type="text" class="form-control" id="council" name="council">
                                </div>
                            </div>
                            
                            <!-- Location Information -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="country_id" class="form-label">Country *</label>
                                    <select class="form-select select2" id="country_id" name="country_id" required>
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="state_id" class="form-label">State *</label>
                                    <select class="form-select select2" id="state_id" name="state_id" required>
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="district_id" class="form-label">District</label>
                                    <select class="form-select select2" id="district_id" name="district_id">
                                        <option value="">Select District</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city_id" class="form-label">City *</label>
                                    <select class="form-select select2" id="city_id" name="city_id" required>
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pincode" class="form-label">Pincode</label>
                                    <input type="text" class="form-control" id="pincode" name="pincode">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                                </div>
                            </div>
                            
                            <!-- Category and Clinic -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category *</label>
                                    <select class="form-select select2" id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="clinic_id" class="form-label">Clinic</label>
                                    <select class="form-select select2" id="clinic_id" name="clinic_id">
                                        <option value="">Select Clinic</option>
                                        @foreach($clinics as $clinic)
                                            <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Additional Information -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="experience_years" class="form-label">Experience (Years)</label>
                                    <input type="number" class="form-control" id="experience_years" name="experience_years" min="0" max="100">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="languages" class="form-label">Languages (comma separated)</label>
                                    <input type="text" class="form-control" id="languages" name="languages" placeholder="English, Hindi, Bengali">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="website" class="form-label">Website</label>
                                    <input type="url" class="form-control" id="website" name="website" placeholder="https://example.com">
                                </div>
                            </div>
                            
                            <!-- Social Media -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="whatsapp" class="form-label">WhatsApp Number</label>
                                    <input type="text" class="form-control" id="whatsapp" name="whatsapp">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="facebook" class="form-label">Facebook</label>
                                    <input type="text" class="form-control" id="facebook" name="facebook" placeholder="Facebook profile URL">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="instagram" class="form-label">Instagram</label>
                                    <input type="text" class="form-control" id="instagram" name="instagram" placeholder="Instagram profile URL">
                                </div>
                            </div>
                            
                            <!-- Profile Picture -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="profile_picture" class="form-label">Profile Picture</label>
                                    <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
                                    <small class="text-muted">Max size: 4MB (JPG, PNG, GIF)</small>
                                </div>
                            </div>
                            
                            <!-- Status and Consultation Mode -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active" selected>Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="consultation_mode" class="form-label">Consultation Mode *</label>
                                    <select class="form-select" id="consultation_mode" name="consultation_mode" required>
                                        <option value="face-to-face" selected>Face to Face</option>
                                        <option value="online">Online</option>
                                        <option value="both">Both</option>
                                        <option value="offline">Offline</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Clinic Schedule Section -->
                            <div class="col-12">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Clinic Schedule</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="scheduleContainer">
                                            <!-- Schedules will be added here -->
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addScheduleBtn">
                                            <i class="bi bi-plus-circle"></i> Add Schedule
                                        </button>
                                        <input type="hidden" id="schedules_json" name="schedules_json">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Profile Details -->
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="profile_details" class="form-label">Profile Details</label>
                                    <textarea class="form-control summernote" id="profile_details" name="profile_details" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Save Doctor
                            </button>
                            <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Schedule Template (Hidden) -->
<div id="scheduleTemplate" class="d-none">
    <div class="schedule-item card mb-2">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-2">
                        <label class="form-label">Clinic</label>
                        <select class="form-select clinic-select">
                            <option value="">Select Clinic</option>
                            @foreach($clinics as $clinic)
                                <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-2">
                        <label class="form-label">Days</label>
                        <select class="form-select days-select" multiple>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-2">
                        <label class="form-label">Start Time</label>
                        <input type="time" class="form-control start-time">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-2">
                        <label class="form-label">End Time</label>
                        <input type="time" class="form-control end-time">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="mb-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-schedule" style="height: 38px;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        width: '100%'
    });
    
    // Initialize Summernote
    $('.summernote').summernote({
        height: 200,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview']]
        ]
    });
    
    // Country change - load states
    $('#country_id').on('change', function() {
        var countryId = $(this).val();
        if (countryId) {
            $('#state_id').empty().append('<option value="">Loading...</option>');
            $.get('/admin/doctors/states/' + countryId, function(data) {
                $('#state_id').empty().append('<option value="">Select State</option>');
                $.each(data, function(key, value) {
                    $('#state_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            });
        } else {
            $('#state_id').empty().append('<option value="">Select State</option>');
            $('#district_id').empty().append('<option value="">Select District</option>');
            $('#city_id').empty().append('<option value="">Select City</option>');
        }
    });
    
    // State change - load districts
    $('#state_id').on('change', function() {
        var stateId = $(this).val();
        if (stateId) {
            $('#district_id').empty().append('<option value="">Loading...</option>');
            $.get('/admin/doctors/districts/' + stateId, function(data) {
                $('#district_id').empty().append('<option value="">Select District</option>');
                $.each(data, function(key, value) {
                    $('#district_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            });
        } else {
            $('#district_id').empty().append('<option value="">Select District</option>');
            $('#city_id').empty().append('<option value="">Select City</option>');
        }
    });
    
    // District change - load cities
    $('#district_id').on('change', function() {
        var districtId = $(this).val();
        if (districtId) {
            $('#city_id').empty().append('<option value="">Loading...</option>');
            $.get('/admin/doctors/cities/' + districtId, function(data) {
                $('#city_id').empty().append('<option value="">Select City</option>');
                $.each(data, function(key, value) {
                    $('#city_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            });
        } else {
            $('#city_id').empty().append('<option value="">Select City</option>');
        }
    });
    
    // Category change - load clinics
    $('#category_id').on('change', function() {
        var categoryId = $(this).val();
        if (categoryId) {
            $('#clinic_id').empty().append('<option value="">Loading...</option>');
            $.get('/admin/doctors/clinics/' + categoryId, function(data) {
                $('#clinic_id').empty().append('<option value="">Select Clinic</option>');
                $.each(data, function(key, value) {
                    $('#clinic_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            });
        }
    });
    
    // Schedule management
    var scheduleCount = 0;
    
    function addSchedule() {
        scheduleCount++;
        var template = $('#scheduleTemplate').html();
        var newSchedule = $(template);
        newSchedule.find('.clinic-select').select2({ width: '100%' });
        newSchedule.find('.days-select').select2({
            width: '100%',
            placeholder: 'Select days'
        });
        $('#scheduleContainer').append(newSchedule);
    }
    
    $('#addScheduleBtn').on('click', function() {
        addSchedule();
    });
    
    $(document).on('click', '.remove-schedule', function() {
        $(this).closest('.schedule-item').remove();
    });
    
    // Form submission
    $('#doctorForm').on('submit', function(e) {
        e.preventDefault();
        
        // Collect schedule data
        var schedules = [];
        $('.schedule-item').each(function() {
            var clinicId = $(this).find('.clinic-select').val();
            var clinicName = $(this).find('.clinic-select option:selected').text();
            var days = $(this).find('.days-select').val();
            var startTime = $(this).find('.start-time').val();
            var endTime = $(this).find('.end-time').val();
            
            if (clinicId || clinicName || days || startTime || endTime) {
                schedules.push({
                    clinic_id: clinicId,
                    clinic_name: clinicName,
                    days: days,
                    start_time: startTime,
                    end_time: endTime
                });
            }
        });
        
        $('#schedules_json').val(JSON.stringify(schedules));
        
        // Submit form via AJAX
        var formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = "{{ route('admin.doctors.index') }}";
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        alert(value[0]);
                    });
                }
            }
        });
    });
});
</script>
@endsection