@extends('admin.layout.app')

@section('title', 'Edit Advertisement')

@section('content')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Edit Advertisement</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('advertisement.index') }}">Advertisement</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <div class="card" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px;">
            <div class="card-body">
                <h5 class="card-title">Edit Advertisement</h5>
                <form action="{{ route('advertisement.update', $advertisement->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $advertisement->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Country -->
                    <div class="mb-3">
                        <label for="country_id" class="form-label">Country <span style="color: red;">*</span></label>
                        <select class="form-control" name="country_id" id="country_id" required>
                            <option value="">Select a country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}"
                                    {{ $advertisement->country_id == $country->id ? 'selected' : '' }}>{{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- State -->
                    <div class="mb-3">
                        <label for="state_id" class="form-label">State (Part) <span style="color: red;">*</span></label>
                        <select class="form-control" name="state_id" id="state_id" required>
                            <option value="">Select a state (part)</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->id }}"
                                    {{ $advertisement->state_id == $state->id ? 'selected' : '' }}>{{ $state->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- District -->
                    <!--<div class="mb-3">-->
                    <!--  <label for="district_id" class="form-label">District/Town/Village/City <span style="color: red;">*</span></label>-->
                    <!--  <select name="district_id" id="district_id" class="form-select" required>-->
                    <!--    <option value="">Select District</option>-->
                    <!--    @foreach ($districts as $district)
    -->
                    <!--      <option value="{{ $district->id }}" {{ $advertisement->district_id == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>-->
                    <!--
    @endforeach-->
                    <!--  </select>-->
                    <!--</div>-->




                    <div class="mb-3">
                        <label for="title" class="form-label">Advertisement Title</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ $advertisement->title }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Advertisement Image</label>

                        <!-- Display existing image if available -->
                        @if (!empty($advertisement->image))
                            <div class="mb-2">
                                <img src="{{ asset('admin/uploads/advertisement/' . $advertisement->image) }}"
                                    alt="Advertisement Image" style="max-width: 150px; height: auto;">
                            </div>
                        @endif

                        <!-- Input for uploading a new image -->
                        <input type="file" class="form-control" id="image" name="image">
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Advertisement Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="1" {{ $advertisement->status == 1 ? 'selected' : '' }}>Show</option>
                            <option value="0" {{ $advertisement->status == 0 ? 'selected' : '' }}>Hide</option>
                        </select>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('advertisement.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
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
