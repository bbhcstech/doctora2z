@extends('admin.layout.app')

@section('title', 'Clinic Details')

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Clinic Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clinics Listing</a></li>
                <li class="breadcrumb-item active">{{ $client->name }}</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $client->name }}</h5>

                <!-- Clinic Details -->
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Name</th>
                            <td>{{ $client->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Phone Number</th>
                            <td>{{ $client->phone_number }}</td>
                        </tr>
                        
                       
                        <tr>
                            <th>Address</th>
                            <td>{{ $client->address }}</td>
                        </tr>
                        <tr>
                            <th>Country</th>
                            <td>{{ $client->country->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>State</th>
                            <td>{{ $client->state->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>District</th>
                            <td>{{ $client->district->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td>{{ $client->city->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Pincode</th>
                            <td>{{ $client->pincode ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Other Information</th>
                            <td>{{ $client->other_information ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Website</th>
                            <td>
                                @if($client->website)
                                    <a href="{{ $client->website }}" target="_blank">{{ $client->website }}</a>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        
                         <tr>
                            <th>Category</th>
                            <td>{{ $client->category ?? 'N/A' }}</td>
                        </tr>
                        
                        <tr>
                            <th>Latitude</th>
                            <td>{{ $client->latitude ?? 'N/A' }}</td>
                        </tr>
                        
                        <tr>
                            <th>Longitude</th>
                            <td>{{ $client->logitude ?? 'N/A' }}</td>
                        </tr>
                        
                         <tr>
                            <th>Status</th>
                            <td>
                                @if($client->status)
                                    {{ $client->status }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        
                      
                    </tbody>
                </table>
                
                 <!-- Display existing images -->
                <h5 class="mt-4">Clinic Images</h5>
                <div id="image-upload-container" class="d-flex flex-wrap">
                    <!-- Check if there are existing images -->
                    @if($existingImages->isNotEmpty())
                        @foreach($existingImages as $index => $image)
                            <div class="image-upload-section" id="image-section-{{ $index + 1 }}" style="margin-right: 10px; margin-bottom: 10px;">
                                <div class="image-preview-container mt-2">
                                    <!-- Display the existing image -->
                                    <img src="{{ asset($image->path) }}" alt="Existing Image" class="preview-thumbnail" style="max-width: 100px;">
                                    
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>No images available.</p>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="mt-3">
                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning">Edit Clinic</a>
                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this clinic?')">Delete Clinic</button>
                    </form>
                    <a href="{{ route('clients.index') }}" class="btn btn-secondary">Back to List</a>
                </div>

            </div>
        </div>
    </section>

</main><!-- End #main -->

@endsection
