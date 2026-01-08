@extends('admin.layout.app')

@section('title', 'Add City/Town/Village')

@section('content')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Add Town/Village</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('town-village.index') }}">Town/Village/City</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </nav>
        </div>
        <div class="card" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px; ">
            <div class="card-body">
                <form action="{{ route('town-village.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="country_id" class="form-label">Country</label>
                        <select name="country_id" id="country_id" class="form-select" required>
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="state_id" class="form-label">State</label>
                        <select name="state_id" id="state_id" class="form-select " required>
                            <option value="">Select State</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="district_id" class="form-label">District</label>
                        <select name="district_id" id="district_id" class="form-select" required>
                            <option value="">Select District</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Town/Village/City Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
            </div>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        </form>
    </main>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var $j = jQuery.noConflict();


        // $j(document).ready(function() {
        //     alert("sfsdg")
        // });

        $j(document).ready(function() {
            $j('#country_id').on('change', function() {
                const countryId = $j(this).val(); // Get selected country ID
                //console.log('Selected Country ID:', countryId);

                if (countryId) {
                    $j.ajax({
                        url: '/public/get-states/' + countryId, // API endpoint
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            console.log('AJAX Response:', response);

                            const stateSelect = $j('#state_id');
                            stateSelect.empty(); // Clear any existing options
                            stateSelect.append(
                            '<option value="">Select State</option>'); // Default option

                            if (response && response.length > 0) {
                                $j.each(response, function(index, state) {
                                    stateSelect.append(
                                        `<option value="${state.id}">${state.name}</option>`
                                        );
                                });
                            } else {
                                stateSelect.append('<option value="">No states found</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error);
                            console.error('Full Response:', xhr.responseText);
                            alert('An error occurred while fetching states. Please try again.');
                        }
                    });
                } else {
                    alert('Please select a country.');
                    $j('#state_id').empty().append(
                    '<option value="">Select State</option>'); // Reset the state dropdown
                }
            });



            $j('#state_id').on('change', function() {
                const stateId = $j(this).val(); // Get selected country ID
                //console.log('Selected Country ID:', countryId);

                if (stateId) {
                    $j.ajax({
                        url: '/public/get-districts/' + stateId, // API endpoint
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            console.log('AJAX Response:', response);

                            const districtSelect = $j('#district_id');
                            districtSelect.empty(); // Clear any existing options
                            districtSelect.append(
                            '<option value="">Select District</option>'); // Default option

                            if (response && response.length > 0) {
                                $j.each(response, function(index, district) {
                                    districtSelect.append(
                                        `<option value="${district.id}">${district.name}</option>`
                                        );
                                });
                            } else {
                                districtSelect.append(
                                    '<option value="">No states found</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error);
                            console.error('Full Response:', xhr.responseText);
                            alert(
                                'An error occurred while fetching district. Please try again.');
                        }
                    });
                } else {
                    alert('Please select a country.');
                    $j('#district_id').empty().append(
                    '<option value="">Select District</option>'); // Reset the state dropdown
                }
            });
        });
    </script>
