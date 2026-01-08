@extends('admin.layout.app')

@section('title', 'Add District')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Add District/City/Town/Village</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('district.index') }}">Districts</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </nav>
        </div>

        <div class="card" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px;">
            <div class="card-body">
                <form action="{{ route('district.store') }}" method="POST">
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
                        <label for="state_id" class="form-label">State(Part)</label>
                        <select name="state_id" id="state_id" class="form-select" required>
                            <option value="">Select State(Part)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">District/City/Town/Village Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
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
                        url: '/get-states/' + countryId,
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            const stateSelect = $('#state_id');
                            stateSelect.empty().append(
                                '<option value="">Select State(Part)</option>');

                            if (response.length > 0) {
                                response.forEach(function(state) {
                                    stateSelect.append(
                                        `<option value="${state.id}">${state.name}</option>`
                                        );
                                });
                            } else {
                                stateSelect.append('<option value="">No states found</option>');
                            }
                        },
                        error: function(xhr) {
                            alert('Something went wrong while fetching states.');
                            console.error(xhr.responseText);
                        }
                    });
                } else {
                    $('#state_id').empty().append('<option value="">Select State(Part)</option>');
                }
            });
        });
    </script>
@endsection
