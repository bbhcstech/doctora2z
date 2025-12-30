@extends('admin.layout.app')

@section('title', 'Add City/Town/Village')

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Add City/Town/Village</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('town-village.index') }}">City/Towns/Villages</a></li>
                <li class="breadcrumb-item active">Add</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('town-village.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="country_id" class="form-label">Country</label>
            <select name="country_id" id="country_id" class="form-select" required>
                <option value="">Select Country</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="state_id" class="form-label">State(Part)</label>
            <select name="state_id" id="state_id" class="form-select" required>
                <option value="">Select State(Part)</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="district_id" class="form-label">District</label>
            <select name="district_id" id="district_id" class="form-select" required>
                <option value="">Select District</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">City/Town/Village Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
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
                        stateSelect.empty().append('<option value="">Select State</option>');
                        $('#district_id').empty().append('<option value="">Select District</option>'); // Reset district
                        if (response.length > 0) {
                            $.each(response, function(index, state) {
                                stateSelect.append(`<option value="${state.id}">${state.name}</option>`);
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
                        districtSelect.empty().append('<option value="">Select District</option>');
                        if (response.length > 0) {
                            $.each(response, function(index, district) {
                                districtSelect.append(`<option value="${district.id}">${district.name}</option>`);
                            });
                        } else {
                            districtSelect.append('<option value="">No districts found</option>');
                        }
                    }
                });
            } else {
                $('#district_id').empty().append('<option value="">Select District</option>');
            }
        });
    });
</script>
@endsection
