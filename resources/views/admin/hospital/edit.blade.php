@extends('admin.layout.app')

@section('title', 'Edit Hospital')

@section('content')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Edit Hospital</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hospital.index') }}">Hospitals Listing</a></li>
                    <li class="breadcrumb-item active">Edit Hospital</li>
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

        <!-- Form to Edit Hospital -->
        <div class="card" style="background-color: #d8e0f1; border-radius: 10px;">
            <div class="card-body">
                <form action="{{ route('hospital.update', $hospital->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Submit Button -->
                    <div class="mb-3 text-end">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>

                    <div class="container"
                        style="background-color: #d8e0f1; padding: 20px; border-radius: 10px; margin:20px;">
                        <div class="col-md-10 mb-3">
                            <label for="name" class="form-label">Hospital Name</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $hospital->name) }}" required>
                        </div>

                        <div class="col-md-10 mb-3">
                            <label for="address_link" class="form-label">Hospital Address Link</label>
                            <input type="text" name="address_link" class="form-control"
                                value="{{ old('address_link', $hospital->address_link) }}" required>
                        </div>

                        <!-- Country -->
                        <div class="col-md-10">
                            <div class="mb-3">
                                <label for="country_id" class="form-label">Country <span
                                        style="color: red;">*</span></label>
                                <select class="form-control" name="country_id" id="country_id" required>
                                    <option value="">Select a country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ old('country_id', $hospital->country_id) == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- State -->
                        <div class="col-md-10">
                            <div class="mb-3">
                                <label for="state_id" class="form-label">State (Part) <span
                                        style="color: red;">*</span></label>
                                <select class="form-control" name="state_id" id="state_id" required>
                                    <option value="">Select a state (part)</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}"
                                            {{ old('state_id', $hospital->state_id) == $state->id ? 'selected' : '' }}>
                                            {{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- District -->
                        <div class="col-md-10">
                            <div class="mb-3">
                                <label for="district_id" class="form-label">District <span
                                        style="color: red;">*</span></label>
                                <select name="district_id" id="district_id" class="form-select" required>
                                    <option value="">Select District</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}"
                                            {{ old('district_id', $hospital->district_id) == $district->id ? 'selected' : '' }}>
                                            {{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- City -->
                        <div class="col-md-10">
                            <div class="mb-3">
                                <label for="city_id" class="form-label">Town/Village/City Name <span
                                        style="color: red;">*</span></label>
                                <select name="city_id" id="city_id" class="form-select" required>
                                    <option value="">Select Town/Village/City Name</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ old('city_id', $hospital->city_id) == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>




                        <!-- Image Upload -->
                        <div class="col-md-4 mb-3">
                            <label for="image" class="form-label">Upload Hospital Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                                name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if ($hospital->image)
                                <div>
                                    <img src="{{ asset('/admin/uploads/hospitals/' . $hospital->image) }}"
                                        alt="Current Image" class="img-fluid mb-2" style="max-width: 100px;">
                                    <p>Current Image</p>
                                </div>
                            @else
                                <div>
                                    <img src="{{ asset('/admin/assets/adminimg/hospital.jpg') }}" alt="Default Image"
                                        class="img-fluid mb-2" style="max-width: 100px;">
                                    <p>Default Image</p>
                                </div>
                            @endif

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main><!-- End #main -->

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



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
@endsection
